<?php

namespace App\Jobs;

use App\Model\BackgroundActivity;
use App\Model\Metafield;
use App\Model\MetafieldConfiguration;
use App\Model\Setting;
use App\Model\Shop;
use App\Model\SyncedDetails;
use App\Traits\GraphQLTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SyncMetafieldsJob implements ShouldQueue
{
    use GraphQLTrait;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $resourceType;
    private $id = '';
    private $shop;
    private $sh_api = '';
    private $sync_id = '';
    private $background_activity_id = '';
    public $timeout = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($resourceType, $id, $shop)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $this->resourceType = $resourceType;
        $this->id = $id;
        $this->shop = $shop;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sh_api = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/';
        try {
            \Log::info('-------------Sync metafield job -----------------');
            $background_activity = new BackgroundActivity();
            $background_activity->shop_id = $this->shop->id;
            $background_activity->job_id = $this->job->getJobId();
            $background_activity->resource_type = $this->resourceType . '-sync';
            $background_activity->status = 'In Progress';
            $background_activity->save();
            $this->background_activity_id = $background_activity->id;

            $sync_detail = new SyncedDetails;
            $sync_detail->shop_id = $this->shop->id;
            $sync_detail->job_id =  $this->job->getJobId();
            $sync_detail->resource_type = $this->resourceType;
            $sync_detail->status = 'in_progress';
            $sync_detail->type = 'sync';
            $sync_detail->description = ucwords(str_replace('_', ' ', $this->resourceType)).' metafields are syncing.';
            $sync_detail->save();
            $this->sync_id = $sync_detail->id;

            if ($sync_detail->save()) {

                $this->syncMeta(250, '');

                $email1 = '';
                $setting = Setting::select('value')->where('key', 'email')->where('shop_id', $this->shop->id)->first();
                if( $setting ){
                    $email1 = $setting->value;
                    \Log::info($email1);
                }

                $query = '{
                          shop {
                            email
                          }
                    }';
                $result = $this->graph($this->shop, $query);
                if ($result->body->shop) {
                    $email2 = $result->body->shop->email;
//                    $email2 = 'ruchita.crawlapps@gmail.com';
                    \Log::info($email2);
                }

                $data = array('data' => $this->resourceType);
                $emails = ( !empty($email1) ) ? array($email1, $email2) : $email2;

                Mail::send('CSVMailFormat.syncmail', $data, function ($message) use ($emails) {
                    $message->to($emails, 'Metafield sync information');
                    $message->subject("Your Metafield sync information");
                });


                \Log::info('------------complete---------------');
                $background_activity->status = 'Complete';
                $background_activity->save();

                $sync_detail->status = 'complete';
                $sync_detail->description = str_replace('_', ' ', $this->resourceType).' Syncing is complete.';
                $sync_detail->delete();
                \Log::info(json_encode($sync_detail));
            }
        } catch (\Exception $e) {
            \Log::info('---------- ERROR --------------');
            \Log::info($e->getMessage());

            $background_activity = BackgroundActivity::where('id', $this->background_activity_id)->where('shop_id',
                $this->shop->id)->first();
            $background_activity->status = 'Failed';
            $background_activity->save();

            \Log::info($this->sync_id);
            $sync = SyncedDetails::where('id', $this->sync_id)->where('shop_id', $this->shop->id)->first();
            $sync->delete();
        }
    }

    // using rest api
    public function syncMeta($limit, $page)
    {
        try {
            \Log::info('-----------syncMeta----------------');
        if ($this->resourceType == 'articles' || $this->resourceType == 'variants') {
            $rt = ($this->resourceType == 'articles') ? 'blogs' : 'products';
            $this->getData($this->id, $rt, $limit, $page);
        } else {
            $this->getData('', '', $limit, $page);
        }
        } catch (\Exception $e) {
            \Log::info('syncMeta ERROR :: ');
            \Log::info(json_encode($e));
        }
    }

    public function getData($id = '', $rtype = '', $limit, $page)
    {
        try {
            \Log::info('-----------getData----------------');
            if ($this->resourceType == 'articles' || $this->resourceType == 'variants') {
                $endPoint = "$this->sh_api$rtype/$id/$this->resourceType.json";
            } else {
                $endPoint = $this->sh_api.$this->resourceType.'.json';
            }

            $field = 'id';
            $data = $this->getResult($limit, $page, $field, $endPoint);

            if ($data->errors) {
                return;
            } else {
                $rt = $this->resourceType;
                $entities = $data->body->$rt;
                if( $this->resourceType == 'shop' ){
                    $this->getMetafields('', 250, '', 250, '');
                }else{
                    if (count($entities) > 0) {
                        $j =0;
                        foreach ($entities as $entkey => $entval) {
                            \Log::info($j . ' ' .$entval->id);
                            $this->getMetafields($id, $rtype, $entval->id, 250, '');

                            if( $this->resourceType == 'products' || $this->resourceType == 'blogs' ){
                                $this->syncChild($id, $this->resourceType, $entval->id, 250, '');
                            }
                            $j++;
                        }
                        if ($data->link) {
                            $page = ($data->link->next) ? $data->link->next : '';
                            ($data->link->next) ? $this->getData($id, $rtype, $limit, $page) : '';
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::info('getData ERROR :: ');
            \Log::info(json_encode($e));
        }
    }

    public function syncChild($id, $rtype, $owner_id, $limit, $page){
        try{
            \Log::info('-----------syncChild----------------');
            $childRtype = ( $rtype == 'products' ) ? 'variants' : 'articles';
            $endPoint = "$this->sh_api$rtype/$owner_id/$childRtype.json";
            $field = 'id';
            $data = $this->getResult($limit, $page, $field, $endPoint);
            \Log::info(json_encode($data));
            if ($data->errors) {
                return;
            } else {

                $entities = $data->body->$childRtype;

                if (count($entities) > 0) {
                        foreach ($entities as $entkey => $entval) {
                            $this->getMetafields($owner_id, $rtype, $entval->id, 250, '');
                        }
                        if ($data->link) {
                            $page = ($data->link->next) ? $data->link->next : '';
                            ($data->link->next) ? $this->syncChild($id, $rtype, $owner_id, $limit, $page) : '';
                        }
                    }
            }
        }catch( \Exception $e ){
            \Log::info('syncChild ERROR :: ');
            \Log::info(json_encode($e));
        }
    }

    public function getMetafields($id = '', $rtype = '', $owner_id, $limitMetafield, $pageMetafield)
    {
        try {
            \Log::info('-----------getMetafields----------------');
            $rt = ( $this->resourceType == 'smart_collections' || $this->resourceType == 'custom_collections') ? 'collections' : $this->resourceType;
//            \Log::info($rt . '_' . $this->shop->id);
            if ($this->resourceType == 'articles' || $this->resourceType == 'variants') {
                $endPoint = "$this->sh_api$rtype/$id/$rt/$owner_id/metafields.json";
            } elseif( $this->resourceType == 'shop' ) {
                $endPoint = "/admin/metafields.json";
            }else{
                $endPoint = "$this->sh_api$rt/$owner_id/metafields.json";
            }
//            \Log::info($endPoint);
            $fields = 'id,namespace,key,value,owner_id';
            $resultMeta = $this->getResult($limitMetafield, $pageMetafield, $fields, $endPoint);
            if ($resultMeta->errors) {
                return;
            } else {
                $metafields = $resultMeta->body->metafields;
                if (count($metafields) > 0) {
                    foreach ($metafields as $metakey => $metaval) {
                        $this->storeMetaField($metaval, $metakey);
                    }
                    if ($resultMeta->link) {
                        $pageMetafield = ($resultMeta->link->next) ? $resultMeta->link->next : '';
                        ($resultMeta->link->next) ? $this->getMetafields($id, $rtype, $owner_id, $limitMetafield,
                            $pageMetafield) : '';
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::info('getMetafields ERROR :: ');
            \Log::info(json_encode($e));
        }
    }

    public function storeMetaField($metafield, $key)
    {
        \Log::info('--------------storeMetaField----------------');
        try {
            DB::beginTransaction();

            $is_exist = Metafield::where('shop_id', $this->shop->id)->where('metafield_id', $metafield->id)->whereIn('resource_type', array($this->resourceType, 'lcl_'.$this->resourceType, 'sync_'.$this->resourceType, 'global_'.$this->resourceType))->first();

            if ($is_exist) {
                $meta_config = MetafieldConfiguration::where('id',
                $is_exist->metafield_configuration_id)->where('shop_id', $this->shop->id)->first();
                $meta_value = $is_exist;
//                $str_rtype = $meta_config->resource_type;
//                $sk = $meta_config->sort_order;
//                $type = $is_exist->metafield_type;

                $meta_value->value = $metafield->value;
                $meta_value->metafield_json = json_encode($metafield);
                $meta_value->save();
            } else {
                $meta_config = new MetafieldConfiguration;
                $meta_value = new Metafield;
                $str_rtype = 'sync_'.$this->resourceType;
                $sk = $key;
                $type = $this->getMetafieldType($metafield->value);
                if( $type == 'string' || $type == 'json'){
                    $metafield->value = trim($metafield->value, ' ');
                }elseif ( $type == 'image' || $type == 'multiple_image' || $type == 'file'){

                    $url = $this->storeImageToLocal($metafield->value, $type);
                    \Log::info('------------------ IMAGE URL -------------------');
                    \Log::info($url);
                    if( !$url ){
                        $metafield->value = $url;
                    }
                }

                $meta_config->shop_id = $this->shop->id;
                $meta_config->label = '';
                $meta_config->namespace = $metafield->namespace;
                $meta_config->key = $metafield->key;
                $meta_config->type = $type;
                $meta_config->resource_type = $str_rtype;
                $meta_config->sort_order = $sk;
                $meta_config->save();

                $meta_value->shop_id = $this->shop->id;
                $meta_value->metafield_configuration_id = $meta_config->id;
                $meta_value->value = $metafield->value;
                $meta_value->metafield_id = $metafield->id;
                $meta_value->metafield_json = json_encode($metafield);
                $meta_value->metafield_type = $type;
                $meta_value->resource_type = $str_rtype;
                $meta_value->owner_id = ($this->resourceType == 'shop' ) ? $this->shop->id : $metafield->owner_id;
                $meta_value->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info('storeMetaField ERROR :: ');
            \Log::info(json_encode($e));
        }
    }

    public function getResult($limit, $page, $fields, $endPoint)
    {
        try {
            \Log::info('-----------getResult----------------');
            $parameter['limit'] = $limit;
            $parameter['page_info'] = $page;
            $parameter['fields'] = $fields;
            $result = $this->shop->api()->rest('GET', $endPoint, $parameter);
            if( !$result->errors ){
                $call_limit = $result->response->getHeader('HTTP_X_SHOPIFY_SHOP_API_CALL_LIMIT')[0];
                $rate = substr($call_limit,0,strpos($call_limit,'/'));
//                \Log::info($rate . '>=' . 35);
                if( $rate >= 35 ){
//                    \Log::info('sleep 1');
                    sleep(1);
                }
            }
            return $result;
        } catch (\Exception $e) {
            \Log::info('getResult ERROR :: ');
            \Log::info(json_encode($e));
        }
    }

    public function getMetafieldType($value)
    {
        $type = '';
        if (preg_match('/^#[a-f0-9]{6}$/i', $value)) {
            $type = 'color_picker';
        } elseif (is_string($value) && is_array(json_decode($value, true))) {
            $type = 'json';
        } elseif (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $type = 'email';
        } elseif (filter_var($value, FILTER_VALIDATE_URL)) {
            $arr = basename(substr($value, 0, (strrpos($value, '?'))));
            if( $arr == '' ){
                $arr = $value;
            }
            $str = substr($arr, (strrpos($arr, '.') + 1));
            $img_ext = ['png', 'jpg', 'jpeg', 'gif', 'svg','webp', 'JPG', 'PNG', 'JPEG', 'GIF', 'SVG', 'WEBP'];

            if (in_array($str, $img_ext)) {
                if (substr_count($value, 'http') > 1) {
                    $type = 'multiple_image';
                } else {
                    $type = 'image';
                }
            } elseif (isDomainAvailible($value)) {
                $type = 'url';
            } else {
                $type = 'file';
            }
        } elseif ($value != strip_tags($value)) {
            $type = 'rich_text_box';
        } elseif (is_numeric($value) || is_string($value)) {
            $type = 'string';
        }
        return $type;
    }
//    public function getMetaField(){
//        if( $this->resourceType == 'variants' ){
//            $endPoint = '/admin/api/2020-01/products.json';
//            $result = getResult($endPoint);
//        }
//        $endPoint = '/admin/api/2020-01/' . $this->resourceType . '.json';
//        $parameter['limit'] = 10;
//        $parameter['page_info'] = '';
//        $result = getResult($endPoint);
//
//
//    }


//    public function storeMetaField($resourceType, $metafield, $key)
//    {
//        \Log::info('--------------storeMetaField----------------');
//        try {
//            DB::beginTransaction();
//            $type = $this->getMetafieldType($metafield->value);
//
//            $is_exist = Metafield::where('shop_id', $this->shop->id)->where('metafield_id', $metafield->id)->whereIn('resource_type', array( $this->resourceType, 'lcl_' . $this->resourceType, 'sync_' . $this->resourceType ))->first();
//
//            if( $is_exist ){
//                $meta_config = MetafieldConfiguration::where('id', $is_exist->metafield_configuration_id)->where('shop_id', $this->shop->id)->first();
//                \Log::info(json_encode($meta_config));
//                $meta_value = $is_exist;
//                $str_rtype = $meta_config->resource_type;
//                $sk = $meta_config->sort_order;
//            }else{
//                $meta_config = new MetafieldConfiguration;
//                $meta_value = new Metafield;
//                $str_rtype = 'sync_' . $this->resourceType;
//                $sk = $key;
//            }
//
//            $meta_config->shop_id = $this->shop->id;
//            $meta_config->label = '';
//            $meta_config->namespace = $metafield->namespace;
//            $meta_config->key = $metafield->key;
//            $meta_config->type = $type;
//            $meta_config->resource_type = $str_rtype;
//            $meta_config->sort_order = $sk;
//            $meta_config->save();
//
//            $meta_value->shop_id = $this->shop->id;
//            $meta_value->metafield_configuration_id = $meta_config->id;
//            $meta_value->value = $metafield->value;
//            $meta_value->metafield_id = $metafield->id;
//            $meta_value->metafield_json = json_encode($metafield);
//            $meta_value->metafield_type = $type;
//            $meta_value->resource_type = 'sync_' . $resourceType;
//            $meta_value->owner_id = $metafield->owner_id;
//            $meta_value->save();
//            DB::commit();
//        } catch (\Exception $e) {
//            DB::rollBack();
//            \Log::info('storeMetaField ERROR :: ');
//            \Log::info(json_encode($e));
//        }
//    }

    public function storeImageToLocal( $value, $type ){
        \Log::info('------------- storeImageToLocal -------------------');
        try{
            if( $type == 'image' ){
                $url = $this->uploadImageToLocal($value);
                if( !$url ){
                    return false;
                }
            }else{
                $images = explode(',', $value);
                foreach ( $images as $key=>$val ){
                    $url = $this->uploadImageToLocal($val);
                    if( !$url ){
                        return false;
                    }else{
                        $urls[] = $url;
                    }
                }
                $url = implode(',', $urls);
            }
            return $url;
        }catch( \Exception $e ){
            \Log::info('------------- ERROR::storeImageToLocal -------------------');
            \Log::info(json_encode($e));
            return false;
        }

    }

    public function uploadImageToLocal($value){
        \Log::info('------------- uploadImageToLocal -------------------');
        try{
            if(!strrpos($value, '?')){
                $img = basename($value);
            }else{
                $img = basename(substr($value, 0, (strrpos($value, '?'))));
            }
            $tempImage = Storage::disk('public')->path('uploads/') .$img;
            copy($value, $tempImage);

            $url = $this->addAssetToShopify($this->shop->api(), $img);
            return $url;
        }catch( \Exception $e ){
            \Log::info('------------- ERROR::uploadImageToLocal -------------------');
            \Log::info(json_encode($e));
            return false;
        }

    }

    // using graphQl
    public function getMeta($resourceType, $limit, $after, $limitm, $afterm)
    {
        \Log::info('--------------getMeta----------------');
        try {
            \Log::info($resourceType);
            $newResourceType = ($resourceType == 'smart' || $resourceType == 'custom') ? 'collections' : $resourceType;
            \Log::info($newResourceType);
            $query = $this->getQuery($resourceType, $limit, $after, $limitm, $afterm);
            $result = $this->graph($this->shop, $query);
            \Log::info(json_encode($result));
            if ($result->errors) {
                return;
            } else {
                $data = ($resourceType == 'shop') ? $result->body->$newResourceType : $result->body->$newResourceType->edges;
                foreach ($data as $key => $val) {
//                \Log::info(json_encode($val));

                    $metafields = ($resourceType == 'shop') ? $val : $val->node->metafields;
                    if ($metafields->edges) {
                        if ($resourceType == 'shop') {
                            $owner_id = 1;
                        } else {
                            $owner_id = ($newResourceType == 'collections') ? $val->node->id : $val->node->legacyResourceId;
                        }

                        if ($newResourceType == 'collections') {
                            $arr = explode('/', $owner_id);
                            $owner_id = end($arr);
                        }
                        $metafield = $metafields->edges;
                        foreach ($metafield as $mk => $mv) {
                            $json = $mv->node;
                            $this->storeMeta($json, $owner_id, $resourceType, $mk);
                        }
                    } else {
                        continue;
                    }
                    if ($metafields->pageInfo->hasNextPage) {
                        $afterm = '"'.end($metafields->edges)->cursor.'"';
                        $this->getMeta($resourceType, $limit, $after, $limitm, $afterm);
                    }
                }
                if ($resourceType != 'shop') {
                    if ($result->body->$newResourceType->pageInfo->hasNextPage) {
                        $after = '"'.end($data)->cursor.'"';
                        $this->getMeta($resourceType, $limit, $after, $limitm, '');
                    }
                }
            }

        } catch (\Exception $e) {
            \Log::info(json_encode($e));
        }
    }

    public function addAssetToShopify($api, $image)
    {
        \Log::info('------------- addAssetToShopify -------------------');
        try {
            $theme = Shop::select('theme_id')->where('id', $this->shop->id)->first();
            $theme_id = $theme->theme_id;
            $url = Storage::disk('public')->url('uploads/').$image;
            $path = \Storage::path('uploads/'.$image);

                $asset = [
                    "asset" => [
                        "key" => "assets/".$image,
                        "src" => $url
                    ]
                ];

                $endPoint = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/themes/'.$theme_id.'/assets.json';
                $sh_asset = $api->rest('PUT', $endPoint, $asset);

                if( !$sh_asset->errors ){
                    if (file_exists($path)) {
                        unlink($path);
                    }
                    $sh_url = $sh_asset->body->asset->public_url;
                    return $sh_url;
                }else{
                    return false;
                }
        } catch (Exception $e) {
            \Log::info('------------- ERROR::addAssetToShopify -------------------');
            \Log::info(json_encode($e));
            return false;
        }
    }

    public function getQuery($resourceType, $limit = 10, $after = '', $limitm = 10, $afterm = '')
    {
        \Log::info('--------------getQuery----------------');
        try {
            if ($resourceType == 'smart' || $resourceType == 'custom') {
                $query = '{
                  collections(first: '.$limit.' query:"collection_type:'.$resourceType.'" after: '.$after.' ) {
                    edges {
                      cursor
                      node {
                         title
                        id
                        metafields(first: '.$limitm.' after: '.$afterm.') {
                          edges {
                            cursor
                            node {
                              id
                              description
                              key
                              legacyResourceId
                              namespace
                              value
                              valueType
                            }
                          }
                          pageInfo {
                            hasNextPage
                          }
                        }

                      }
                    }
                    pageInfo {
                      hasNextPage
                    }
                  }
                }';
            } elseif ($resourceType == 'shop') {
                $query = '{
                    shop {
                        metafields(first: '.$limitm.' after: '.$afterm.') {
                        edges {
                            cursor
                            node {
                                  description
                                  namespace
                                  value
                                  key
                                  legacyResourceId
                                  valueType
                                }
                              }
                              pageInfo {
                                hasNextPage
                              }
                            }
                          }
                        }';
            } else {
                $query = '{
                  '.$resourceType.'(first: '.$limit.' after: '.$after.') {
                    edges {
                      node {
                        metafields(first: '.$limitm.' after: '.$afterm.') {
                          edges {
                            node {
                              key
                              legacyResourceId
                              value
                              namespace
                            }
                            cursor
                          }
                          pageInfo {
                            hasNextPage
                          }
                        }
                        legacyResourceId
                      }
                      cursor
                    }
                    pageInfo {
                      hasNextPage
                    }
                  }
                }';
            }
            return $query;
        } catch (\Exception $e) {
            \Log::info(json_encode($e));
        }

    }

    public function storeMeta($data, $owner_id, $resourceType, $sort_key)
    {
        \Log::info('--------------storeMeta----------------');
        try {
            DB::beginTransaction();
            $resourceType = ($resourceType == 'smart' || $resourceType == 'custom') ? $resourceType.'_collections' : ($resourceType == 'productVariants') ? 'variants' : $resourceType;

            $is_exist = Metafield::where('shop_id', $this->shop->id)->where('metafield_id',
                $data->legacyResourceId)->whereIn('resource_type',
                array($this->resourceType, 'lcl_'.$this->resourceType, 'sync_'.$this->resourceType, 'global_'.$this->resourceType))->first();

            if ($is_exist) {
                $meta_config = MetafieldConfiguration::where('id', $is_exist->metafield_configuration_id)->where('shop_id', $this->shop->id)->first();
                $meta_value = $is_exist;
                $str_rtype = $meta_config->resource_type;
                $sk = $meta_config->sort_order;
                $type = $meta_config->metafield_type;
            } else {
                $meta_config = new MetafieldConfiguration;
                $meta_value = new Metafield;
                $str_rtype = 'sync_'.$this->resourceType;
                $sk = $sort_key;
                $type = $this->getMetafieldType($data->value);
            }

            $meta_config->shop_id = $this->shop->id;
            $meta_config->namespace = $data->namespace;
            $meta_config->key = $data->key;
            $meta_config->type = $type;
            $meta_config->resource_type = $str_rtype;
            $meta_config->sort_order = $sk;

            $meta_config->save();

            $meta_value->shop_id = $this->shop->id;
            $meta_value->metafield_configuration_id = $meta_config->id;
            $meta_value->value = $data->value;
            $meta_value->metafield_id = $data->legacyResourceId;
            $meta_value->metafield_json = json_encode($data);
            $meta_value->metafield_type = $type;
            $meta_value->resource_type = $str_rtype;
            $meta_value->owner_id = $owner_id;

            $meta_value->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info(json_encode($e));
        }
    }
}

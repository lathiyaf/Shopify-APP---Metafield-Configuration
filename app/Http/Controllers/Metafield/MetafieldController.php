<?php

namespace App\Http\Controllers\Metafield;

use App\Http\Controllers\Controller;
use App\Http\Requests\GlobalMetafieldRequest;
use App\Http\Requests\MetafieldRequest;
use App\Jobs\GlobalMetafieldJob;
use App\Jobs\RemoveGlobalMetafieldJob;
use App\Model\GlobalMetafield;
use App\Model\Metafield;
use App\Model\MetafieldConfiguration;
use App\Model\Setting;
use App\Model\Shop;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;

class MetafieldController extends Controller
{
    protected $shop = '';
    protected $api = '';
    protected $ownerId = '';
    protected $handle = '';
    protected $resourceType = '';
    protected $lclresourceType = 'lcl_';
    protected $syncresourceType = 'sync_';
    protected $is_custom = '';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('api')) {
            return $this->json($request);
        }
    }

    public function json($request)
    {
        try {
            $this->shop = \ShopifyApp::shop();
            $localRtype = 'lcl_'.$request->resourceType;
            $globalRtype = 'global_'.$request->resourceType;
            $owner_id = $request->owner_id;
            $syncRtype = 'sync_'.$request->resourceType;

            $count = Metafield::where('owner_id', $request->owner_id)->where('resource_type', $syncRtype)->where('shop_id',  $this->shop->id)->orderBy('metafield_id', 'asc')->count();

            $count += MetafieldConfiguration::where('resource_type', $request->resourceType)->where('shop_id',  $this->shop->id)->count();

            $count += Metafield::where('owner_id', $request->owner_id)->where('resource_type',
                $localRtype)->where('shop_id',  $this->shop->id)->count();

            $count += MetafieldConfiguration::where('resource_type', 'global_' . $request->resourceType)->where('shop_id',  $this->shop->id)->count();
//            $count += Metafield::where('owner_id', $request->owner_id)->where('resource_type',
//                $globalRtype)->where('shop_id',  $this->shop->id)->orderBy('created_at', 'desc')->count();

            if( $request->type == 'sync' ){
                $sync_entities = Metafield::select('id', 'metafield_configuration_id', 'value')->with('belongsToConfiguration')->where('owner_id', $request->owner_id)->where('resource_type', $syncRtype)->where('shop_id',  $this->shop->id)->orderBy('metafield_id', 'asc')->get();

                if( $sync_entities ){
                    $snc_entity = $sync_entities->map(function ($name) {
                        return [
                            'id' => $name->id,
                            'metafield_configuration_id' => (  $name->belongsToConfiguration  ) ? $name->belongsToConfiguration->id : '',
                            'namespace' => ( $name->belongsToConfiguration ) ? $name->belongsToConfiguration->namespace : '',
                            'key' => ( $name->belongsToConfiguration ) ? $name->belongsToConfiguration->key : '',
                            'type' => ( $name->belongsToConfiguration  ) ? $name->belongsToConfiguration->type : '',
                            'label' => ( $name->belongsToConfiguration  ) ? $name->belongsToConfiguration->label : '',
                            'value' => $name->value,
                        ];
                    })->toArray();
                }
                $arr = $snc_entity;
                $data['sync'] = $arr;
                $data['apps'] = [];
            }else{
                $entities = MetafieldConfiguration::with([
                    'hasManyMetafield' => function ($query) use ($owner_id) {
                        $query->where('owner_id', $owner_id);
                    }
                ])->where('resource_type', $request->resourceType)->where('shop_id',  $this->shop->id)->orderBy('sort_order',
                    'asc')->get();
                $custom_entities = Metafield::select('id', 'metafield_configuration_id',
                    'value')->with('belongsToConfiguration')->where('owner_id', $request->owner_id)->where('resource_type',
                    $localRtype)->where('shop_id',  $this->shop->id)->orderBy('created_at', 'desc')->get();

                $global_entities = MetafieldConfiguration::with([
                    'hasManyMetafield' => function ($query) use ($owner_id) {
                        $query->where('owner_id', $owner_id);
                    }
                ])->where('resource_type', 'global_' . $request->resourceType)->where('shop_id',  $this->shop->id)->orderBy('sort_order',
                    'asc')->get();

//                $global_entities = Metafield::select('id', 'metafield_configuration_id',
//                    'value')->with('belongsToConfiguration')->where('owner_id', $request->owner_id)->where('resource_type',
//                    $globalRtype)->where('shop_id',  $this->shop->id)->orderBy('created_at', 'desc')->get();

                if ($entities) {
                    $entity = $entities->map(function ($name) {
                        return [
                            'id' => (count($name->hasManyMetafield) > 0) ? $name->hasManyMetafield[0]->id : '',
                            'metafield_configuration_id' => $name->id,
                            'namespace' => $name->namespace,
                            'key' => $name->key,
                            'type' => $name->type,
                            'label' => $name->label,
                            'value' => (count($name->hasManyMetafield) > 0) ? $name->hasManyMetafield[0]->value : ''
                        ];
                    })->toArray();
                }

                if ($custom_entities) {
                    $cs_entity = $custom_entities->map(function ($name) {
                        return [
                            'id' => $name->id,
                            'metafield_configuration_id' => ($name->belongsToConfiguration) ? $name->belongsToConfiguration->id : '',
                            'namespace' => ($name->belongsToConfiguration) ? $name->belongsToConfiguration->namespace : '',
                            'key' => ($name->belongsToConfiguration) ? $name->belongsToConfiguration->key : '',
                            'type' => ($name->belongsToConfiguration) ? $name->belongsToConfiguration->type : '',
                            'label' => ($name->belongsToConfiguration) ? $name->belongsToConfiguration->label : '',
                            'value' => $name->value,
                        ];
                    })->toArray();
                }

                if ($global_entities) {
                    $gl_entity = $global_entities->map(function ($name) {
                        return [
                            'id' => (count($name->hasManyMetafield) > 0) ? $name->hasManyMetafield[0]->id : '',
                            'metafield_configuration_id' => $name->id,
                            'namespace' => $name->namespace,
                            'key' => $name->key,
                            'type' => $name->type,
                            'label' => $name->label,
                            'value' => (count($name->hasManyMetafield) > 0) ? $name->hasManyMetafield[0]->value : ''
                        ];
                    })->toArray();
                }

                $cs_gl_arr = array_merge($cs_entity, $gl_entity);
                $arr = array_merge($cs_gl_arr, $entity);
                $data['apps'] = $arr;
                $data['sync'] = [];
            }

            $data['count'] = $count;
            return response::json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response::json(['data' => $e->getMessage()], 422);
        }
    }

//    public function json($request)
//    {
//        try {
//            $this->shop = \ShopifyApp::shop();
//            $localRtype = 'lcl_'.$request->resourceType;
//            $owner_id = $request->owner_id;
//            $syncRtype = 'sync_'.$request->resourceType;
//            $entities = MetafieldConfiguration::with([
//                'hasManyMetafield' => function ($query) use ($owner_id) {
//                    $query->where('owner_id', $owner_id);
//                }
//            ])->where('resource_type', $request->resourceType)->where('shop_id',  $this->shop->id)->orderBy('sort_order',
//                'asc')->get();
//
//            $sync_entities = Metafield::select('id', 'metafield_configuration_id', 'value')->with('belongsToConfiguration')->where('owner_id', $request->owner_id)->where('resource_type', $syncRtype)->where('shop_id',  $this->shop->id)->orderBy('created_at', 'desc')->get();
//
//            $custom_entities = Metafield::select('id', 'metafield_configuration_id',
//                'value')->with('belongsToConfiguration')->where('owner_id', $request->owner_id)->where('resource_type',
//                $localRtype)->where('shop_id',  $this->shop->id)->orderBy('created_at', 'desc')->get();
//
//            if ($entities) {
//                $entity = $entities->map(function ($name) {
//                    return [
//                        'id' => (count($name->hasManyMetafield) > 0) ? $name->hasManyMetafield[0]->id : '',
//                        'metafield_configuration_id' => $name->id,
//                        'namespace' => $name->namespace,
//                        'key' => $name->key,
//                        'type' => $name->type,
//                        'label' => $name->label,
//                        'value' => (count($name->hasManyMetafield) > 0) ? $name->hasManyMetafield[0]->value : ''
//                    ];
//                })->toArray();
//            }
//
//            if( $sync_entities ){
//                $snc_entity = $sync_entities->map(function ($name) {
//                    return [
//                        'id' => $name->id,
//                        'metafield_configuration_id' => (  $name->belongsToConfiguration  ) ? $name->belongsToConfiguration->id : '',
//                        'namespace' => ( $name->belongsToConfiguration ) ? $name->belongsToConfiguration->namespace : '',
//                        'key' => ( $name->belongsToConfiguration ) ? $name->belongsToConfiguration->key : '',
//                        'type' => ( $name->belongsToConfiguration  ) ? $name->belongsToConfiguration->type : '',
//                        'label' => ( $name->belongsToConfiguration  ) ? $name->belongsToConfiguration->label : '',
//                        'value' => $name->value,
//                    ];
//                })->toArray();
//            }
//            if ($custom_entities) {
//                $cs_entity = $custom_entities->map(function ($name) {
//                    return [
//                        'id' => $name->id,
//                        'metafield_configuration_id' => ($name->belongsToConfiguration) ? $name->belongsToConfiguration->id : '',
//                        'namespace' => ($name->belongsToConfiguration) ? $name->belongsToConfiguration->namespace : '',
//                        'key' => ($name->belongsToConfiguration) ? $name->belongsToConfiguration->key : '',
//                        'type' => ($name->belongsToConfiguration) ? $name->belongsToConfiguration->type : '',
//                        'label' => ($name->belongsToConfiguration) ? $name->belongsToConfiguration->label : '',
//                        'value' => $name->value,
//                    ];
//                })->toArray();
//            }
//            $arr_entity = array_merge($cs_entity, $snc_entity);
//
//            $arr = array_merge($arr_entity, $entity);
//
//            return response::json(['data' => $arr], 200);
//        } catch (\Exception $e) {
//            return response::json(['data' => $e->getMessage()], 422);
//        }
//    }

    public function getCustomNamespace(Request $request)
    {
        try {
            if ($request->has('api')) {
                $shop = \ShopifyApp::shop();
                $namespace = Setting::where('key', 'global_namespace')->where('shop_id', $shop->id)->first();

               $res = ( $namespace ) ? $namespace->value : '';
                return response::json(['data' => $res], 200);
            }
        } catch (\Exception $e) {
            return response::json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MetafieldRequest $request)
    {
        try {
            DB::beginTransaction();
            if ($request->isSubmit) {
                $this->shop = \ShopifyApp::shop();
                $this->api = $this->shop->api();
                $data = json_decode($request->data, 1);
                $this->owner_id = $request->owner_id;
                $this->handle = $request->handle;
                $this->resourceType = $request->resourceType;
                $this->lclresourceType = $this->lclresourceType.$this->resourceType;
                $this->syncresourceType = $this->syncresourceType.$this->resourceType;
                $this->is_custom = $request->is_custom;

                if ($request->is_custom == "true") {
                    $res = $this->saveCustomField($request);
                } else {
                    foreach ($data as $dk => $dv) {
                        if (($dv['value'] != null || ($dv['type'] == 'file' && @$request->file[$dk] != null) || ($dv['type'] == 'image' && @$request->image[$dk] != null) || ($dv['type'] == 'multiple_image' && @$request->multiple_image[$dk] != [])) && $dv['is_remove'] == false && $dv['is_change'] == true) {
                            $value_type = ($dv['type'] == 'json') ? 'json_string' : 'string';
//                            $value_type = ($dv['type'] == 'number') ? 'integer' : $value_type;

                            $t = $dv['type'];
                            if( $t == 'string' || $t == 'json' ){
                                $dv['value'] = rtrim($dv['value'], ' ');
                            }
                            /* add image */
                            if ($t == 'image' || $t == 'multiple_image' || $t == 'file') {
                                /* locally */

                                if ($t == 'file') {
                                    $value = $request->file[$dk];
                                    if (gettype($value) == "string") {
                                        continue;
                                    }
                                    $dv['value'] = $this->uploadImage($value, 'add');
                                }
                                if ($t == 'image') {
                                    $value = $request->image[$dk];
                                    if (gettype($value) == "string") {
                                        continue;
                                    }
                                    $dv['value'] = $this->uploadImage($value, 'add');
                                }
                                if ($t == 'multiple_image') {
                                    $images = $request->multiple_image[$dk];
                                    $v = [];
                                    foreach ($images as $imagek => $imagev) {
                                        if (gettype($imagev) == "string") {
                                            $v[$imagek] = $imagev;
                                            continue;
                                        }
                                        $v[$imagek] = $this->uploadImage($imagev, 'add');
                                    }
                                    $dv['value'] = implode(',', $v);
                                }

                                if ($dv['value'] == false) {
                                    return response::json(['data' => 'Error to store image'], 422);
                                }
                                /* shopify */
                            }
                            /* add metafield in shopify */
                            $metafieldJson = [
                                "metafield" => [
                                    'namespace' => $dv['namespace'],
                                    'key' => $dv['key'],
                                    'value' => $dv['value'],
                                    'value_type' => $value_type
                                ]
                            ];
                            $sh_metafield = $this->addMetafieldToShopify($metafieldJson);


                            /* add metafield in local db */
                            $this->addMetafieldToLocal($sh_metafield, $dv['metafield_configuration_id'], $dv['value'],
                                $dv['type']);

                        } elseif ($dv['is_remove'] || (!$dv['is_remove'] && $dv['value'] == null && $dv['id'] != '')) {

                            $lcl_metafield = Metafield::where('shop_id',
                                $this->shop->id)->where('id',
                                $dv['id'])->where('owner_id', $request->owner_id)->first();

                            $metafield_id = $lcl_metafield->metafield_id;
                            $rtl = $lcl_metafield->resource_type;

//                            dd((!$dv['is_remove'] && ( substr($rtl, 0, 3) == 'lcl' || substr($rtl, 0, 4) == 'sync')) || $dv['is_remove']);
//
//                            dd((!$dv['is_remove'] && ( substr($rtl, 0, 3) == 'lcl' || substr($rtl, 0, 4) == 'sync')) || ( $dv['is_remove'] && ( substr($rtl, 0, 3) == 'lcl' || substr($rtl, 0, 4) == 'sync')));
                            if ((!$dv['is_remove'] && ( substr($rtl, 0, 3) == 'lcl' || substr($rtl, 0, 4) == 'sync')) || ( $dv['is_remove'] && ( substr($rtl, 0, 3) == 'lcl' || substr($rtl, 0, 4) == 'sync'))) {
                                $configuration = MetafieldConfiguration::where('id',
                                    $lcl_metafield->metafield_configuration_id)->first();
//                                dd($configuration);
                                $res = ($configuration) ? $configuration->delete() : '';
                            }
                            $res = ($lcl_metafield) ?  $lcl_metafield->delete() : '';
                            $this->removeMetafieldFromShopify($metafield_id);

                            if ($dv['type'] == 'image' || $dv['type'] == 'multiple_image' || $dv['type'] == 'file') {
                                $value = explode(',', $lcl_metafield->value);

                                foreach ($value as $v) {
                                    $image = basename(substr($v, 0, strpos($v, '?')));
//                                    $this->addAssetToShopify($this->api, $image, 'remove');
                                }
                            }
                        }
                    }
                }
                DB::commit();
                return response::json(['data' => 'metafield saved!'], 200);
            } else {
                return response::json(['data' => true], 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info('-----------------------ERROR: store-------------------------');
            \Log::info(json_encode($e));
            return response::json(['data' => $e], 422);
        }
    }

    public function saveCustomField($request)
    {
        try {
            DB::beginTransaction();
            $data = json_decode($request->data, 1);
            $value_type = ($data['typev'] == 'json') ? 'json_string' : 'string';
//            $value_type = ($data['typev'] == 'number') ? 'integer' : $value_type;

            $metafield = new MetafieldConfiguration;

            $metafield->shop_id = $this->shop->id;
            $metafield->label = $data['label'];
            $metafield->namespace = $data['namespace'];
            $metafield->key = $data['key'];
            $metafield->type = $data['typev'];
            $metafield->sort_order = 0;
            $metafield->resource_type = $this->lclresourceType;
            $metafield->save();

            /* add image */

            $t = $data['typev'];

            if ($t == 'image' || $t == 'multiple_image' || $t == 'file') {
                if ($t == 'file' || $t == 'image') {
                    $data['value'] = $this->uploadImage($request->file, 'add');
                } elseif ($t == 'multiple_image') {
                    $images = $request->file;
                    foreach ($images as $imagek => $imagev) {
                        $v[$imagek] = $this->uploadImage($imagev, 'add');
                    }
                    $data['value'] = implode(',', $v);
                }
            }

            $metafieldJson = [
                "metafield" => [
                    'namespace' => $data['namespace'],
                    'key' => $data['key'],
                    'value' => $data['value'],
                    'value_type' => $value_type
                ]
            ];

            $sh_metafield = $this->addMetafieldToShopify($metafieldJson);


            $res = $this->addMetafieldToLocal($sh_metafield, $metafield->id, $data['value'], $data['typev']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info('-----------------------ERROR: saveCustomField-------------------------');
            \Log::info(json_encode($e));
            return response::json(['data' => $e], 422);
        }
    }

    public function uploadImage($value, $action)
    {
        try {
            $image = ImageTrait::makeImage($value, 'uploads/');
            return $this->addAssetToShopify($this->api, $image, $action);
        } catch (\Exception $e) {
            \Log::info('-----------------------ERROR: uploadImage-------------------------');
            \Log::info(json_encode($e));
            return response::json(['data' => $e], 422);
        }
    }

    public function addMetafieldToShopify($metafield)
    {
        try {
            $endPoint = ($this->resourceType == 'shop') ? '/admin/api/'. env('SHOPIFY_API_VERSION') .'/metafields.json' : '/admin/api/'. env('SHOPIFY_API_VERSION') .'/'.$this->resourceType.'/'.$this->owner_id.'/metafields.json';

            $result = $this->api->rest('POST', $endPoint, $metafield);   // shopify metafield result.

            return $result->body;
        } catch (\Exception $e) {
            \Log::info('-----------------------ERROR: addMetafieldToShopify-------------------------');
            \Log::info(json_encode($e));
            return response::json(['data' => $e], 422);
        }
    }

    public function removeMetafieldFromShopify($metafield_id)
    {
        try {
//            $endPoint = ($this->resourceType == 'shop') ? '/admin/api/2020-01/'.$this->resourceType.'/'.$this->owner_id.'/metafields/'.$metafield_id : '/admin/api/2020-01/metafields/'.$metafield_id;

            $endPoint ='/admin/api/'. env('SHOPIFY_API_VERSION') .'/metafields/'.$metafield_id;

            $result = $this->api->rest('DELETE', $endPoint);   // shopify metafield result.
        } catch (\Exception $e) {
            return response::json(['data' => $e], 422);
        }
    }

    public function addMetafieldToLocal($sh_metafield, $metaConfigId, $value, $type)
    {
        try {
            DB::beginTransaction();
            $local_meta = Metafield::where('shop_id', $this->shop->id)->where('metafield_id',
                $sh_metafield->metafield->id)->where('owner_id', $this->owner_id)->first();

            if ($local_meta) {
                $entity = $local_meta;
            } else {
                $entity = new Metafield();
            }

            $rt = ($this->is_custom == "true") ? $this->lclresourceType : $this->resourceType;

            $entity->shop_id = $this->shop->id;
            $entity->owner_id = $this->owner_id;
            $entity->handle = $this->handle;
            $entity->metafield_configuration_id = $metaConfigId;
            $entity->value = $value;
            $entity->metafield_id = $sh_metafield->metafield->id;
            $entity->metafield_json = json_encode($sh_metafield);
            $entity->resource_type = ($entity->resource_type) ? $entity->resource_type : $rt;
            $entity->metafield_type = $type;

            $entity->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info('-----------------------ERROR: addMetafieldToLocal-------------------------');
            \Log::info(json_encode($e));
            return response::json(['data' => $e], 422);
        }
    }

    public function addAssetToShopify($api, $image, $action)
    {
        try {
            $theme = Shop::select('theme_id')->where('id', $this->shop->id)->first();
            $theme_id = $theme->theme_id;
            $url = Storage::disk('public')->url('uploads/').$image;
            $path = \Storage::path('uploads/'.$image);

            if ($action == 'add') {
                $asset = [
                    "asset" => [
                        "key" => "assets/".$image,
                        "src" => $url
                    ]
                ];

                $endPoint = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/themes/'.$theme_id.'/assets.json';
                $sh_asset = $api->rest('PUT', $endPoint, $asset);
                if (file_exists($path)) {
                    unlink($path);
                }
                $sh_url = $sh_asset->body->asset->public_url;

                return $sh_url;
            } elseif ($action == 'remove') {
                $key = "assets/".$image;
                $endPoint = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/themes/'.$theme_id.'/assets.json';
                $param['asset[key]'] = $key;
                $sh_asset = $api->rest('DELETE', $endPoint, $param);

                return $sh_asset;
            }

        } catch (Exception $e) {
            DB::rollBack();
            \Log::info('-----------------------ERROR: addAssetToShopify-------------------------');
            \Log::info(json_encode($e));
            return false;
        }
    }

    public function removeMetafield(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->shop = \ShopifyApp::shop();
            $this->api = $this->shop->api();
            $this->resourceType = $request->resourceType;
            $this->lclresourceType = $this->lclresourceType.$this->resourceType;
            $this->syncresourceType = $this->syncresourceType.$this->resourceType;
            if ($request->isMultiCheck) {
                if ($request->checkPageOnly) {
                    $this->deleteMetafield($request->data);
                } else {
                    $entity = Metafield::select('metafield_id', 'owner_id')->where('shop_id',
                        $this->shop->id)->whereIn('resource_type', array($this->resourceType, $this->lclresourceType, $this->syncresourceType))->get();
                    if( $entity ){
                        $res = $this->removeMetafieldFromLocal($entity);
                    }
                }
            } elseif ($request->isSingleCheck) {
                $this->deleteMetafield($request->data);
            }
            DB::commit();
            return response::json(['data' => 'Metafield Deleted!'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info('-----------------------ERROR: removeMetafield-------------------------');
            \Log::info(json_encode($e));
            return response::json(['data' => $e->getMessage()], 422);
        }
    }

    public function deleteMetafield($data){
        foreach ($data as $key => $val) {
            $entity = Metafield::select('metafield_id', 'owner_id')->where('shop_id',
                $this->shop->id)->where('owner_id', $val)->whereIn('resource_type', array($this->resourceType, $this->lclresourceType, $this->syncresourceType))->get();
            if( $entity ){
                $this->removeMetafieldFromLocal($entity);
            }
        }
    }
    public function removeMetafieldFromLocal($entity)
    {
        try {
            DB::beginTransaction();
            foreach ($entity as $key=>$val){
                $this->ownerId = $val->owner_id;
                $this->removeMetafieldFromShopify($val->metafield_id);
                Metafield::where('metafield_id', $val->metafield_id)->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info('-----------------------ERROR: removeMetafieldFromLocal-------------------------');
            \Log::info(json_encode($e));
            return $e->getMessage();
        }
    }

    public function deleteSingleMetafield(Request $request){
        try {
            DB::beginTransaction();
            $this->shop = shShop();
            $this->api = shShop()->api();
            $id = $request->id;
            $entity = Metafield::where('id', $id)->where('shop_id', shShop()->id)->first();
            if( $entity ){
                $metafield_id = $entity->metafield_id;
                $type = $entity->metafield_type;
                $rtl = $entity->resource_type;
                $value = $entity->value;

                $configuration = MetafieldConfiguration::where('id', $entity->metafield_configuration_id)->whereIn('resource_type', array('lcl_' . $rtl, 'sync_' . $rtl))->first();
                if( $configuration ){
                    $configuration->delete();
                }
                $entity->delete();
                $this->removeMetafieldFromShopify($metafield_id);

                if ($type == 'image' || $type == 'multiple_image' || $type == 'file') {
                    $value = explode(',', $value);

                    foreach ($value as $v) {
                        $image = basename(substr($v, 0, strpos($v, '?')));
//                        $this->addAssetToShopify($this->api, $image, 'remove');
                    }
                }
            }
            $data['title'] = 'Success!';
            $data['msg'] = 'Metafield Deleted Successfully...';
            DB::commit();
            return response::json(['data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response::json(['data' => $e], 422);
        }
    }
    public function storeImages(){
        // never delete
    }

    public function globalMetafield(GlobalMetafieldRequest $request){
          try{
              $data = $request->all();
              if( $data['isSubmit'] ){
                  $this->shop = \ShopifyApp::shop();
                  $this->api = $this->shop->api();
                  $data = json_decode($request->data, 1);

                  $meta_config = ( $data['id'] == '' ) ? new MetafieldConfiguration : MetafieldConfiguration::where('shop_id', $this->shop->id)->where('id', $data['id'])->where('resource_type', 'global_'. $data['resourceType'])->first();

                  $meta_config->shop_id = $this->shop->id;
                  $meta_config->label = $data['label'];
                  $meta_config->namespace = $data['namespace'];
                  $meta_config->key = $data['key'];
                  $meta_config->type = $data['typev'];
                  $meta_config->sort_order = 0;
                  $meta_config->resource_type = 'global_' . $data['resourceType'];
                  $meta_config->save();

                  if( $data['id'] == '' ){
                      if( $data['add_in'] == 'all' ){
                          $title = '';
                      }else {
                          foreach ($data['resourceList'] as $key => $val) {
                              $title[] = $val['title'];
                          }
                          $title = implode(',', $title);
                      }

                      \Log::info($title);
                      $global_metafield = new GlobalMetafield;
                      $global_metafield->shop_id = $this->shop->id;
                      $global_metafield->metafield_configuration_id = $meta_config->id;
                      $global_metafield->add_in = $data['add_in'];
                      $global_metafield->titles = $title;
                      $global_metafield->value = $data['value'];
                      $global_metafield->status = 'in_progress';
                      $global_metafield->resource_type = $data['resourceType'];
                      $global_metafield->save();
                  }else{
                      $global_metafield = GlobalMetafield::where('metafield_configuration_id', $data['id'])->where('shop_id', $this->shop->id)->first();
                      $global_metafield->value = $data['value'];
                      $global_metafield->status = 'in_progress';
                      $global_metafield->save();
                  }

                  if( $data['typev'] == 'file' || $data['typev'] == 'image'){
                      $data['value'] = $this->uploadImage($request->file, 'add');
                  }elseif ( $data['typev'] == 'multiple_image' ){
                      $images = $request->file;
                      $v = [];
                      foreach ($images as $imagek => $imagev) {
                          if (gettype($imagev) == "string") {
                              $v[$imagek] = $imagev;
                              continue;
                          }
                          $v[$imagek] = $this->uploadImage($imagev, 'add');
                      }
                      $data['value'] = implode(',', $v);
                  }
                  GlobalMetafieldJob::dispatch($data, $this->shop, $meta_config->id, $global_metafield->id);

                  $action = ($data['id'] == '') ? 'creating' : 'editing';
                  $data['msg'] = "Global metafield $action in background..";
                  return response::json(['data' => $data], 200);
              }else{
                  return response::json(['data' => true], 200);
              }
          }catch(\Exception $e){
              DB::rollBack();
              \Log::info('-----------------------ERROR: globalMetafield-------------------------');
              \Log::info(json_encode($e));
              return response::json(['data' => $e], 422);
          }
    }

    public function getGlobalMetafields(Request $request){
        try{
            $rtype = 'global_' . $request->rtype;
            $entities = MetafieldConfiguration::with('hasOneGlobalMetafield')->where('shop_id', shShop()->id)->where('resource_type', $rtype)->paginate(10);

            if ($entities) {
                $entity = $entities->map(function ($name) {
                    $add_in = '';
                    if( $name->hasOneGlobalMetafield ){
                       $d = $name->hasOneGlobalMetafield;
                       if( $d->add_in == 'all' ){
                           $add_in = 'all';
                       }else{
                           $titles = explode(',', $d->titles);
                           $add_in = (is_array($titles)) ? count($titles) : 0;
                       }
                    }

                    return [
                        'id' => $name->id,
                        'namespace' => $name->namespace,
                        'key' => $name->key,
                        'type' => $name->type,
                        'label' => $name->label,
                        'add_in' => $add_in,
                        'status' => ( $name->hasOneGlobalMetafield ) ? $name->hasOneGlobalMetafield->status : '',
                    ];
                })->toArray();
            }

            $data['data'] = $entity;
            $data['prev'] = $entities->previousPageUrl();
            $data['next'] = $entities->nextPageUrl();
            return response::json(['data' => $data], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response::json(['data' => $e], 422);
        }
    }

    public function getGlobalMetafield(Request $request){
        try{
           $id = $request->id;
           $entity = MetafieldConfiguration::where('id', $id)->where('shop_id', shShop()->id)->first();

           $global_metafield = GlobalMetafield::where('metafield_configuration_id', $id)->where('shop_id', shShop()->id)->first();


                   if( $global_metafield ){
                       $t = explode(',', $global_metafield->titles);
                       foreach ($t as $k=>$v){
                           $r[$k]['title'] = $v;
                       }
                   }else{
                       $r = [];
                   }

            if( $entity ){
                $data['id'] = $entity->id;
                $data['namespace'] = $entity->namespace;
                $data['key'] = $entity->key;
                $data['typev'] = $entity->type;
                $data['label'] = $entity->label;
                $data['value'] = ( $global_metafield  ) ? $global_metafield->value : '';
                $data['add_in'] = ( $global_metafield  ) ? $global_metafield->add_in : '';
                $data['resourceList'] = $r;
                $data['resourceType'] = '';
               }
            return response::json(['data' => $data], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response::json(['data' => $e], 422);
        }
    }

    public function removeGlobalMetafield(Request $request){
        try{
            RemoveGlobalMetafieldJob::dispatch($request->id, $request->rt, shShop());
            $msg = 'Global metafield removing in background..';
            return response::json(['data' => $msg], 200);
        }catch (\Exception $e){
            DB::rollBack();
            return response::json(['data' => $e], 422);
        }
    }
}

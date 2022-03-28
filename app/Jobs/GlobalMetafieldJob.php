<?php

namespace App\Jobs;

use App\Model\BackgroundActivity;
use App\Model\GlobalMetafield;
use App\Model\Metafield;
use App\Model\Setting;
use App\Model\SyncedDetails;
use App\Traits\GraphQLTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GlobalMetafieldJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, GraphQLTrait;

    private $shop = '';
    private $data = '';
    private $resourceType = '';
    private $meta_config_id = '';
    private $global_metafield_id = '';
    public $timeout = 0;
    private $sync_id = '';
    private $background_activity_id = '';
    /**
     * resource type to export csv
     */
    private $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $shop, $meta_config_id, $global_metafield_id)
    {
        $this->data = $data;
        $this->shop =  $shop;
        $this->resourceType = $data['resourceType'];
        $this->meta_config_id = $meta_config_id;
        $this->global_metafield_id = $global_metafield_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            \Log::info('----------------------Global metafield job------------------------');
            $data = $this->data;
            \Log::info(json_encode($data));
            if( $data['id'] == '' ){
                $type = $this->resourceType . '-createglobalmetafield';
                $action = 'creating';
            }else{
                $type = $this->resourceType . '-editglobalmetafield';
                $action = 'editing';
            }
            $background_activity = new BackgroundActivity;
            $background_activity->shop_id = $this->shop->id;
            $background_activity->resource_type =  $type;
            $background_activity->status = 'In Progress';
            $background_activity->save();
            $this->background_activity_id = $background_activity->id;

            $sync_detail = new SyncedDetails;
            $sync_detail->shop_id = $this->shop->id;
            $sync_detail->resource_type = $this->resourceType;
            $sync_detail->status = 'in_progress';
            $sync_detail->type = 'global_metafield';
            $sync_detail->description = str_replace('_', ' ', $this->resourceType).' global metafield are '. $action ;
            $sync_detail->save();
            $this->sync_id = $sync_detail->id;

            $value_type = ($data['typev'] == 'json') ? 'json_string' : 'string';

            $metafieldJson = [
                "metafield" => [
                    'namespace' => $data['namespace'],
                    'key' => $data['key'],
                    'value' => $data['value'],
                    'value_type' => $value_type
                ]
            ];
           if( $data['add_in'] == 'all' ){
               $this->getData('', $metafieldJson, $this->meta_config_id, $data);
            }else{
               if( $data['id'] == '' ) {
                   foreach ($data['resourceList'] as $key => $val) {
                       $sh_metafield = $this->addMetafieldToShopify($metafieldJson, $val['resource_id']);
                       $this->addMetafieldToLocal($sh_metafield, $this->meta_config_id, $data['value'],
                           $data['typev'], $val);
                   }
               }else{
                   $entities = Metafield::where('metafield_configuration_id', $data['id'])->where('shop_id', $this->shop->id)->get();
                   foreach($entities as $key=>$val){
                       $resource['resource_id'] = $val->owner_id;
                       $resource['handle'] = $val->handle;
                       $sh_metafield = $this->addMetafieldToShopify($metafieldJson, $val->owner_id);
                       $this->addMetafieldToLocal($sh_metafield, $this->meta_config_id, $data['value'],
                           $data['typev'], $resource);
                   }
               }
           }

           // send mail
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
//                $email2 = 'ruchita.crawlapps@gmail.com';
                \Log::info($email2);
            }

            $data = [
                'rtype' => $this->resourceType,
                'action' => $action
            ];
            $emails = ( !empty($email1) ) ? array($email1, $email2) : $email2;
            Mail::send('CSVMailFormat.globalvalue', $data, function ($message) use ($emails) {
                $message->to($emails, 'Global metafield information');
                $message->subject("Your global metafield information");
            });

            $global = GlobalMetafield::where('id', $this->global_metafield_id)->first();
            $global->status = 'complete';
            $global->save();

            $sync_detail->status = 'complete';
            $sync_detail->description = str_replace('_', ' ', $this->resourceType).'global metafield creating is complete.';
            $sync_detail->delete();

            $background_activity->status = 'Complete';
            $background_activity->save();
        } catch (\Exception $e) {
            \Log::info('---------- ERROR :: handle--------------');
            \Log::info(json_encode($e));
            $background_activity = BackgroundActivity::where('id', $this->background_activity_id)->where('shop_id',
                $this->shop->id)->first();
            $background_activity->status = 'Failed';
            $background_activity->save();

            $global = GlobalMetafield::where('id', $this->global_metafield_id)->first();
            \Log::info(json_encode($global));
            $global->status = 'complete';
            $global->save();

            \Log::info($this->sync_id);
            $sync = SyncedDetails::where('id', $this->sync_id)->where('shop_id', $this->shop->id)->first();
            $sync->delete();
        }
    }

    public function getData($page, $metafieldJson, $meta_config_id, $data){
        \Log::info('----------------------getData------------------------');
        try {
            $endPoint = '/admin/api/'.env('SHOPIFY_API_VERSION').'/' . $this->resourceType . '.json';

            $rnm = ( $this->resourceType == 'customers' ) ? 'email' : 'handle';
            $rnm = ( $this->resourceType == 'orders' ) ? "name" : $rnm;
            $fields = "id,$rnm";
            $result = $this->getResult(10, $page, $fields, $endPoint);
            if ($result->errors) {
                return;
            } else {
                $rt = $this->resourceType;
                $sh_data = $result->body->$rt;
                if (count($sh_data) > 0) {
                    foreach ($sh_data as $key => $val) {
                        $sh_metafield = $this->addMetafieldToShopify($metafieldJson, $val->id);
                        $v['resource_id'] = $val->id;
                        $v['handle'] = $val->$rnm;
                        $this->addMetafieldToLocal($sh_metafield, $meta_config_id, $data['value'],
                            $data['typev'], $v);
                    }
                    if ($result->link) {
                        $page = ($result->link->next) ? $result->link->next : '';
                        ($result->link->next) ? $this->getData($page, $metafieldJson, $meta_config_id, $data) : '';
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::info('-----------------------ERROR: getData-------------------------');
            \Log::info(json_encode($e));
        }
    }

    public function addMetafieldToShopify($metafield, $owner_id)
    {
        \Log::info('----------------------addMetafieldToShopify------------------------');
        try {
            $endPoint =  '/admin/api/'. env('SHOPIFY_API_VERSION') .'/'.$this->resourceType.'/'.$owner_id.'/metafields.json';

            $result = $this->shop->api()->rest('POST', $endPoint, $metafield);   // shopify metafield result.
            return $result->body;

        } catch (\Exception $e) {
            \Log::info('-----------------------ERROR: addMetafieldToShopify-------------------------');
            \Log::info(json_encode($e));
            return response::json(['data' => $e], 422);
        }
    }

    public function addMetafieldToLocal($sh_metafield, $metaConfigId, $value, $type, $resource)
    {
        \Log::info('----------------------addMetafieldToLocal------------------------');
        try {
            DB::beginTransaction();
            $global_meta = Metafield::where('shop_id', $this->shop->id)->where('metafield_id',
                $sh_metafield->metafield->id)->where('owner_id', $resource['resource_id'])->where('metafield_configuration_id', $metaConfigId)->first();

            if ($global_meta) {
                $entity = $global_meta;
            } else {
                $entity = new Metafield();
            }

            $entity->shop_id = $this->shop->id;
            $entity->owner_id = $resource['resource_id'];
            $entity->handle = $resource['handle'];
            $entity->metafield_configuration_id = $metaConfigId;
            $entity->value = $value;
            $entity->metafield_id = $sh_metafield->metafield->id;
            $entity->metafield_json = json_encode($sh_metafield);
            $entity->resource_type = 'global_' . $this->resourceType;
            $entity->metafield_type = $type;

            $entity->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info('-----------------------ERROR: addMetafieldToLocal-------------------------');
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
                \Log::info($rate . '>=' . 35);
                if( $rate >= 35 ){
                    \Log::info('sleep 1');
                    sleep(1);
                }
            }
            return $result;
        } catch (\Exception $e) {
            \Log::info('getResult ERROR :: ');
            \Log::info(json_encode($e));
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        $background_activity = BackgroundActivity::where('id', $this->background_activity_id)->where('shop_id',
            $this->shop->id)->first();
        $background_activity->status = 'Failed';
        $background_activity->save();

        \Log::info($this->sync_id);
        $sync = SyncedDetails::where('id', $this->sync_id)->where('shop_id', $this->shop->id)->first();
        $sync->delete();
    }
}

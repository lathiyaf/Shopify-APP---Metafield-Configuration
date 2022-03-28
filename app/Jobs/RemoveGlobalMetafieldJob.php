<?php

namespace App\Jobs;

use App\Model\BackgroundActivity;
use App\Model\GlobalMetafield;
use App\Model\Metafield;
use App\Model\MetafieldConfiguration;
use App\Model\SyncedDetails;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveGlobalMetafieldJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $shop = '';
    private $id = '';
    private $resourceType = '';
    private $sync_id = '';
    private $background_activity_id = '';
    public $timeout = 0;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $rtype, $shop)
    {
        $this->id = $id;
        $this->resourceType = $rtype;
        $this->shop = $shop;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            \Log::info('----------------------handle------------------------');
            $background_activity = new BackgroundActivity;
            $background_activity->shop_id = $this->shop->id;
            $background_activity->resource_type =  $this->resourceType . '-removeglobalmetafield';
            $background_activity->status = 'In Progress';
            $background_activity->save();
            $this->background_activity_id = $background_activity->id;

            $sync_detail = new SyncedDetails;
            $sync_detail->shop_id = $this->shop->id;
            $sync_detail->resource_type = $this->resourceType;
            $sync_detail->status = 'in_progress';
            $sync_detail->type = 'global_metafield';
            $sync_detail->description = str_replace('_', ' ', $this->resourceType).' global metafield are removing.';
            $sync_detail->save();
            $this->sync_id = $sync_detail->id;

            $global = GlobalMetafield::where('metafield_configuration_id', $this->id)->where('shop_id', $this->shop->id)->first();
            $global->status = 'in_progress';
            $global->save();

            $entities = Metafield::where('metafield_configuration_id', $this->id)->where('shop_id', $this->shop->id)->get();
            if( count($entities) > 0 ){
                foreach ($entities as $key=>$val){
                    if($this->removeFromShopify($val->metafield_id)){
                        $val->delete();
                    }
                }
            }

            MetafieldConfiguration::find($this->id)->delete();

            \Log::info('-----------------complete--------------------');
            $sync_detail->status = 'complete';
            $sync_detail->description = str_replace('_', ' ', $this->resourceType).'global metafield creating is complete.';
            $sync_detail->delete();

            $background_activity->status = 'Complete';
            $background_activity->save();
        }catch(\Exception $e){
            \Log::info('----------------------ERROR:: handle------------------------');
            \Log::info(json_encode($e));
            $background_activity = BackgroundActivity::where('id', $this->background_activity_id)->where('shop_id',
                $this->shop->id)->first();
            $background_activity->status = 'Failed';
            $background_activity->save();

            \Log::info($this->sync_id);
            $sync = SyncedDetails::where('id', $this->sync_id)->where('shop_id', $this->shop->id)->first();
            $sync->delete();

            $global = GlobalMetafield::where('metafield_configuration_id', $this->id)->where('shop_id', $this->shop->id)->first();
            $global->status = 'failed';
            $global->save();
        }
    }

    public function removeFromShopify($metafield_id){
        try {
            \Log::info('----------------------removeFromShopify------------------------');
            $endPoint = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/metafields/'.$metafield_id;
            $result = $this->shop->api()->rest('DELETE', $endPoint);   // shopify metafield result.
            return $result->errors;
        } catch (\Exception $e) {
            \Log::info('----------------------ERROR:: removeFromShopify------------------------');
            \Log::info(json_encode($e));
        }
    }

    public function fail($exception = null)
    {
        $background_activity = BackgroundActivity::where('id', $this->background_activity_id)->where('shop_id',
            $this->shop->id)->first();
        $background_activity->status = 'Failed';
        $background_activity->save();

        \Log::info($this->sync_id);
        $sync = SyncedDetails::where('id', $this->sync_id)->where('shop_id', $this->shop->id)->first();
        $sync->delete();

        $global = GlobalMetafield::where('metafield_configuration_id', $this->id)->where('shop_id', $this->shop->id)->first();
        $global->status = 'failed';
        $global->save();
    }
}

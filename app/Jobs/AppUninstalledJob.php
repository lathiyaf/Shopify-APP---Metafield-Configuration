<?php namespace App\Jobs;

use App\Model\BackgroundActivity;
use App\Model\ExportCSV;
use App\Model\GlobalMetafield;
use App\Model\Metafield;
use App\Model\MetafieldConfiguration;
use App\Model\SyncedDetails;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AppUninstalledJob extends \OhMyBrew\ShopifyApp\Jobs\AppUninstalledJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('--------------- app uninstall ---------------------');

        if (!$this->shop) {
            return false;
        }
        $this->deleteRecords();
        $this->cancelCharge();
        $this->cleanShop();
        $this->softDeleteShop();
    }

    public function deleteRecords(){
        Metafield::where('shop_id', $this->shop->id)->delete();
        GlobalMetafield::where('shop_id', $this->shop->id)->delete();
        MetafieldConfiguration::where('shop_id', $this->shop->id)->delete();
        ExportCSV::where('shop_id', $this->shop->id)->delete();
        BackgroundActivity::where('shop_id', $this->shop->id)->delete();
        SyncedDetails::where('shop_id', $this->shop->id)->delete();
    }
}

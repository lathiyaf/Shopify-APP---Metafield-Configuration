<?php

namespace App\Jobs;

use App\Exports\MetafieldsCSVExports;
use App\Model\SyncedDetails;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;

class ExportConfigurationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $shop;
    public $timeout = 0;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->shop = ShopifyApp::shop();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('--------- Export Configuration CSV ----------');
        $sync_detail = new SyncedDetails;
        $sync_detail->shop_id = $this->shop->id;
        $sync_detail->resource_type = 'all';
        $sync_detail->status = 'in_progress';
        $sync_detail->type = 'export configuration';
        $sync_detail->description = 'metafields configurations are exporting.';
        $sync_detail->save();



        \Log::info('--------- complete ----------');
//                $csvs = SyncedDetails::find($sync_detail->id);
        $sync_detail->description = str_replace('_', ' ', $this->resource_type).'configuration exporting is complete.';
        $sync_detail->delete();
    }
}

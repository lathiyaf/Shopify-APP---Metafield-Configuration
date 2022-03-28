<?php

namespace App\Jobs;

use App\Exports\MetafieldsCSVExports;
use App\Exports\OutletStoreProducts;
use App\Imports\MetafieldImport;
use App\Model\BackgroundActivity;
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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use Maatwebsite\Excel\Facades\Excel;

class OutletStoreProductsCSVJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, GraphQLTrait;


    public $timeout = 0;
    /**
     * resource type to export csv
     */
    private $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            \Log::info('---------- OutletStoreProductsCSVJob --------------');
            $filename = date('d-m-yy-h-m-s') .'OutletStoreProducts-1.csv';
            $res = Excel::store(new OutletStoreProducts(), $filename, 'public');
        } catch (\Exception $e) {
            \Log::info('---------- ERROR --------------');
            \Log::info(json_encode($e));

        }
    }
}

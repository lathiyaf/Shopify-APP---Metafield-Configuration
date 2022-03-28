<?php

namespace App\Jobs;

use App\Exports\MetafieldsCSVExports;
use App\Imports\MetafieldConfigurationImport;
use App\Imports\MetafieldImport;
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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use Maatwebsite\Excel\Facades\Excel;

class ImportConfigurationCSVJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, GraphQLTrait;


    private $file;
    private $shop;
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
    public function __construct($file_path)
    {
        $this->file = $file_path;
        $this->shop =  ShopifyApp::shop();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            session(['rowNumber' => 1]);
            session(['mailData' => []]);
            \Log::info('------------ import CSV --------------');
            $sync_detail = new SyncedDetails;
            $sync_detail->shop_id = $this->shop->id;
            $sync_detail->resource_type = 'all';
            $sync_detail->status = 'in_progress';
            $sync_detail->type = 'import';
            $sync_detail->description = 'metafields configuration are importing.';
            $sync_detail->save();

            $res = Excel::import(new MetafieldConfigurationImport($this->shop), $this->file);

            \Log::info('---- end ----');
            unlink($this->file);
            $sync_detail->status = 'complete';
            $sync_detail->description = 'Configuration importing is complete.';
            $sync_detail->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info(json_encode($e));
        }
    }
}

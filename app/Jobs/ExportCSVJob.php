<?php

namespace App\Jobs;

use App\Exports\MetafieldsCSVExports;
use App\Model\BackgroundActivity;
use App\Model\ExportCSV;
use App\Model\Metafield;
use App\Model\Setting;
use App\Model\Shop;
use App\Model\SyncedDetails;
use App\Traits\GraphQLTrait;
use App\Traits\ImageTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use Maatwebsite\Excel\Facades\Excel;

class ExportCSVJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, GraphQLTrait;

    private $data;
    private $export_metafield;
    private $id;
    public $timeout = 0;
    private $background_activity_id = '';
    private $sync_id = '';
    private $csvs_id = '';
    /**
     * Shop's id
     */
    private $shop;

    /**
     * resource type to export csv
     */
    private $resource_type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($resourceType, $export_metafield, $data, $id, $shop_id)
    {
        $this->shop = $shop = Shop::where('id', $shop_id)->first();
        $this->resource_type = $resourceType;
        $this->data = $data;
        $this->id = $id;
        $this->export_metafield = $export_metafield;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            \Log::info('--------- Export CSV ----------');
            $job_id = $this->job->getJobId();
            \Log::info($job_id);
            $background_activity = new BackgroundActivity();
            $background_activity->shop_id = $this->shop->id;
            $background_activity->job_id = $job_id;
            $background_activity->resource_type = $this->resource_type . '-export';
            $background_activity->status = 'In Progress';
            $background_activity->save();

            \Log::info(json_encode($background_activity));
            $this->background_activity_id = $background_activity->id;
            \Log::info( $this->background_activity_id);
            $sync_detail = new SyncedDetails;
            $sync_detail->shop_id = $this->shop->id;
            $sync_detail->job_id =  $this->job->getJobId();
            $sync_detail->resource_type = $this->resource_type;
            $sync_detail->status = 'in_progress';
            $sync_detail->type = 'export';
            $sync_detail->description = str_replace('_', ' ', $this->resource_type).' metafields are exporting.';
            $sync_detail->save();

            $this->sync_id = $sync_detail->id;

            $fileName = $this->resource_type.'_metafields_export_'.date('d-m-yy-h-m-s').'.csv';
            $csvs = new ExportCSV;
            $csvs->shop_id = $this->shop->id;
            $csvs->resource_type = $this->resource_type;
            $csvs->file = $fileName;
            $csvs->status = 'In Progress';
            $csvs->save();

            $this->csvs_id = $csvs->id;

//            $res = Excel::store(new MetafieldsCSVExports($this->resource_type, $this->shop->id, $this->export_metafield,
//                $this->data, $this->id), $fileName, 'public');
//            \Log::info('----res----');

            $file = \Storage::path($fileName);
            $data['file'] = $file;
            $data['fileName'] = $fileName;

            $setting = Setting::select('value')->where('key', 'email')->where('shop_id', $this->shop->id)->first();
            if ($setting) {
                $email1 = $setting->value;
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
            }

            $emails = (!empty($email1)) ? array($email1, $email2) : $email2;
            \Log::info(json_encode($emails));
            Mail::send('CSVMailFormat.csvmail', [], function ($message) use ($data, $emails) {
                $message->to($emails, 'csv');
                $message->subject("Your ".str_replace('_', ' ',
                        $this->resource_type)." Metafield CSV Export is complete");
                $message->attach(($data['file']),
                    [
                        'as' => $data['fileName'],
                        'mime' => 'text/csv',
                    ]);
            });
            \Log::info('--------- complete ----------');
            $background_activity->status = 'Complete';
            $background_activity->save();

//                $csvs = SyncedDetails::find($sync_detail->id);
            $sync_detail->description = str_replace('_', ' ', $this->resource_type).' exporting is complete.';
            $sync_detail->delete();

//                $csvs = ExportCSV::find($csvs->id);
            $csvs->status = 'Complete';
            $csvs->save();


//            DB::commit();
        } catch (\Exception $e) {
            \Log::info('------------- ERROR ---------------');
            \Log::info($e->getMessage());

            $background_activity = BackgroundActivity::where('id', $this->background_activity_id)->where('shop_id',
                $this->shop->id)->first();
            $background_activity->status = 'Failed';
            $background_activity->save();

            $csvs = ExportCSV::where('id', $this->csvs_id)->where('shop_id', $this->shop->id)->first();
            $csvs->delete();

            $sync = SyncedDetails::where('id', $this->sync_id)->where('shop_id', $this->shop->id)->first();
            $sync->delete();
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
//    public function failed(\Exception $exception)
//    {
//        \Log::info('------ export csv job failed------');
//        \Log::info(json_encode($this->background_activity_id));
////        $background_activity = BackgroundActivity::where('id', $this->background_activity_id)->where('shop_id', $this->shop->id)->first();
////        $background_activity->status = 'Failed';
////        $background_activity->save();
//    }
}

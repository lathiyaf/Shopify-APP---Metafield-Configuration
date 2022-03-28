<?php

namespace App\Jobs;

use App\Exports\MetafieldsCSVExports;
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

class ImportCSVJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, GraphQLTrait;


    private $resourceType;
    private $is_replace;
    private $file;
    private $shop;
    private $sync_id = '';
    private $background_activity_id = '';
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
    public function __construct($rtype, $is_replace, $file_path, $shop_id)
    {
        $this->resourceType = $rtype;
        $this->is_replace = $is_replace;
        $this->file = $file_path;
        $this->shop =  Shop::where('id', $shop_id)->first();
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
            \Log::info("------------ import CSV {{$this->resourceType}}--------------");

            $background_activity = new BackgroundActivity();
            $background_activity->shop_id = $this->shop->id;
            $background_activity->resource_type = $this->resourceType . '-import';
            $background_activity->status = 'In Progress';
            $background_activity->save();
            $this->background_activity_id = $background_activity->id;

            $sync_detail = new SyncedDetails;
            $sync_detail->shop_id = $this->shop->id;
            $sync_detail->resource_type = $this->resourceType;
            $sync_detail->status = 'in_progress';
            $sync_detail->type = 'import';
            $sync_detail->description = str_replace('_', ' ', $this->resourceType).' metafields are importing.';
            $sync_detail->save();
            $this->sync_id = $sync_detail->id;

            $res = Excel::import(new MetafieldImport( $this->resourceType, $this->is_replace, $this->shop), $this->file);

            \Log::info('---- end ----');
            unlink($this->file);

            $mailData = Session::get('mailData');
            \Log::info(json_encode($mailData));
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
                \Log::info($email2);
//                    $email2 = 'ruchita.crawlapps@gmail.com';
            }

            $emails = ( !empty($email1) ) ? array($email1, $email2) : $email2;
            if (!empty($mailData)) {
                Mail::send('CSVMailFormat.importcsvmail', [], function ($message) use ($emails) {
                    $message->to($emails, 'Metafield import information');
                    $message->subject("Your Metafield import file information");
                });
            }else{
                Mail::send('CSVMailFormat.importsuccess', [], function ($message) use ($emails) {
                    $message->to($emails, 'Metafield import information');
                    $message->subject("Your Metafield import file information");
                });
            }
            $sync_detail->status = 'complete';
            $sync_detail->description = str_replace('_', ' ', $this->resourceType).' importing is complete.';
            $sync_detail->delete();

            $background_activity->status = 'Complete';
            $background_activity->save();
//            DB::commit();
        } catch (\Exception $e) {
            \Log::info('---------- ERROR --------------');
//            DB::rollBack();
            \Log::info(json_encode($e));
            $background_activity = BackgroundActivity::where('id', $this->background_activity_id)->where('shop_id',
                $this->shop->id)->first();
            $background_activity->status = 'Failed';
            $background_activity->save();

            $sync = SyncedDetails::where('id', $this->sync_id)->where('shop_id', $this->shop->id)->first();
            $sync->delete();

        }
    }
}

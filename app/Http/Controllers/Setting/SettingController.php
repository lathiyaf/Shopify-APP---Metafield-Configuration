<?php

namespace App\Http\Controllers\Setting;

use App\Events\ProcesingJob;
use App\Exports\MetafieldsConfigurationCSVExports;
use App\Exports\MetafieldsCSVExports;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Imports\MetafieldImport;
use App\Jobs\ExportConfigurationJob;
use App\Jobs\ExportCSVJob;
use App\Jobs\ImportConfigurationCSVJob;
use App\Jobs\ImportCSVJob;
use App\Jobs\SyncMetafieldsJob;
use App\Model\BackgroundActivity;
use App\Model\ExportCSV;
use App\Model\Metafield;
use App\Model\MetafieldConfiguration;
use App\Model\Setting;
use App\Model\SyncedDetails;
use App\Traits\GraphQLTrait;
use App\Traits\ImageTrait;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use Response;

class SettingController extends Controller
{
    use GraphQLTrait, ImageTrait;
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
            $shop = ShopifyApp::shop();
            $entities = Setting::where('shop_id', $shop->id)->get();

            $entity = [];
            if( $entities ){
                foreach ($entities as $key=>$val){
                    $entity[$val->key] = $val->value;
                }
            }

            return response::json(['data' => $entity], 200);
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
    public function store( SettingRequest $request)
    {
        try{
            DB::beginTransaction();
            $shop = \ShopifyApp::shop();
            $data = $request['data'];
            foreach($data as $key=>$val){
                $setting = Setting::where('key', $key)->where('shop_id', $shop->id)->first();
                if( $setting ){
                    $setting->value = $val;
                }else{
                    $setting = new Setting();
                    $setting->shop_id = $shop->id;
                    $setting->key = $key;
                    $setting->value = $val;
                }
                $setting->save();
            }

            DB::commit();
            return response::json(['data' => 'Saved!'], 200);
        }catch( \Exception $e ){
            DB::rollBack();
            return response::json(['data' => $e], 422);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */

    public function generateCSV(Request $request)
    {
        try {
            $resourceType = $request['resourceType'];
            $shop = \ShopifyApp::shop();
            $queue = $resourceType . '_' . $shop->id;

            if ($request['type'] == 'export') {
                if( $resourceType == 'shop' ){
                    $entities = Metafield::select('id', 'metafield_configuration_id', 'value','owner_id','metafield_id')->whereIn('resource_type', array( $resourceType, $request->resource_type, 'lcl_' . $resourceType, 'sync_' . $resourceType, 'global_' . $resourceType))->where('shop_id', $shop->id)->count();
                }else{
                    if( $request->export_metafield == 'all' ){
                        $entities = Metafield::select('id', 'metafield_configuration_id', 'value','owner_id','metafield_id', 'resource_type')->whereIn('resource_type', array( $resourceType, 'lcl_' . $resourceType, 'sync_' . $resourceType, 'global_' . $resourceType))->where('shop_id', $shop->id)->count();
                    }else{
                        $entities = Metafield::select('id', 'metafield_configuration_id', 'value','owner_id','metafield_id')->whereIn('resource_type', array( $resourceType, 'lcl_' . $resourceType, 'sync_' . $resourceType, 'global_' . $resourceType))->where('shop_id', $shop->id)->whereIn('owner_id', $request->data)->count();
                    }
                }
                if( $entities > 0 ){
                    \Log::info('---exportCSV---');

                    $id = ( $resourceType == 'articles' || $resourceType == 'variants' ) ? $request->id : '';

                    $res = ExportCSVJob::dispatch($resourceType, $request->export_metafield, $request->data, $id, $shop->id);
                    $data['msg'] = 'Exporting started in background.';

                }else{
//                    $data['title'] = 'Export Warning!!';
                   $msg = 'No metafield found.';
                    return response()->json(['data' => $msg], 422);
                }
            } else {
                $file = ImageTrait::makeImage($request->file('csv_file'), 'uploads/');
                $file_path = Storage::disk('public')->path('uploads/' . $file);

                $header = ['namespace', 'key', 'type', 'value', 'label', 'owner_id'];
                $row = 1;
                if (($handle = fopen($request->file('csv_file'), "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                        if( $row == 1 ){
                            $num = count($data);
                            if( $request->resourceType == 'shop' ){
                                $r = 5;
                            }else{
                               if($request->resourceType == 'variants' || $request->resourceType == 'articles' ){
                                   $header = ['namespace', 'key', 'type', 'value', 'label', 'owner_id'];
                                   $r = 6;
                               }else{
                                   $r = 7;
                                   $new = ( $request->resourceType == 'orders' ) ? 'name' : 'handle';
                                   $new = ( $request->resourceType == 'customers' ) ? 'email' : $new;
                                   $header = ['namespace', 'key', 'type', 'value', 'label', 'owner_id', $new];
                               }
                            }
                            if( $num < $r ){
                                $msg = 'Uploaded file is not like sample file.';
                                return response()->json(['data' => $msg], 422);
                            }
                            for ($c = 0; $c < $num; $c++) {
                                if( !in_array(trim($data[$c]), $header) ){
                                    $msg = 'Uploaded file is not like sample file.';
                                    return response()->json(['data' => $msg], 422);
                                }
                            }
                        }
                        $row++;
                    }
                }
                ImportCSVJob::dispatch($resourceType, $request->is_replace, $file_path, $shop->id);
                $data['msg'] = 'Importing started in background.';
                }
            return response()->json(['data' => $data], 200);
        }catch( \Exception $e ){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    public function getExportedCSV(){
        try{
            $shop = \ShopifyApp::shop();
            $entities = ExportCSV::where('shop_id', $shop->id)->orderBy('created_at', 'desc')->paginate(10);

            if( $entities ){
                $entity = $entities->map(function ($name) {
                    return [
                        'module' => $name->resource_type,
                        'status' => $name->status,
                        'file' => Storage::disk('public')->url($name->file),
                        'created_at' => date_format($name->created_at,"Y-m-d H:i:s")
                    ];
                })->toArray();
            }

            $data['data'] = $entity;
            $data['prev'] = $entities->previousPageUrl();
            $data['next'] = $entities->nextPageUrl();
            return response()->json(['data' => $data], 200);
        }catch( \Exception $e ){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    public function getSampleCSV(Request $request){
        return SampleCSVPath($request->t);
    }

    public function syncMeta(Request $request){
        try{
            $shop = shShop();
            $resourceType = $request->rt;
            $id = ( $resourceType == 'articles' || $resourceType == 'variants' ) ? $request->id : '';
            SyncMetafieldsJob::dispatch($resourceType, $id, $shop);
            $msg = 'Syncing started in background.';
            return response()->json(['data' => $msg], 200);
        }catch( \Exception $e ){
            return response()->json(['data' => $e], 422);
        }
    }
    public function getIsSynced(Request $request){
        try{
            $shop = shShop();
            $entities_count = 0;
            $resource = 'global_' . $request->rtype;
            $entities_count = MetafieldConfiguration::where('shop_id', $shop->id)->where('resource_type', $resource)->count();
            $data['synced_data'] = $this->getSyncDetails($request);
            $data['is_global'] = $entities_count > 0;
            return response()->json(['data' => $data], 200);
        }catch( \Exception $e ){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    public function getSyncDetails($request){
        try{
            if( $request->rtype == 'products' ){
                $srch_rt = ['products', 'variants'];
            }elseif ( $request->rtype == 'blogs' ){
                $srch_rt = ['blogs', 'articles'];
            }else{
                $srch_rt = [$request->rtype];
            }
            $sync = SyncedDetails::select('description')->whereIn('resource_type', $srch_rt)->where('shop_id', shShop()->id)->where('status', 'in_progress')->first();

            if( $sync ){
                $data['is_syncing'] = true;
                $data['data'] = $sync->description;
            }else{
                $data['is_syncing'] = false;
            }
            return $data;
        }catch( \Exception $e ){
            return false;
        }
    }

    public function exportImportConfiguration(Request $request){
        try{
            $shop = shShop();
            $data = [];
            $headers = '';
            if( $request->t === 'export' ){
                $entity = MetafieldConfiguration::where('shop_id', $shop->id)->where(\DB::raw('substr(resource_type, 1, 3)'), '!=', 'lcl' )->where(\DB::raw('substr(resource_type, 1, 4)'), '!=', 'sync')->where(\DB::raw('substr(resource_type, 1, 6)'), '!=', 'global')->count();
                if( $entity > 0 ){
//                    ExportConfigurationJob::dispatchNow($shop);
                    $fileName = date('d-m-yy-h-m-s') .'metafields_configuration_export.csv';
//                   $res =  Excel::download(new MetafieldsConfigurationCSVExports, 'Configurations.csv');

                   $res = Excel::store(new MetafieldsConfigurationCSVExports(), $fileName, 'public');

                    $path = Storage::disk('public')->url($fileName);
                    $data['is_download'] = true;
                    $data['file'] = $path;
                    $data['filename'] = $fileName;
                }else{
                    $data['is_download'] = false;
                    $data['title'] = 'Configuration Export Warning!!';
                    $data['msg'] = 'Their are no any configuration to export!';
                }
            }elseif ( $request->t === 'import' ){

                $file = ImageTrait::makeImage($request->file('csv_file'), 'uploads/');
                $file_path = Storage::disk('public')->path('uploads/' . $file);

                $header = ['resource_type', 'namespace', 'key', 'type', 'label'];
                $row = 1;
                if (($handle = fopen($request->file('csv_file'), "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                        if( $row == 1 ){
                            $num = count($data);
                            for ($c = 0; $c < $num; $c++) {
                                if( !in_array($data[$c], $header) ){
                                    $msg = 'Uploaded file is not like sample file ';
                                    return response()->json(['data' => $msg], 422);
                                }
                            }
                        }
                        $row++;
                    }
                }
                ImportConfigurationCSVJob::dispatchNow($file_path);
                $data['title'] = 'Import Successfull!';
                $data['msg'] = 'Import started in background..';
            }

            return response()->json(['data' => $data], 200);
        }catch( \Exception $e ){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    public function getActivityLogs(){
        try{
            $shop = \ShopifyApp::shop();
            $entities = BackgroundActivity::where('shop_id', $shop->id)->orderBy('created_at', 'desc')->paginate(10);
            if( $entities ){
                $entity = $entities->map(function ($name) {
                    return [
                        'id' => $name->id,
                        'job_id' => $name->job_id,
                        'module' => $name->resource_type,
                        'status' => $name->status,
                        'created_at' => date_format($name->created_at,"Y-m-d H:i:s")
                    ];
                })->toArray();
            }

            $data['data'] = $entity;
            $data['prev'] = $entities->previousPageUrl();
            $data['next'] = $entities->nextPageUrl();
            return response()->json(['data' => $data], 200);
        }catch( \Exception $e ){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    public function getResource(Request $request)
    {
        try {
            $shop = shShop();
            $resourceType = $request->rt;
            $endPoint = '/admin/api/'.env('SHOPIFY_API_VERSION').'/'.$resourceType.'.json';
            if( $request->s != '' ){
                $endPoint = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/'.$resourceType.'.json';
            }

            $rnm = ( $resourceType == 'customers' ) ? 'email' : 'title';
            $rnm = ( $resourceType == 'orders' ) ? "name" : $rnm;
            $parameter[$rnm] = $request->s;
            $parameter['fields'] = "id,$rnm,handle";
            $parameter['limit'] = 10;
            $parameter['page_info'] = ($request->page != '' || $request->page != null) ? $request->page : '';
            $result = $shop->api()->rest('GET', $endPoint, $parameter);

            if ($result->body->$resourceType) {
                $data = $result->body->$resourceType;
                foreach ($data as $key => $val) {
                    $resource[$key]['resource_id'] = $val->id;
                    $resource[$key]['title'] =  $val->$rnm;
                    if( $resourceType != 'customers' && $resourceType != 'orders' && $resourceType != 'variants') {
                        $resource[$key]['handle'] = $val->handle;
                    }else{
                        $resource[$key]['handle'] = $val->$rnm;
                    }
                    $resource[$key]['image'] = (@$val->image) ? $val->image->src : asset('static_upload/no-image-box.png');
                    $resource[$key]['is_checked'] = false;
                }
            } else {
                $resource = '';
            }

            $data['next'] = ($result->link) ? $result->link->next : '';
            $data['prev'] = ($result->link) ? $result->link->previous : '';
            $data['resource'] = $resource;
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function stoppedJob(Request $request){
        try{
//            Job::where('id', $request->jid)->delete();
            event(new ProcesingJob($request->id, $request->jid));
            $activity = BackgroundActivity::where('id', $request->id)->first();
            if( $activity ){
                $activity->status = 'Stopped';
                $activity->save();
            }
//            return response()->json(['data' => 'Job stopped successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }
}

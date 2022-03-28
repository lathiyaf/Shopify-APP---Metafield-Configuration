<?php

namespace App\Imports;

use App\Model\Metafield;
use App\Model\MetafieldConfiguration;
use App\Model\Setting;
use App\Traits\GraphQLTrait;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use Response;

class MetafieldImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    public $model = Metafield::class;
    public $header = ['namespace', 'key', 'type', 'value', 'label', 'owner_id'];
    public $verifyHeader = false; // Header verification toggle

    public $truncate = false; // We want to truncate table before the import
    private $resourceType;
    private $is_replace;
    private $shop;
    private $owner_id;
    private $handle;

    public function __construct($rtype, $is_replace, $shop)
    {
        \Log::info('metafieldImport');
        $this->resourceType = $rtype;
        $this->is_replace = $is_replace;
        $this->shop = $shop;
    }

    /**
     * @param  array  $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            \Log::info(json_encode($row));
            session(['rowNumber' => Session::get('rowNumber') + 1]);
            DB::beginTransaction();
            $this->owner_id = ($this->resourceType == 'shop') ? 1 : trim($row['owner_id']);
            logger($this->owner_id);
            if( $this->owner_id == '' ){
                if( $this->resourceType != 'variants' || $this->resourceType != 'articles' ){
                    $search_name = ( $this->resourceType == 'orders' ) ? 'name' : 'handle';
                    $search_name = ( $this->resourceType == 'customers' ) ? 'email' : $search_name;

                    $this->handle = $row[$search_name];
                    if( $row[$search_name] == '' ){
                        $r = Session::get('rowNumber');
                        $this->setSession("Row number  $r owner id or $search_name required.");
                    }else {
                        $endPoint = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/'.$this->resourceType.'.json';
                        $parameter[$search_name] = $row[$search_name];
                        $parameter['fields'] = 'id';
                        $result = $this->shop->api()->rest('GET', $endPoint, $parameter);
                        if ($result->errors) {
                            $r = Session::get('rowNumber');
                            $this->setSession("Row number  $r $search_name is invalid.");
                        } else {
                            $rt = $this->resourceType;
                            $data = $result->body->$rt;
                            if (count($data) == 1) {
                                $this->owner_id = $data[0]->id;
                            } else {
                                $r = Session::get('rowNumber');
                                $this->setSession("Row number  $r $search_name is invalid.");
                            }
                        }
                    }
                }else{
                    $r = Session::get('rowNumber');
                    $this->setSession("Row number  $r owner id is missing.");
                }
            }

            if( $row['namespace'] == '' ){
                $setting = Setting::where('shop_id', $this->shop->id)->where('key', 'global_namespace')->where('value', '!=', '')->first();
                if( $setting ){
                    $row['namespace'] = $setting->value;
                }else{
                    $row['namespace'] = 'global';
                }
            }

            if( $this->owner_id != '' ) {
                $value_type = ($row['type'] == 'json') ? 'json_string' : 'string';
                $value_type = ($row['type'] == 'number') ? 'integer' : $value_type;

                if (trim($row['key']) == '' || trim($row['value']) == '') {
                    \Log::info('missing');
                    $r = Session::get('rowNumber');
                    $this->setSession("Row number  $r some data is missing.");
                } else {
                    if (strlen(trim($row['key'])) < 3 || strlen(trim($row['key'])) > 20) {
                        \Log::info('length');
                        $r = Session::get('rowNumber');
                        $this->setSession("Row number  $r Key must be 3 to 20 character long.");
                    } else {
                        $metafieldJson = [
                            "metafield" => [
                                'namespace' => trim($row['namespace']),
                                'key' => trim($row['key']),
                                'value' => trim($row['value']),
                                'value_type' => $value_type
                            ]
                        ];

                        $is_exist_config = MetafieldConfiguration::where('key', trim($row['key']))->where('namespace',
                            trim($row['namespace']))->whereIn('resource_type', array(
                            $this->resourceType, 'lcl_'.$this->resourceType, 'sync_'.$this->resourceType
                        ))->where('shop_id', $this->shop->id)->first();

                        if( $is_exist_config ){
                            $is_exist = Metafield::where('metafield_configuration_id', $is_exist_config['id'])->where('shop_id', $this->shop->id)->where('owner_id', $this->owner_id)->first();

                            if ($is_exist) {
                                if ($this->is_replace === "true") {
                                    $metafield = $is_exist;
    //                                $metafield = Metafield::where('metafield_configuration_id', $is_exist->id)->where('owner_id', $this->owner_id)->first();
                                    $result = $this->addMetafieldToShopify($metafieldJson);
                                    if ($result->errors) {
                                        $r = Session::get('rowNumber');
                                        $this->setSession("Row number $r owner id is not valid");
                                    } else {
                                        if ($metafield) {
                                            $metafield->metafield_type = trim($row['type']);
                                            $metafield->value = trim($row['value']);
                                            $metafield->metafield_id = $result->body->metafield->id;
                                            $metafield->metafield_json = json_encode($result);
                                            $metafield->save();
                                        } else {
                                            $this->addMetafieldToLocal($result, $is_exist->id, $is_exist->resource_type,
                                                trim($row['value']), trim($row['type']));
                                        }
                                    }
                                } else {
                                    // send mail and skip
                                    $r = Session::get('rowNumber');
                                    $this->setSession("Row number $r metafield is already exist");
                                }
                            }else{
                                $result = $this->addMetafieldToShopify($metafieldJson);
                                if ($result->errors) {
                                    $r = Session::get('rowNumber');
                                    $this->setSession("Row number $r owner id is not valid");
                                } else {
                                    $this->addMetafieldToLocal($result, $is_exist_config->id, $is_exist_config->resource_type, trim($row['value']), trim($row['type']));
                                }
                            }
                        } else {

                            $result = $this->addMetafieldToShopify($metafieldJson);
                            if ($result->errors) {
                                $r = Session::get('rowNumber');
                                $this->setSession("Row number $r owner id is not valid");
                            } else {
                                \Log::info('--------store data----------');
                                $meta_config = new MetafieldConfiguration;
                                $meta_config->shop_id = $this->shop->id;
                                $meta_config->namespace = $row['namespace'];
                                $meta_config->key = trim($row['key']);
                                $meta_config->type = trim($row['type']);
                                $meta_config->label = trim($row['label']);
                                $meta_config->resource_type = 'lcl_'.$this->resourceType;
                                $meta_config->sort_order = MetafieldConfiguration::whereIn('resource_type',
                                    array($this->resourceType, 'lcl_'.$this->resourceType))->where('shop_id',
                                    $this->shop->id)->count();
                                $meta_config->save();

                                $this->addMetafieldToLocal($result, $meta_config->id, $meta_config->resource_type, trim($row['value']), trim($row['type']));
                            }
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info(json_encode($e));
            throw new \Exception($e);

        }
    }

    public function setSession($msg){
        $data = Session::get('mailData');
        array_push($data, $msg);
        session(['mailData' =>  $data]);
        \Log::info(Session::get('mailData'));
    }
    public function addMetafieldToShopify($metafieldJson)
    {
        $endPoint = ($this->resourceType == 'shop') ? '/admin/api/'. env('SHOPIFY_API_VERSION') .'/metafields.json' : '/admin/api/'. env('SHOPIFY_API_VERSION') .'/'.$this->resourceType.'/'.$this->owner_id.'/metafields.json';

        logger($endPoint);
        $result = $this->shop->api()->rest('POST', $endPoint, $metafieldJson);
        logger(json_encode($result));
       // shopify metafield result.
        return $result;
    }

    public function addMetafieldToLocal($result, $meta_config_id, $resource_type, $value, $type)
    {
        try {
            $metafield = new Metafield;
            $metafield->shop_id = $this->shop->id;
            $metafield->owner_id = $this->owner_id;
            $metafield->handle = $this->handle;
            $metafield->metafield_configuration_id = $meta_config_id;
            $metafield->value = $value;
            $metafield->metafield_id = $result->body->metafield->id;
            $metafield->metafield_json = json_encode($result);
            $metafield->resource_type = $resource_type;
            $metafield->metafield_type = $type;
            $metafield->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dump($e);
        }
    }

    /**
     * @inheritDoc
     */
//    public function rules(): array
//    {
//        return [
//            'namespace' => 'required',
//            'key' => 'required|min:3|max:20',
//            'value' => 'required',
//            'label' => 'required'
//        ];
//    }

    /**
     * @param  Failure[]  $failures
     * @throws \Maatwebsite\Excel\Validators\ValidationException
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            echo 'Errors: '.implode($failure->errors()).' '; // Actual error messages from Laravel validator
            throw new \Exception('The given data was invalidzsfzdsf.');
//            echo 'Row: ' . $failure->row() . ' '; // row that went wrong
//            echo 'Column: ' . $failure->attribute()  . ' '; // either heading key (if using heading row concern) or column index
//            // $failure->errors(); // Actual error messages from Laravel validator

//            // $failure->values(); // The values of the row that has failed.
//            // echo implode($failure->values());
//            echo 'Value: ' . $failure->values()[$failure->attribute()] . '<br>'; // The values of the row that has failed.
            // echo array_search($failure->attribute(), $failure->values()) . '<br>'; // The values of the row that has failed.
        }
    }
}

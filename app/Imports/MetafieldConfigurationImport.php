<?php

namespace App\Imports;

use App\Model\Metafield;
use App\Model\MetafieldConfiguration;
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

class MetafieldConfigurationImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    public $model = Metafield::class;
    public $header = ['resource_type','namespace', 'key', 'type', 'label'];
    public $verifyHeader = false; // Header verification toggle

    public $truncate = false; // We want to truncate table before the import
    private $shop;

    public function __construct($shop)
    {
        \Log::info('metafieldImport');
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

            $configuration_count = MetafieldConfiguration::where('key', $row['key'])->where('namespace', $row['namespace'])->where('resource_type', $row['resource_type'])->where('shop_id', $this->shop->id)->count();

            \Log::info($configuration_count);
            if( $configuration_count <= 0 ){
                $type = $this->getMetaType($row['type']);
                $configuration = new MetafieldConfiguration;
                $configuration->shop_id = $this->shop->id;
                $configuration->namespace = ( $row['namespace'] ) ? $row['namespace'] : 'global';
                $configuration->key = ( $row['key'] ) ? $row['key'] : 'custom';
                $configuration->type = $type;
                $configuration->label = $row['label'];
                $configuration->sort_order = MetafieldConfiguration::where('shop_id', $this->shop->id)->where('resource_type', $row['resource_type'])->count();
                $configuration->resource_type = $row['resource_type'];
                $configuration->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info(json_encode($e));
            throw new \Exception($e);

        }
    }

    public function getMetaType($type){
         if( $type == 'color_picker' ){
             $t = 'color_picker';
         }elseif ( $type == 'date_picker' ){
             $t = 'date_picker';
         }elseif ( $type == 'json' ){
             $t = 'json';
         }elseif ( $type == 'image' ){
             $t = 'image';
         }elseif ( $type == 'multiple_image' ){
             $t = 'multiple_image';
         }elseif ( $type == 'rich_text_box' ){
             $t = 'rich_text_box';
         }elseif ( $type == 'email' ){
             $t = 'email';
         }elseif ( $type == 'file' ){
             $t = 'file';
         }elseif ( $type == 'url' ){
             $t = 'url';
         }elseif ( $type == 'phone' ){
             $t = 'phone';
         }elseif ( $type == 'number' ){
             $t = 'phone';
         }else{
             $t = 'string';
         }
         return $t;
    }
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

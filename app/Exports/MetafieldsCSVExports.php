<?php

namespace App\Exports;

use App\Jobs\SyncMetafieldsJob;
use App\Model\Metafield;
use App\Model\Shop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MetafieldsCSVExports implements FromCollection, WithHeadings
{
    private $shop;
    private $resource_type;
    private $export_metafield;
    private $data;
    private $id;
    private $owner_ids = [];

    public function __construct($resourceType, $shop_id, $export_metafield, $data, $id)
    {
        \Log::info('Export maatwebsite');
        $this->shop = $shop_id;
        $this->resource_type = $resourceType;
        $this->export_metafield = $export_metafield;
        $this->data = $data;
        $this->id = $id;
    }
    public function collection()
    {
        if( $this->resource_type == 'shop' ){
            $entities = Metafield::select('id', 'metafield_configuration_id', 'value','owner_id','metafield_id')->with('belongsToConfiguration')->whereIn('resource_type', array( $this->resource_type, 'lcl_' . $this->resource_type, 'sync_'. $this->resource_type, 'global_' . $this->resource_type))->where('shop_id', $this->shop)->get();
        }else{
            if( $this->export_metafield == 'all' ){
                if( $this->resource_type == 'variants' || $this->resource_type == 'articles' ){
                    $this->getResource(250, '');
                    $entities = Metafield::select('id', 'metafield_configuration_id', 'value','handle','owner_id','metafield_id')->with('belongsToConfiguration')->whereIn('resource_type', array( $this->resource_type, 'lcl_' . $this->resource_type, 'sync_'. $this->resource_type, 'global_' . $this->resource_type))->where('shop_id', $this->shop)->whereIn('owner_id', $this->owner_ids)->get();

                }else{
                    $entities = Metafield::select('id', 'metafield_configuration_id', 'value','handle','owner_id','metafield_id')->with('belongsToConfiguration')->whereIn('resource_type', array( $this->resource_type, 'lcl_' . $this->resource_type, 'sync_'. $this->resource_type, 'global_' . $this->resource_type))->where('shop_id', $this->shop)->get();
                }
            }else{
                $entities = Metafield::select('id', 'metafield_configuration_id', 'value','handle','owner_id','metafield_id')->with('belongsToConfiguration')->whereIn('resource_type', array( $this->resource_type, 'lcl_' . $this->resource_type, 'sync_'. $this->resource_type, 'global_' . $this->resource_type))->where('shop_id', $this->shop)->whereIn('owner_id', $this->data)->get();
            }
        }

        $entity = $this->createFile($entities);
        return collect($entity);
    }

    public function getResource($limit, $page){
        \Log::info('----------getResource--------------');
        $rt = ( $this->resource_type == 'variants') ? 'products' : 'blogs';
        $endPoint = "/admin/api/" . env('SHOPIFY_API_VERSION') . "/$rt/$this->id/$this->resource_type.json";
        $fields = 'id';
        $result = $this->getResult($limit, $page, $fields, $endPoint);
        if ($result->errors) {
            return;
        } else {
                $rtype = $this->resource_type;
               $resource = $result->body->$rtype;
               foreach ( $resource as $key=>$val ){
                  $this->owner_ids[] = $val->id;
               }
        }
        if( $result->link ){
            $this->getResource(250, $result->link->next);
        }
    }

    public function getResult($limit, $page, $fields, $endPoint)
    {
        try {
            \Log::info('-----------getResult----------------');
            $shop = Shop::where('id', $this->shop)->firstOrFail();
            $parameter['limit'] = $limit;
            $parameter['page_info'] = $page;
            $parameter['fields'] = $fields;
            $result = $shop->api()->rest('GET', $endPoint, $parameter);
            if( !$result->errors ){
                $call_limit = $result->response->getHeader('HTTP_X_SHOPIFY_SHOP_API_CALL_LIMIT')[0];
                $rate = substr($call_limit,0,strpos($call_limit,'/'));
                \Log::info($rate . '>=' . 35);
                if( $rate >= 35 ){
                    \Log::info('sleep 1');
                    sleep(1);
                }
            }
            return $result;
        } catch (\Exception $e) {
            \Log::info('getResult ERROR :: ');
            \Log::info(json_encode($e));
        }
    }

    public function createFile($entities){
        \Log::info('----------create File--------------');
        if( $entities ){

            $entity = $entities->map(function ($name){
                $data = [
                    'namespace' => ( $name->belongsToConfiguration ) ? $name->belongsToConfiguration->namespace : '',
                    'key' => ( $name->belongsToConfiguration ) ? $name->belongsToConfiguration->key : '',
                    'type' => ( $name->belongsToConfiguration  ) ? $name->belongsToConfiguration->type : '',
                    'value' => $name->value,
                    'label' => $name->label,

                ];
                if( $this->resource_type == 'orders' ){
                    $data['name'] =  $name->handle;
                }elseif ( $this->resource_type == 'customers' ){
                    $data['email'] =  $name->handle;
                }elseif ( $this->resource_type != 'variants' ||  $this->resource_type != 'articles'){
                    $data['handle'] =  $name->handle;
                }
                if( $this->resource_type != 'shop' ){
                    $data['owner_id'] =  $name->owner_id;
                }
                $data['metafield_id'] = $name->metafield_id;
                return $data;
            });
        }
        return $entity;
    }
    public function headings(): array
    {
        $data = [
            'namespace',
            'key',
            'type',
            'value',
            'label',
        ];

        if( $this->resource_type == 'orders' ){
            $data[] =  'name';
        }elseif ( $this->resource_type == 'customers' ){
            $data[] =  'email';
        }elseif ( $this->resource_type != 'variants' ||  $this->resource_type != 'articles'){
            $data[] =  'handle';
        }

        if( $this->resource_type != 'shop' ){
            $data[] =  $this->resource_type . '_id';
        }
        $data[] = 'metafield_id';
        return $data;
    }
}

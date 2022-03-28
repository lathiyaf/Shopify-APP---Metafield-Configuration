<?php

namespace App\Exports;

use App\Jobs\SyncMetafieldsJob;
use App\Model\Metafield;
use App\Model\MetafieldConfiguration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MetafieldsConfigurationCSVExports implements FromCollection, WithHeadings
{
    private $shop;

    public function __construct()
    {
        \Log::info('Export configuration maatwebsite');
        $this->shop = shShop();
    }
    public function collection()
    {
        $entities = MetafieldConfiguration::where('shop_id', $this->shop->id)->where(\DB::raw('substr(resource_type, 1, 3)'), '!=', 'lcl' )->where(\DB::raw('substr(resource_type, 1, 4)'), '!=', 'sync')->where(\DB::raw('substr(resource_type, 1, 6)'), '!=', 'global')->get();

        if( $entities ){
            $entity = $this->createFile($entities);
            return $entity;
            return collect($entity);
        }
    }



    public function createFile($entities){
        \Log::info('----------craete File--------------');
        if( $entities ){
            $entity = $entities->map(function ($name) {
                $data = [
                    'resource type' => $name->resource_type,
                    'namespace' => $name->namespace ,
                    'key' => $name->key,
                    'type' => $name->type,
                    'label' => ( $name->label ) ? $name->label : '',
                ];
                return $data;
            });
        }
        return $entity;
    }
    public function headings(): array
    {
        $data = [
            'resource_type',
            'namespace',
            'key',
            'type',
            'label'
        ];

        return $data;
    }
}

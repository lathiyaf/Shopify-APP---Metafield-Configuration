<?php

namespace App\Exports;

use App\Jobs\SyncMetafieldsJob;
use App\Model\Metafield;
use LukeTowers\ShopifyPHP\Shopify;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OutletStoreProducts implements FromCollection, WithHeadings
{

    public $all_data = [];

    public function __construct()
    {
        \Log::info('-----------------------OutletStoreProducts-------------------------');
    }

    public function collection()
    {
        $this->getData('');
        return collect($this->all_data);
    }

    public function getData($page)
    {
        $api = new Shopify('bodyoutlet-se.myshopify.com', '05426fafb31eeabedcc8c043d615d869');

//         Get all products
        $result = $api->call('GET', 'admin/products/count.json');
        $total_page = number_format(ceil($result->count / 250), 0);

        $j=1;
        for ($i = 1; $i <= $total_page; $i++) {
            \Log::info($i);
                $parameter['limit'] = 250;
                $parameter['page'] = $i;
                $result = $api->call('GET', 'admin/products.json', $parameter);
            $sh_data = $result->products;
            if (count($sh_data) > 0) {
                foreach ($sh_data as $key => $val) {
                    $variant = $val->variants;
                    if (is_array($variant) ) {
                        foreach ($variant as $k => $v) {
                            \Log::info($j++);
                            $d['Product Title'] = ($val->title) ? iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $val->title) : '';
                            $d['Title'] = ($v->option1) ? iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $v->option1) : '';
                            $d['Full Title'] = ($v->option1) ? iconv('UTF-8', 'ISO-8859-1//TRANSLIT' ,$val->title.' - '.$v->option1) : iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $val->title);
                            $d['Vendor'] = ($val->vendor) ? $val->vendor : '';
                            $d['SKU'] = ($v->sku) ? $v->sku : '';
                            $d['URL'] = 'https://bodyoutlet-se.myshopify.com/products/'.$val->handle;
                            $d['Product Id'] = $val->id;
                            $d['Variant Id'] = $v->id;
                            array_push($this->all_data, $d);
                        }
                    }
                }
            }
        }
    }

    public function headings(): array
    {
        $data = [
            'Product Title',
            'Title',
            'Full Title',
            'Vendor',
            'SKU',
            'URL',
            'Product Id',
            'Variant Id'
        ];
        return $data;
    }
}

<?php

namespace App\Http\Controllers\Test;

use App\Exports\OutletStoreProducts;
use App\Http\Controllers\Controller;
use App\Jobs\OutletStoreProductsCSVJob;
use App\Model\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use LukeTowers\ShopifyPHP\Shopify;
use Maatwebsite\Excel\Facades\Excel;


class TestController extends Controller
{
    public function test(Request $request){
        try {
            // Initialize the client
//            OutletStoreProductsCSVJob::dispatchNow();

//            $api = new Shopify('bodyoutlet-se.myshopify.com', '05426fafb31eeabedcc8c043d615d869');
//
//// Get all products
//            $parameter['limit'] = 250;
//            $result = $api->call('GET', 'admin/products.json', $parameter);
//
//            dd($result);
        }catch( \Exception $e ){
            dd($e);
        }
    }
}

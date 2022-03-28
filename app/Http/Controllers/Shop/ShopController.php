<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Traits\GraphQLTrait;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    use GraphQLTrait;
    public function getShop()
    {
        try {
            $shop = \ShopifyApp::shop();
            $s['id'] = $shop->id;
            $s['domain'] = $shop->shopify_domain;

            $data['shop'] = $s;


            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }
}

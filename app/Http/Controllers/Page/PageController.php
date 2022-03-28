<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;

class PageController extends Controller
{
    public function getPages(Request $request)
    {
        try {
            $shop = ShopifyApp::shop();
            if ($request->s != '') {
                $endPoint = '/admin/api/'. env('SHOPIFY_SEARCH_API_VERSION') .'/pages.json';
                $parameter['title'] = $request->s;
            } else {
                $endPoint = '/admin/api/'.env('SHOPIFY_API_VERSION').'/pages.json';
                $parameter['limit'] = 10;
                $parameter['page_info'] = ($request->page) ? $request->page : '';
            }
            $parameter['fields'] = "id,title,handle,created_at,published_at";

            $pages = $shop->api()->rest('GET', $endPoint, $parameter);

            if ($pages->body->pages) {
                foreach ($pages->body->pages as $pk => $pv) {
                    $page[$pk]['id'] = $pv->id;
                    $page[$pk]['title'] = $pv->title;
                    $page[$pk]['handle'] = $pv->handle;
                    $page[$pk]['created_at'] = date("Y-m-d H:i:s", strtotime($pv->created_at));
                    $page[$pk]['published_at'] = ( $pv->published_at == null) ? 'Hidden' : 'Visible';
                    $page[$pk]['is_checked'] = false;
                }
            } else {
                $page = '';
            }

            $data['next'] = ($pages->link) ? $pages->link->next : '';
            $data['prev'] = ($pages->link) ? $pages->link->previous : '';
            $data['page'] = $page;
            $data['shop'] = $shop->id;

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }
}

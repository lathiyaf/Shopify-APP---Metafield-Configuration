<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\GraphQLTrait;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;

class CollectionController extends Controller
{
    use GraphQLTrait;
    public function getCollections(Request $request){
        try{
            $shop = ShopifyApp::shop();
            $type = $request->type;
            if( $request->s != '' ){
                $endPoint = '/admin/api/'. env('SHOPIFY_SEARCH_API_VERSION') .'/' . $type . '.json';
                $parameter['title'] = $request->s;
            }else{
                $endPoint = '/admin/api/' . env('SHOPIFY_API_VERSION') . '/' . $type . '.json';
                $parameter['limit'] = 10;
                $parameter['page_info'] = ($request->page) ? $request->page : '';
            }
            $parameter['fields'] = "id,title,handle,image,published_at,";

            $collections = $shop->api()->rest('GET', $endPoint, $parameter);
            if( $collections->body->$type ){
                foreach ( $collections->body->$type as $pk=>$pv){
                    $collection[$pk]['id'] = $pv->id;
                    $collection[$pk]['title'] = $pv->title;
                    $collection[$pk]['handle'] = $pv->handle;
                    $collection[$pk]['created_at'] = date("Y-m-d H:i:s", strtotime($pv->published_at));
                    $collection[$pk]['published_at'] = ( $pv->published_at == null) ? 'Hidden' : 'Visible';
                    $collection[$pk]['image'] = ( @$pv->image ) ? $pv->image->src : noImagePath();
                    $collection[$pk]['is_checked'] = false;
                }
            }else{
                $collection = '';
            }


            $data['next'] = ($collections->link) ? $collections->link->next : '';
            $data['prev'] = ($collections->link) ? $collections->link->previous : '';
            $data['collection'] = $collection;
            $data['shop'] = $shop->id;

            return response()->json(['data' => $data], 200);
        }catch( \Exception $e ){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }
}

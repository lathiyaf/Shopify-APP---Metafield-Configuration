<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Traits\GraphQLTrait;
use Illuminate\Http\Request;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;

class BlogController extends Controller
{
    use GraphQLTrait;

    public function getBlogs(Request $request){
        try{
            $shop = ShopifyApp::shop();
//            if( $request->s != '' ){
//                $endPoint = '/admin/api/2019-10/blogs.json'; //2019-04
//                $parameter['handle'] = $request->s;
//            }else{
//                $endPoint = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/blogs.json';
//                $parameter['limit'] = 10;
//                $parameter['page_info'] = ($request->page) ? $request->page : '';
//            }
            $endPoint = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/blogs.json';
            $parameter['limit'] = 10;
            $parameter['page_info'] = ($request->page) ? $request->page : '';
            $parameter['fields'] = "id,title,handle,created_at";

            $blogs = $shop->api()->rest('GET', $endPoint, $parameter);
            if( $blogs->body->blogs ){
                foreach ( $blogs->body->blogs as $pk=>$pv){
                    $blog[$pk]['id'] = $pv->id;
                    $blog[$pk]['title'] = $pv->title;
                    $blog[$pk]['handle'] = $pv->handle;
                    $blog[$pk]['created_at'] = date("Y-m-d H:i:s", strtotime($pv->created_at));
                    $blog[$pk]['is_checked'] = false;
                }
            }else{
                $blog = '';
            }

            $data['next'] = ($blogs->link) ? $blogs->link->next : '';
            $data['prev'] = ($blogs->link) ? $blogs->link->previous : '';
            $data['blog'] = $blog;
            $data['shop'] = $shop->id;

            return response()->json(['data' => $data], 200);
        }catch( \Exception $e ){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    public function getArticles(Request $request){
        try{
            $shop = ShopifyApp::shop();
//            if( $request->s != '' ){
//                $endPoint = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/blogs/' . $request->blog_id . '/articles.json';
//                $parameter['author'] = $request->s;
//            }else{
//                $endPoint = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/blogs/' . $request->blog_id . '/articles.json';
//                $parameter['limit'] = 10;
//                $parameter['page_info'] = ($request->page) ? $request->page : '';
//            }
            $endPoint = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/blogs/' . $request->blog_id . '/articles.json';
            $parameter['limit'] = 10;
            $parameter['page_info'] = ($request->page) ? $request->page : '';
            $parameter['fields'] = "id,title,author,image,created_at,published_at";

            $articles = $shop->api()->rest('GET', $endPoint,$parameter);
            if( $articles->body->articles ){
                foreach (  $articles->body->articles as $pk=>$pv){
                    $article[$pk]['id'] = $pv->id;
                    $article[$pk]['blog_id'] = $request->blog_id;
                    $article[$pk]['title'] = $pv->title;
                    $article[$pk]['author'] = $pv->author;
                    $article[$pk]['image'] = ( @$pv->image ) ? $pv->image->src : noImagePath();
                    $article[$pk]['created_at'] = date("Y-m-d H:i:s", strtotime($pv->created_at));
                    $article[$pk]['published_at'] = ( $pv->published_at == null) ? 'Hidden' : 'Visible';;
                    $article[$pk]['is_checked'] = false;
                }
            }else{
                $article = '';
            }

            $data['next'] = ($articles->link) ? $articles->link->next : '';
            $data['prev'] = ($articles->link) ? $articles->link->previous : '';
            $data['article'] = $article;
            $data['shop'] = $shop->id;

            return response()->json(['data' => $data], 200);
        }catch( \Exception $e ){
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }
}

<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Jobs\SyncInitialMetafieldsJob;
use App\Model\Metafield;
use App\Model\MetafieldConfiguration;
use App\Model\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use App\Traits\GraphQLTrait;

class ProductController extends Controller
{
    use GraphQLTrait;

    public function getProducts(Request $request)
    {
        try {
            $shop = ShopifyApp::shop();
            if ($request->s != '') {
                // $endPoint = '/admin/products.json';
                $endPoint = '/admin/api/'. env('SHOPIFY_SEARCH_API_VERSION') .'/products.json';
                $parameter['title'] = $request->s;
            } else {
                $endPoint = '/admin/api/'.env('SHOPIFY_API_VERSION').'/products.json';
                $parameter['limit'] = 10;
                $parameter['page_info'] = ($request->page) ? $request->page : '';
            }
            $parameter['fields'] = "id,title,image,handle,product_type,published_at";

            $products = $shop->api()->rest('GET', $endPoint, $parameter);

            if ($products->body->products) {
                foreach ($products->body->products as $pk => $pv) {
                    $product[$pk]['id'] = $pv->id;
                    $product[$pk]['title'] = $pv->title;
                    $product[$pk]['image'] = ($pv->image) ? $pv->image->src : noImagePath();
                    $product[$pk]['handle'] = $pv->handle;
                    $product[$pk]['type'] = $pv->product_type;
                    $product[$pk]['published_at'] = ( $pv->published_at == null) ? 'Hidden' : 'Visible';
                    $product[$pk]['is_checked'] = false;
                }
            } else {
                $product = '';
            }

            $data['next'] = ($products->link) ? $products->link->next : '';
            $data['prev'] = ($products->link) ? $products->link->previous : '';
            $data['product'] = $product;
            $data['shop'] = $shop->id;

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }

    public function getVariants(Request $request)
    {
        try {
            $shop = \ShopifyApp::shop();
            $product_id = $request['product_id'];

            $limit = 10;
            $after = @($request['after']) ? '"'.$request['after'].'"' : null;
            $before = @($request['before']) ? '"'.$request['before'].'"' : null;
            $page = ($before == null) ? 'after:' : 'before:';
            $cursor = ($after == null) ? $before : $after;
            $dd = ($page == 'before:') ? 'last' : 'first';
            $s = @($request['s']);

            if ($s) {
                $page = ( empty($cursor) ) ? '' : $page;
                $query = ' {
                          productVariants('.$dd.': '.$limit.' query:"product_id:'.$product_id.' title:'.$s.'" '.$page.$cursor.') {
                            edges {
                              cursor
                              node {
                                 legacyResourceId
                                 image {
                                    originalSrc
                                 }
                                 title
                              }
                            }
                            pageInfo {
                                hasNextPage
                                hasPreviousPage
                            }
                          }
                        }';
            } else {
                $page = ( empty($cursor) ) ? '' : $page;

                $query = ' {
                              productVariants('.$dd.': '.$limit.' query:"product_id:'.$product_id.'" '.$page.$cursor.') {
                                edges {
                                  cursor
                                  node {
                                     legacyResourceId
                                     image {
                                        originalSrc
                                     }
                                     title
                                  }
                                }
                                pageInfo {
                                    hasNextPage
                                    hasPreviousPage
                                }
                              }
                            }';
            }

            $parameters = ['limit' => $limit];
            if ($after) {
                $parameters['after'] = $after;
            }

            $result = $this->graph($shop, $query, $parameters);

            $variants = $result->body->productVariants->edges;
            $after = end($result->body->productVariants->edges)->cursor ?? null;
            $before = $result->body->productVariants->edges[0]->cursor ?? null;

            if ($variants) {
                foreach ($variants as $pk => $pv) {
                    $node = $pv->node;
                    $variant[$pk]['id'] = $node->legacyResourceId;
                    $variant[$pk]['product_id'] = $product_id;
                    $variant[$pk]['title'] = $node->title;
                    $variant[$pk]['image'] = ($node->image) ? $node->image->originalSrc : noImagePath();
                    $variant[$pk]['is_checked'] = false;
                }
            } else {
                $variant = '';
            }

               $data['variant'] = $variant;
            $data['pageInfo'] = $result->body->productVariants->pageInfo;
            $data['next'] = $after;
            $data['prev'] = $before;
            $data['shop'] = $shop->id;

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['data' => $e], 422);
        }
    }



}

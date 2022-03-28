<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Traits\GraphQLTrait;
use Illuminate\Http\Request;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;

class OrderController extends Controller
{
    use GraphQLTrait;
    public function getOrders(Request $request){
        try {
            $shop = \ShopifyApp::shop();

            $limit = 10;
            $after = @($request['after']) ? '"' . $request['after'] . '"' : null;
            $before = @($request['before']) ? '"' . $request['before'] . '"' : null;
            $page = ( $before == null ) ? 'after:' : 'before:';
            $cursor = ( $after == null ) ? $before : $after;

            $s =  ($request->s) ? '"' . $request->s .'*"' : '""';

            $dd = ( $page == 'before:' ) ? 'last' : 'first';
            $page = ( empty($cursor) ) ? '' : $page;
            $query = '{
                          orders('. $dd .': ' . $limit . ' query: '. $s .' '. $page.$cursor.') {
                            edges {
                              node {
                                name
                                legacyResourceId
                                email
                                displayFinancialStatus
                              }
                              cursor
                            }
                            pageInfo {
                                   hasNextPage
                                   hasPreviousPage
                              }
                          }
                    }';   
            $parameters = ['limit' => $limit];
            if ($after) {
                $parameters['after'] = $after;
            }
            if ($before) {
                $parameters['before'] = $before;
            }

            $result = $this->graph($shop, $query, $parameters);
            $orders = $result->body->orders->edges;
            $after = end($result->body->orders->edges)->cursor ?? null;
            $before = $result->body->orders->edges[0]->cursor ?? null;


            if ($orders) {
                foreach ($orders as $pk => $pv) {
                    $node = $pv->node;
                    $order[$pk]['id'] = $node->legacyResourceId;
                    $order[$pk]['name'] = $node->name;
                    $order[$pk]['email'] = $node->email;
                    $order[$pk]['payment_status'] = $node->displayFinancialStatus;
                    $order[$pk]['is_checked'] = false;
                }
            } else {
                $order = '';
            }

            $data['order'] = $order;
            $data['pageInfo'] = $result->body->orders->pageInfo;
            $data['next'] = $after;
            $data['prev'] = $before;
            $data['shop'] = $shop->id;

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }
}

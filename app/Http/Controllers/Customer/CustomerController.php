<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\GraphQLTrait;

class CustomerController extends Controller
{
    use GraphQLTrait;
    public function getCustomers(Request $request)
    {
        try {
            $shop = \ShopifyApp::shop();

            $limit = 10;
            $after = @($request['after']) ? '"' . $request['after'] . '"' : null;
            $before = @($request['before']) ? '"' . $request['before'] . '"' : null;
            $page = ( $before == null ) ? 'after:' : 'before:';
            $cursor = ( $after == null ) ? $before : $after;

            $dd = ( $page == 'before:' ) ? 'last' : 'first';
            $page = ( empty($cursor) ) ? '' : $page;
            $query = '{
                          customers('. $dd .': ' . $limit . ' query:"'. $request->s .'*" '  .$page.$cursor.') {
                            edges {
                              node {
                                legacyResourceId
                                image {
                                  originalSrc
                                }
                                email
                                displayName
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

            $customers = $result->body->customers->edges;

            $after = end($result->body->customers->edges)->cursor ?? null;
            $before = $result->body->customers->edges[0]->cursor ?? null;

            if ($customers) {
                foreach ($customers as $pk => $pv) {
                    $node = $pv->node;
                    $customer[$pk]['id'] = $node->legacyResourceId;
                    $customer[$pk]['name'] = $node->displayName;
                    $customer[$pk]['email'] = $node->email;
                    $customer[$pk]['image'] = ($node->image) ? $node->image->originalSrc : noImagePath();
                    $customer[$pk]['is_checked'] = false;
                }
            } else {
                $customer = '';
            }

            $data['customer'] = $customer;
            $data['pageInfo'] = $result->body->customers->pageInfo;
            $data['next'] = $after;
            $data['prev'] = $before;
            $data['shop'] = $shop->id;

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['data' => $e->getMessage()], 422);
        }
    }
}

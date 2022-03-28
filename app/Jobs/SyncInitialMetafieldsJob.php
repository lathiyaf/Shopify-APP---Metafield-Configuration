<?php

namespace App\Jobs;

use App\Model\Metafield;
use App\Model\MetafieldConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Traits\GraphQLTrait;

class SyncInitialMetafieldsJob implements ShouldQueue
{
    use GraphQLTrait;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var shop
     */
    public $shop;
    public $timeout = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->shop = \ShopifyApp::shop();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('--------------sync metafields----------------');
        try {
            $this->syncUsingRest();
            $this->syncUsingGraph();
        } catch (\Exception $e) {
            \Log::info(json_encode($e));
        }
    }

    public function syncUsingRest()
    {
        \Log::info('--------------syncUsingRest----------------');
        try {
            // shop resource

            $endPoint = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/metafields.json';
            $results = $this->shop->api()->rest('GET', $endPoint);
            $metafields = $results->body->metafields;
            foreach ($metafields as $key => $val) {
                $this->storeMetaField('shop', $val, $key);
            }

            // blog
            $endPoint = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/blogs.json';
            $result = $this->shop->api()->rest('GET', $endPoint);
            $blogs = $result->body->blogs;
            foreach ($blogs as $bk => $bv) {
                $endPoint = '/admin/blogs/' . $bv->id . '/metafields.json';
                $result = $this->shop->api()->rest('GET', $endPoint);
                $metafields = $result->body->metafields;
                foreach ($metafields as $mbk => $mbv) {
                    $this->storeMetaField('blogs', $mbv, $mbk);
                }
                $art_endPoint = '/admin/api/'. env('SHOPIFY_API_VERSION') .'/blogs/' . $bv->id . '/articles.json';
                $result = $this->shop->api()->rest('GET', $art_endPoint);
                $articles = $result->body->articles;
                foreach ($articles as $ak => $av) {
                    $endPoint = '/admin/blogs/' . $bv->id . '/articles/' . $av->id . '/metafields.json';
                    $result = $this->shop->api()->rest('GET', $endPoint);
                    $metafields = $result->body->metafields;
                    foreach ($metafields as $mak => $mav) {
                        $this->storeMetaField('articles', $mav, $mak);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::info(json_encode($e));
        }
    }

    public function storeMetaField($resourceType, $metafield, $key)
    {
        \Log::info('--------------storeMetaField----------------');
        try {
            DB::beginTransaction();
            $type = 'string';

            $meta_config = new MetafieldConfiguration;
            $meta_config->shop_id = $this->shop->id;
            $meta_config->label = '';
            $meta_config->namespace = $metafield->namespace;
            $meta_config->key = $metafield->key;
            $meta_config->type = $type;
            $meta_config->resource_type = 'sync_' . $resourceType;
            $meta_config->sort_order = $key;
            $meta_config->save();

            $meta_value = new Metafield;
            $meta_value->shop_id = $this->shop->id;
            $meta_value->metafield_configuration_id = $meta_config->id;
            $meta_value->value = $metafield->value;
            $meta_value->metafield_id = $metafield->id;
            $meta_value->metafield_json = json_encode($metafield);
            $meta_value->metafield_type = $type;
            $meta_value->resource_type = 'sync_' . $resourceType;
            $meta_value->owner_id = $metafield->owner_id;
            $meta_value->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info(json_encode($e));
        }
    }

    public function syncUsingGraph()
    {
        \Log::info('--------------syncUsingGraph----------------');
        try {
            $resourceType = ['products', 'productVariants', 'customers', 'collections' => ['smart', 'custom']];

            foreach ($resourceType as $rk => $rv) {
                if (is_array($rv)) {
                    foreach ($rv as $k => $v) {
                        $this->getMeta($v, 10, '', 10, '');
                    }
                } else {
                    $this->getMeta($rv, 10, '', 10, '');
                }
            }
        } catch (\Exception $e) {
            \Log::info(json_encode($e));
        }
    }

    public function getMeta($resourceType, $limit, $after, $limitm, $afterm)
    {
        \Log::info('--------------getMeta----------------');
        try {
            \Log::info($resourceType);
            $newResourceType = ($resourceType == 'smart' || $resourceType == 'custom') ? 'collections' : $resourceType;
            $query = $this->getQuery($resourceType, $limit, $after, $limitm, $afterm);
            $result = $this->graph($this->shop, $query);
            \Log::info(json_encode($result));
            $data = $result->body->$newResourceType->edges;

            foreach ($data as $key => $val) {
                $metafields = $val->node->metafields;
                if ($metafields->edges) {
                    $owner_id = ($newResourceType == 'collections') ? $val->node->id : $val->node->legacyResourceId;
                    if ($newResourceType == 'collections') {
                        $arr = explode('/', $owner_id);
                        $owner_id = end($arr);
                    }
                    $metafield = $metafields->edges;
                    foreach ($metafield as $mk => $mv) {
                        $json = $mv->node;
                        $this->storeMeta($json, $owner_id, $resourceType, $mk);
                    }
                } else {
                    continue;
                }
                if ($metafields->pageInfo->hasNextPage) {
                    $afterm = '"' . end($metafields->edges)->cursor . '"';
                    $this->getMeta($resourceType, $limit, $after, $limitm, $afterm);
                }
            }
            if ($result->body->$newResourceType->pageInfo->hasNextPage) {
                $after = '"' . end($data)->cursor . '"';
                $this->getMeta($resourceType, $limit, $after, $limitm, '');
            }
        } catch (\Exception $e) {
            \Log::info(json_encode($e));
        }
    }

    public function getQuery($resourceType, $limit = 10, $after = '', $limitm = 10, $afterm = '')
    {
        \Log::info('--------------getQuery----------------');
        try {
            if ($resourceType == 'smart' || $resourceType == 'custom') {
                $query = '{
                  collections(first: ' . $limit . ' query:"collection_type:' . $resourceType . '" after: ' . $after . ' ) {
                    edges {
                      cursor
                      node {
                         title
                        id
                        metafields(first: ' . $limitm . ' after: ' . $afterm . ') {
                          edges {
                            cursor
                            node {
                              id
                              description
                              key
                              legacyResourceId
                              namespace
                              value
                              valueType
                            }
                          }
                          pageInfo {
                            hasNextPage
                          }
                        }

                      }
                    }
                    pageInfo {
                      hasNextPage
                    }
                  }
                }';
            } else {
                $query = '{
                  ' . $resourceType . '(first: ' . $limit . ' after: ' . $after . ') {
                    edges {
                      node {
                        metafields(first: ' . $limitm . ' after: ' . $afterm . ') {
                          edges {
                            node {
                              description
                              id
                              key
                              legacyResourceId
                              value
                              namespace
                              valueType
                            }
                            cursor
                          }
                          pageInfo {
                            hasNextPage
                          }
                        }
                        legacyResourceId
                      }
                      cursor
                    }
                    pageInfo {
                      hasNextPage
                    }
                  }
                }';
            }
            return $query;
        } catch (\Exception $e) {
            \Log::info(json_encode($e));
        }

    }

    public function storeMeta($data, $owner_id, $resourceType, $sort_key)
    {
        \Log::info('--------------storeMeta----------------');
        try {
            DB::beginTransaction();
            $resourceType = ($resourceType == 'smart' || $resourceType == 'custom') ? $resourceType . '_collections' : ($resourceType == 'productVariants') ? 'variants' : $resourceType;

            $shop = \ShopifyApp::shop();
            $type = 'string';
            $meta_config = new MetafieldConfiguration;
            $meta_config->shop_id = $this->shop->id;
            $meta_config->label = $data->description;
            $meta_config->namespace = $data->namespace;
            $meta_config->key = $data->key;
            $meta_config->type = $type;
            $meta_config->resource_type = 'sync_' . $resourceType;
            $meta_config->sort_order = $sort_key;

            $meta_config->save();

            $meta_value = new Metafield;
            $meta_value->shop_id = $this->shop->id;
            $meta_value->metafield_configuration_id = $meta_config->id;
            $meta_value->value = $data->value;
            $meta_value->metafield_id = $data->legacyResourceId;
            $meta_value->metafield_json = json_encode($data);
            $meta_value->metafield_type = $type;
            $meta_value->resource_type = 'sync_' . $resourceType;
            $meta_value->owner_id = $owner_id;

            $meta_value->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info(json_encode($e));
        }
    }
}

<?php

namespace App\Http\Controllers\Metafield;

use App\Http\Controllers\Controller;
use App\Http\Requests\MetafieldConfigurationRequest;
use App\Model\MetafieldConfiguration;
use App\Model\Setting;
use Illuminate\Http\Request;
use MongoDB\Driver\Exception\ExecutionTimeoutException;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use Response;

class MetafieldConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('api')) {
            return $this->json($request);
        }
    }

    public function json($request)
    {
        try {
            $shop = \ShopifyApp::shop();
           if( $request->api == '1' ){
               $entities = MetafieldConfiguration::where('resource_type', $request->resourceType)->where('shop_id', $shop->id)->orderBy('sort_order','asc')->get();
           }else{
               $entities = MetafieldConfiguration::where('shop_id', $shop->id)->where('resource_type', 'NOT LIKE', 'lcl_%')->where('resource_type', 'NOT LIKE', 'sync_%')->orderBy('sort_order', 'asc')->get();
           }
            $entity = [];
            if( $entities ){
                foreach ($entities as $k => $e) {
                    $entity[$k]['id'] = $e->id;
                    $entity[$k]['namespace'] = $e->namespace;
                    $entity[$k]['key'] = $e->key;
                    $entity[$k]['type'] = $e->type;
                    $entity[$k]['label'] = $e->label;
                    $entity[$k]['rtype'] = $e->resource_type;
                }
            }

            $shop = \ShopifyApp::shop();
            $namespace = Setting::where('key', 'global_namespace')->where('shop_id', $shop->id)->first();
            $data['namespace'] = ( $namespace ) ? $namespace->value : '';
            $data['entity'] = $entity;
            return response::json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response::json(['data' => $e->getMessage()], 422);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MetafieldConfigurationRequest $request)
    {
        try{
            if( $request->isSubmit ){

                $shop = \ShopifyApp::shop();
                $data = $request->data;
                foreach( $data as $dk=>$dv ){
                    if( $dv['id'] ){
                        $metafield = MetafieldConfiguration::where('id', $dv['id'])->first();
                    }else{
                        $metafield = new MetafieldConfiguration;
                    }
                    $metafield->shop_id = $shop->id;
                    $metafield->label = $dv['label'];
                    $metafield->namespace = $dv['namespace'];
                    $metafield->key = $dv['key'];
                    $metafield->type = $dv['typev'];
                    $metafield->sort_order = $dk;
                    $metafield->resource_type = $request->resourceType;
                    $metafield->save();
                }

                if( $remove = $request->removeField ){
                    foreach( $remove as $rk=>$rv ){
                        MetafieldConfiguration::where('id', $rv)->delete();
                    }
                }
                return response::json(['data' => 'Metafield Saved!'], 200);
            }else{
                return response::json(['data' => 1], 200);
            }
        }catch(Exception $e) {
            return response::json(['data' => 'Metafield not added...'. $e->getContent()], 422);
        }
    }

    public function allMetafieldConfiguration(MetafieldConfigurationRequest $request){
        try{
            if( $request->isSubmit ){
                $shop = \ShopifyApp::shop();
                $data = $request->data;
                foreach( $data as $dkey=>$dval ){
                    if( $dval ){
                        foreach( $dval as $k=>$v ) {
                            if ($v['id']) {
                                $metafield = MetafieldConfiguration::where('id', $v['id'])->where('shop_id',
                                    $shop->id)->first();
                            } else {
                                $metafield = new MetafieldConfiguration;
                            }
                            $metafield->shop_id = $shop->id;
                            $metafield->label = $v['label'];
                            $metafield->namespace = $v['namespace'];
                            $metafield->key = $v['key'];
                            $metafield->type = $v['typev'];
                            $metafield->sort_order = $k;
                            $metafield->resource_type = $v['rtype'];
                            $metafield->save();
                        }
                    }
                }

                if( $remove = $request->removeField ){
                    foreach( $remove as $rk=>$rv ){
                        MetafieldConfiguration::where('id', $rv)->delete();
                    }
                }
                return response::json(['data' => 'Metafield Saved!'], 200);
            }else{
                return response::json(['data' => 1], 200);
            }
        }catch(Exception $e) {
            return response::json(['data' => 'Metafield not added...'. $e->getContent()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}

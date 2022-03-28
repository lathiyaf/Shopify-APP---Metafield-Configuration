<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.app');
})->middleware(['auth.shop', 'billable'])->name('home');


Route::get('get-type', 'Test\TestController@getMetafieldType')->name('gettype');

Route::group(['middleware' => ['auth.shop', 'billable']], function () {
    // test
    Route::get('test', 'Test\TestController@test')->name('test');


// product
    Route::get('get-products', 'Product\ProductController@getProducts')->name('getproducts');
    Route::get('get-variants', 'Product\ProductController@getVariants')->name('getvariants');

//collection
    Route::get('get-collections', 'Collection\CollectionController@getCollections')->name('getcollections');

//customer
    Route::get('get-customers', 'Customer\CustomerController@getCustomers')->name('getcustomers');

//blog
    Route::get('get-blogs', 'Blog\BlogController@getBlogs')->name('getblogs');
    Route::get('get-articles', 'Blog\BlogController@getArticles')->name('getarticles');

//shop
    Route::get('get-shop', 'Shop\ShopController@getShop')->name('getshop');

// order
    Route::get('get-orders', 'Order\OrderController@getOrders')->name('getorders');

// page
    Route::get('get-pages', 'Page\PageController@getPages')->name('getpages');

// metafield Configuration routes
    Route::resource('metafieldconfiguration', 'Metafield\MetafieldConfigurationController');
    Route::post('allmetafieldconfiguration',
        'Metafield\MetafieldConfigurationController@allMetafieldConfiguration')->name('allmetafieldconfiguration');

// metafield routes
    Route::resource('metafield', 'Metafield\MetafieldController');
    Route::get('get-customnamespace', 'Metafield\MetafieldController@getCustomNamespace')->name('getcustomnamespace');
    Route::post('remove-metafield', 'Metafield\MetafieldController@removeMetafield')->name('remove-metafield');
    Route::get('delete-metafield', 'Metafield\MetafieldController@deleteSingleMetafield')->name('delete-metafield');
    Route::post('global-metafield', 'Metafield\MetafieldController@globalMetafield')->name('global-metafield');
    Route::get('delete-globalmetafield', 'Metafield\MetafieldController@removeGlobalMetafield')->name('delete-globalmetafield');
    Route::get('get-globalmetafields', 'Metafield\MetafieldController@getGlobalMetafields')->name('get-globalmetafields');
    Route::get('get-globalmetafield', 'Metafield\MetafieldController@getGlobalMetafield')->name('get-globalmetafield');

// setting routes
    Route::resource('setting', 'Setting\SettingController');

// csv route
    Route::post('generate-csv', 'Setting\SettingController@generateCSV')->name('generate-csv');
    Route::get('get-exportedcsvs', 'Setting\SettingController@getExportedCSV')->name('get-exportedcsvs');
    Route::get('get-sampleCSV', 'Setting\SettingController@getSampleCSV');
    Route::get('sync-meta', 'Setting\SettingController@syncMeta');
    Route::get('get-issynced', 'Setting\SettingController@getIsSynced');
    Route::post('export-importconfiguration',
        'Setting\SettingController@exportImportConfiguration')->name('export-importconfiguration');
    Route::get('get-resource', 'Setting\SettingController@getResource')->name('getresource');

// instruction page
    Route::get('installation-instruction', function (){
       return view('pages.installationpage') ;
    });

//plan page
Route::get('plan-pricing', function (){
    return view('pages.pricing') ;
});

// activity log route
    Route::get('get-activitylogs', 'Setting\SettingController@getActivityLogs')->name('get-activitylogs');
    Route::get('stopped-job', 'Setting\SettingController@stoppedJob');
});


/*Route::get('{url?}', function () {
    return view('layouts.app');
})->where('url', '([A-z\d-\/_.]+)?');*/

//
//Route::get('/{any?}', function () {
//    return view('layouts.app');
//})->middleware(['auth.shop', 'billable'])->name('home');

Route::get('flush', function(){
//    phpinfo();die();
    request()->session()->flush();
});

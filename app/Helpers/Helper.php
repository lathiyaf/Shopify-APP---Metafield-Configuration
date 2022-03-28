<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('noImagePath')) {
    /**
     * @return mixed
     */
    function noImagePath()
    {
        return asset('static_upload/no-image-box.png');
    }
}

if (!function_exists('SampleCSVPath')) {
    function SampleCSVPath($t)
    {
        $path = ( $t == 'shop' ) ? 'static_upload/sample_import_shop.csv' :'static_upload/sample_import.csv' ;
        return asset($path);
    }
}

if (!function_exists('shShop')) {
    function shShop()
    {
        return  \ShopifyApp::shop();
    }
}

if (!function_exists('isDomainAvailible')) {
    function isDomainAvailible($url)
    {

        $file_headers = @get_headers($url);
//        dump($file_headers);
        if(!$file_headers || $file_headers[0] == 'HTTP/1.0 404 Not Found') {
            return false;
        } else {
            return true;
        }
    }
}

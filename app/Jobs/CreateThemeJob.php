<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;

class CreateThemeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $shop;
    public $timeout = 0;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->shop = ShopifyApp::shop();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('---------- craete theme -----------');
        if( $this->shop->theme_id ){
            $theme_id = $this->shop->theme_id;
            \Log::info($this->shop->theme_id);
            $result = $this->shop->api()->rest('GET', "/admin/api/". env('SHOPIFY_API_VERSION') ."/themes/$theme_id.json");
            \Log::info(json_encode($result));
            if( $result->errors ) {
                \Log::info('errrr');
                $this->addTheme();
            }
        }else{
            \Log::info('elseeee');
            $this->addTheme();
        }
    }

    public function addTheme(){
        \Log::info('---------- add theme -----------');
        $zip = asset('static_upload/advance-metafield.zip');
//        $zip = 'http://c6f17a85.ngrok.io/static_upload/advance-metafield.zip';

        $themeJson = [
            "theme" => [
                "name" => "METAFIELDS-CONFIGURATION-DO-NOT-DELETE",
                "src" => $zip,
                "role" => "unpublished"
            ]
        ];
        $result = $this->shop->api()->rest('POST', '/admin/api/'. env('SHOPIFY_API_VERSION') .'/themes.json', $themeJson);
        \Log::info('----------  theme  result -----------');
        if( $result->errors ){
            \Log::info(json_encode($result));
        }else{
            $theme = $result->body->theme;
                \Log::info($theme->id);
                $this->shop->theme_id = $theme->id;
                $this->shop->save();
        }
    }
}

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ config('app.name', 'Metafields Configuration') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-url" content="{{ env('APP_API_URL') }}">
    <!-- Fonts -->
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://sdks.shopifycdn.com/polaris/3.11.0/polaris.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.1.2/css/ionicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset(mix('css/app.css'))}}" type="text/css" />

    @if(config('shopify-app.appbridge_enabled'))
        <script src="https://unpkg.com/@shopify/app-bridge"></script>
        <script>
            var AppBridge = window['app-bridge'];
            var createApp = AppBridge.default;
            window.shopify_app_bridge = createApp({
                apiKey: '{{ config('shopify-app.api_key') }}',
                shopOrigin: '{{ ShopifyApp::shop()->shopify_domain }}',
                forceRedirect: true,
            });
        </script>
    @endif
</head>
<body class="">
<div id="preloaders" class="preloader"></div>
<div id="app">
    <router-view></router-view>
</div>
</body>
<script src="{{  asset(mix('js/app.js'))  }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
        $(window).on('load', function() {
            $("#preloaders").fadeOut();
        });
        $(this).on('load', function() {
            $("#preloaders").remove();
        });
        $(document).on('load', function() {
            $("#preloaders").removeClass('.preloader');
        });

</script>
</html>

<style>
    .preloader
    {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url({{ asset('static_upload/metafield-preloader.gif')  }}) 50% 50% no-repeat rgb(249,249,249);
        opacity: .8;
    }
</style>

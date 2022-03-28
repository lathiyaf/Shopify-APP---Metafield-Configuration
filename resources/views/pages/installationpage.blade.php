<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <style>
        .meta-config-install-img{
            width: 90%;
        }
        .meta-config-install-text{
            margin-top: 1rem;
        }
        a{
            color: #637381;
        }
        a:hover{
            text-decoration: none;
            color: #637381;
        }
        .back-dashboard {
            font-size: 1.5rem;
            font-weight: 400;
            line-height: 2rem;
            text-transform: none;
            letter-spacing: normal;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0;
            padding: 0;
            padding: 0.9rem 0.8rem 0rem 0.8rem;
            background: none;
            border: none;
            font-size: inherit;
            line-height: inherit;
            position: relative;
            display: -webkit-box;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            min-height: 3.6rem;
            margin: -0.4rem -0.8rem -0.4rem -0.4rem;
            /* padding: .8rem .8rem; */
            color: #637381;
            text-decoration: none;
        }
        .back-text {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            position: relative;
            z-index: 20;
            font-size: 14px;
        }
        .back-arrow {
            height: 1.5rem;
            width: 1.2rem;
            margin: -1rem 0 -1rem -0.8rem;
            color: #637381;
        }
        .Polaris-Page__Content{
            margin:0.5rem 0rem 0.5rem 0rem;
        }
        body{
            background-color: inherit;
        }
        .btn-link{
            color: #000000;
        }
        .btn-link:hover{
            text-decoration: none;
            color: #000000;
        }
        .card-header{
            background-color: #ffffff;
        }
        </style>
</head>
<body>
<div>
    <div class="container-fluid text-center">
    <div class="row content">
        <div class="col-sm-3 sidenav"></div>

        <div class="col-sm-6 text-left">
            <div class="accordion" id="metafieldsConfiguration">

                <div class="Polaris-Page__Content">
                    <nav role="navigation">
                        <label class="back-dashboard">
                            <a data-polaris-unstyled="true" class="back-arrow" style="cursor: pointer;" href="{{route('home')}}">
                                <span class="_2-hnq">
                                    <svg viewBox="0 0 20 20" class="v3ASA" focusable="false" aria-hidden="true">
                                        <path
                                            d="M12 16a.997.997 0 0 1-.707-.293l-5-5a.999.999 0 0 1 0-1.414l5-5a.999.999 0 1 1 1.414 1.414L8.414 10l4.293 4.293A.999.999 0 0 1 12 16"
                                            fill-rule="evenodd">
                                        </path>
                                    </svg>
                                </span>
                            </a>
                            <a data-polaris-unstyled="true" class="back-text" style="cursor: pointer;" href="{{route('home')}}"> Dashboard
                            </a>
                        </label>
                    </nav>
                </div>

{{--                 configurations--}}
                <div class="card configurations" data-toggle="collapse" data-target="#collapse-configurations" aria-expanded="true" aria-controls="collapseConfigurations">
                    <div class="card-header" id="configurationsOne" style="cursor:pointer;">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" >
                                Configurations
                            </button>
                        </h2>
                    </div>

                    <div id="collapse-configurations" class="collapse show" aria-labelledby="configurationsOne" data-parent="#metafieldsConfiguration">
                        <div class="card-body">
                            <ul>
                                <li><h5>About tutorial</h5></li>
                                <p>
                                    Here, we are going to create configuration for each module.
                                </p>
                                <li><h5>Create Metafield Configuration</h5></li>
                                <p>- First, open metafields configuration app and enter in configurations module.<br>
                                    - Here, create configuration for any resource you want to create metafield.
                                </p>
                                <img src="/static_upload/installation_ss/configurations/1.png" class="meta-config-install-img">
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="card settings" data-toggle="collapse" data-target="#collapse-settings" aria-expanded="true" aria-controls="collapseOne" >
                    <div class="card-header" id="configurationsOne" style="cursor:pointer;">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" >
                                Settings
                            </button>
                        </h2>
                    </div>

                    <div id="collapse-settings" class="collapse show" aria-labelledby="configurationsOne" data-parent="#metafieldsConfiguration">
                        <div class="card-body">
                            <ul>
                                <li><h5>About tutorial</h5></li>
                                <p>
                                    Here, you can create global setting for this app.
                                </p>
                                <li><h5>Create Setting</h5></li>
                                <p>- First, open metafields configuration app and enter in setting module.</p>
                                <img src="/static_upload/installation_ss/settings/1.png" class="meta-config-install-img">
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Exported csvs --}}
                <div class="card exported-csv" data-toggle="collapse" data-target="#collapse-exported-csv" aria-expanded="true" aria-controls="collapseOne">
                    <div class="card-header" id="exported-csvOne" style="cursor:pointer;">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" >
                                Exported Metafield CSV
                            </button>
                        </h2>
                    </div>

                    <div id="collapse-exported-csv" class="collapse show" aria-labelledby="exported-csvOne" data-parent="#metafieldsConfiguration">
                        <div class="card-body">
                            <ul>
                                <li><h5>About tutorial</h5></li>
                                <p>
                                    Here, You can see listing of all exported file.
                                </p>
                                <li><h5>See Exported file listing</h5></li>
                                <p>- First, open metafields configuration app and enter in exported metafield CSV module.</p>
                                <img src="/static_upload/installation_ss/exported_csvs/1.png" class="meta-config-install-img">
                            </ul>
                        </div>
                    </div>
                </div>

{{--                 shop--}}
                <div class="card shop" data-toggle="collapse" data-target="#collapse-shop" aria-expanded="true" aria-controls="collapseOne">
                    <div class="card-header" id="shopOne" style="cursor: pointer;">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" >
                                Shop
                            </button>
                        </h2>
                    </div>

                    <div id="collapse-shop" class="collapse show" aria-labelledby="shopOne" data-parent="#metafieldsConfiguration">
                        <div class="card-body">
                            <ul>
                                <li><h5>About tutorial</h5></li>
                                    <p>
                                        Here,we are going to see, how to add global message in our shopify store using shop metafield.
                                    </p>
                                <li><h5>Create Metafield</h5></li>
                                    <p>- First, open metafields configuration app and enter in shop module.<br>
                                       - Here, create shop configuration. <br>
                                        - Add metafield value
                                    </p>
                                <div><img src="/static_upload/installation_ss/shop/1.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Add Metafield Value</h5></li>
                                <p>- First, open metafields configuration app and enter in shop module.</p>
                                <div><img src="/static_upload/installation_ss/shop/2.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Add metafield in theme code.</h5></li>
                                <p>- Now, click on “Online Store” menu and look for the edit code option under ‘Action’ dropdown option.</p>
                                <div><img src="/static_upload/installation_ss/shop/3.png" class="meta-config-install-img"></div>
                                <p class="meta-config-install-text">- Now, place the copied code where you required.<br> - In this example, code is placed in theme.liquid file below header.<br>
                                        - Save the file.</p>
                                <div><img src="/static_upload/installation_ss/shop/4.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Preview.</h5></li>
                                <p>- Now, in preview the theme you can see it in below the header.</p>
                                <div><img src="/static_upload/installation_ss/shop/5.png" class="meta-config-install-img"></div>
                            </ul>
                        </div>
                    </div>
                </div>

{{--                product--}}
                <div class="card products" data-toggle="collapse" data-target="#collapseProducts" aria-expanded="false" aria-controls="collapse-products" >
                    <div class="card-header" id="products" style="cursor: pointer;">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" >
                                Product
                            </button>
                        </h2>
                    </div>
                    <div id="collapseProducts" class="collapse" aria-labelledby="products" data-parent="#metafieldsConfiguration">
                        <div class="card-body">
                            <ul>
                                <li><h5>About tutorial</h5></li>
                                <p>
                                    Here,we are going to see, how to add product unique gif with their description.
                                </p>
                                <li><h5>Create Metafield</h5></li>
                                <p>- First, open metafields configuration app and enter in product module.<br>
                                    - Here, create product configuration. <br>
                                    - Add metafield value
                                </p>
                                <div><img src="/static_upload/installation_ss/product/1.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Add Metafield Value</h5></li>
                                <p>- First, open metafields configuration app and enter in product module.</p>
                                <div><img src="/static_upload/installation_ss/product/2.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Add metafield in theme code.</h5></li>
                                <p>- Now, click on “Online Store” menu and look for the edit code option under ‘Action’ dropdown option.</p>
                                <div><img src="/static_upload/installation_ss/shop/3.png" class="meta-config-install-img"></div>
                                <p class="meta-config-install-text">- Now, place the copied code where you required.<br> - In this example, code is placed in product-template.liquid file above product description.<br>
                                    - Save the file.</p>
                                <div><img src="/static_upload/installation_ss/product/4.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Preview.</h5></li>
                                <p>- Now, in preview the theme you can see it in above product description.</p>
                                <div><img src="/static_upload/installation_ss/product/5.png" class="meta-config-install-img"></div>
                            </ul>
                        </div>
                    </div>
                </div>

                {{--                collection--}}
                <div class="card collections" data-toggle="collapse" data-target="#collapseCollections" aria-expanded="false" aria-controls="collapse-collections">
                    <div class="card-header" id="collections" style="cursor: pointer;">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" >
                                Collection
                            </button>
                        </h2>
                    </div>
                    <div id="collapseCollections" class="collapse" aria-labelledby="collections" data-parent="#metafieldsConfiguration">
                        <div class="card-body">
                            <ul>
                                <li><h5>About tutorial</h5></li>
                                <p>
                                    Here,we are going to see, how to add collection feature image.
                                </p>
                                <li><h5>Create Metafield</h5></li>
                                <p>- First, open metafields configuration app and enter in collection module.<br>
                                    - Here, create collection configuration.
                                </p>
                                <div><img src="/static_upload/installation_ss/collection/1.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Add Metafield Value</h5></li>
                                <p>- First, open metafields configuration app and enter in collection module.</p>
                                <div><img src="/static_upload/installation_ss/collection/2.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Add metafield in theme code.</h5></li>
                                <p>- Now, click on “Online Store” menu and look for the edit code option under ‘Action’ dropdown option.</p>
                                <div><img src="/static_upload/installation_ss/shop/3.png" class="meta-config-install-img"></div>
                                <p class="meta-config-install-text">- Now, place the copied code where you required.<br> - In this example, code is placed in collection.liquid file.<br>
                                    - Save the file.</p>
                                <div><img src="/static_upload/installation_ss/collection/4.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Preview.</h5></li>
                                <p>- Now, in preview the theme you can see it in collection.</p>
                                <div><img src="/static_upload/installation_ss/collection/5.png" class="meta-config-install-img"></div>
                            </ul>
                        </div>
                    </div>
                </div>

                {{--                customer--}}
                <div class="card customer" data-toggle="collapse" data-target="#collapseCustomer" aria-expanded="false" aria-controls="collapse-customer">
                    <div class="card-header" id="customer" style="cursor: pointer;">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" >
                                Customer
                            </button>
                        </h2>
                    </div>
                    <div id="collapseCustomer" class="collapse" aria-labelledby="customer" data-parent="#metafieldsConfiguration">
                        <div class="card-body">
                            <ul>
                                <li><h5>About tutorial</h5></li>
                                <p>
                                    Here,we are going to see, how to add customer data.
                                </p>
                                <li><h5>Create Metafield</h5></li>
                                <p>- First, open metafields configuration app and enter in customer module.<br>
                                    - Here, create customer configuration.
                                </p>
                                <div><img src="/static_upload/installation_ss/customer/1.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Add Metafield Value</h5></li>
                                <p>- First, open metafields configuration app and enter in customer module.</p>
                                <div><img src="/static_upload/installation_ss/customer/2.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Add metafield in theme code.</h5></li>
                                <p>- Now, click on “Online Store” menu and look for the edit code option under ‘Action’ dropdown option.</p>
                                <div><img src="/static_upload/installation_ss/shop/3.png" class="meta-config-install-img"></div>
                                <p class="meta-config-install-text">- Now, place the copied code where you required.<br> - In this example, code is placed in customer/account.liquid file.<br>
                                    - Save the file.</p>
                                <div><img src="/static_upload/installation_ss/customer/4.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Preview.</h5></li>
                                <p>- Now, in preview the theme you can see it in account page.</p>
                                <div><img src="/static_upload/installation_ss/customer/5.png" class="meta-config-install-img"></div>
                            </ul>
                        </div>
                    </div>
                </div>

                {{--                blog--}}
                <div class="card blog" data-toggle="collapse" data-target="#collapseBlog" aria-expanded="false" aria-controls="collapse-blog">
                    <div class="card-header" id="blog" style="cursor: pointer">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" d>
                                Blog
                            </button>
                        </h2>
                    </div>
                    <div id="collapseBlog" class="collapse" aria-labelledby="blog" data-parent="#metafieldsConfiguration">
                        <div class="card-body">
                            <ul>
                                <li><h5>About tutorial</h5></li>
                                <p>
                                    Here,we are going to see, how to add article data.
                                </p>
                                <li><h5>Create Metafield</h5></li>
                                <p>- First, open metafields configuration app and enter in blog module.<br>
                                    - Here, create blog configuration.
                                </p>
                                <div><img src="/static_upload/installation_ss/blog/1.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Add Metafield Value</h5></li>
                                <p>- First, open metafields configuration app and enter in blog module.</p>
                                <div><img src="/static_upload/installation_ss/blog/2.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Add metafield in theme code.</h5></li>
                                <p>- Now, click on “Online Store” menu and look for the edit code option under ‘Action’ dropdown option.</p>
                                <div><img src="/static_upload/installation_ss/shop/3.png" class="meta-config-install-img"></div>
                                <p class="meta-config-install-text">- Now, place the copied code where you required.<br> - In this example, code is placed in article-template.liquid file after article content.<br>
                                    - Save the file.</p>
                                <div><img src="/static_upload/installation_ss/blog/4.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Preview.</h5></li>
                                <p>- Now, in preview the theme you can see it after article content.</p>
                                <div><img src="/static_upload/installation_ss/blog/5.png" class="meta-config-install-img"></div>
                            </ul>
                        </div>
                    </div>
                </div>

                {{--                order--}}
                <div class="card order" data-toggle="collapse" data-target="#collapseOrder" aria-expanded="false" aria-controls="collapse-order">
                    <div class="card-header" id="order" style="cursor: pointer;">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" >
                                Order
                            </button>
                        </h2>
                    </div>
                    <div id="collapseOrder" class="collapse" aria-labelledby="order" data-parent="#metafieldsConfiguration">
                        <div class="card-body">
                            <ul>
                                <li><h5>About tutorial</h5></li>
                                <p>
                                    Here,we are going to see, how to add order data.
                                </p>
                                <li><h5>Create Metafield</h5></li>
                                <p>- First, open metafields configuration app and enter in order module.<br>
                                    - Here, create order configuration.
                                </p>
                                <div><img src="/static_upload/installation_ss/order/1.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Add Metafield Value</h5></li>
                                <p>- First, open metafields configuration app and enter in order module.</p>
                                <div><img src="/static_upload/installation_ss/order/2.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Add metafield in theme code.</h5></li>
                                <p>- Now, click on “Online Store” menu and look for the edit code option under ‘Action’ dropdown option.</p>
                                <div><img src="/static_upload/installation_ss/shop/3.png" class="meta-config-install-img"></div>
                                <p class="meta-config-install-text">- Now, place the copied code where you required.<br> - In this example, code is placed in customer/order.liquid file above order table.<br>
                                    - Save the file.</p>
                                <div><img src="/static_upload/installation_ss/order/4.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Preview.</h5></li>
                                <p>- Now, in preview the theme you can see it in below order created date.</p>
                                <div><img src="/static_upload/installation_ss/order/5.png" class="meta-config-install-img"></div>
                            </ul>
                        </div>
                    </div>
                </div>


                {{--                page--}}
                <div class="card page" data-toggle="collapse" data-target="#collapsePage" aria-expanded="false" aria-controls="collapse-page">
                    <div class="card-header" id="page" style="cursor: pointer;">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" >
                                Page
                            </button>
                        </h2>
                    </div>
                    <div id="collapsePage" class="collapse" aria-labelledby="page" data-parent="#metafieldsConfiguration">
                        <div class="card-body">
                            <ul>
                                <li><h5>About tutorial</h5></li>
                                <p>
                                    Here,we are going to see, how to add page data.
                                </p>
                                <li><h5>Create Metafield</h5></li>
                                <p>- First, open metafields configuration app and enter in page module.<br>
                                    - Here, create page configuration.
                                </p>
                                <div><img src="/static_upload/installation_ss/page/1.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Add Metafield Value</h5></li>
                                <p>- First, open metafields configuration app and enter in page module.</p>
                                <div><img src="/static_upload/installation_ss/page/2.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Add metafield in theme code.</h5></li>
                                <p>- Now, click on “Online Store” menu and look for the edit code option under ‘Action’ dropdown option.</p>
                                <div><img src="/static_upload/installation_ss/shop/3.png" class="meta-config-install-img"></div>
                                <p class="meta-config-install-text">- Now, place the copied code where you required.<br> - In this example, code is placed in page.liquid file.<br>
                                    - Save the file.</p>
                                <div><img src="/static_upload/installation_ss/page/4.png" class="meta-config-install-img"></div>
                                <li class="meta-config-install-text"><h5>Preview.</h5></li>
                                <p>- Now, in preview the theme you can see it in about us page.</p>
                                <div><img src="/static_upload/installation_ss/page/5.png" class="meta-config-install-img"></div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-3 sidenav"></div>
    </div>
</div>
</div>
</body>
</html>
<script>
    $('.collapse').collapse()
</script>

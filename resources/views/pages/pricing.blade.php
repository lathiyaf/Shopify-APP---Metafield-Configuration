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
    </style>

    <style>
        * {
            box-sizing: border-box;
        }

        .price {
            list-style-type: none;
            border: 1px solid #eee;
            margin: 0;
            padding: 0;
            -webkit-transition: 0.3s;
            transition: 0.3s;
        }

        .price .header {
            padding: 15px 15px;
            background: linear-gradient(180deg,#6371c7,#5563c1);
            color: white;
            font-size: 25px;
        }

        .price li {
            padding: 20px;
            text-align: center;
        }

        .price .grey {
            padding: 5px 5px;
            background-color: #eee;
            font-size: 15px;
        }

        @media only screen and (max-width: 600px) {
            .columns {
                width: 100%;
            }
        }
        .card-plan{
            background-color: #fff;
            padding: 1.6rem;
            border-radius: 3px;
            box-shadow: 0 0 0 1px rgba(63, 63, 68, 0.05), 0 1px 3px 0 rgba(63, 63, 68, 0.15);
        }
        .card-plan-heading{
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 30px;
        }
    </style>

</head>
<body>
<div>
    <div class="container-fluid text-center">
        <div class="row content">
            <div class="col-sm-3 sidenav"></div>

            <div class="col-sm-6 text-left">
                <div class="pricing" id="pricing">
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
                </div>
                <div align="center" class="card-plan">
                    <h3 align="left" class="card-plan-heading">Plan & Pricing</h3>
                    <h6 align="left">The <b> Metafields Configuration </b> has only one plan and this plan includes below listed features.</h6>
                    <p align="left" style="color:#989898;font-size: 80%;">We will update you by email for the new features in upcoming days.</p>
                    <div class="columns">
                        <ul class="price">
                            <li class="header">Basic</li>
                            <li class="grey" style="background-color: #efecec;">USD$ <span style="font-size: 20px"><b>18</b></span>/month </br>14 days free trial</li>
                            <li class="list-group-item">Global Configuration</li>
                            <li class="list-group-item">Sync Existing Metafields</li>
                            <li class="list-group-item">Bulk Import/Export Metafields via CSV</li>
                            <li class="list-group-item">Add/Edit all Metafields easily</li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="col-sm-3 sidenav"></div>
        </div>
    </div>
</div>
</body>
</html>

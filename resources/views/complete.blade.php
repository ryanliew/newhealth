<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Newleaf - {{ __('auth.register') }}</title>

    <link href="/img/favicon.144x144.png" rel="apple-touch-icon" type="image/png" sizes="144x144">
    <link href="/img/favicon.114x114.png" rel="apple-touch-icon" type="image/png" sizes="114x114">
    <link href="/img/favicon.72x72.png" rel="apple-touch-icon" type="image/png" sizes="72x72">
    <link href="/img/favicon.57x57.png" rel="apple-touch-icon" type="image/png">
    <link href="/img/favicon.png" rel="icon" type="image/png">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/css/separate/pages/login.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/separate/vendor/select2.min.css"> 
    <link rel="stylesheet" href="/css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/start-ui.css">
    <link rel="stylesheet" href="/css/separate/buttons.css">
</head>
<body>
    <div class="top-bar" style="position:fixed; right:0">
                            
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {!! App::isLocale('zh') ? '<span class="flag-icon flag-icon-cn"></span>' : '<span class="flag-icon flag-icon-us"></span>' !!}

            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-menu-col">
                    <a class="dropdown-item @if(App::isLocale('en')) current @endif" href="/register/complete?lang=en"><span class="flag-icon flag-icon-us"></span> English</a>
                    <a class="dropdown-item @if(App::isLocale('zh')) current @endif" href="/register/complete?lang=zh"><span class="flag-icon flag-icon-cn"></span> 中文</a>
                </div>
            </div>
        </div>
    </div><!--.site-header-shown-->

    <div class="page-center">
        <div class="page-center-in">
            <div class="container">
                <div class="row">
                    <div class="col-sm bg-white p-4">

                        <div class="d-flex justify-content-center mb-3">
                            <img class="img-fluid" src="/img/logo.png">
                        </div>
                        
                        <div class="alert alert-success" role="alert">
                            <h2 class="alert-heading">{{ __('auth.thank_you') }}</h2>
                            <p>{{ __('auth.process_registration') }} @if(auth()->user()->country_id) {{ __('auth.referral_id', ['code' => auth()->user()->referral_code]) }} @endif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--.page-center-->
    <div class="copyright-text text-center text-muted mb-3">
        Copyright &copy 2018 Newleaf Plantation Berhad. All rights reserved.
    </div>

<script src="/js/lib/jquery/jquery-3.2.1.min.js"></script>
<script src="/js/lib/popper/popper.min.js"></script>
<script src="/js/lib/tether/tether.min.js"></script>
<script src="/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="/js/lib/select2/select2.full.min.js"></script>
<script src="/js/plugins.js"></script>
    <script type="text/javascript" src="/js/lib/match-height/jquery.matchHeight.min.js"></script>
    <script>
        $(function() {
            $('.page-center').matchHeight({
                target: $('html')
            });

            $(window).resize(function(){
                setTimeout(function(){
                    $('.page-center').matchHeight({ remove: true });
                    $('.page-center').matchHeight({
                        target: $('html')
                    });
                },100);
            });
        });
    </script>
<script src="/js/start-ui.js"></script>
<script src="/js/registration.js"></script>
</body>
</html>
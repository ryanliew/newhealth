<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Newleaf - {{ __('auth.register') }}</title>

    <link href="img/favicon.144x144.png" rel="apple-touch-icon" type="image/png" sizes="144x144">
    <link href="img/favicon.114x114.png" rel="apple-touch-icon" type="image/png" sizes="114x114">
    <link href="img/favicon.72x72.png" rel="apple-touch-icon" type="image/png" sizes="72x72">
    <link href="img/favicon.57x57.png" rel="apple-touch-icon" type="image/png">
    <link href="img/favicon.png" rel="icon" type="image/png">
    <link href="img/favicon.ico" rel="shortcut icon">

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
                    <a class="dropdown-item @if(App::isLocale('en')) current @endif" href="/register/success?lang=en"><span class="flag-icon flag-icon-us"></span> English</a>
                    <a class="dropdown-item @if(App::isLocale('zh')) current @endif" href="/register/success?lang=zh"><span class="flag-icon flag-icon-cn"></span> 中文</a>
                </div>
            </div>
        </div>
    </div><!--.site-header-shown-->

    <div class="page-center">
        <div class="page-center-in">
            <div class="container">
                <div class="row">
                    <div class="col-sm bg-white p-4">
                        <form action="{{ route('register') }}" method="POST">
                        <div class="alert alert-success" role="alert">
                            <h2 class="alert-heading">{{ __('auth.thank-you') }}</h2>
                            <p>{{ __('auth.success') }}</p>
                            @if(auth()->user()->email == auth()->user()->social_id . "@email.com")
                                <hr>
                                <p class="mb-0">{{ __('auth.success-email') }}</p>
                            @endif

                            @if(!auth()->user()->country_id)
                                <hr>
                                <p>{{ __('auth.success-country') }}</p>
                            @endif
                        </div>
                        

                        @csrf

                        @if(auth()->user()->email == auth()->user()->social_id . "@email.com")
                            <div class="form-group">
                                <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.email') }}" value="{{ old('email') }}" required autofocus/>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        @endif

                        @if(!auth()->user()->country_id)
                            <label>{{ __('auth.country') }} <span class="text-danger">*</span></label>
                            <select class="select2" name="country">
                               @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                               @endforeach
                            </select>
                        @endif

                        <div class="form-group mt-3">
                            <input type="text" class="form-control{{ $errors->has('referrer') ? ' is-invalid' : '' }}" name="referrer" value="{{ old('referrer') }}"  autofocus placeholder="{{ __('auth.referrer') }}"/>
                            @if ($errors->has('referrer'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('referrer') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <button type="submit" class="btn btn-rounded btn-success sign-up">{{ __('auth.submit') }}</button>
                        
                    </div>
                </div>
            </div>
        </div>
    </div><!--.page-center-->

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
</body>
</html>
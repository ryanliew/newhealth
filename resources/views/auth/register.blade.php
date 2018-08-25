<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ __('auth.title') }}</title>

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
    <link rel="stylesheet" href="/css/separate/pages/login.min.css?v=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/separate/vendor/select2.min.css"> 
    <link rel="stylesheet" href="/css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/start-ui.css?v=1.0">
    <link rel="stylesheet" href="/css/separate/buttons.css">
</head>
<body>
    <div class="top-bar" style="position:fixed; right:0; z-index: 5;">
                            
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {!! App::isLocale('zh') ? '<span class="flag-icon flag-icon-cn"></span>' : '<span class="flag-icon flag-icon-us"></span>' !!}

            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-menu-col">
                    <a class="dropdown-item @if(App::isLocale('en')) current @endif" href="/register?lang=en"><span class="flag-icon flag-icon-us"></span> English</a>
                    <a class="dropdown-item @if(App::isLocale('zh')) current @endif" href="/register?lang=zh"><span class="flag-icon flag-icon-cn"></span> 中文</a>
                </div>
            </div>
        </div>
    </div><!--.site-header-shown-->

    <div class="page-center login-bg">
        <div class="page-center-in">
            <div class="container-fluid">
                <div class="col-md-5"></div>
                <div class="col-md-7">
                    <form class="sign-box" action="{{ route('register') }}" method="POST">
                        @csrf
                        <div><img class="img-fluid" src="/img/logo.png"></div>
                        <header class="sign-title" style="font-size:18px; padding:30px 0;">{{ __('auth.register_today') }}<b>{{ __('auth.register') }}</b>{{ __('auth.en_space') }}{{ __('auth.register_message') }}</header>
                        <div class="form-group">
                            <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.full_name') }}" value="{{ old('name') }}" required autofocus/>
                            @if ($errors->has('name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="{{ __('auth.email') }}"/>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="{{ __('auth.password') }}"/>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <input name="password_confirmation" type="password" class="form-control" placeholder="{{ __('auth.repeat') }}{{ __('auth.password') }}"/>
                        </div>
                        <div class="form-group">
                            <label>{{ __('auth.country') }} <span class="text-danger">*</span></label>
                            <select class="select2" name="country">
                               @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @if($country->id == 162) selected @endif>{{ $country->name }}</option>
                               @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="referrer" value="{{ request()->referrer }}">
                        <button type="submit" class="btn btn-rounded btn-success sign-up">{{ __('auth.register') }}</button>
                        <p class="sign-note">{{ __('auth.already_have_account') }} <a href="{{ route('login') }}?lang={{ App::isLocale('zh') ? 'zh' : 'en' }}">{{ __('auth.login') }}</a></p>
                        <!--<button type="button" class="close">
                            <span aria-hidden="true">&times;</span>
                        </button>-->
                        
                        {{-- <a href="{{ url('/social/wechat/login') }}" class="btn btn-wechat btn-block"><i class="fa fa-wechat"></i> {{ __('auth.wechat-signup') }}</a> --}}
                        {{-- <a href="{{ url('/social/facebook/login') }}{{ request()->has('referrer') ? '?referrer=' . request()->referrer : '' }}" class="btn btn-facebook btn-block"><i class="fa fa-facebook-square"></i> {{ __('auth.facebook_signup') }}</a>
                        <a href="{{ url('/social/google/login')}}{{ request()->has('referrer') ? '?referrer=' . request()->referrer : '' }}" class="btn btn-google btn-block"><i class="fa fa-google"></i> {{ __('auth.google_signup') }}</a> --}}
                    </form>
                </div>
            </div>
        </div>
    </div><!--.page-center-->

<script src="/js/lib/jquery/jquery-3.2.1.min.js"></script>
<script src="/js/lib/popper/popper.min.js"></script>
<script src="/js/lib/tether/tether.min.js"></script>
<script src="/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="/js/plugins.js"></script>
<script src="/js/lib/select2/select2.full.min.js"></script>

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
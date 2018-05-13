<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Newleaf</title>

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
                    <a class="dropdown-item @if(App::isLocale('en')) current @endif" href="/login?lang=en"><span class="flag-icon flag-icon-us"></span> English</a>
                    <a class="dropdown-item @if(App::isLocale('zh')) current @endif" href="/login?lang=zh"><span class="flag-icon flag-icon-cn"></span> 中文</a>
                </div>
            </div>
        </div>
    </div><!--.site-header-shown-->
    <div class="page-center">
        <div class="page-center-in">
            <div class="container-fluid">
                <form class="sign-box" method="POST" action="{{ route('login') }}">
                    <div><img class="img-fluid" src="/img/logo.png"></div>
                    <header class="sign-title">{{ __('auth.login') }}</header>

                    @csrf
                    
                    <div class="form-group">
                        <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" name="email" placeholder="{{ __('auth.email') }}"/>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('auth.password') }}"/>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="checkbox float-left">
                            <input type="checkbox" name="remeber" id="signed-in"/>
                            <label for="signed-in">{{ __('auth.remember') }}</label>
                        </div>
                        {{-- <div class="float-right reset">
                            <a href="{{ route('password.request') }}">{{ __('auth.forgot_password') }}</a>
                        </div> --}}
                    </div>
                    <button type="submit" class="btn btn-rounded">{{ __('auth.login') }}</button>
                    <p class="sign-note">{{ __('auth.new_to_site') }} <a href="{{ route('register') }}?lang={{ App::isLocale('zh') ? 'zh' : 'en' }}">{{ __('auth.register') }}</a></p>
                    <!--<button type="button" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>-->
                     <a href="{{ url('/social/facebook/login') }}" class="btn btn-facebook btn-block"><i class="fa fa-facebook-square"></i> {{ __('auth.facebook_login') }}</a>
                    <a href="{{ url('/social/google/login') }}" class="btn btn-google btn-block"><i class="fa fa-google"></i> {{ __('auth.google_login') }}</a>
                </form>
            </div>
        </div>
    </div><!--.page-center-->


<script src="/js/lib/jquery/jquery-3.2.1.min.js"></script>
<script src="/js/lib/popper/popper.min.js"></script>
<script src="/js/lib/tether/tether.min.js"></script>
<script src="/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="/js/plugins.js"></script>
    <script type="text/javascript" src="js/lib/match-height/jquery.matchHeight.min.js"></script>
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
<script src="js/start-ui.js"></script>
</body>
</html>


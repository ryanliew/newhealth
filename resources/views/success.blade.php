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
                
                        <div class="d-flex justify-content-center mb-3">
                            <img class="img-fluid" style="width:258px;height:72px;" src="/img/logo.png">
                        </div>
                        <form id="app" method="POST" action="{{ url('/register/success') }}">
                            <div class="alert alert-success" role="alert">
                                <h2 class="alert-heading">{{ __('auth.thank_you') }} {{ __('auth.last_step') }}</h2>
                                <p>{{ __('auth.success') }}</p>
                            </div>
                            @if($errors->count() > 0)
                            <div class="alert alert-danger" role="alert">
                                <h2 class="alert-heading">Error</h2>
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @csrf
                            <h4>{{ __('auth.general_info') }}</h4>
                            @if(!auth()->user()->parent_id)
                                <div class="form-group">
                                    <referrer referrer="{{ session('referrer') }}" app_locale="{{ App::isLocale('zh') ? 'zh' : 'en' }}"></referrer> 
                                    @if ($errors->has('referrer'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('referrer') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ __('auth.select_package') }} <span class="text-danger">*</span></label>
                                    <select class="select2" name="package" required>
                                       @foreach($packages as $package)
                                            <option value="{{ $package->id }}">{{ $package->tree_count }} {{ trans_choice('auth.tree', $package->tree_count) }}</option>
                                       @endforeach
                                    </select>
                                </div>

                            @endif
                            <hr>
                            <h4>{{ __('auth.personal_info') }}</h4>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label>{{ __('auth.name') }}  <span class="text-danger">*</span></label>
                                        <input name='name' type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.name') }}" value="{{ auth()->user()->name }}" required autofocus/>
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label>{{ __('auth.identification') }}  <span class="text-danger">*</span></label>
                                        <input name='identification' type="text" class="form-control{{ $errors->has('identification') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.identification') }}" value="{{ old('identification') }}" required/>
                                        @if ($errors->has('identification'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('identification') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ __('auth.email') }}  <span class="text-danger">*</span></label>
                                <input name='email' type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.email') }}" value="{{ ends_with(auth()->user()->email, '@email.com') ? old('email') : auth()->user()->email }}" required autofocus/>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>{{ __('auth.address') }}  <span class="text-danger">*</span></label>
                                <input name='address_line_1' type="text" class="form-control{{ $errors->has('address_line_1') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.address1') }}" value="{{ old('address_line_1') }}" required/>
                                @if ($errors->has('address_line_1'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address_line_1') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input name='address_line_2' type="text" class="form-control{{ $errors->has('address_line_2') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.address2') }}" value="{{ old('address_line_2') }}" />
                                @if ($errors->has('address_line_2'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address_line_2') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-sm">
                                    <label>{{ __('auth.country') }} <span class="text-danger">*</span></label>
                                    <select class="select2" name="country_id">
                                       @foreach($countries as $country)
                                            <option value="{{ $country->id }}" @if(auth()->user()->country_id == $country->id) selected @endif>{{ $country->name }}</option>
                                       @endforeach
                                    </select>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label>{{ __('auth.postcode') }}  <span class="text-danger">*</span></label>
                                        <input name='postcode' type="text" class="form-control{{ $errors->has('postcode') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.postcode') }}" value="{{ old('postcode') }}" required/>
                                        @if ($errors->has('postcode'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('postcode') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm">
                                    <label>{{ __('auth.nationality') }} <span class="text-danger">*</span></label>
                                    <select class="select2" name="nationality">
                                       @foreach($countries as $country)
                                            <option value="{{ $country->name }}" @if(auth()->user()->country_id == $country->id) selected @endif>{{ $country->name }}</option>
                                       @endforeach
                                    </select>
                                     @if ($errors->has('nationality'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('nationality') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label>{{ __('auth.phone') }}  <span class="text-danger">*</span></label>
                                        <input name='phone' type="tel" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.phone') }}" value="{{ old('phone') }}"/>
                                        @if ($errors->has('phone'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('phone') }}</strong>
                                            </span>
                                        @endif
                                    </div>  
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label>{{ __('auth.gender') }}</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="gender" type="radio" id="gender_male" value="male">
                                            <label class="form-check-label" for="gender_male">{{ __('auth.gender_male') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="gender" type="radio" id="gender_female" value="female">
                                            <label class="form-check-label" for="gender_female">{{ __('auth.gender_female') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <hr>
                            <h4>{{ __('auth.company_info') }}</h4>
                            <company-registration :countries="{{ json_encode($countries) }}" user_country="{{ auth()->user()->country_id }}" app_locale="{{ App::isLocale('zh') ? 'zh' : 'en' }}"></company-registration>

                            <hr>
                            <h4>{{ __('auth.bank_info') }}</h4>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label>{{ __('auth.bank_name') }}  <span class="text-danger">*</span></label>
                                        <input name='bank_name' type="text" class="form-control{{ $errors->has('bank_name') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.bank_name') }}" value="{{ old('bank_name') }}" required/>
                                        @if ($errors->has('bank_name'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('bank_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label>{{ __('auth.bank_swift') }}</label>
                                        <input name='bank_swift' type="text" class="form-control{{ $errors->has('bank_swift') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.bank_swift') }}" value="{{ old('bank_swift') }}"/>
                                        @if ($errors->has('bank_swift'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('bank_swift') }}</strong>
                                            </span>
                                        @endif
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label>{{ __('auth.bank_address') }}  <span class="text-danger">*</span></label>
                                        <input name='bank_address' type="text" class="form-control{{ $errors->has('bank_address') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.bank_address') }}" value="{{ old('bank_address') }}" required/>
                                        @if ($errors->has('bank_address'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('bank_address') }}</strong>
                                            </span>
                                        @endif
                                    </div> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label>{{ __('auth.account_type') }}  <span class="text-danger">*</span></label>
                                        <input name='account_type' type="text" class="form-control{{ $errors->has('account_type') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.account_type') }}" value="{{ old('account_type') }}" required/>
                                        @if ($errors->has('account_type'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('account_type') }}</strong>
                                            </span>
                                        @endif
                                    </div>   
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label>{{ __('auth.account_no') }}  <span class="text-danger">*</span></label>
                                        <input name='account_no' type="text" class="form-control{{ $errors->has('account_no') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.account_no') }}" value="{{ old('account_no') }}" required/>
                                        @if ($errors->has('account_no'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('account_no') }}</strong>
                                            </span>
                                        @endif
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label>{{ __('auth.beneficiary_name') }}  <span class="text-danger">*</span></label>
                                        <input name='beneficiary_name' type="text" class="form-control{{ $errors->has('beneficiary_name') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.beneficiary_name') }}" value="{{ old('beneficiary_name') }}" required/>
                                        @if ($errors->has('beneficiary_name'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('beneficiary_name') }}</strong>
                                            </span>
                                        @endif
                                    </div> 
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label>{{ __('auth.beneficiary_identification') }}  <span class="text-danger">*</span></label>
                                        <input name='beneficiary_identification' type="text" class="form-control{{ $errors->has('beneficiary_identification') ? ' is-invalid' : '' }}" placeholder="{{ __('auth.beneficiary_identification') }}" value="{{ old('beneficiary_identification') }}" required/>
                                        @if ($errors->has('beneficiary_identification'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('beneficiary_identification') }}</strong>
                                            </span>
                                        @endif
                                    </div> 
                                </div>
                            </div>
                                                        
                            <button type="submit" class="btn btn-rounded btn-success sign-up">{{ __('auth.submit') }}</button>
                        </form>
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
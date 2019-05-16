<body style="margin:0 auto;" >
   
    <!-- content -->
    <div style="width: 70%; margin:0 auto; font-family:Calibri">
        <div style="width:100%; height:auto">
           <img height="auto" width="200" src="{{ url('/img/mail/logo.jpg') }}">
        </div>

        <div style=" font-size:24px;">
            <b> 
            {{ __('mail.welcome_onboard') }} <span style="color:#0e5796">{{ $user->name }}</span>{{ __('mail.welcome_onboard_behind') }}!
            </b>
        </div>
        <br>
        <div style="width:80%; float:left;">
            {{ __('mail.thank_you_signup') }} {{ __('mail.thank_you_signup_2') }}

            <br><br>
            <b> 
                {{ __('mail.newleaf_grower_id') }} <span style="color:#0e5696">{{ $user->referral_code }}</span>
                <br>
                <span style="color:#000000;">{{ __('mail.username') }}: </span><span style="color:#0e5696;"> {{ $user->email }}</span>
                @if($user->parent)
                <br>
                <span style="color:#000000;">{{ __('mail.sponsor_id') }}: </span><span style="color:#0e5696;"> {{ $user->parent->referral_code }}</span>
                <br>
                <span>{{ __('mail.sponsor_name') }}: <span style="color:#0e5696;">{{ $user->parent->name }}</span>
                @endif 
            </b>
            <br><br>
            <a target="_blank" href=" {{ url('/login') }}" class="button button-blue" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); color: #FFF; display: inline-block; text-decoration: none; -webkit-text-size-adjust: none; background-color: #3097D1; border-top: 10px solid #3097D1; border-right: 18px solid #3097D1; border-bottom: 10px solid #3097D1; border-left: 18px solid #3097D1;">{{ __('mail.click_to_login') }}</a>
            <br><br>
            {{ __('mail.enquiry_program') }} {{ __('mail.enquiry_program_2') }}
            <br><br>
            {{ __('mail.thank_you_signup_again') }}.
        </div>
        
        <div style="width:100%; float:left; padding:20px 0; border-bottom:1px solid #999; margin-bottom:20px;"></div>
     
    <!-- footer -->
        
        {{-- <div style="width:100%; padding:20px 0; ">
            <div style="width:80%; float:left; text-align: left; color:#0e5696">
                <b> NEWLEAF PLANATION BERHAD <span style="font-size:12px">(1251569-U)</span></b><br>
            </div>
            <div style="width:80%; float:left; text-align:left; color:#999">
                Suite E-7-1, Block E, Plaza Mont Kiara, No 2 Jalan Kiara, 50480 Kuala Lumpur<br>
                <b>T</b> +6036201 6336 | <b>W</b> www.newleaf.com.my | <b>E</b> support@newleaf.com.my
            </div>
            <div style="width:20%; float:right; color:#999">
                <div><b>FOLLOW US</b></div>
                <div>
                    <div style="float:left; margin-right:10px;">
                        <img src="https://portal.newleaf.com.my/img/mail/icon1.jpg">
                    </div>
                    <div style="float:left; margin-right: 10px;">
                        <a href="https://www.facebook.com/Newleaf-158391578339214/"><img src="https://portal.newleaf.com.my/img/mail/icon2.jpg"></a>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <!-- end content --> 
    
    <div style="width:100%; float:left; padding:20px 0; margin-bottom:20px;"></div>
         
</body>
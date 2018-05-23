<style type="text/css">
        #mobileshow    { display:none; }
        
        @media screen and (max-width: 500px) {
            #mobileshow   { display:block; }
        }
</style>
        
<style type="text/css">
        #mobileshow1   { display:block; }
        
        @media screen and (max-width: 500px) {
            #mobileshow1   { display:none; }
        }
</style>
        
<body style="margin:0">
    <!-- desktop view -->
    <div id="mobileshow1">
    
        <div style="width: 100%; height:auto;">
            <img src="https://portal.newleaf.com.my/img/mail/background.png" width="100%" height="auto">
        </div>
        <div style="width: 80%; height:auto; margin:0 auto; font-family:Calibri">
            <div style=" font-size:24px; "><b> {{ __('mail.welcome_onboard') }} <span style="color:#0e5796">{{ $user->name }}</span>{{ __('mail.welcome_onboard_behind') }}!</b>
            </div>
            <br>

            <div style="width:80%; float:left;">
                {{ __('mail.thank_you_signup') }} {{ __('mail.thank_you_signup_2') }}
            </div>

            <div style="width:80%; float:left; margin-top:10px;">
                <b> {{ __('mail.newleaf_grower_id') }} <span style="color:#0e5796">{{ $user->referral_code }}</span>
                    @if($user->parent)
                    <br>
                    <span style="color:#000000;">{{ __('mail.sponsor_id') }}: </span><span style="color:#0e5796;"> {{ $user->parent->referral_code }}</span>
                    <br>
                    <span>{{ __('mail.sponsor_name') }}: <span style="color:#0e5796;">{{ $user->parent->name }}</span>
                    @endif 
                </b><br><br>
                {{ __('mail.enquiry_program') }}<br>{{ __('mail.enquiry_program_2') }}<a href="mailto:support@newleaf.com.my">support@newleaf.com.my</a>
                <br><br>
                {{ __('mail.thank_you_signup_again') }}.
            </div>

            <div style="width:100%; float:left; padding:20px 0; border-bottom:1px solid #999; margin-bottom:20px;"></div>
             
             
            <div style="width:100%; padding:20px 0; ">
                <div style="width:80%; float:left; text-align:left; color:#999">
                    <b > NEWLEAF PLANATION BERHAD (1251569-U)</b><br>
                    Suite E-7-1, Block E, Plaza Mont Kiara, No 2 Jalan Kiara, 50480 Kuala Lumpur<br>
                    <b>T</b> +6036201 6336 | <b>W</b> www.newleaf.com.my | <b>E</b> support@newleaf.com.my
                </div>
                <div style="width:20%; float:right; color:#999">
                    <div><b>FOLLOW US</b></div>
                    <div>
                        <div style="float:left; margin-right:10px;">
                            <img src="https://portal.newleaf.com.my/img/mail/icon1.jpg">
                        </div>
                        <div style="float:left; margin-right:10px;">
                            <img src="https://portal.newleaf.com.my/img/mail/icon2.jpg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end desktop view -->

    <!--mobile view -->
       <div id="mobileshow">
    
    <div style="width: 100%; height:auto;">
        <img src="https://portal.newleaf.com.my/img/mail/background.png" width="100%" height="auto">
    </div>
    <div style="padding:0 20px">
        <div style="width: 100%; height:auto; margin:0 auto; font-family:Calibri; text-align:center;">
            <div style=" font-size:24px; "><b> {{ __('mail.welcome_onboard') }} <span style="color:#0e5796">{{ $user->name }}</span>{{ __('mail.welcome_onboard_behind') }}!</b></div>
            <br>
            <div style="width:100%;  text-align:center;">
                {{ __('mail.thank_you_signup') }} {{ __('mail.thank_you_signup_2') }}
            </div>
            
            <div style="width:100%; float:left; margin-top:10px;">
                <b> 
                    {{ __('mail.newleaf_grower_id') }} <span style="color:#0e5796">{{ $user->referral_code }}</span>
                        @if($user->parent)
                        <br>
                        <span style="color:#000000;">{{ __('mail.sponsor_id') }}: </span><span style="color:#0e5796;"> {{ $user->parent->referral_code }}</span>
                        <br>
                        <span>{{ __('mail.sponsor_name') }}: <span style="color:#0e5796;">{{ $user->parent->name }}</span>
                        @endif 
                </b>
                <br><br>
                {{ __('mail.enquiry_program') }}<br>{{ __('mail.enquiry_program_2') }} <a href="mailto:support@newleaf.com.my">support@newleaf.com.my</a> 
                <br><br>
                {{ __('mail.thank_you_signup_again') }}.
            </div>
             
            <div style="width:100%; float:left; padding:20px 0; border-bottom:1px solid #999; margin-bottom:20px;"></div>
             
             
            <div style="width:100%; padding:20px 0; ">
                <div style="width:100%;  text-align:center; color:#999">
                    <b> NEWLEAF PLANATION BERHAD (1251569-U)</b><br>
                    Suite E-7-1, Block E, Plaza Mont Kiara, No 2 Jalan Kiara, 50480 Kuala Lumpur<br>
                    <b>T</b> +6036201 6336 | <b>W</b> www.newleaf.com.my | <b>E</b> support@newleaf.com.my
                </div>
                <br><br>
                <div style="width:100%; color:#999">
                    <div><b>FOLLOW US</b></div>
                    <div style="text-align:center; width:92px; margin:0 auto">
                        <div style="float:left; margin-right:10px;">
                            <img src="https://portal.newleaf.com.my/img/mail/icon1.jpg">
                        </div>
                        <div style="flaot:left;">
                            <img src="https://portal.newleaf.com.my/img/mail/icon2.jpg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- end mobile view -->
    
</body>

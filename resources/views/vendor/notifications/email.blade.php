
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
<title>{{ config('app.name') }}</title>

<style type="text/css">

    body{width: 100%; background-repeat: no-repeat; background-size: contain; background-position: left top; background-color: #ffffff !important; margin:0; padding:0; -webkit-font-smoothing: antialiased;mso-margin-top-alt:0px; mso-margin-bottom-alt:0px; mso-padding-alt: 0px 0px 0px 0px;}

    p,h1,h2,h3,h4{margin-top:20px !important;margin-bottom:0;padding-top:0;padding-bottom:0;line-height:1.8;}

    span.preheader{display: none; font-size: 1px;}

    html{width: 100%;}

    table{font-size: 12px;border: 0;}

    .menu-space{padding-right:25px;}

    a,a:hover { text-decoration:none; color:#4D95AD;}
    
    .desktop-hidden{ display: none; }

    .logo{ padding-right: 45px; width: 25vw;max-width:300px; }

    @media only screen and (max-width:640px)
    {
        body {width:auto!important;}
        table.main {width:440px !important;}
        table.two-left {width:420px !important; margin:0px auto;}
        table.full {width:100% !important; margin:0px auto;}
        table.two-left-inner {width:400px !important; margin:0px auto;}
        table.menu-icon { display:none;}
        .mobile-hidden{ display:none; }
        .desktop-hidden{ display:table-row !important; }
        .mobile-wide{ width: 100%; }
        .logo{ padding-right: 0 !important; padding-top: 30px; width: 80vw; max-width: 80vw;}

    }

    @media only screen and (max-width:479px)
    {
        body {width:auto!important;}
        table.main {width:310px !important;}
        table.two-left {width:300px !important; margin:0px auto;}
        table.full {width:100% !important; margin:0px auto;}
        table.two-left-inner {width:280px !important; margin:0px auto;}
        table.menu-icon { display:none;}  
        .mobile-hidden{ display:none; }
        .desktop-hidden{ display:table-row !important; }
        .mobile-wide{ width: 100%; }
        .logo{ padding-right: 0 !important; padding-top: 30px; width: 80vw; max-width: 80vw; }
    }



</style>

</head>

<body yahoo="fix" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<!--Main Table Start-->
    <table class="main" style="margin: auto;" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td class="mobile-hidden"><img src="https://portal.newleaf.com.my/img/left-durian-bg-01.jpg" height="350px"></td>
            <td align="center" valign="middle" style="width:25vw;max-width:300px;">
                <a href="https://portal.newleaf.com.my"><img class="logo" src="https://portal.newleaf.com.my/img/logo.png"/></a>
            </td>
        </tr>
        <tr>
            <td class="mobile-wide" style="word-wrap:break-word;font-size:0px;padding:10px 20px 20px 20px;border-bottom:1px solid #ccc;" align="left">
                <div style="cursor:auto;color:#000000;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:11px;line-height:22px;text-align:left;">
                    <p style="color:black;">
                        <h2 style="font-size:36px;">{{ __('mail.welcome_onboard') }}<span style="color:#185C99;">{{ $user->name }}</span>{{ __('mail.welcome_onboard_behind') }}!</h2>
                    </p>
                    <p style="color:black;">
                        <span style="font-size:14px;">{{ __('mail.thank_you_signup') }}</span>
                    </p>
                    <p></p>
                    <p style="color:black;">
                        <strong><span style="font-size:16px;">{{ __('mail.newleaf_grower_id') }} <span style="color:#185C99;">{{ $user->referral_code }}</span>@if($user->parent)<br><span style="color:#000000;">{{ __('mail.sponsor_name') }}: </span><span style="color:#185C99;"> {{ $user->parent->referrerl_code }}</span></span></strong><br><strong><span style="font-size:16px;">{{ __('mail.sponsor_name') }}: <span style="color:#185C99;">{{ $user->parent->name }}</span>@endif</span></strong>
                    </p>
                    <p style="color:black;">
                        <span style="font-size:14px;">{{ __('mail.enquiry_program') }}<a href="mailto:support@newleaf.com.my">support@newleaf.com.my</a></span>
                    </p>
                    <p style="color:black;">
                        <span style="font-size:14px;">{{ __('mail.thank_you_signup_again') }}.</span>
                    </p>
                </div>
            </td>
            <td style="border-bottom:1px solid #ccc;"></td>
        </tr>
        <tr>
            <td class="mobile-wide" style="word-wrap:break-word;font-size:0px;padding:0px 20px 30px 20px;" align="left">
                <div style="cursor:auto;color:#000000;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:11px;line-height:22px;text-align:left;">
                    <p>
                        <span style="font-family:Ubuntu, Helvetica, Arial, sans-serif;color:#777;font-size:14px;"><strong style="font-size:15px;">NEWLEAF PLANATION BERHAD <small style="font-size:12px;">(1251569-U)</small></strong><br>Suite E-7-1, Block E, Plaza Mont Kiara, No 2 Jalan Kiara, 50480 Kuala Lumpur<br><strong>T</strong> <a href="tel:+60362016336">+6036201 6336</a> | <strong>W</strong> <a href="http://www.newleaf.com.my">www.newleaf.com.my</a> | <strong>E</strong> <a href="mailto:support@newleaf.com.my">support@newleaf.com.my</a></span>
                    </p>
                </div>
            </td>
            <td class="mobile-hidden">
                <strong style="font-family:Ubuntu, Helvetica, Arial, sans-serif;color: #777;font-size: 15px;">{{ __('mail.follow_us') }}</strong>
                <br>
                <a href="#"><img src="https://portal.newleaf.com.my/img/mail/wechat.jpg"></a>
                <a href="https://www.facebook.com/Newleaf-158391578339214/"><img src="https://portal.newleaf.com.my/img/mail/facebook.jpg"></a>
            </td>
        </tr>
        <tr class="desktop-hidden">
            <td align="center">
                <strong style="font-family:Ubuntu, Helvetica, Arial, sans-serif;color: #777;font-size: 15px;">{{ __('mail.follow_us') }}</strong>
                <br>
                <a href="#"><img src="https://portal.newleaf.com.my/img/mail/wechat.jpg"></a>
                <a href="https://www.facebook.com/Newleaf-158391578339214/"><img src="https://portal.newleaf.com.my/img/mail/facebook.jpg"></a>
            </td>
            <td>
            </td>
        </tr>
    </table>
    

<!--Main Table End-->

</body>
</html>

<body style="margin:0 auto;" >
    <style>
        table tr td {
            border: 0px solid transparent;
        }

        table tr {
            border: 0px solid transparent;
        }

        table, body {
            font-family: Helvetica;
        }

        p {
            font-size: 18px;
        }
        
    </style>
   <table style="width:1170px; padding: 10px;margin: 0 auto;">
        <tr style="background: black">
            <td colspan="2">
                <img src="{{ url('img/mail/newsletter-header.png') }}" width="100%" style="width：100%">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <img src="{{ url( 'storage/' . $post->cover_photo ) }}" width="100%">
            </td>
        </tr>
        <tr height="30px">
            <td></td>
            <td></td>
        </tr>
        <tr style="vertical-align: middle;">
            <td style="width:526.5px;">
                <div style="padding-bottom:30px;border-bottom: 10px solid black;margin-bottom: 30px;">
                    <b style="font-size: 35px;line-height:35px;" v-else>
                        @if($locale == 'zh')
                            {{ $post->title_zh }}
                        @else
                            {{ $post->title }}
                        @endif
                    </b>
                </div>
                <img src="{{ url( 'storage/' . $post->left_photo ) }}" width="100%" v-else>
            </td>
            <td>
                <div style="padding: 50px; font-size: 17px;">
                    @if($locale == 'zh')
                        {!! $post->content_zh !!}
                    @else
                        {!! $post->content !!}
                    @endif
                </div>
            </td>
        </tr>
    </table>    
     
    <!-- footer -->
        
        <div style="width:100%; text-align:center;font-family: Helvetica;">
            <img src="https://portal.newleaf.com.my/img/mail/email-logo.png"><br>
            <b> NEWLEAF PLANATION BERHAD </b><br>
            <span style="font-size:12px">(1251569-U)</span>
            <div>
                Suite E-7-1, Block E, Plaza Mont Kiara, No 2 Jalan Kiara, 50480 Kuala Lumpur
            </div>
            <div>
                <b>T</b> +603 6201 6336 <b style="padding-left: 5%;">F</b> +603 6201 7337  <b style="padding-left: 5%;">W</b> www.newleaf.com.my
            </div>
        </div>
    <!-- end content --> 
    
    <div style="width:100%; float:left; padding:20px 0; margin-bottom:20px;border-bottom:15px solid black"></div>
         
</body>
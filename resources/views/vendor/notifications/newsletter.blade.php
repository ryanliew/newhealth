<body style="margin:0 auto;" >
    <style>
        table tr td {
            border: 0px solid transparent;
        }

        table tr {
            border: 0px solid transparent;
        }

        table {
            font-family: Helvetica;
        }
        
    </style>
   <table style="width:1170px; padding: 10px;margin: 0 auto;">
        <tr style="background: black">
            <td style="padding:35px 0;" colspan="2">
                <div style="width:100%">
                    <b style="font-size:60px;color:white;margin-left: 30px;line-height: 44px;margin-top:15px;">{{ __('mail.newleaf_bulletin') }}</b>
                </div>
                <div style="float: left; margin-right: 30%">
                    <span style="font-size:19.5px;color:white;margin-left: 31px;margin-bottom:15px;">{{ __('mail.latest_news') }}</span>
                </div>
                <div style="float:left;">
                     <b style="font-size: 19.5px;color:white;">{{ $post->created_at->format("jS F Y") }}</b>
                 </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <img src="{{ url( 'storage/' . $post->cover_photo ) }}" width="100%" v-else>
            </td>
        </tr>
        <tr style="vertical-align: middle;">
            <td style="width:45%;">
                <div style="padding-bottom:30px;border-bottom: 10px solid black;margin-bottom: 30px;">
                    <b style="font-size: 45px;line-height:45px;" v-else>
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
                <div style="padding: 50px">
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
        
        <div style="width:100%; text-align:center; ">
            <img src="https://portal.newleaf.com.my/img/logo.png" style="width:250px;"><br>
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
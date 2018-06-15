<body style="margin:0 auto;" >
    <style>
        table tr td {
            border: 0px solid transparent;
            text-align: center;
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
    <table style="width:1170px;text-align:center;">
        <tr style="background: black">
            <td>
                <img src="{{ url('/img/mail/newsletter-header.png') }}" width="100%" style="width:100%;display:block;">
            </td>
        </tr>
    </table>
    <table style="width:1170px;text-align:center;">
        <tr>
            <td>
                <img src="{{ url('storage/' . $post->cover_photo) }}" width="100%" style="width:100%;display:block;">
            </td>
        </tr>
    </table>
    <table style="height:30px">
        <tr>
            <td></td>
        </tr>
    </table>
    <table style="width:1170px;text-align:center;">
        <tr>
            <td>
                <b>{{ __('post.published_date') }}: {{ $post->created_at->format("jS F Y") }}</b>
            </td>
        </tr>
    </table>
    <table style="width:1170px;text-align:center;">
        <tr>
            <td>
                <b style="font-size: 35px;line-height:35px;" v-else>
                    @if($locale == 'zh')
                        {{ $post->title_zh }}
                    @else
                        {{ $post->title }}
                    @endif
                </b>
            </td>
        </tr>
    </table>
    <table style="height:25px">
        <tr>
            <td>
            </td>
        </tr>
    </table>
    <table style="width:1170px; ">
        <tr>
            <td style="width:{{ (1170 - $post->thumbnail_table_width) / 2 }}px;"></td>
            <td>
                <table style="width: {{ $post->thumbnail_table_width }}px;text-align:center">
                    <tr style="vertical-align: middle;">
                        @if($post->left_photo)
                        <td >
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{ url('storage/' . $post->left_photo) }}" width="100%" style="width:100%">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i>
                                            @if($locale == 'zh')
                                                {{ $post->left_caption_zh }}
                                            @else
                                                {{ $post->left_caption }}
                                            @endif
                                        </i>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        @endif
                        @if($post->middle_photo)
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{ url('storage/' . $post->middle_photo) }}" width="100%" style="width:100%">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i>
                                            @if($locale == 'zh')
                                                {{ $post->middle_caption_zh }}
                                            @else
                                                {{ $post->middle_caption }}
                                            @endif
                                        </i>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        @endif
                        @if($post->right_photo)
                        <td >
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{ url('storage/' . $post->right_photo) }}" width="100%" style="width:100%">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i>
                                            @if($locale == 'zh')
                                                {{ $post->right_caption_zh }}
                                            @else
                                                {{ $post->right_caption }}
                                            @endif
                                        </i>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        @endif           
                    </tr>
                </table>
            </td>
            <td style="width:{{ (1170 - $post->thumbnail_table_width) / 2 }}px;"></td>
        </tr>
    </table>
    <table style="width:1170px; text-align:center;">
        <tr>
            <td>
                <div style="padding: 30px">
                    @if($locale == 'zh')
                        {!! $post->content_zh !!}
                    @else
                        {!! $post->content !!}
                    @endif
                </div>
            </td>   
        </tr>
    </table>   

    <table style="height: 25px;">
        <tr>
            <td>
            </td>   
        </tr>
    </table>     
     
    <!-- footer -->
    <table style="width:1170px">
        <tr>
            <td style="width:185px;"></td>
            <td>
                <table style="width:800px;text-align:center;">
                    <tr>
                        <td colspan="3">
                            <img src="https://portal.newleaf.com.my/img/mail/email-logo.png">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <b> NEWLEAF PLANATION BERHAD </b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <span style="font-size:12px">(1251569-U)</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            Suite E-7-1, Block E, Plaza Mont Kiara, No 2 Jalan Kiara, 50480 Kuala Lumpur
                        </td>
                    </tr>
                    <tr>
                        <td><b>T</b> +603 6201 6336</td>
                        <td><b>F</b> +603 6201 7337</td>
                        <td><b>W</b> www.newleaf.com.my</td>
                    </tr>
                </table>
            </td>
            <td style="width:185px;"></td>
        </tr>
    </table>
    <!-- end content --> 
    
    <div style="width:100%; float:left; height:25px; margin-bottom:20px;border-bottom:15px solid black"></div>
         
</body>
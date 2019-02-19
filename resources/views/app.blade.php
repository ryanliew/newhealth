<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

            
        <link href="/img/fav180x180.png" rel="apple-touch-icon" type="image/png" sizes="180x180">
        <link href="/img/fav32x32.png" rel="apple-touch-icon" type="image/png" sizes="32x32">
        <link href="/img/fav16x16.png" rel="apple-touch-icon" type="image/png" sizes="16x16">
        <link href="/img/fav.png" rel="icon" type="image/png">
        <title>Newhealth 新子午线</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <link rel="stylesheet" href="/css/separate/elements/steps.min.css">
        <link href="./css/start-ui.css?v=1.3" rel="stylesheet" />
        <link href="./css/app.css?v=1.5" rel="stylesheet" />
    </head>
    <body class="with-side-menu-compact">
        <div id="app"></div>
       
        <script src="./js/form.js?v=1.2"></script> 
        <script src="./js/app.js?v=1.8"></script>
        <script src="./js/lib/tether/tether.min.js"></script> 
        <script src="./js/plugins.js"></script>
        <script src="./js/start-ui.js"></script>

        <script>
            @if(session('status'))
                flash('{{ session('status') }}');
            @endif
        </script>
    </body>
</html>

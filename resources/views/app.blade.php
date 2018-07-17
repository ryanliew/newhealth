<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

            
        <link href="/img/favicon.144x144.png" rel="apple-touch-icon" type="image/png" sizes="144x144">
        <link href="/img/favicon.114x114.png" rel="apple-touch-icon" type="image/png" sizes="114x114">
        <link href="/img/favicon.72x72.png" rel="apple-touch-icon" type="image/png" sizes="72x72">
        <link href="/img/favicon.57x57.png" rel="apple-touch-icon" type="image/png">
        <link href="/img/favicon.png" rel="icon" type="image/png">
        <title>Newleaf</title>

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
        <script src="./js/app.js?v=1.48"></script>
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

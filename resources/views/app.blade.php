<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Newleaf</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="./css/start-ui.css" rel="stylesheet" />
        <link href="./css/app.css" rel="stylesheet" />
    </head>
    <body class="with-side-menu-compact">
        <div id="app"></div>
       
        <script src="./js/form.js"></script> 
        <script src="./js/app.js"></script>
        <script src="./js/lib/tether/tether.min.js"></script> 
        <script src="./js/plugins.js"></script>
        <script src="./js/start-ui.js"></script>
    </body>
</html>

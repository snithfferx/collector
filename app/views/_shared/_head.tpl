<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{$head.data.description}}">
<link rel="apple-touch-icon" sizes="57x57" href="assets/img/icons/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="assets/img/icons/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="assets/img/icons/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="assets/img/icons/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="assets/img/icons/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="assets/img/icons/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="assets/img/icons/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="assets/img/icons/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="assets/img/icons/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="assets/img/icons/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="assets/img/icons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="assets/img/icons/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="assets/img/icons/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#333333">
<meta name="msapplication-TileImage" content="assets/img/icons/ms-icon-144x144.png">
<meta name="theme-color" content="#333333">
<title>{{block name="title"}}{{$head.data.title}}{{/block}} | {{$head.data.app_name}}</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
{{if isset($head.css)}}{{$head.css}}{{/if}}
{{block name="css"}}{{/block}}
<link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
<link rel="stylesheet" href="assets/css/adminlte.min.css">
<link rel="stylesheet" href="assets/css/style.css">
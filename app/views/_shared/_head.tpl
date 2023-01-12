<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{$head.data.description}}">
<link rel="manifest" href="src/manifest.json">
<meta name="msapplication-TileColor" content="#333333">
<meta name="theme-color" content="#333333">
<title>{{block name="title"}}{{$head.data.title}}{{/block}} | {{$head.data.app_name}}</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
{{if isset($head.css)}}{{$head.css}}{{/if}}
{{block name="css"}}{{/block}}
<link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
<link rel="stylesheet" href="assets/css/style.css">
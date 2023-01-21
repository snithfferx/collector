<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{$head.data.description}}">
<link rel="manifest" href="/src/manifest.json">
<meta name="msapplication-TileColor" content="#333333">
<meta name="theme-color" content="#333333">
<title>{{block name="title"}}{{$head.data.title}}{{/block}} | {{$head.data.app_name}}</title>
<link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/open-iconic-master\open-iconic-bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/jquery-ui\jquery-ui.min.css">
{{if isset($head.css)}}{{$head.css}}{{/if}}
{{block name="css"}}{{/block}}
<link rel="stylesheet" type="text/css" href="/assets/css/toastr/toastr.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/style.css">
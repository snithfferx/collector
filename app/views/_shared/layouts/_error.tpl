<html lang="{{$head.data.lang}}">

<head>
    {{block name=head}}
        {{if isset($head.template) && !empty($head.template)}}
            {{include file=_VIEW_|cat:$head.template}}
        {{else}}
            {{include file=_VIEW_|cat:"_shared/_head.tpl"}}
        {{/if}}
    {{/block}}
</head>
{{* 
    <body
        class="{{$body.layout}} accent-orange
{{if $body.darkmode === true or $navbar.darkmode === "true"}}dark-mode{{/if}}"
style="height:auto;">
<div class="preloader flex-column justify-content-center align-items-center" style="height: 0px;">
    <img class="animation__shake"
        src="assets/img/{{if isset($data.view.sidebar.data.app_logo)}}{{$data.view.sidebar.data.app_logo}}{{else}}{{$data.layout.sidebar.data.app_logo}}{{/if}}"
        alt="{{$head.data.app_name}}" style="display: none; background-color:dodgerblue" width="60" height="60">
</div>
*}}

<body style="height:auto;">
    <div class="wrapper">
        {{if isset($sidebar)}}
            {{block name=navbar}}
                <!--NAVBAR-->
                {{if isset($navbar.template)}}
                    {{include file=_VIEW_|cat:$navbar.template}}
                {{else}}
                    {{include file=_VIEW_|cat:"_shared/_navbar.tpl"}}
                {{/if}}
                <!-- NAVBAR END -->
            {{/block}}
        {{/if}}
        {{if isset($sidebar)}}
            {{block name="sidebar"}}
                <!-- SIDEBAR -->
                {{if isset($sidebar.template)}}
                    {{include file=_VIEW_|cat:$sidebar.template}}
                {{else}}
                    {{include file=_VIEW_|cat:"_shared/_sidebar.tpl"}}
                {{/if}}
                <!-- SIDEBAR END -->
            {{/block}}
        {{/if}}
        <!-- MAIN CONTENT -->
        <div class="content-wrapper" id="mainContentWraper">
            {{if isset($data.content.breadcrumbs) && !empty($data.content.breadcrumbs)}}
                {{assign var="breadcrumb" value=$data.content.breadcrumbs}}
            {{/if}}
            {{block name="breadcrumbs"}}
                <!-- Content Header (Page header) -->
                {{if isset($data.content.breadcrumbs) && !empty($data.content.breadcrumbs)}}
                    {{include file=_VIEW_|cat:"_shared/_breadcrumbs.tpl"}}
                {{/if}}
                <!-- /.content-header -->
            {{/block}}
            <!-- Main content -->
            <div class="content" id="mainContent">
                {{if isset($data.content.datos)}}
                    {{assign var="content" value=$data.content.datos}}
                {{/if}}
                {{block name="mainContent"}}
                    {{if isset($data.content.template)}}
                        {{include file=_VIEW_|cat:$data.content.template}}
                    {{else}}
                        {{var_dump($data)}}
                    {{/if}}
                {{/block}}
            </div>
            <!-- /.Main content -->
        </div>
        <!-- MAIN CONTENT END -->
        {{block name="footer"}}
            <!-- FOOTER -->
            {{if isset($footer.template)}}
                {{include file=_VIEW_|cat:$footer.template}}
            {{else}}
                {{include file=_VIEW_|cat:"_shared/_footer.tpl"}}
            {{/if}}
            <!-- FOOTER END -->
        {{/block}}
    </div>
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    {{block name="jslibs"}}{{/block}}
    <!-- Toastr -->
    <script src="assets/plugins/toastr/toastr.min.js"></script>
    <script src="assets/js/adminlte.min.js"></script>
    <script src="assets/js/functions.js"></script>
    {{block name="scripts"}}{{/block}}
</body>

</html>
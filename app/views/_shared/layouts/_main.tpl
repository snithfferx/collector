<html lang="{{$head.data.lang}}">

<head>
    {{block name=head}}
        {{if isset($head.template) && !empty($head.template)}}
            {{include file=_VIEW_|cat:$head.template}}
        {{else}}
            {{include file=_VIEW_|cat:"_shared/templates/_head.tpl"}}
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

<body>
    {{if isset($navbar)}}
        {{block name=navbar}}
            <!--NAVBAR-->
            {{if isset($navbar.template)}}
                {{include file=_VIEW_|cat:$navbar.template}}
            {{else}}
                {{include file=_VIEW_|cat:"_shared/templates/_navbar.tpl"}}
            {{/if}}
            <!-- NAVBAR END -->
        {{/block}}
    {{/if}}
    <main>
        <div class="wrapper">
            {{if isset($sidebar)}}
                {{block name="sidebar"}}
                    <!-- SIDEBAR -->
                    {{if isset($sidebar.template)}}
                        {{include file=_VIEW_|cat:$sidebar.template}}
                    {{else}}
                        {{include file=_VIEW_|cat:"_shared/templates/_sidebar.tpl"}}
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
                        {{include file=_VIEW_|cat:"_shared/templates/_breadcrumbs.tpl"}}
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
                    {{include file=_VIEW_|cat:"_shared/templates/_footer.tpl"}}
                {{/if}}
                <!-- FOOTER END -->
            {{/block}}
        </div>
    </main>
    <script type="text/javascript" src="/assets/js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/js/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="/assets/js/toastr/toastr.min.js"></script>
    {{block name="jslibs"}}{{/block}}
    <script type="text/javascript" src="/assets/js/global/functions.js"></script>
    {{block name="scripts"}}{{/block}}
</body>

</html>
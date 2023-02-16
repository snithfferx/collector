<!DOCTYPE html>
{{if isset($data.view.head)}}
    {{assign var="head" value=$data.view.head}}
{{else}}
    {{assign var="head" value=$data.layout.head}}
{{/if}}
{{if isset($data.view.body)}}
    {{assign var="body" value=$data.view.body}}
{{else}}
    {{assign var="body" value=$data.layout.body}}
{{/if}}
{{if isset($data.footer)}}
    {{assign var="footer" value=$data.footer}}
{{else}}
    {{assign var="footer" value=$data.layout.footer}}
{{/if}}
{{if isset($data.scripts)}}
    {{$data.scripts}}
{{else}}
    {{if isset($data.layout.scripts)}}
        {{$data.layout.scripts}}
    {{/if}}
{{/if}}

{{if isset($data.view.navbar)}}
    {{assign var="navbar" value=$data.view.navbar}}
{{elseif isset($data.layout.navbar)}}
    {{assign var="navbar" value=$data.layout.navbar}}
{{/if}}

{{if isset($data.view.sidebar)}}
    {{assign var="sidebar" value=$data.view.sidebar}}
{{elseif isset($data.layout.sidebar)}}
    {{assign var="sidebar" value=$data.layout.sidebar}}
{{/if}}
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

<body style="height:auto;">
    <div class="wrapper">
        {{if isset($sidebar)}}
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
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    {{block name="jslibs"}}{{/block}}
    <!-- Toastr -->
    <script src="/assets/plugins/toastr/toastr.min.js"></script>
    <script src="/assets/js/adminlte.min.js"></script>
    <script src="/assets/js/functions.js"></script>
    {{block name="scripts"}}{{/block}}
</body>

</html>
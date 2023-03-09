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
{{if isset($data.content.datos.viewType)}}
    {{assign var="layout_type" value=$data.content.datos.viewType }}
{{else}}
    {{assign var="layout_type" value="plain" }}
{{/if}}
{{include file=_VIEW_|cat:"errors/layouts/_"|cat:$layout_type|cat:".tpl"}}
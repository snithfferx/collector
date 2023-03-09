{{extends file=_VIEW_|cat:"errors/_layout.tpl"}}
{{block name="breadcrumb"}}{{/block}}
{{block name="mainContent"}}
    <pre>
    {{var_dump($data)}}
    </pre>
{{/block}}
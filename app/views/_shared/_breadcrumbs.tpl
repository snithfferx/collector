<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{$breadcrumb.main}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    {{foreach $breadcrumb.routes as $item}}
                        {{if $item@last}}
                            <li class="breadcrumb-item active">{{$item.text}}</li>
                        {{else}}
                            <li class="breadcrumb-item"><a href="?ctr={{$item.controller}}&mtd={{$item.method}}&prm={{$item.param}}">{{$item.text}}</a></li>
                        {{/if}}
                    {{/foreach}}
                </ol>
            </div>
        </div>
    </div>
</div>
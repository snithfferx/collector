{{extends file=_VIEW_|cat:"_shared/_layout.tpl"}}
{{block name="breadcrumb"}}{{/block}}
{{block name="mainContent"}}
    {{assign var="collection" value=$data.content.datos}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">
                            Colección
                            <span id="spinger">
                                <i class="fas fa-sync-alt fa-spin text-info"></i>
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        {{foreach $collection as $key => $item}}
                            <div class="col-6">
                                <table class="table table-stripped">
                                    <tr>
                                        <td>ID</td>
                                        <td> : </td>
                                        <td>
                                            {{assign var="gid" value=explode("/",$common.gqid)}}
                                            <a href="https://piensads.myshopify.com/admin/{{$gid[3]}}/{{$gid[4]}}">
                                                {{$item.id}}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nombre</td>
                                        <td> : </td>
                                        <td>{{$item.title}}</td>
                                    </tr>
                                    <tr>
                                        <td>Handle</td>
                                        <td> : </td>
                                        <td>{{$item.handle}}</td>
                                    </tr>
                                    <tr>
                                        <td>Productos</td>
                                        <td> : </td>
                                        <td>{{$item.products}}</td>
                                    </tr>
                                    <tr>
                                        <td>Orden</td>
                                        <td> : </td>
                                        <td>{{$item.sort}}</td>
                                    </tr>
                                </table>
                                <div class="col-6">
                                    <ul class="nav nav-tabs" id="Tab_{{$key}}_{{$item.id}}" role="tablist">
                                        {{foreach $item.common as $index => $common}}
                                            <li class="nav-item">
                                                <a class="nav-link {{if $index == 0}}active{{/if}}"
                                                    id="{{$common.name}}-tab" data-toggle="tab" href="#{{$common.name}}"
                                                    role="tab" aria-controls="{{$common.name}}"
                                                    aria-selected="true">{{$common.name}}</a>
                                            </li>
                                        {{/foreach}}
                                    </ul>
                                        <div class="tab-content mt-3 pt-3" id="TabContent_{{$key}}_{{$item.id}}">
                                            {{foreach $item.common as $indice => $common}}
                                                <div class="tab-pane fade show {{if $indice == 0}}active{{/if}}"
                                                    id="{{$common.name}}" role="tabpanel"
                                                    aria-labelledby="{{$common.name}}-tab">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <table class="table table-stripped">
                                                                <tr>
                                                                    <td>ID</td>
                                                                    <td> : </td>
                                                                    <td>
                                                                        {{assign var="gid" value=explode("/",$common.gqid)}}
                                                                        <a
                                                                            href="https://piensads.myshopify.com/admin/{{$gid[3]}}/{{$gid[4]}}">
                                                                            {{$item.id}}
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Nombre</td>
                                                                    <td> : </td>
                                                                    <td>{{$item.title}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Handle</td>
                                                                    <td> : </td>
                                                                    <td>{{$item.handle}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Productos</td>
                                                                    <td> : </td>
                                                                    <td>{{$item.products}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Orden</td>
                                                                    <td> : </td>
                                                                    <td>{{$item.sort}}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="col-6">
                                                            <table class="table table-stripped">
                                                                <tr>
                                                                    <td>Condiciones</td>
                                                                    <td> : </td>
                                                                    <td>
                                                                        {{assign var="conditions" value=json_decode($item.rules,true)}}
                                                                        <table>
                                                                            {{foreach $conditions.rules as $key=>$cond}}
                                                                                <tr>
                                                                                    <td>{{$cond.relation}}</td>
                                                                                    <td>{{$cond.column}}</td>
                                                                                    <td>{{$cond.condition}}</td>
                                                                                </tr>
                                                                            {{/foreach}}
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>SEO</td>
                                                                    <td> : </td>
                                                                    <td>{{$item.seo}}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            {{/foreach}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {{/foreach}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <pre>
{{var_dump($data.content.datos)}}
    </pre>
{{/block}}
{{block name="scripts"}}
    <script>
        $('[data-toggle="tooltip"]').tooltip()
        $("#spinger").hide();
    </script>
{{/block}}
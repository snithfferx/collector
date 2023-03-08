{{* extends file=_VIEW_|cat:"_shared/_layout.tpl" *}}
{{* block name="breadcrumb"}}{{/block *}}
{{* block name="mainContent" *}}
    {{assign var="collection" value=$data.content.datos.collections}}
    {{assign var="common" value=$data.content.datos.commonNames}}
    {{assign var="change" value=$data.content.datos.changes}}
    {{assign var="error" value=$data.content.datos.errors}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">
                            Colleción a borrarse
                            <span id="spinger">
                                <i class="fas fa-sync-alt fa-spin text-info"></i>
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-10 offset-1">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    {{foreach $collection as $key => $item}}
                                        <li class="nav-item">
                                            <a class="nav-link {{if $item@first}}active{{/if}}" id="{{$item.title}}-tab"
                                                data-toggle="tab" href="#{{$item.title}}" role="tab"
                                                aria-controls="{{$item.title}}" aria-selected="true">
                                                {{$item.title}}
                                            </a>
                                        </li>
                                    {{/foreach}}
                                </ul>
                                <div class="tab-content mt-3 pt-3" id="myTabContent">
                                    {{foreach $collection as $key => $item}}
                                        <div class="tab-pane fade show {{if $item@first}}active{{/if}}" id="{{$item.title}}"
                                            role="tabpanel" aria-labelledby="{{$item.title}}-tab">
                                            <div class="row">
                                                <div class="col-6">
                                                    <table class="table table-stripped">
                                                        <tr>
                                                            <td>ID</td>
                                                            <td> : </td>
                                                            <td>
                                                                {{assign var="gid" value=explode("/",$item.gqid)}}
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
                                                        <tr>
                                                            <td>
                                                                <div class="btn-group" role="group" aria-label="Action buttons">
                                                                    <a class="btn btn-warning" href="/collections/confirmDeletation/">Confirmar</a>
                                                                    <a class="btn btn-primary" href="/collections/index">Regresar</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    {{/foreach}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{foreach $common as $key => $cnItem}}
                                <div class="col-5 offset-1">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>Nombre</td>
                                            <td> : </td>
                                            <td>{{$cnItem.name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Fecha de creacion</td>
                                            <td> : </td>
                                            <td>{{date("d/m/Y",strtotime($cnItem.date))}}</td>
                                        </tr>
                                        <tr>
                                            <td>ID Tienda</td>
                                            <td> : </td>
                                            <td>{{$cnItem.store_id}}</td>
                                        </tr>
                                        <tr>
                                            <td>Handle</td>
                                            <td> : </td>
                                            <td>{{$cnItem.handle}}</td>
                                        </tr>
                                        <tr>
                                            <td>Palabras clave</td>
                                            <td> : </td>
                                            <td>{{$cnItem.keywords}}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-5">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>Posicion</td>
                                            <td> : </td>
                                            <td>{{$cnItem.possition}}</td>
                                        </tr>
                                        <tr>
                                            <td>Estado</td>
                                            <td> : </td>
                                            <td>{{$cnItem.active}}</td>
                                        </tr>
                                        <tr>
                                            <td>Categoria</td>
                                            <td> : </td>
                                            <td><a href="/categories/read?id={{$cnItem.tp_id}}">
                                                    {{$cnItem.sub_category}}
                                                </a></td>
                                        </tr>
                                        <tr>
                                            <td>Sub-ategoria</td>
                                            <td> : </td>
                                            <td><a href="/categories/subcategory?id={{$cnItem.tc_id}}">
                                                    {{$cnItem.category}}
                                                </a></td>
                                        </tr>
                                    </table>
                                </div>
                            {{/foreach}}
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="btn-group" role="group" aria-label="Action buttons">
                            <a class="btn btn-danger" href="/collections/confirmDeletation/">Confirmar todo</a>
                            <a class="btn btn-primary" href="/collections/index">Regresar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <pre>
        {{* var_dump($data.content.datos) *}}
    </pre>
    <script>
        $("#spinger").hide();
    </script>
{{* /block *}}
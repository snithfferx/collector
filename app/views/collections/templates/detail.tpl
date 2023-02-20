{{extends file=_VIEW_|cat:"_shared/_layout.tpl"}}
{{block name="breadcrumb"}}{{/block}}
{{block name="mainContent"}}
    {{assign var="commonName" value=$data.content.datos.common}}
    {{assign var="collection" value=$data.content.datos.collection}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">
                            Nombre Común
                            <span id="spinger">
                                <i class="fas fa-sync-alt fa-spin text-info"></i>
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{foreach $commonName as $key => $item}}
                                <div class="col-6">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>Nombre</td>
                                            <td> : </td>
                                            <td>{{$item.name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Fecha de creacion</td>
                                            <td> : </td>
                                            <td>{{$item.date}}</td>
                                        </tr>
                                        <tr>
                                            <td>ID Tienda</td>
                                            <td> : </td>
                                            <td>{{$item.store_id}}</td>
                                        </tr>
                                        <tr>
                                            <td>Handle</td>
                                            <td> : </td>
                                            <td>{{$item.handle}}</td>
                                        </tr>
                                        <tr>
                                            <td>Palabras clave</td>
                                            <td> : </td>
                                            <td>{{$item.keywords}}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>Posicion</td>
                                            <td> : </td>
                                            <td>{{$item.possition}}</td>
                                        </tr>
                                        <tr>
                                            <td>Estado</td>
                                            <td> : </td>
                                            <td>{{$item.active}}</td>
                                        </tr>
                                        <tr>
                                            <td>Categoria</td>
                                            <td> : </td>
                                            <td><a href="/categories/read?id={{$item.tp_id}}">
                                                    {{$item.sub_category}}
                                                </a></td>
                                        </tr>
                                        <tr>
                                            <td>Sub-ategoria</td>
                                            <td> : </td>
                                            <td><a href="/categories/subcategory?id={{$item.tc_id}}">
                                                    {{$item.category}}
                                                </a></td>
                                        </tr>
                                    </table>
                                </div>
                            {{/foreach}}
                        </div>
                        <div class="row">
                            <div class="col-8 offset-2">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    {{foreach $collection as $key => $item}}
                                        <li class="nav-item">
                                            <a class="nav-link {{if $key == 0}}active{{/if}}" id="{{$item.title}}-tab"
                                                data-toggle="tab" href="#{{$item.title}}" role="tab"
                                                aria-controls="{{$item.title}}" aria-selected="true">{{$item.title}}</a>
                                        </li>
                                    {{/foreach}}
                                </ul>
                                <div class="tab-content mt-3 pt-3" id="myTabContent">
                                    {{foreach $collection as $key => $item}}
                                        <div class="tab-pane fade show {{if $key == 0}}active{{/if}}" id="{{$item.title}}"
                                            role="tabpanel" aria-labelledby="{{$item.title}}-tab">
                                            <div class="row">
                                                <div class="col-6">
                                                    <table class="table table-stripped">
                                                        <tr>
                                                            <td>ID</td>
                                                            <td> : </td>
                                                            <td>
                                                                {{assign var="gid" value=explode("/",$item.gqid)}}
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
                </div>
            </div>
        </div>
    </div>
{{/block}}
{{block name="scripts"}}
    <script>
        $('[data-toggle="tooltip"]').tooltip()
        $("#spinger").hide();
    </script>
{{/block}}
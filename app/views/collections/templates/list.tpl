{{extends file=_VIEW_|cat:"_shared/_layout.tpl"}}
{{* /**
     * @author Snithfferx <snithfferx@outlook.com>
     * @version 1.4.0
     * 18/01/23
    */
*}}
{{block name="breadcrumb"}}{{/block}}
{{block name="mainContent"}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">Lista de Colecciones</h5>
                        {{*if $content.max_page > 0*}}
                        <nav aria-label="Page navigation" id="collections_pagination">
                            <ul class="pagination justify-content-end">
                                {{* 
                                <li class="page-item">
                                    <b class="text-primary mr-2 ml-3" style="display:block;">
                                        {{if isset($content.current_page)}}
                            {{$content.current_page}} de
                            {{else}}
                            1 de
                            {{/if}}
                            {{$content.max_page}}
                            </b>
                            </li> *}}
                                {{*if !empty($content.pagination.prev_page)*}}
                                <li class="page-item">
                                    <a class="page-link" title="Lleva a la página anterior" type="text" target="_self"
                                        href="#" id="collections_pagination_prev">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                {{*/if*}}
                                {{* foreach $content.pagination as $in => $page}}
                            {{if !empty($content.pagination[($in + 1)].prev_page)}}
                            <li class="page-item">
                                <a class="page-link active"
                                    href="/collections/previous{{$content.pagination[($in + 1)].prev_page}}">{{page.page_id}}</a>
                            </li>
                            {{/if}}
                            {{/foreach *}}
                                {{*if !empty($content.pagination.next_page)*}}
                                <li class="page-item">
                                    <a class="page-link" title="Lleva a la página siguiente" type="text" target="_self"
                                        href="#" id="collections_pagination_next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                {{*/if*}}
                            </ul>
                        </nav>
                        {{*/if*}}
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive table-striped" id="collectionsList">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-uppercase font-weight-bolder text-center">ID Tienda</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Fecha</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Nombre Común</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Titulo</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Handle</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Categoría</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Sub-Categoría</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Handle Local</th>
                                    <th class="text-uppercase font-weight-bolder text-center">ID Local</th>
                                    <th class="text-uppercase font-weight-bolder text-center">keywords</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Orden</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Productos</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Activo</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Posición</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{* foreach $content.collections as $key=>$item}}
                            <tr>
                                <td class="text-center">
                                    <a href="collections/read/{{$item.store_id}}">
                                        {{$item.store_id}}
                                    </a>
                                </td>
                                <td class="text-center">{{$item.date}}</td>
                                <td class="text-center">
                                    <a href="collections/read?id={{$item.id}}" target="_self" title="{{$item.name}}"
                                        type="text">
                                        {{$item.name}}
                                    </a>
                                </td>
                                <td class="text-center">{{$item.store_title}}</td>
                                <td class="text-center">{{$item.store_handle}}</td>
                                <td class="text-center">{{$item.category}}</td>
                                <td class="text-center">{{$item.sub_category}}</td>
                                <td class="text-center">{{$item.handle}}</td>
                                <td class="text-center">{{$item.id_tienda}}</td>
                                <td class="text-center">{{$item.keywords}}</td>
                                <td class="text-center">{{$item.sort_order}}</td>
                                <td class="text-center">{{$item.product_count}}</td>
                                <td class="text-center">
                                    {{if $item.active == 0}}Inactivo{{else}}Activo{{/if}}
                                </td>
                                <td class="text-center">{{$item.possition}}</td>
                                <td class="text-center">
                                    <div class='btn-group'>
                                        <button type='button' class='btn btn-outline-info dropdown-toggle dropdown-icon'
                                            data-toggle='dropdown'>
                                            Eleija...
                                        </button>
                                        <span class='sr-only'>Acciones</span>
                                        <div class='dropdown-menu' role='menu'>
                                            {{if !empty($item.id)}}
                                            <a href="/collections/read?id={{$item.id}}"
                                                title="Ver detalles de colección" tabindex="{{$key + 1}}" target="_self"
                                                type="text" class="btn btn-success btn-sm btn-block">
                                                <i class="fas fa-eye mr-3"></i>Detalles
                                            </a>
                                            <a href="/collections/compare?id={{$item.store_id}}"
                                                title="Compara los datos de una colección" tabindex="{{$key + 2}}"
                                                target="_self" type="text" class="btn btn-info btn-sm btn-block">
                                                <i class="fas fa-copy mr-3"></i>Comparar
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a href="/collections/edit?id={{$item.id}}"
                                                title="Editar datos de la colección local" tabindex="{{$key + 3}}"
                                                target="_self" type="text" class="btn btn-warning btn-sm btn-block">
                                                <i class="fas fa-edit mr-3"></i>Editar Local
                                            </a>
                                            <a href="/collections/sync?id={{$item.id}}"
                                                title="Sincroniza datos de tienda a local" tabindex="{{$key + 4}}"
                                                target="_self" type="text" class="btn btn-primary btn-sm btn-block">
                                                <i class="fas fa-trash mr-3"></i>Borrar Local
                                            </a>
                                            <a href="collections/delete?id={{$item.id}}"
                                                title="Borra una colección localmente" tabindex="{{$key + 5}}"
                                                target="_self" type="text" class="btn btn-danger btn-sm btn-block">
                                                <i class="fas fa-trash mr-3"></i>Borrar Local
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            {{/if}}
                                            <a href="collections/edit?id={{$item.store_id}}"
                                                title="Editar datos de la colección tienda" tabindex="{{$key + 6}}"
                                                target="_self" type="text" class="btn btn-warning btn-sm btn-block">
                                                <i class="fas fa-edit mr-3"></i>Editar Local
                                            </a>
                                            <a href="collections/delete?id={{$item.store_id}}"
                                                title="Borra una colección en la nube" tabindex="{{$key + 7}}"
                                                target="_self" type="text" class="btn btn-danger btn-sm btn-block">
                                                <i class="fas fa-trash mr-3"></i>Borrar Local
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            {{/foreach *}}
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{*if $content.max_page > 0*}}
                        <nav aria-label="Page navigation" id="collections_pagination">
                            <ul class="pagination justify-content-center">
                                {{* 
                                <li class="page-item">
                                    <b class="text-primary mr-2 ml-3" style="display:block;">
                                        {{if isset($content.current_page)}}
                            {{$content.current_page}} de
                            {{else}}
                            1 de
                            {{/if}}
                            {{$content.max_page}}
                            </b>
                            </li> *}}
                                {{*if !empty($content.pagination.prev_page)*}}
                                <li class="page-item">
                                    <a class="page-link" title="Lleva a la página anterior" type="text" target="_self"
                                        href="#" id="collections_pagination_prev">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                    <input type="hidden" value="" id="previousPage">
                                </li>
                                {{*/if*}}
                                {{* foreach $content.pagination as $in => $page}}
                            {{if !empty($content.pagination[($in + 1)].prev_page)}}
                            <li class="page-item">
                                <a class="page-link active"
                                    href="/collections/previous{{$content.pagination[($in + 1)].prev_page}}">{{page.page_id}}</a>
                            </li>
                            {{/if}}
                            {{/foreach *}}
                                {{*if !empty($content.pagination.next_page)*}}
                                <li class="page-item">
                                    <a class="page-link" title="Lleva a la página siguiente" type="text" target="_self"
                                        href="#" id="collections_pagination_next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                    <input type="hidden" value="" id="nextPage">
                                </li>
                                {{*/if*}}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{* <pre>{{var_dump($data.content)}}</pre> *}}
{{/block}}
{{block name='css'}}
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables-bs4/dataTables.bootstrap4.min.css">
    {{* <link rel="stylesheet" type="text/css" href="/assets/css/datatables-responsive/responsive.bootstrap4.min.css"> *}}
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables-buttons/buttons.bootstrap4.min.css">
{{/block}}
{{block name="jslibs"}}
    <script type="text/javascript" src="/assets/js/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-bs4/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-responsive/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-responsive/responsive.bootstrap4.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.bootstrap4.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.flash.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.html5.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.print.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="/assets/js/jszip/jszip.min.js"></script>
    <script type="text/javascript" src="/assets/js/pdfmake/pdfmake.min.js"></script>
    <script type="text/javascript" src="/assets/js/pdfmake/vfs_fonts.js"></script>
    <script type="text/javascript" src="/assets/js/moment/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="/assets/js/global/tabler.js"></script>
{{/block}}
{{block name="scripts"}}
    {{literal}}
        <script>
            $("#collections_pagination").hide();
            const columnas = [
                { data: 'store_id' },
                { '_': "date.display", 'sort': "date.timestamp" },
                { data: 'name' },
                { data: 'store_title' },
                { data: 'store_handle' },
                { data: 'category' },
                { data: 'sub_category' },
                { data: 'handle' },
                { data: 'id_tienda' },
                { data: 'keywords' },
                { data: 'sort_order' },
                { data: 'product_count' },
                { data: 'active' },
                { data: 'possition' },
            ];
            let collections, result;
            $(document).ready(function() {
                var collectionsTable;
                $.ajax({
url: '/collections/lista',
                    type: 'GET',
                    success: function(r) {
                        console.log(r);
                        result = JSON.parse(r);
                        collections = result.collections;
                        if (result.pagination.next_page) {
                            $("#collections_pagination").show();
                            var urlNext = '/collections/next?page=' +
                                result.pagination.next_page +
                                '&page_id=' +
                                result.pagination.page_id +
                                '&view_origin=' +
                                result.view_origin;
                            var urlPrev = '/collections/previous?page=' +
                                result.pagination.prev_page +
                                '&page_id=' +
                                result.pagination.page_id +
                                '&view_origin=' +
                                result.view_origin;
                            $("#nextPage").val(urlNext);
                            $("#previousPage").val(urlPrev);
                        }
                        collectionsTable = tableRefill('collectionsList', collections, columnas, 10, true, 2);
                    }
                });
                console.log(collections);
                $("#collections_pagination_next").click(function(e) {
                    preventDefault(e);
                    var url = $("#nextPage").val();
                    $.post(url, {}, function(r) {
                        console.log(r);
                        result = JSON.parse(r);
                        collections = result.collections;
                        if (result.pagination.next_page) {
                            var urlNext = '/collections/next?page=' +
                                result.pagination.next_page +
                                '&page_id=' +
                                result.pagination.page_id +
                                '&view_origin=' +
                                result.view_origin;
                                $("#nextPage").val(urlNext);
                        }
                        if (result.pagination.prev_page) {
                            var urlPrev = '/collections/previous?page=' +
                                result.pagination.prev_page +
                                '&page_id=' +
                                result.pagination.page_id +
                                '&view_origin=' +
                                result.view_origin;
                                $("#previousPage").val(urlPrev);
                        }
                        collectionsTable.ajax.reload();
                    });
                });
                $("#collections_pagination_next").click(function(e) {
                    preventDefault(e);
                    var url = $("#nextPage").val();
                    $.post(url, {}, function(r) {
                        console.log(r);
                        result = JSON.parse(r);
                        collections = result.collections;
                        if (result.pagination.next_page) {
                            var urlNext = '/collections/next?page=' +
                                result.pagination.next_page +
                                '&page_id=' +
                                result.pagination.page_id +
                                '&view_origin=' +
                                result.view_origin;
                                $("#nextPage").val(urlNext);
                            }
                            if (result.pagination.prev_page) {
                                var urlPrev = '/collections/previous?page=' +
                                    result.pagination.prev_page +
                                    '&page_id=' +
                                    result.pagination.page_id +
                                    '&view_origin=' +
                                    result.view_origin;
                                    $("#previousPage").val(urlPrev);
                                }
                        collectionsTable.ajax.reload();
                    });
                });
            });
        </script>
    {{/literal}}
{{/block}}
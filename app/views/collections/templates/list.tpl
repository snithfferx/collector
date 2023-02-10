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
                                <li class="page-item collections_pagination_prev">
                                    <a class="page-link" title="Lleva a la página anterior" type="text" target="_self"
                                        href="#">
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
                                <li class="page-item collections_pagination_next">
                                    <a class="page-link" title="Lleva a la página siguiente" type="text" target="_self"
                                        href="#">
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
                                    <th class="text-uppercase font-weight-bolder text-center">Titulo</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Handle</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Tipo</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Nombre Común</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Categoría</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Handle Local</th>
                                    <th class="text-uppercase font-weight-bolder text-center">ID Local</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Productos</th>
                                    <th class="text-uppercase font-weight-bolder text-center">Activo</th>
                                    <th class="text-uppercase font-weight-bolder text-center">metadatos</th>
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
                                <li class="page-item collections_pagination_prev">
                                    <a class="page-link" title="Lleva a la página anterior" type="text" target="_self"
                                        href="#">
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
                                <li class="page-item collections_pagination_next">
                                    <a class="page-link" title="Lleva a la página siguiente" type="text" target="_self"
                                        href="#">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                {{*/if*}}
                            </ul>
                            <input type="hidden" value="" id="previousPage">
                            <input type="hidden" value="" id="nextPage">
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{* <pre>{{var_dump($data.content)}}</pre> *}}
    <!-- Modal -->
    <div class="modal fade" id="type-Changer" tabindex="-1" role="dialog" aria-labelledby="type-Changer-Label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="type-Changer-Label">Asociar Nombre Común</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Minimal</label>
                                <select class="form-control select2" style="width: 100%;" id="categories">
                                    <option selected="selected">Elija una categoría</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Minimal</label>
                                <select class="form-control select2" style="width: 100%;" id="subcategories">
                                    <option selected="selected">Elija una subcategoría</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteCollection_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmación de eliminación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Se eliminará <b><span id="idCollection"></span></b></p>
                    <p>Favor confirme que desea eliminar la colección</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteCommon_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmación de eliminación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Se eliminará <b><span id="idCommon"></span></b></p>
                    <p>Favor confirme que desea eliminar el nombre común</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editCommon_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edición de nombre común</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">File input</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
{{/block}}
{{block name='css'}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables-buttons/buttons.bootstrap4.min.css">
    {{* <link rel="stylesheet" type="text/css" href="/assets/css/datatables-bs4/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables-responsive/responsive.bootstrap4.min.css">
     *}}
{{/block}}
{{block name="jslibs"}}
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js">
    </script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap4.min.js">
    </script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.bootstrap4.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.flash.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.html5.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.print.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.colVis.min.js"></script>
    {{* 
        <script type="text/javascript" src="/assets/js/datatables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="/assets/js/datatables-bs4/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript" src="/assets/js/datatables-responsive/dataTables.responsive.min.js"></script>
        <script type="text/javascript" src="/assets/js/datatables-responsive/responsive.bootstrap4.min.js"></script>
        <script type="text/javascript" src="/assets/js/jszip/jszip.min.js"></script>
        <script type="text/javascript" src="/assets/js/pdfmake/pdfmake.min.js"></script>
        <script type="text/javascript" src="/assets/js/pdfmake/vfs_fonts.js"></script>
        <script type="text/javascript" src="/assets/js/moment/moment-with-locales.min.js"></script>
    *}}
    <script type="text/javascript" src="/assets/js/global/tabler.js"></script>
{{/block}}
{{block name="scripts"}}
    {{literal}}
        <script>
            $('[data-toggle="tooltip"]').tooltip()
            $("#collections_pagination").hide();
            $(".collections_pagination_prev").hide();
            $(".collections_pagination_next").hide();
            const columnas = [
                { data: 'store_id' },
                { data: 'date' },
                { data: 'store_title' },
                { data: 'store_handle' },
                { data: 'store_type' },
                { data: 'name' },
                { data: 'category' },
                { data: 'handle' },
                { data: 'id_tienda' },
                { data: 'product_count' },
                { data: 'active' },
                { data: 'metadatos' },
                { data: 'actions' }
            ];
            let collections, result, urlNext, urlPrev, collectionsTable;
            $(document).ready(function() {
                $.ajax({
                    url: '/collections/lista',
                    type: 'GET',
                    success: function(r) {
                        result = JSON.parse(r);
                        collections = result.collections;
                        hasPages(result.pagination);
                        collectionsTable = $("#collectionsList").DataTable({
                            "data": collections,
                            "columns": columnas,
                            "paging": true,
                            "scrollY": 'auto',
                            "lengthMenu": lineas(10),
                            "searching": true,
                            "ordering": true,
                            "order": [
                                [2, 'asc']
                            ],
                            "autoWidth": true,
                            "responsive": true,
                            "buttons": botones()
                        }).buttons().container().appendTo('#collectionsList_wrapper .col-sm-12:eq(0)');
                        if (result.error != undefined) {
                            alertaPopUp(result.error);
                        }
                    }
                });
                //collectionsTable = tableRefill('collectionsList', collections, columnas, 10, true,2);
                $(".collections_pagination_next").click(function(e) {
                    e.preventDefault();
                    var url = $("#nextPage").val();
                    $.post(url, {}, function(r) {
                        //console.log(collectionsTable);
                        result = JSON.parse(r);
                        collections = result.collections;
                        collectionsTable = $("#collectionsList").DataTable().clear().draw();
                        collectionsTable.rows.add(collections).draw();
                        //collectionsTable.ajax.reload();
                        tableRefiller('collectionsList', collections);
                    });
                });
                $(".collections_pagination_prev").click(function(e) {
                    e.preventDefault();
                    var url = $("#nextPage").val();
                    $.post(url, {}, function(r) {
                        console.log(collectionsTable);
                        result = JSON.parse(r);
                        collections = result.collections;
                        hasPages(result.pagination);
                        //collectionsTable.draw();
                        //collectionsTable.ajax.reload();
                    });
                });
                //$.ajax({

                // });
                $('#type-Changer').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget) // Button that triggered the modal
                    var recipient = button.data('collectid') // Extract info from data-* attributes
                    var recipient = button.data('prodcat') // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                    var modal = $(this)
                    modal.find('.modal-title').text('New message to ' + recipient)
                    modal.find('.modal-body input').val(recipient)
                });
            });

            function hasPages(paginacion) {
                let maxPages = paginacion.max;
                if (paginacion.next != paginacion.max) {
                    urlNext = '/collections/next?page=' + paginacion.next + "&cursor=next&limit=" + paginacion.limit;
                    $("#nextPage").val(urlNext);
                    $(".collections_pagination_next").show();
                } else {
                    $(".collections_pagination_next").hide();
                }
                if (paginacion.prev > 1) {
                    urlPrev = '/collections/previous?page=' + paginacion.prev + "&cursor=prev&limit=" + paginacion.limit;
                    $("#previousPage").val(urlPrev);
                    $(".collections_pagination_prev").show();
                }
                var max = Numeric(paginacion.max);
                var lim = Numeric(paginacion.limit);
                var maxStepPages = Math.ceil(max / lim);
                var pageStep = 0;
                for (var i = 0; i < maxStepPages; i++) {
                    if (max > lim) {
                        max = max - lim;
                        pageStep += lim;
                        console.log('/collections/next?page=' + pageStep + "&cursor=page&limit=" + paginacion.limit);
                    } else {
                        console.log('/collections/next?page=' + max + "&cursor=page&limit=" + paginacion.limit);
                    }
                }
                $("#collections_pagination").show();
            }

            function syncCollection(id) {
                $.post('collections/sync',{"id":id},function (r) {
                result = JSON.parse(r);
                alertaPopUp(result);
            });
            }

            function deleteCollection(id) {
                $.post('collections/delete',{"id":id},function (r) {
                result = JSON.parse(r);
                alertaPopUp(result);
            });
            }

            function changeState(id, currentState) {
                $.post('collections/state',{"id":id,"current":currentState},function (r) {
                result = JSON.parse(r);
                alertaPopUp(result);
            });
            }
        </script>
    {{/literal}}
{{/block}}
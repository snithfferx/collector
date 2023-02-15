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
                        <h5 class="m-0">
                            Lista de Colecciones
                            <span id="spinger">
                                <i class="fas fa-sync-alt fa-spin text-info"></i>
                            </span>
                        </h5>
                        <nav aria-label="Page navigation collections_pagination">
                            <ul class="pagination justify-content-end" id="collections_pagination_top">
                            </ul>
                        </nav>
                    </div>
                    <div class="card-body">
                        <form role="form">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="categories">Categoría</label>
                                        <select class="form-control" id="categories">
                                            <option value="0" selected="selected">Elija una categoría</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="subcategories">Sub-Categoría</label>
                                        <select class="form-control" id="subcategories">
                                            <option value="0" selected="selected">Elija una subcategoría</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="collection_name"></label>
                                        <input type="text" class="form-control" id="collection_name"
                                            aria-describedby="collection_name_help" placeholder="Digite un nombre a filtrar">
                                        <small id="collection_name_help" class="form-text text-muted">Puede ser un nombre común, colección</small>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="deshabiliatdo">
                                        <label class="form-check-label" for="deshabiliatdo">Incluir
                                            deshabiliatdos</label>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <button type="submit" class="btn btn-primary" onclick="buscar()">Buscar</button>
                                </div>
                                <div class="col-10 offset-1">
                                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                        <div class="btn-group mr-2" role="group" aria-label="First group">
                                            <button type="button" class="btn btn-secondary" onclick="buscar('a')" id="letter_Filter_a">A</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('b')" id="letter_Filter_b">B</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('c')" id="letter_Filter_c">C</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('d')" id="letter_Filter_d">D</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('e')" id="letter_Filter_e">E</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('f')" id="letter_Filter_f">F</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('g')" id="letter_Filter_g">G</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('h')" id="letter_Filter_h">H</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('i')" id="letter_Filter_i">I</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('j')" id="letter_Filter_j">J</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('k')" id="letter_Filter_k">K</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('l')" id="letter_Filter_l">L</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('m')" id="letter_Filter_m">M</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('n')" id="letter_Filter_n">N</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('o')" id="letter_Filter_o">O</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('p')" id="letter_Filter_p">P</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('q')" id="letter_Filter_q">Q</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('r')" id="letter_Filter_r">R</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('s')" id="letter_Filter_s">S</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('t')" id="letter_Filter_t">T</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('u')" id="letter_Filter_u">U</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('v')" id="letter_Filter_v">V</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('w')" id="letter_Filter_w">W</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('x')" id="letter_Filter_x">X</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('y')" id="letter_Filter_y">Y</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('z')" id="letter_Filter_z">Z</button>
                                            <button type="button" class="btn btn-secondary" onclick="buscar('ñ')" id="letter_Filter_ñ">Ñ</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-responsive table-striped" id="collectionsList">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-uppercase font-weight-bolder text-center">ID Tienda</th>
                                            <th class="text-uppercase font-weight-bolder text-center">ID Local</th>
                                            <th class="text-uppercase font-weight-bolder text-center">Titulo Tienda</th>
                                            <th class="text-uppercase font-weight-bolder text-center">Nombre Común</th>
                                            <th class="text-uppercase font-weight-bolder text-center">Handle Tienda</th>
                                            <th class="text-uppercase font-weight-bolder text-center">Handle Local</th>
                                            <th class="text-uppercase font-weight-bolder text-center">Categoría</th>
                                            <th class="text-uppercase font-weight-bolder text-center">Sub-Categoría</th>
                                            <th class="text-uppercase font-weight-bolder text-center">Productos</th>
                                            <th class="text-uppercase font-weight-bolder text-center">Verificado</th>
                                            <th class="text-uppercase font-weight-bolder text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <nav aria-label="Page navigation collections_pagination">
                            <ul class="pagination justify-content-center" id="collections_pagination_bottom">
                            </ul>
                        </nav>
                        <input type="hidden" value="" id="previousPage">
                        <input type="hidden" value="" id="nextPage">
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
                                <label>Categoría</label>
                                <select class="form-control select2" style="width: 100%;" id="categories">
                                    <option value="0" selected="selected">Elija una categoría</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sub-Categoría</label>
                                <select class="form-control select2" style="width: 100%;" id="subcategories">
                                    <option value="0" selected="selected">Elija una subcategoría</option>
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
    <link rel="stylesheet" type="text/css" href="/assets/css/switchmtrlz/switchmtrlz.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables-buttons/buttons.bootstrap4.min.css">
    {{* <link rel="stylesheet" type="text/css" href="/assets/css/datatables-bs4/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables-responsive/responsive.bootstrap4.min.css">
     *}}
{{/block}}
{{block name="jslibs"}}
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap4.min.js"></script>
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
            $(".collections_pagination").hide();
            $(".collections_pagination_prev").hide();
            $(".collections_pagination_next").hide();
            $("#spinger").hide();
            const columnas = [
                { data: 'store_id' },
                { data: 'id_tienda' },
                { data: 'store_title' },
                { data: 'name' },
                { data: 'store_handle' },
                { data: 'handle' },
                { data: 'category' },
                { data: 'sub_category' },
                { data: 'product_count' },
                { data: 'verified' },
                { data: 'actions' }
            ];
            let collections = {}, result, urlNext, urlPrev;
            let collectionsTable = $("#collectionsList").DataTable({
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
                "processing" : true,
                "language": {
                    "emptyTable": "No hay registros en la tabla",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ líneas",
                    "infoEmpty": "No hay registros",
                    "infoFiltered": "(_MAX_ líneas en total)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar: _MENU_ líneas",
                    "loadingRecords": '<i class="fas fa-sync fa-spin fa-2x text-primary"></i>',
                    "processing": '<i class="fas fa-cog fa-spin fa-2x text-primary"></i>',
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron coincidencias.",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Previo"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar columna ascendentemente",
                        "sortDescending": ": activar para ordenar columna descendentemente"
                    }
                },
                "buttons": botones()
            });
            //$.fn.dataTable.Buttons(collectionsTable, { buttons: botones() });
            collectionsTable.buttons().container().appendTo('#collectionsList_wrapper .col-sm-12:eq(0)');
            $(document).ready(function() {
                getTableData('/collections/lista');
                getCategories();
                getSubCategories();
                $("#collection_name").autocomplete({
                    source : "/collections/fill",
                    limit : 10,
                    select : function (e,u) {
                        var id = u.item.id
                        getTableData('/collections/search',{name:id});
                    }
                });
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
                $("#categories").change(function () {
                    var cat = $("#categories").val();
                    getSubCategories(cat);
                });
            });
            /* function hasPages(paginacion) {
                var pages = '';
                urlNext = '/collections/next?page=' + paginacion.next + "&cursor=next&limit=" + paginacion.limit;
                urlPrev = '/collections/previous?page=' + paginacion.prev + "&cursor=prev&limit=" + paginacion.limit;
                var max = Number(paginacion.max);
                var lim = Number(paginacion.limit);
                var maxStepPages = Math.ceil(max / lim);
                var pageStep = 0;
                var stepo;
                if (paginacion.prev != undefined && paginacion.prev > 1) {
                    pages += `
                    <li class="page-item collections_pagination_prev">
                        <a class="page-link" title="Lleva a la página anterior" type="text" target="_self" 
                            href="#" onclick="getNewPage(${paginacion.prev},'prev',${paginacion.limit})">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Anterior</span>
                        </a>
                    </li>`;
                }
                //console.log(paginacion.prev);
                for (var i = 0; i < maxStepPages; i++) {
                    if (max > lim) {
                        max = max - lim;
                        pageStep += lim;
                        stepo = (i + 1);
                        pages += `<li class="page-item">
                            <a class="page-link active"
                            href="#" onclick="getNewPage(${pageStep},'page',${paginacion.limit})">${stepo}</a>
                        </li>`;
                    } else {
                        pages += `<li class="page-item">
                            <a class="page-link"
                            href="#" onclick="getNewPage(${max},'page',${paginacion.limit})">${stepo}</a>
                        </li>`;
                    }
                }
                if (paginacion.next != undefined && paginacion.next > 1) {
                    pages += `
                        <li class="page-item collections_pagination_next">
                            <a class="page-link" title="Lleva a la página siguiente" type="text" target="_self"
                                href="#" onclick="getNewPage(${paginacion.prev},'next',${paginacion.limit})">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Siguiente</span>
                            </a>
                        </li>`;
                }
                //console.log(paginacion.next);
                document.getElementById("collections_pagination_top").innerHTML = pages;
                document.getElementById("collections_pagination_bottom").innerHTML = pages;
                if (paginacion.next != paginacion.max) {
                    $("#nextPage").val(urlNext);
                    $(".collections_pagination_next").show();
                } else {
                    $(".collections_pagination_next").hide();
                }
                if (paginacion.prev > 1) {
                    $("#previousPage").val(urlPrev);
                    $(".collections_pagination_prev").show();
                }

                //$(".collections_pagination").show();
            } */
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
            /* function getNewPage (last,cursor,limit) {
                $.ajax({
                    url: '/collections/lista',
                    type: 'POST',
                    data: {
                        page:last,cursor:cursor,limit:limit
                    },
                    beforeSend:function () {
                        $("#spinger").show();
                    },
                    success: function(r) {
                        result = JSON.parse(r);
                        collections = result.collections;
                        hasPages(result.pagination);
                    },
                    complete: function () {
                        collectionsTable.ajax.reload();
                        $("#spinger").hide();
                    }
                });
            } */
            // Busca reigstros
            function buscar (parametro) {
                var activo,cats = $("#categories").val(), sucat = $("#subcategories").val(),coleccion = $("#collection_name").val();
                if ($('#deshabiliatdo').is(':checked')) {
                    activo = true;
                } else {
                    activo = false;
                }
                if (parametro != undefined) {
                    $.ajax({
                        url: '/collections/search',
                        type: 'POST',
                        data: {
                            letter:parametro, active:activo
                        },
                        beforeSend : function () {
                            $("#spinger").show();
                        },
                        success: function(r) {
                            result = JSON.parse(r);
                            collections = result.collections;
                            collectionsTable.rows.add(collections).draw();
                            if (result.error != undefined) {
                                alertaPopUp(result.error);
                            }
                        },
                        complete: function () {
                            $("#spinger").hide();
                        }
                    });
                } else {
                    $.ajax({
                        url: '/collections/search',
                        type: 'POST',
                        data: {
                            cat: cats,
                            scat: sucat,
                            active:activo,
                            name:coleccion
                        },
                        beforeSend : function () {
                            $("#spinger").show();
                        },
                        success: function(r) {
                            result = JSON.parse(r);
                            collections = result.collections;
                            collectionsTable.rows.add(collections).draw();
                            if (result.error != undefined) {
                                alertaPopUp(result.error);
                            }
                        },
                        complete: function () {
                            $("#spinger").hide();
                        }
                    });
                }
            }
            // Trae las categorias
            function getCategories () {
                $.ajax({
                    url: '/categories/categorieslist',
                    type:'POST',
                    success: function (r) {
                        var result = JSON.parse(r);
                        var fopcion;
                        $.each(result,function (index,val) {
                            fopcion = new Option(val.name,val.id,false,false);
                            $("#categories").append(fopcion);
                        });
                    }
                });
            }
            // Trae las sub categorias
            function getSubCategories (id) {
                $.ajax({
                    url: '/categories/subcategorieslist',
                    type:'POST',
                    data: {
                        cid : id
                    },
                    success: function (r) {
                        let opcion = new Option('Elija una subcategoria',0,true,true);
                        $("#subcategories").append(opcion).trigger('change');
                        var result = JSON.parse(r);
                        var fopcion;
                        $.each(result,function (index,val) {
                            fopcion = new Option(val.name,val.id,false,false); 
                            $("#subcategories").append(fopcion);
                        });
                    } 
                });
            }
            function getTableData (direccion,args=null) {
                $.ajax({
                    url: direccion,
                    type: 'POST',
                    data: args,
                    beforeSend : function () {
                        $("#spinger").show();
                    },
                    success: function(r) {
                        result = JSON.parse(r);
                        collections = result.collections;
                        //hasPages(result.pagination);
                        collectionsTable.rows.add(collections).draw();
                        if (result.error != undefined) {
                            alertaPopUp(result.error);
                        }
                    },
                    complete: function () {
                        $("#spinger").hide();
                    }
                });
            }
        </script>
    {{/literal}}
{{/block}}
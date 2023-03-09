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
                                <div class="col-10 offset-1 mb-3">
                                    <div class="btn-toolbar justify-content-between" role="toolbar"
                                        aria-label="Toolbar with button groups">
                                        {{assign var="letras" value=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','Ñ']}}
                                        {{foreach $letras as $key => $letra}}
                                            <div class="btn-group mr-2" role="group" aria-label="Group-{{$key}}">
                                                <button type="button" class="btn btn-secondary" onclick="buscar('{{$letra}}')"
                                                    id="letter_Filter_{{$letra}}">{{$letra}}</button>
                                            </div>
                                        {{/foreach}}
                                    </div>
                                </div>
                                <div class="col-1">
                                    <button type="button" class="btn btn-primary btn-block btn-sm" data-toggle="modal"
                                        data-target="#searching_modal">
                                        Buscar
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped table-bordered" id="collectionsList">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-uppercase font-weight-bolder text-center">ID Tienda</th>
                                            <th class="text-uppercase font-weight-bolder text-center">ID Local</th>
                                            <th class="text-uppercase font-weight-bolder text-center">Titulo Tienda</th>
                                            <th class="text-uppercase font-weight-bolder text-center">Nombre Común</th>
                                            <th class="text-uppercase font-weight-bolder text-center">Handle Tienda</th>
                                            <th class="text-uppercase font-weight-bolder text-center">Handle Local</th>
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
        <div class="modal-dialog modal-xl modal-dialog-centered">
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
                    {{* <button type="button" class="btn btn-primary">Guardar</button> *}}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteCollection_modal" tabindex="-1" role="dialog" aria-labelledby="Delete Collection"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmación de eliminación</h4>
                    <div class="spinner-grow" role="status" id="spinfus">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="deleteCollection_modal_content">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    {{* <button type="button" class="btn btn-primary">Confirmar</button> *}}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteCommon_modal" tabindex="-1" role="dialog" aria-labelledby="Delete Common Name"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmación de eliminación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="deleteCommon_modal">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    {{* <button type="button" class="btn btn-primary">Confirmar</button> *}}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editation_modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Edición de nombre común
                        <div class="spinner-grow" role="status" id="spinfus">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="common_id" />
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="common_date">Fecha de creación</label>
                                    <input type="text" class="form-control disabled" id="common_date"
                                        placeholder="Fecha de creación" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="common_common">Titulo</label>
                                    <input type="text" class="form-control" id="common_common" placeholder="titulo">
                                </div>
                                <div class="form-group">
                                    <label for="common_handle">Titulo url</label>
                                    <input type="text" class="form-control disabled" id="common_handle"
                                        placeholder="User friendly URL">
                                </div>
                                <div class="form-group">
                                    <label for="common_store_id">ID de tienda</label>
                                    <input type="text" class="form-control disabled" id="common_store_id"
                                        placeholder="Identificador de tienda">
                                </div>
                                {{* <div class="form-group">
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
                                </div> *}}
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="common_possition">Posición</label>
                                    <input type="text" class="form-control" id="common_possition" placeholder="Posición">
                                </div>
                                <div class="form-group">
                                    <label for="common_terms">Terminos de Busqueda</label>
                                    <input type="text" class="form-control disabled" id="common_terms" placeholder="Keywords">
                                </div>
                                <div class="form-group">
                                    <label>Categoría</label>
                                    <select class="form-control select2" style="width: 100%;" id="common_category">
                                        <option value="0" selected="selected">Elija una categoría</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Sub-Categoría</label>
                                    <select class="form-control select2" style="width: 100%;" id="common_subcategory">
                                        <option value="0" selected="selected">Elija una subcategoría</option>
                                    </select>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="common_active">
                                    <label class="form-check-label" for="common_active">Activo</label>
                                </div>
                            </div>
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

    <div class="modal fade" id="searching_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Busqueda</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="categories">Categoría</label>
                                <select class="form-control" id="categories">
                                    <option value="0" selected="selected">Elija una categoría</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="subcategories">Sub-Categoría</label>
                                <select class="form-control" id="subcategories">
                                    <option value="0" selected="selected">Elija una subcategoría</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="collection_name"></label>
                                <input type="text" class="form-control" id="collection_name"
                                    aria-describedby="collection_name_help" placeholder="Digite un nombre a filtrar">
                                <small id="collection_name_help" class="form-text text-muted">Puede ser un nombre
                                    común o colección</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="deshabiliatdo">
                                <label class="form-check-label" for="deshabiliatdo">Incluir
                                    deshabiliatdos</label>
                            </div>
                        </div>
                        <div class="col-3 offset-9">
                            <button type="submit" class="btn btn-primary" onclick="buscar()">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    {{/block}}
    {{block name='css'}}
        <link rel="stylesheet" type="text/css" href="/assets/css/switchmtrlz/switchmtrlz.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/datatables-bs4/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/datatables-buttons/buttons.bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/datatables-responsive/responsive.bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/datatables-scroller/scroller.bootstrap4.min.css">
    {{/block}}
    {{block name="jslibs"}}
        <script type="text/javascript" src="/assets/js/datatables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="/assets/js/datatables-bs4/dataTables.bootstrap4.min.js"></script>

        <script type="text/javascript" src="/assets/js/datatables-buttons/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.bootstrap4.min.js"></script>
        <script type="text/javascript" src="/assets/js/jszip/jszip.min.js"></script>
        <script type="text/javascript" src="/assets/js/pdfmake/pdfmake.min.js"></script>
        <script type="text/javascript" src="/assets/js/pdfmake/vfs_fonts.js"></script>
        <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.html5.min.js"></script>
        <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.print.min.js"></script>
        <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.colVis.min.js"></script>
        <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.flash.min.js"></script>

        <script type="text/javascript" src="/assets/js/datatables-responsive/dataTables.responsive.min.js"></script>
        <script type="text/javascript" src="/assets/js/datatables-responsive/responsive.bootstrap4.min.js"></script>

        <script type="text/javascript" src="/assets/js/datatables-scroller/dataTables.scroller.min.js"></script>
        <script type="text/javascript" src="/assets/js/datatables-scroller/scroller.bootstrap4.min.js"></script>
        <script type="text/javascript" src="/assets/js/moment/moment-with-locales.min.js"></script>
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
                    { data: 'verified', className: 'text-center' },
                    { data: 'actions' }
                ];
                let collections = {},
                    result, urlNext, urlPrev;
                $(document).ready(function() {
                    let collectionsTable = $("#collectionsList").DataTable({
                        ajax: {
                            url: '/collections/lista',
                            dataSrc: function(r) {
                                if (r.error != undefined) {
                                    alertaPopUp(r.error);
                                } else {
                                    collections = r.collections;
                                    return r.collections;
                                }
                            }
                        },
                        "columns": columnas,
                        /* "columnDefs": [
                    { width: '120px', "targets": [1,2,3,4,5] }
                ], */
                        "paging": true,
                        "scrollY": '600px',
                        "lengthMenu": lineas(10),
                        "searching": true,
                        "ordering": true,
                        "order": [
                            [2, 'asc']
                        ],
                        "responsive": true,
                        "processing": false,
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
                        "buttons": botones(['imprimir', 'pdf', 'excel'])
                    });
                    //.buttons().container().appendTo('#collectionsList_wrapper .col-sm-12:eq(0)');
                    //new $.fn.dataTable.Buttons(collectionsTable, { buttons: botones() });
                    //collectionsTable.buttons().container().appendTo('#collectionsList_wrapper .col-sm-12:eq(0)');
                    collectionsTable.buttons().container().appendTo('#collectionsList_wrappe .col-sm-12:eq(0)');
                    //getTableData('/collections/lista');
                    getCategories();
                    getSubCategories();
                    $("#collection_name").autocomplete({
                        source: "/collections/fill",
                        limit: 10,
                        select: function(e, u) {
                            var id = u.item.id,
                                activo;
                            if ($('#deshabiliatdo').is(':checked')) {
                                activo = true;
                            } else {
                                activo = false;
                            }
                            getTableData('/collections/search',{name:id,active:activo});
                        }
                    });
                    $("#categories").change(function() {
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
                <
                span aria - hidden = "true" > & laquo; < /span> <
                span class = "sr-only" > Anterior < /span> < /
                a > <
                    /li>`;
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
                }*/

                function syncCollection(id) {
                    $.post('collections/sync',{"id":id},function (r) {
                    result = JSON.parse(r);
                    alertaPopUp(result);
                });
                }

                function deleteCollection(id, ubi) {
                    $.ajax({
                        url: '/collections/delete',
                        type: "POST",
                        data: {
                            id: id,
                            where: ubi
                        },
                        beforeSend: function() {
                            $("#spinfus").show();
                        },
                        success: function(r) {
                            $("#deleteCollection_modal_content").html(r);
                            $('#deleteCollection_modal').modal('show');
                        },
                        complete: function() {
                            $("#spinfus").hide();
                        }
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
                function buscar(parametro) {
                    event.preventDefault();
                    var searched, activo, cats = $("#categories").val(),
                        sucat = $("#subcategories").val(),
                        coleccion = $("#collection_name").val();
                    if ($('#deshabilitado').is(':checked')) {
                        activo = true;
                    } else {
                        activo = false;
                    }
                    if (parametro != undefined) {
                        searched = new Array;
                        $.each(collections, function(index, value) {
                            if (value.title != null) {
                                if (value.title.startsWith(parametro)) {
                                    if (activo == true) {
                                        searched.push(value);
                                    } else {
                                        if (value.active == 1) {
                                            searched.push(value);
                                        }
                                    }
                                }
                            }
                        });
                        if (searched.length == 0) {
                            alertaPopUp('error', {
                                class: "bg-info",
                                title: "¡AVISO!",
                                subtitle: "No data parsed..",
                                autohide: true,
                                delay: 7500,
                                body: "Sorry no data found!",
                                icon: "fas fa-info-circle",
                            });
                        } else {
                            collectionsTable.clear();
                            collectionsTable.rows.add(searched).draw();
                        }
                        /* $.ajax({
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
                        collectionsTable.clear();
                        collectionsTable.rows.add(collections).draw();
                        if (result.error != undefined) {
                            alertaPopUp(result.error);
                        }
                    },
                    complete: function () {
                        $("#spinger").hide();
                    }
                }); */
                    } else {
                        $.ajax({
                            url: '/collections/search',
                            type: 'POST',
                            data: {
                                cat: cats,
                                scat: sucat,
                                active: activo,
                                name: coleccion
                            },
                            beforeSend: function() {
                                $("#spinger").show();
                            },
                            success: function(r) {
                                result = JSON.parse(r);
                                collections = result.collections;
                                collectionsTable.clear();
                                collectionsTable.rows.add(collections).draw();
                                if (result.error != undefined) {
                                    alertaPopUp(result.error);
                                }
                            },
                            complete: function() {
                                $("#spinger").hide();
                            }
                        });
                    }
                }
                // Trae las categorias
                function getCategories(elemento = "categories") {
                    $.ajax({
                        url: '/categories/categorieslist',
                        type: 'POST',
                        success: function(r) {
                            var result = JSON.parse(r);
                            var fopcion;
                            fopcion = new Option('Elija de Categorías', 0, true, true);
                            $.each(result, function(index, val) {
                                fopcion = new Option(val.name, val.id, false, false);
                                $("#" + elemento).append(fopcion);
                            });
                        }
                    });
                }
                // Trae las sub categorias
                function getSubCategories(id, element = "subcategories") {
                    $.ajax({
                        url: '/categories/subcategorieslist',
                        type: 'POST',
                        data: {
                            cid: id
                        },
                        success: function(r) {
                            $("#" + element).empty();
                            let opcion = new Option('Elija una subcategoria', 0, true, true);
                            $("#" + element).append(opcion).trigger('change');
                            var result = JSON.parse(r);
                            var fopcion;
                            $.each(result, function(index, val) {
                                fopcion = new Option(val.name, val.id, false, false);
                                $("#" + element).append(fopcion);
                            });
                        }
                    });
                }

                function getTableData(direccion, args = null) {
                    $.ajax({
                        url: direccion,
                        type: 'POST',
                        data: args,
                        beforeSend: function() {
                            $("#spinger").show();
                            toastr.info('Acquiring data..')
                        },
                        success: function(r) {
                            result = JSON.parse(r);
                            collections = result.collections;
                            //collectionsTable.clear();
                            //collectionsTable.rows.add(collections).draw();
                            collectionsTable.ajax.reload();
                            if (result.error != undefined) {
                                console.log(result)
                                alertaPopUp(result.error);
                            }
                        },
                        complete: function() {
                            $("#spinger").hide();
                            alertaPopUp({title:"¡Exito!",body:'Data acquiried..'},'success');
                        }
                    });
                }

                function verifyCollection(collection, state) {
                    $.ajax({
                        url: '/collections/verify',
                        type: 'POST',
                        data: {id_collection:collection,current:state},
                        beforeSend: function() {
                            $("#spinger").show();
                        },
                        success: function(r) {
                            result = JSON.parse(r);
                            alertaPopUp(result);
                        },
                        complete: function() {
                            $("#spinger").hide();
                        }
                    });
                }

                function editElementById(elemento) {
                    $('#editation_modal').modal('show');
                    var typus = $(this).data('editType');
                    var urlAddress = '/commonnames/get';
                    if (typus == "collection") {
                        urlAddress = '/collections/get';
                    }
                    $("#common_url").val(urlAddress);
                    getCategories("common_category");
                    $.ajax({
                        url: urlAddress,
                        type: 'POST',
                        data: {
                            list: 'single',
                            id: elemento,
                            type: typus
                        },
                        beforeSend: function() {
                            $("#spinfus").show();
                        },
                        success: function(r) {
                            result = JSON.parse(r);
                            console.log(result)
                            $("#common_id").val(result.data[0].id);
                            $("#common_common").val(result.data[0].common);
                            $("#common_possition").val(result.data[0].posicion);
                            $("#common_date").val(result.data[0].date);
                            if (result.data[0].activo > 0) {
                                $("#common_active").attr('checked', true);
                            }
                            $("#common_store_id").val(result.data[0].id_tienda);
                            $("#common_handle").val(result.data[0].handle);
                            $("#common_terms").val(result.data[0].terminos);
                            document.getElementById("common_category").selectedIndex = result.data[0].idc;
                            $("#common_category").val(result.data[0].idc).trigger('change');
                            getSubCategories(result.data[0].idc, "common_subcategory");
                            setTimeout(function() {
                                document.getElementById("common_subcategory").selectedIndex = result.data[0]
                                    .ids;
                                $("#common_subcategory").val(result.data[0].ids).trigger('change');
                            }, 1000);
                        },
                        complete: function() {
                            $("#spinfus").hide();
                        }
                    });
                }

                function saveEditation() {
                    var id = $("#common_id").val();
                    var name = $("#common_common").val();
                    var poss = $("#common_possition").val();
                    var date = $("#common_date").val();
                    var activ = $("#common_active").is('checked');
                    var store = $("#common_store_id").val();
                    var handle = $("#common_handle").val();
                    var keyw = $("#common_terms").val();
                    var cat = $("#category option:selected").val();
                    var scat = $("#subcategory option:selected").val();
                    var curl = $("#common_url").val();
                    $.ajax({
                        url: curl,
                        type: 'POST',
                        data: {
                            list: 'single',
                            registre: id,
                            title: name,
                            possition: poss,
                            active: activ,
                            store_id: store,
                            handle: handle,
                            keywords: keyw,
                            category: cat,
                            subcategory: scat
                        },
                        beforeSend: function() {
                            $("#spinfus").show();
                        },
                        success: function(r) {
                            result = JSON.parse(r);
                            alertaPopUp(result.error, 'error');
                        },
                        complete: function() {
                            $("#spinfus").hide();
                        }
                    });
                }
                $('#editation_modal').on('hidden.bs.modal', function(e) {
                    console.log(e);
                })
            </script>
        {{/literal}}
{{/block}}
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
                            <span class="text-primary" id="counter"></span>
                        </h5>
                        <div class="justified-content-end">
                            <buttton type="button" class="btn btn-primary" id="populateDB">Descargar</buttton>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form role="form">
                                    <div class="row">
                                        <div class="col-10 offset-1 mb-3">
                                            <div class="btn-toolbar justify-content-between" role="toolbar"
                                                aria-label="Toolbar with button groups">
                                                {{assign var="letras" value=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','Ñ']}}
                                                {{foreach $letras as $key => $letra}}
                                                    <div class="btn-group mr-2" role="group" aria-label="Group-{{$key}}">
                                                        <button type="button" class="btn btn-secondary"
                                                            onclick="buscar('{{$letra}}')"
                                                            id="letter_Filter_{{$letra}}">{{$letra}}</button>
                                                    </div>
                                                {{/foreach}}
                                            </div>
                                        </div>
                                        {{* <div class="col-1">
                                            <button type="button" class="btn btn-primary btn-block btn-sm" data-toggle="modal"
                                                data-target="#searching_modal">
                                                Buscar
                                            </button>
                                        </div> *}}
                                    </div>
                                </form>
                            </div>
                            <div class="col-12">
                                <table class="table table-striped table-bordered" style="width:100%" id="collectionsList">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-uppercase font-weight-bolder text-center">ID</th>
                                            <th class="text-uppercase font-weight-bolder text-center">StoreID</th>
                                            <th class="text-uppercase font-weight-bolder text-center">title</th>
                                            <th class="text-uppercase font-weight-bolder text-center">handle</th>
                                            <th class="text-uppercase font-weight-bolder text-center">productos</th>
                                            <th class="text-uppercase font-weight-bolder text-center">sort</th>
                                            <th class="text-uppercase font-weight-bolder text-center">rules</th>
                                            <th class="text-uppercase font-weight-bolder text-center">seo</th>
                                            <th class="text-uppercase font-weight-bolder text-center">verificada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>
    {{* var_dump($data.content) *}}
{{/block}}
{{block name='css'}}
    <link rel="stylesheet" type="text/css" href="/assets/css/switchmtrlz/switchmtrlz.css">
    {{* <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> *}}
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables-bs4/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables-buttons/buttons.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables-responsive/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables-scroller/scroller.bootstrap4.min.css">
{{/block}}
{{block name="jslibs"}}
    {{* <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script> *}}
    <script type="text/javascript" src="/assets/js/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-bs4/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.bootstrap4.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.flash.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.html5.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-buttons/buttons.print.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-responsive/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-responsive/responsive.bootstrap4.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-scroller/dataTables.scroller.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-scroller/scroller.bootstrap4.min.js"></script>
    <script type="text/javascript" src="/assets/js/jszip/jszip.min.js"></script>
    <script type="text/javascript" src="/assets/js/pdfmake/pdfmake.min.js"></script>
    <script type="text/javascript" src="/assets/js/pdfmake/vfs_fonts.js"></script>
    <script type="text/javascript" src="/assets/js/moment/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="/assets/js/global/tabler.js"></script>
{{/block}}
{{block name="scripts"}}
    {{literal}}
        <script>
            $('[data-toggle="tooltip"]').tooltip()
            $("#spinger").hide();
            const columnas = [
                { data: "collection_id" },
                { data: "id" },
                { data: "title" },
                { data: "handle" },
                { data: "products" },
                { data: "sort" },
                { data: "rules" },
                { data: "seo" },
                { data: "verified" }
            ];
            let collections = {},
                result;
            let collectionsTable = $("#collectionsList").DataTable({
                ajax: {
                    url: '/collections/download/list',
                    dataSrc: function(r) {
                        console.log(r)
                        if (r.error != undefined) {
                            alertaPopUp(r.error);
                        } else {
                            collections = r.collections;
                            return r.collections;
                        }
                    }
                },
                "columns": columnas,
                "columnDefs": [
                    { width: '120px', "targets": [2, 3, 4, 7] }
                ],
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
                "buttons": botones()
            });
            $(document).ready(function() {
                collectionsTable.buttons().container().appendTo($('.col-sm-6:eq(0)', collectionsTable.table().container()));
                $("#populateDB").click(function() {
                    populateDatabase();
                    setInterval(() => {
                        contarLineas();
                        collectionsTable.ajax.reload();
                    }, 300000);
                });
                contarLineas();
            });

            function populateDatabase() {
                $.ajax({
                    url: '/collections/download/proceed',
                    type: 'POST',
                    beforeSend: function() {
                        $("#spinger").show();
                    },
                    success: function(r) {
                        result = JSON.parse(r);
                        if (result.error != undefined) {
                            alertaPopUp(result.error);
                        } else {
                            collectionsTable.ajax.reload();
                        }
                    },
                    complete: function() {
                        $("#spinger").hide();
                    }
                });
            }

            function contarLineas() {
                $.ajax({
                    url: '/collections/download/checking',
                    type: 'POST',
                    success: function(r) {
                        result = JSON.parse(r);
                        console.log(result)
                        $("#counter").text(result.data);
                        if (result.error != []) {
                            alertaPopUp(result.error);
                        }
                    }
                });
            }

            function buscar(parametro) {
                event.preventDefault();
                var searched;
                if (parametro != undefined) {
                    searched = new Array;
                    $.each(collections, function(index, value) {
                        if (value.title != null) {
                            if (value.title.startsWith(parametro)) {
                                searched.push(value);
                            }
                        }
                    });
                    if (searched.length == 0) {
                        alertaPopUp({
                            class: "bg-info",
                            title: "¡AVISO!",
                            subtitle: "No data parsed..",
                            autohide: true,
                            delay: 7500,
                            body: "Sorry no data found!",
                            icon: "fas fa-info-circle",
                        },'error');
                    } else {
                        collectionsTable.clear();
                        collectionsTable.rows.add(searched).draw();
                    }
                } else {
                    /* $.ajax({
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
                    }); */
                    alert("Función aún no disponible");
                }
            }
        </script>
    {{/literal}}
{{/block}}
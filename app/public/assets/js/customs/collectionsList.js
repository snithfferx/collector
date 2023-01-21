$(document).ready(function () {
    var url_base = "/collections/read/";
    $("#collections_pagination").hide();
    /* $.ajax({
        url: url_base + 'lista',
        type: 'GET',
        success: function (r) {
            console.log(r);
        }
    }); */
    /* [
        {
            "id":null,
            "name":null,
            "possition":null,
            "date":null,
            "active":null,
            "sub_category":null,
            "category":null,
            "handle":null,
            "keywords":null,
            "store_id":415267848416,
            "store_title":"ACCESORIOS Back To School",
            "store_handle":"accesorios-back-to-school"} */
    var collectionsTable = $('#collectionsList').DataTable({
        ajax: {
            url: url_base + 'lista',
            dataSrc: function (r) {
                var previo, siguiente;
                $("#collections_pagination_pages").append(r.max_page);
                previo = "/collections/previous" + r.prev_page;
                siguiente = "/collections/next" + r.next_page;
                $("#collections_pagination_prev").attr("href",previo);
                $("#collections_pagination_next").attr("href",siguiente);
                $("#collections_pagination").show();
                return r.data;
            }
        },
        columns: [
            { data: 'store_id' },
            { data: 'date' },
            { data: 'name' },
            { data: 'store_title' },
            { data: 'store_handle' },
            { data: 'category' },
            { data: 'sub_category' },
            { data: 'active' },
            { data: 'possition' },
            {
                title: 'actions',
                render: function (d, t, r, f) {
                    var acciones = '';
                    if (t == "display") {
                        acciones =
                        `<div class='btn-group'>
                            <button type='button' class='btn btn-outline-info dropdown-toggle dropdown-icon'
                                data-toggle='dropdown'>
                                Eleija...
                            </button>
                            <span class='sr-only'>Acciones</span>
                            <div class='dropdown-menu' role='menu'>`;
                                if (r.id != null) {
                                    acciones += `<a href="collections/read/${r.id}" class="dropdown-item btn btn-primary">
                                            <i class="fas fa-eye mr-3"></i>Detalles
                                        </a>
                                        <a href="collections/compare/${r.store_id}" class="dropdown-item btn btn-primary">
                                            <i class="fas fa-copy mr-3"></i>Comparar
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a href="collections/edit/${r.id}" class="dropdown-item btn btn-warning">
                                            <i class="fas fa-edit mr-3"></i>Editar Local
                                        </a>
                                        <a href="collections/delete/${r.id}" class="dropdown-item btn btn-danger">
                                            <i class="fas fa-trash mr-3"></i>Borrar Local
                                        </a>`;
                                }
                                acciones +=
                                `<div class="dropdown-divider"></div>
                                <a href="collections/edit/${r.store_id}" class="dropdown-item btn btn-warning">
                                    <i class="fas fa-edit mr-3"></i>Editar Local
                                </a>
                                <a href="collections/delete/${r.store_id}" class="dropdown-item btn btn-danger">
                                    <i class="fas fa-trash mr-3"></i>Borrar Local
                                </a>
                            </div>
                        </div>`;
                    }
                    return acciones;
                }
            }
        ],
        createdRow: function (row, data) {
            if (data.active != null && data.active > 0) {
                $('td', row).eq(7).text("Activo");
                $('td', row).eq(7).addClass("bg-success");
            } else {
                if (data.active == 0) {
                    $('td', row).eq(7).text("Inactivo");
                }
                $('td', row).eq(7).addClass("bg-danger");
            }
            if (data.name == null && data.id == null) {
                $('td', row).addClass("bg-gradient-warning");
            }
        },
        /* columnDefs: [{
            targes: -1,
            title: 'actions',
            render:function (data, type,full,meta) {
                    
                }
        }], */
        paging: true,
        scrollY: '500px',
        lengthMenu: [10, 15, 20, 25],
        searching: true,
        ordering: true,
        order: [
            [1, 'asc']
        ],
        info: true,
        autoWidth: 'auto',
        responsive: true,
        processing: true,
        language: {
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
        }
    });
    $.fn.dataTable.Buttons(collectionsTable, {
       Buttons: botones 
    });
    collectionsTable.buttons().container().appendTo('#collections_pagination_wrapper .col-sm-12:eq(0)');
});
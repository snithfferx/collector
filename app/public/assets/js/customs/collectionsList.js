$(document).ready(function () {
    var url_base = "/collections/read/";
    $("#collections_pagination").hide();
    var columnas = [
        { data: 'id' },
        { data: 'date' },
        { data: 'name' },
        { data: 'store_title' },
        { data: 'store_handle' },
        { data: 'category' },
        { data: 'sub_category' },
        { data: 'active' },
        { data: 'possition' },
        { data: 'actions' }
    ];
    /* */
    var activityTable = $('#collectionsList').DataTable({
        ajax: {
            url: url_base + 'lista',
            dataSrc: function (r) {
                $("#collections_pagination_pages").append(r.max_page);
                $("#collections_pagination_prev").attr("href","/collections/previous/" + r.prev_page);
                $("#collections_pagination_next").attr("href","/collections/next/" + r.next_page);
                $("#collections_pagination").show();
                return r.data;
            }
        },
        columns: columnas,
        createdRow: function( row, data, dataIndex ) {
            $('td', row).eq().html(
                `<div class='btn-group'>
                    <button type='button' class='btn btn-outline-info dropdown-toggle dropdown-icon'
                        data-toggle='dropdown'>
                        Eleija...
                    </button>
                    <span class='sr-only'>Acciones</span>
                    <div class='dropdown-menu' role='menu'>
                        <a href="collections/read/${data.id}"
                            class="dropdown-item btn btn-primary">
                            <i class="fas fa-eye mr-3"></i>
                            Detalles
                        </a>
                        <a href="collections/compare/${data.id}"
                            class="dropdown-item btn btn-primary">
                            <i class="fas fa-eye mr-3"></i>
                            Comparar
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="collections/edit/${data.id}}"
                            class="dropdown-item btn btn-warning">
                            <i class="fas fa-edit mr-3"></i>
                            Editar Local
                        </a>
                        <a href="collections/delete/${data.id}"
                            class="dropdown-item btn btn-danger">
                            <i class="fas fa-trash mr-3"></i>
                            Borrar Local
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="collections/edit/${data.store_id}"
                            class="dropdown-item btn btn-warning">
                            <i class="fas fa-edit mr-3"></i>
                            Editar Local
                        </a>
                        <a href="collections/delete/${data.store_id}"
                            class="dropdown-item btn btn-danger">
                            <i class="fas fa-trash mr-3"></i>
                            Borrar Local
                        </a>
                    </div>
                </div>`);
        },
        paging: true,
        scrollY: '600px',
        lengthMenu: [10, 15, 20, 25],
        searching: true,
        ordering: true,
        order: [
            [1, 'desc']
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
});
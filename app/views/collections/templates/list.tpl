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
                        {{if $data.content.datos.max_page > 0}}
                            <nav aria-label="Page navigation" id="collections_pagination">
                                <ul class="pagination justify-content-end">
                                    <li class="page-item">Paginas <b><span
                                                id="collections_pagination_pages">{{$data.content.datos.max_page}}</span></b>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="{{$data.content.datos.prev_page}}"
                                            id="collections_pagination_prev">
                                            Previo
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="{{$data.content.datos.next_page}}"
                                            id="collections_pagination_next">
                                            Siguiente
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        {{/if}}
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive table-striped" id="collectionsList">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="uppercase text-center">ID Tienda</th>
                                    <th class="uppercase text-center">Fecha</th>
                                    <th class="uppercase text-center">Nombre Común</th>
                                    <th class="uppercase text-center">Titulo</th>
                                    <th class="uppercase text-center">CEO</th>
                                    <th class="uppercase text-center">Categoría</th>
                                    <th class="uppercase text-center">Sub-Categoría</th>
                                    <th class="uppercase text-center">Activo</th>
                                    <th class="uppercase text-center">Posición</th>
                                    <th class="uppercase text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{foreach $data.content.datos as $item}}
                                    <tr>
                                        <td class="text-center">
                                            <a href="collections/read/{{$item.store_id}}">
                                                {{$item.store_id}}
                                            </a>
                                        </td>
                                        <td class="text-center">{{$item.date|date_format:%d/%m/%y}}</td>
                                        <td class="text-center">
                                            <a href="collections/read/{{$item.id}}">
                                                {{$item.name}}
                                            </a>
                                        </td>
                                        <td class="text-center">{{$item.store_title}}</td>
                                        <td class="text-center">{{$item.store_handle}}</td>
                                        <td class="text-center">{{$item.category}}</td>
                                        <td class="text-center">{{$item.sub_category}}</td>
                                        <td class="text-center">
                                            {{if $item.active == 0}}Inactivo{{else}}Activo{{/if}}
                                        </td>
                                        <td class="text-center">{{$item.possition}}</td>
                                        <td class="text-center">
                                            <div class='btn-group'>
                                                <button type='button' class='btn btn-outline-info dropdown-toggle dropdown-icon' data-toggle='dropdown'>
                                                    Eleija...
                                                </button>
                                                <span class='sr-only'>Acciones</span>
                                                <div class='dropdown-menu' role='menu'>
                                                    {{if $item.id != null}}
                                                        <a href="collections/read/{{$item.id}}"
                                                            class="dropdown-item btn btn-primary">
                                                            <i class="fas fa-eye mr-3"></i>Detalles
                                                        </a>
                                                        <a href="collections/compare/{{$item.store_id}}"
                                                            class="dropdown-item btn btn-primary">
                                                            <i class="fas fa-copy mr-3"></i>Comparar
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a href="collections/edit/{{$item.id}}"
                                                            class="dropdown-item btn btn-warning">
                                                            <i class="fas fa-edit mr-3"></i>Editar Local
                                                        </a>
                                                        <a href="collections/delete/{{$item.id}}"
                                                            class="dropdown-item btn btn-danger">
                                                            <i class="fas fa-trash mr-3"></i>Borrar Local
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                    {{/if}}
                                                    <a href="collections/edit/{{$item.store_id}}"
                                                        class="dropdown-item btn btn-warning">
                                                        <i class="fas fa-edit mr-3"></i>Editar Local
                                                    </a>
                                                    <a href="collections/delete/{{$item.store_id}}"
                                                        class="dropdown-item btn btn-danger">
                                                        <i class="fas fa-trash mr-3"></i>Borrar Local
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                {{/foreach}}
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{if $data.content.datos.max_page > 0}}
                            <nav aria-label="Page navigation" id="collections_pagination">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item">Paginas <b><span
                                                id="collections_pagination_pages">{{$data.content.datos.max_page}}</span></b>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="{{$data.content.datos.prev_page}}"
                                            id="collections_pagination_prev">
                                            Previo
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="{{$data.content.datos.next_page}}"
                                            id="collections_pagination_next">
                                            Siguiente
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        {{/if}}
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    {{* {'_':"date.display",'sort':"date.timestamp"} *}}
    <script>
        var collectionsTable = tabler('collectionsList', 10);
    </script>
{{/block}}
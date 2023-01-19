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
                                            <a href="collections/read/{{$item.store.store_id}}">
                                                {{$item.store.store_id}}
                                            </a>
                                        </td>
                                        <td class="text-center">{{$item.local.date}}</td>
                                        <td class="text-center">
                                            <a href="collections/read/{{$item.local.id}}">
                                                {{$item.local.name}}
                                            </a>
                                        </td>
                                        <td class="text-center">{{$item.store.store_title}}</td>
                                        <td class="text-center">{{$item.store.store_handle}}</td>
                                        <td class="text-center">{{$item.local.category}}</td>
                                        <td class="text-center">{{$item.local.sub_category}}</td>
                                        <td class="text-center">
                                            {{if $item.local.active == 0}}Inactivo{{else}}Activo{{/if}}
                                        </td>
                                        <td class="text-center">{{$item.local.possition}}</td>
                                        <td class="text-center">
                                            <div class='btn-group'>
                                                <button type='button' class='btn btn-outline-info dropdown-toggle dropdown-icon'
                                                    data-toggle='dropdown'>
                                                    Eleija...
                                                </button>
                                                <span class='sr-only'>Acciones</span>
                                                <div class='dropdown-menu' role='menu'>
                                                    <a href="collections/read/{{$item.local.id}}"
                                                        class="dropdown-item btn btn-primary">
                                                        <i class="fas fa-eye mr-3"></i>
                                                        Detalles
                                                    </a>
                                                    <a href="collections/compare/{{$item.local.id}}"
                                                        class="dropdown-item btn btn-primary">
                                                        <i class="fas fa-eye mr-3"></i>
                                                        Comparar
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a href="collections/edit/{{$item.local.id}}"
                                                        class="dropdown-item btn btn-warning">
                                                        <i class="fas fa-edit mr-3"></i>
                                                        Editar Local
                                                    </a>
                                                    <a href="collections/delete/{{$item.local.id}}"
                                                        class="dropdown-item btn btn-danger">
                                                        <i class="fas fa-trash mr-3"></i>
                                                        Borrar Local
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a href="collections/edit/{{$item.store.store_id}}"
                                                        class="dropdown-item btn btn-warning">
                                                        <i class="fas fa-edit mr-3"></i>
                                                        Editar Local
                                                    </a>
                                                    <a href="collections/delete/{{$item.store.store_id}}"
                                                        class="dropdown-item btn btn-danger">
                                                        <i class="fas fa-trash mr-3"></i>
                                                        Borrar Local
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
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item {{if $data.content.pre_page <= 1}}disabled{{/if}}">
                                    <a class="page-link" href="/collections/read/previous">
                                        Previo
                                    </a>
                                </li>
                                <li class="page-item {{if $data.content.next_page < 1}}disabled{{/if}}">
                                    <a class="page-link" href="/collections/read/next">
                                        Siguiente
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{*var_dump($data.content)*}}
{{/block}}
{{block name='css'}}
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables-bs4/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables-responsive/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables-buttons/buttons.bootstrap4.min.css">
{{/block}}
{{block name="jslibs"}}
    <script type="text/javascript" src="/assets/js/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/assets/js/datatables-bs4/dataTables.bootstrap4.min.js"></script>
    {{* <script type="text/javascript" src="/assets/js/datatables-responsive/dataTables.responsive.min.js"></script> *}}
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
    <script>
        /* let currentTime = 0,
            currentDate;
        let tiempo = new Date();
        currentDate = moment().format('DD/MM/YYYY') */
        $(document).ready(function() {
            //$.fn.dataTable.ext.errMode = 'none';
            var collectionsTable = tabler('collectionsList', 10);
        });
    </script>
{{/block}}
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
                        <nav aria-label="Page navigation" id="collections_pagination">
                            <ul class="pagination justify-content-end">
                                <li class="page-item">Paginas <b><span id="collections_pagination_pages"></span></b></li>
                                <li class="page-item" id="collections_pagination_prev">
                                    <a class="page-link" href="">
                                        Previo
                                    </a>
                                </li>
                                <li class="page-item" id="collections_pagination_next">
                                    <a class="page-link" href="">
                                        Siguiente
                                    </a>
                                </li>
                            </ul>
                        </nav>
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
                                {{*foreach $data.content.datos as $item}}
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

                                </td>
                            </tr>
                            {{/foreach*}}
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <nav aria-label="Page navigation" id="collections_pagination">
                            <ul class="pagination justify-content-center">
                                <li class="page-item" id="collections_pagination_prev">
                                    <a class="page-link" href="">
                                        Previo
                                    </a>
                                </li>
                                <li class="page-item" id="collections_pagination_next">
                                    <a class="page-link" href="">
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
    <pre>
        {{*var_dump($data.content)*}}
    </pre>
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
    <script type="text/javascript" src="/assets/js/customs/collectionsList.js"></script>
{{/block}}
{{block name="scripts"}}
    {{* {'_':"date.display",'sort':"date.timestamp"} *}}
    <script>
        //var collectionsTable = loadTable('collections/read/lista','collectionsList', 10);
    </script>
{{/block}}
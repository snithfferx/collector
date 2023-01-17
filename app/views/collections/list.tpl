{{extends file=_VIEW_|cat:"_shared/_layout.tpl"}}
{{* /**
     * @author Snithfferx <snithfferx@outlook.com>
     * @version 1.2.0
     * 23/06/22
    */
*}}
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
                                {{*foreach $data.content as $item}}
                                    <tr>
<td class="text-center"><a href="collections/read/{{$item.store.store_id}}">{{$item.store.store_id}}</a></td>
                                    <td class="text-center">{{$item.fecha}}</td>
                                    <td class="text-center">{{$item.local.name}}</td>
<td class="text-center">{{$item.store.store_title}}</td>
<td class="text-center">{{$item.store.store_handle}}</td>
                                            <td class="text-center">{{$item.local.category}}</td>
                                            <td class="text-center">{{$item.local.type}}</td>
                                            <td class="text-center">
                                            {{if $item.local.active == 0}}Falso{{else}}Verdadero{{/if}}
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
                                                    <a href="collections/read/{{$item.store.id}}"
                                                        class="dropdown-item btn btn-primary">
                                                        <i class="fas fa-eye mr-3"></i>
                                                        Detalles
                                                    </a>
                                                    <a href="collections/edit/{{$item.store.id}}"
                                                        class="dropdown-item btn btn-warning">
                                                        <i class="fas fa-edit mr-3"></i>
                                                        Editar
                                                    </a>
                                                    <a href="collections/delete/{{$item.store.id}}"
                                                        class="dropdown-item btn btn-danger">
                                                        <i class="fas fa-trash mr-3"></i>
                                                        Borrar
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                {{/foreach*}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{var_dump($data)}}
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
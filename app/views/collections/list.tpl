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
                        <div class="col-6">
                            <table class="table table-responsive table-striped" id="collectionsList">
                                <thead class="head-dark">
                                    <tr>
                                        <th class="uppercase text-center">id</th>
                                        <th class="uppercase text-center">handle</th>
                                        <th class="uppercase text-center">title</th>
                                        <th>
                                        <th class="uppercase text-center">Fecha</th>
                                        <th class="uppercase text-center">Nombre</th>
                                        <th class="uppercase text-center">Categoría</th>
                                        <th class="uppercase text-center">Tipo</th>
                                        <th class="uppercase text-center">Activo</th>
                                        <th class="uppercase text-center">Posición</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{foreach $data.content as $item}}
                                        <tr>
                                            <td colspan="3">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{$item.store.id}}</td>
                                                            <td>{{$item.store.title}}</td>
                                                            <td>{{$item.store.handle}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td colspan="6">
                                                <table>
                                                    <tbody>
                                                        {{if empty($item.local) || is_null($item.local)}}
                                                            <tr>
                                                                <td colspan="6" class="text-center">- Sin Datos -</td>
                                                            </tr>
                                                        {{else}}
                                                            <tr>
                                                                <td>{{$item.local.fecha}}</td>
                                                                <td>{{$item.local.name}}</td>
                                                                <td>{{$item.local.category}}</td>
                                                                <td>{{$item.local.type}}</td>
                                                                <td>{{if $item.local.active == 0}}Falso{{else}}Verdadero{{/if}}</td>
                                                                <td>{{$item.local.possition}}</td>
                                                            </tr>
                                                        {{/if}}
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    {{/foreach}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{/block}}
{{block name="title"}}
    {{$data.content.datos.title}}
{{/block}}
{{block name='css'}}
    <link rel="stylesheet" type="text/css" href="/assets/css/datatables-buttons/buttons.bootstrap4.min.css">
{{/block}}
{{block name="jslibs"}}
    <script src="assets/js/datatables-buttons/dataTables.buttons.min.js"></script>
    <script src="assets/js/datatables-buttons/buttons.bootstrap4.min.js"></script>
    <script src="assets/js/jszip/jszip.min.js"></script>
    <script src="assets/js/pdfmake/pdfmake.min.js"></script>
    <script src="assets/js/pdfmake/vfs_fonts.js"></script>
    <script src="assets/js/datatables-buttons/js/buttons.flash.min.js"></script>
    <script src="assets/js/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="assets/js/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="assets/js/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="assets/js/moment/moment-with-locales.min.js"></script>
{{/block}}
{{block name="scripts"}}
    <script>
        let currentTime = 0,
            currentDate;
        let tiempo = new Date();
        currentDate = moment().format('DD/MM/YYYY')
        $(document).ready(function() {
            $.fn.dataTable.ext.errMode = 'none';
            var activityTable = tabler('#collectionsList');
        });0
    </script>
{{/block}}
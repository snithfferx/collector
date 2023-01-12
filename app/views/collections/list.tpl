{{extends file=_VIEW_|cat:"_shared/_layout.tpl"}}
{{* /**
     * @author Snithfferx <snithfferx@outlook.com>
     * @version 2.5
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
                        <table>
                            <thead>
                                <tr>
                                    <th class="uppercase text-center">id</th>
                                    <th class="uppercase text-center">handle</th>
                                    <th class="uppercase text-center">title</th>
                                </tr>
                            </thead>
                            <tbody>
                            {{foreach $data.content as $item}}
                                <tr>
                                    <td>{{$item.id}}</td>
                                    <td>{{$item.title}}</td>
                                    <td>{{$item.handle}}</td>
                                </tr>
                            {{/foreach}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{/block}}
{{extends file=_VIEW_|cat:"_shared/_layout.tpl"}}
{{* /**
     * @author Jorge Echeverria <jecheverria@bytes4run.com>
     * @version 1.0.0.0
     * 11/01/23
    */
*}}
{{block name="mainContent"}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">Creaci&oacute;n Nueva Actividad</h5>
                    </div>
                    <div class="card-body">
                        {{var_dump($data)}}
                    </div>
                </div>
            </div>
        </div>
    </div>
{{/block}}
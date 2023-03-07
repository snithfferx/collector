//$.post('http://10.0.10.35/Core/Select/FindNotUpdatedProducts.php', {}, function (show) { $("#spannotupdated").text(show); }); $.post('http://10.0.10.35/Core/Select/FindWhichCampaignisToday.php', {}, function (show) { $("#spancampaign").text(show); }); $.post('orders/count/open', {}, function (show) { var jason = JSON.parse(show); $("#lastOrders").text(jason.data); });
function alertaPopUp(data,type = null) {
    if (data != undefined) {
        let titulo, ocultar, retraso, clase, subtitulo, icono, cuerpo
        if (data.title != undefined) { titulo = data.title; } else { titulo = "Alerta..!"; }
        if (data.subtitle != undefined) { subtitulo = data.subtitle; } else { subtitulo = ""; }
        if (data.autohide != undefined) { ocultar = data.autohide; } else { ocultar = true; }
        if (data.delay != undefined) { retraso = data.delay; } else { retraso = 5500; }
        if (data.icon != undefined) { icono = data.icon; } else { icono = ""; }
        if (data.body != undefined) { cuerpo = data.body; } else { cuerpo = ""; }
        if (type != null || type != undefined) {
            if (type == "error") {
                clase = "bg-danger";
                toastr['error'](`
                    <div role="alert" aria-live="assertive" aria-atomic="true" class="${clase}">
                        <div class="toast-header">
                            <strong class="mr-auto">${titulo}</strong>
                            <small>${subtitulo}</small>
                        </div>
                        <div class="toast-body">
                            ${cuerpo}
                        </div>
                    </div>`)
            } else if (type == "warning") {
                clase = "bg-warning";
                toastr['warning'](`
                    <div role="alert" aria-live="assertive" aria-atomic="true" class="${clase}">
                        <div class="toast-header">
                            <strong class="mr-auto">${titulo}</strong>
                            <small>${subtitulo}</small>
                        </div>
                        <div class="toast-body">
                            ${cuerpo}
                        </div>
                    </div>`)
            } else if (type == "success") {
                clase = "bg-success";
                toastr['success'](`
                    <div role="alert" aria-live="assertive" aria-atomic="true" class="${clase}">
                        <div class="toast-header">
                            <strong class="mr-auto">${titulo}</strong>
                            <small>${subtitulo}</small>
                        </div>
                        <div class="toast-body">
                            ${cuerpo}
                        </div>
                    </div>`)
            } else if (type == "alert") {
                clase = "bg-info";
                toastr['warning'](`
                    <div role="alert" aria-live="assertive" aria-atomic="true" class="${clase}">
                        <div class="toast-header">
                            <strong class="mr-auto">${titulo}</strong>
                            <small>${subtitulo}</small>
                        </div>
                        <div class="toast-body">
                            ${cuerpo}
                        </div>
                    </div>`)
            } else {
                clase = "bg-primary";
                toastr['info'](`
                    <div role="alert" aria-live="assertive" aria-atomic="true" class="${clase}">
                        <div class="toast-header">
                            <strong class="mr-auto">${titulo}</strong>
                            <small>${subtitulo}</small>
                        </div>
                        <div class="toast-body">
                            ${cuerpo}
                        </div>
                    </div>`)
            }
        } else if (data.class != null || data.class != "") {
            type = data.type;
            clase = data.class;
        } else {
            type = 'info'
            clase = "bg-alert";
        }
        /* $(document).toast(`
            <div role="alert" aria-live="assertive" aria-atomic="true" class="toast">
                <div class="toast-header">
                    <img src="..." class="rounded mr-2" alt="...">
                    <strong class="mr-auto">Bootstrap</strong>
                    <small>11 mins ago</small>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                </div>
            </div>`, {
            class    : clase,
            title    : titulo,
            subtitle : subtitulo,
            autohide : ocultar,
            delay    : retraso,
            body     : cuerpo,
            icon     : icono,
        }); */
        
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": retraso,
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "show",
            "hideMethod": "fadeOut"
        }
    } else {
        console.log(data);
    }
}

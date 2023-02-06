//$.post('http://10.0.10.35/Core/Select/FindNotUpdatedProducts.php', {}, function (show) { $("#spannotupdated").text(show); }); $.post('http://10.0.10.35/Core/Select/FindWhichCampaignisToday.php', {}, function (show) { $("#spancampaign").text(show); }); $.post('orders/count/open', {}, function (show) { var jason = JSON.parse(show); $("#lastOrders").text(jason.data); });
function alertaPopUp(data,type = null) {
    if (data != undefined) {
        let titulo, ocultar, retraso, clase, subtitulo, icono, cuerpo
        if (data.title != undefined) { titulo = data.title; } else { titulo = "Alerta..!"; }
        if (data.subtitle != undefined) { subtitulo = data.subtitle; } else { subtitulo = "Error en ejecución"; }
        if (data.autohide != undefined) { ocultar = data.autohide; } else { ocultar = true; }
        if (data.delay != undefined) { retraso = data.delay; } else { retraso = 5500; }
        if (data.icon != undefined) { icono = data.icon; } else { icono = ""; }
        if (data.body != undefined) { cuerpo = data.body; } else { cuerpo = ""; }
        if (type != null || type != undefined) {
            if (type == "error") {
                clase = "bg-danger";
            } else if (type == "warning") {
                clase = "bg-warning";
            } else if (type == "success") {
                clase = "bg-success";
            } else if (type == "alert") {
                clase = "bg-info";
            } else {
                clase = "bg-primary";
            }
        } else if (data.class != null || data.class != "") {
            clase = data.class;
        } else {
            clase = "bg-alert";
        }
        $(document).Toasts('create', {
            class    : clase,
            title    : titulo,
            subtitle : subtitulo,
            autohide : ocultar,
            delay    : retraso,
            body     : cuerpo,
            icon     : icono,
        });
    } else {
        console.log(data);
    }
}
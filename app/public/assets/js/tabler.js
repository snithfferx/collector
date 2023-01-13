function tabler(elemento,lineas = 5,sort=[0,'asc'],ordenado=true, botones = true, alto = "Auto", ancho = true) {
    let lin = [], b = lineas, btns = [];
    if (typeof botones == "array") {
        for (let j = 0; j < botones.length; j++) {
            if (botones[j] == "copiar") {
                btns.push({extend:'copy',text:'Copiar'});
            } else {
                if (botones[j] == "imprimir") {
                    btns.push({extend:'print',text:'Imprimir'});
                } else {
                    if (botones[j] == "columnas") {
                        btns.push({extend:'colvis',text:'Columnas'});
                    } else {
                        if (botones[j] == "cols") {
                            btns.push({extend:'colvis',text:'Columnas'});
                        } else {
                            if (botones[j] == "colvis") {
                                btns.push({extend:'colvis',text:'Columnas'});
                            } else {
                                if (botones[j] == "copy") {
                                    btns.push({extend:'copy',text:'Copiar'});
                                } else {
                                    if (botones[j] == "print") {
                                        btns.push({extend:'print',text:'Imprimir'});
                                    } else {
                                        if (botones[j] == "pdf") {
                                            btns.push({extend: 'pdf',text: 'PDF',exportOptions: {columns: ':visible'}});
                                        } else {
                                            btns.push(botones[j]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    } else {
        if (botones === true) {
            btns = [
                {extend:'copy',text:'Copiar'},
                "csv",
                "excel",
                {
                    extend: 'pdf',
                    text: 'PDF',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {extend:'print',text:'Imprimir'},
                {extend:'colvis',text:'Columnas'}];
        }
    }
    for (let a = 1; a < 6; a++) {
        if (a == 1) {
            b = b * a;
        } else {
            b = b * 2;
        }
        lin.push(b);
    }
    $('#'+elemento).DataTable({
        "paging"     : true,
        "scrollY"    : alto,
        "lengthMenu" : lin,
        "searching"  : true,
        "ordering"   : ordenado,
        "order"      : [sort],
        "info"       : true,
        "autoWidth"  : ancho,
        "responsive" : true,
        "language"   : {
            "emptyTable"   : "No hay registros en la tabla",
            "info"         : "Mostrando _START_ a _END_ de _TOTAL_ líneas",
            "infoEmpty"    : "No hay registros",
            "infoFiltered" : "(_MAX_ líneas en total)",
            "infoPostFix"  : "",
            "thousands"    : ",",
            "lengthMenu"   : "Mostrar: _MENU_ líneas",
            "loadingRecords" : "Cargando...",
            "processing"   : "Procesando...",
            "search"       : "Buscar:",
            "zeroRecords"  : "No se encontraron coincidencias.",
            "paginate"     : {
                "first"    : "Primero",
                "last"     : "Ultimo",
                "next"     : "Siguiente",
                "previous" : "Previo"
            },
            "aria"         : {
                "sortAscending"  :  ": activar para ordenar columna ascendentemente",
                "sortDescending" : ": activar para ordenar columna descendentemente"
            }
        },
        "buttons": btns
    }).buttons().container().appendTo('#'+elemento+'_wrapper .col-sm-12:eq(0)');
}
function tableRefill (elemento,datos,columnas) {
    $('#'+elemento).DataTable({
        retrieve : true,
        data     : datos,
        columns  : columnas
    });
}
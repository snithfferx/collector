
function botones(requested) {
    let btns = [];
    if (typeof requested == "array") {
        for (let j = 0; j < requested.length; j++) {
            if (requested[j] == "copiar") {
                btns.push({extend:'copy',text:'Copiar'});
            } else {
                if (requested[j] == "imprimir") {
                    btns.push({extend:'print',text:'Imprimir'});
                } else {
                    if (requested[j] == "columnas") {
                        btns.push({extend:'colvis',text:'Columnas'});
                    } else {
                        if (requested[j] == "cols") {
                            btns.push({extend:'colvis',text:'Columnas'});
                        } else {
                            if (requested[j] == "colvis") {
                                btns.push({extend:'colvis',text:'Columnas'});
                            } else {
                                if (requested[j] == "copy") {
                                    btns.push({extend:'copy',text:'Copiar'});
                                } else {
                                    if (requested[j] == "print") {
                                        btns.push({extend:'print',text:'Imprimir'});
                                    } else {
                                        if (requested[j] == "pdf") {
                                            btns.push({extend: 'pdf',text: 'PDF',exportOptions: {columns: ':visible'}});
                                        } else {
                                            btns.push(requested[j]);
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
        if (requested === true) {
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
    return btns;
}
function lineas(numero) {
    let lin = [], b = numero;
    for (let a = 1; a < 6; a++) {
        if (a == 1) {
            b = b * a;
        } else {
            b = b * 2;
        }
        lin.push(b);
    }
    return lin;
}
/**
 * Funcción que devuelve una tabla usando librería DataTables
 * 
 * @param {string} elemento identificador del elemento tabla a ser llenado
 * @param {int} lineas numero de líneas a ser mostradas
 * @param {boolean} paginado mostrar paginado
 * @param {int} columna columna por la que se ordena la tabla
 * @param {boolean} ordenado permite ordenar o no la tabla
 * @param {string} disposicion disposición de ordenamiento, predeterminado 'ASC' (ascendente)
 * @param {boolean} acciones la tabla tendrá botones de interacción
 * @param {string|int} alto alto de la tabla, permite definir el número de pixeles de altura
 */
function tabler(
    elemento,
    lines = 5,
    paginado = true,
    columna=0,
    ordenado = true,
    disposicion = 'asc',
    acciones = true,
    alto = "auto") {
    var lin = lineas(lines);
    var btns = botones(acciones);
    var sorting = [columna, disposicion];
    var table = $('#'+elemento).DataTable({
        "paging"     : paginado,
        "scrollY"    : alto,
        "lengthMenu" : lin,
        "searching"  : true,
        "ordering"   : ordenado,
        "order"      : [sorting],
        "info"       : true,
        "autoWidth"  : true,
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
    }).buttons().container().appendTo('#' + elemento + '_wrapper .col-sm-12:eq(0)');
    return table;
}
function tableRefill(
    elemento,
    datos,
    columnas,
    lines = 5,
    paginado = true,
    columna=0,
    ordenado = true,
    disposicion = 'asc',
    acciones = true,
    alto = "auto") {
    var lin = lineas(lines);
    var btns = botones(acciones);
    var sorting = [columna, disposicion];
    var table = $('#' + elemento).DataTable({
        "retrieve"   : true,
        "data"       : datos,
        "columns"    : columnas,
        "paging"     : paginado,
        "scrollY"    : alto,
        "lengthMenu" : lin,
        "searching"  : true,
        "ordering"   : ordenado,
        "order"      : [sorting],
        "info"       : true,
        "autoWidth"  : true,
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
    }).buttons().container().appendTo('#' + elemento + '_wrapper .col-sm-12:eq(0)');
    console.log(table);
    return table;
}
/**
 * 
 * @param {string} origin dirección de origen de datos
 * @param {Array} columnas columnas a ser mostradas en la tabla
 * @param {string} elemento elemento tabla donde se mostrarán los datos
 * @param {int} lineas número de filas a ser mostradas en la tabla
 * @param {boolean} paginado se requiere paginado o no
 * @param {int} filtro columna por la que se ordenarán los datos en la tabla 
 * @param {boolean} ordenado se requiere que los datos se presenten ordenados o no
 * @param {string} disposicion disposición de ordenamiento de los datos
 * @param {boolean|Array} acciones se requiere mostrar botones, o lista de botones a mostrarse
 * @param {string|int} alto alto en pixeles
 * @returns 
 */
function loadTable(
    origin,
    columnas,
    elemento,
    lineas = 5,
    paginado = true,
    filtro=0,
    ordenado = true,
    disposicion = 'asc',
    acciones = true,
    alto = "auto") {
    var lin = lineas(lineas);
    var btns = botones(acciones);
    var sorting = [filtro, disposicion];
    var table = $('#' + elemento).DataTable({
        ajax: {
            url: origin,
            dataSrc: function (r) {
                return r.lista
            }
        },
        "columns"    : columnas,
        "paging"     : paginado,
        "scrollY"    : alto,
        "lengthMenu" : lin,
        "searching"  : true,
        "ordering"   : ordenado,
        "order"      : [sorting],
        "info"       : true,
        "autoWidth"  : true,
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
    }).buttons().container().appendTo('#' + elemento + '_wrapper .col-sm-12:eq(0)');
    return table;
}
/* function tableRefiller(elemento,rows) {
    var tabla = $("#" + elemento).Datatable();
    return tabla    
        .clear()
        .rows.add(rows)
        .draw();
} */
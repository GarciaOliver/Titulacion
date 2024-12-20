function listarIdiomas() {
    let dataTableOpciones = {
        lengthMenu: [5, 10, 15, 20],
        pageLength: 5,
        language: {
            lengthMenu: "Mostrar _MENU_ registros por página",
            zeroRecords: "Ningún usuario encontrado",
            info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
            infoEmpty: "Ningún usuario encontrado",
            infoFiltered: "(filtrados desde _MAX_ registros totales)",
            search: "Buscar:",
            loadingRecords: "Cargando...",
            paginate: {
                first: "Primero",
                last: "Último",
                next: ">",
                previous: "<"
            }
        },
        ajax: {
            "method": "POST",
            "url": "../ajax/idioma.php?op=listarIdiomas"
        },
        columnDefs: [
            { orderable: false, targets: [] },
            { searchable: false, targets: [] },
            { width: "50%", targets: [] },
            { width: "10%", targets: [] },
            { width: "20%", targets: [] }
        ],
        columns: [
            { 
                "data": null, 
                "render": function (data, type, row, meta) {
                    return meta.row + 1;
                },
            },
            { "data": "idio_idioma" },
            { "data": "idio_dependenciaD" },
            {
                "data": "cat_id", "render": function (data) {
                    return data == 1 ? 'Activo' : 'Inactivo';
                }
            },
            {
                "data": "idio_id", "render": function (data) {
                    return `<button id="${data}" class="btn btn-primary btn-sm">Editar</button>`
                }
            }
        ]
    };

    window.tablaIdiomas = $('#tablaIdiomas').DataTable(dataTableOpciones);
}

function recargarTabla() {
    if (window.tablaIdiomas) {
        window.tablaIdiomas.ajax.reload(null, false);
    }
}

function vistaAgregarIdioma(valor){
    if(valor==true){
        $.ajax({
            type: "POST",
            url: "../ajax/idioma.php?op=selectIdiomas",
            success: function (datos) {
                $("#dependencias").html(datos);
            }
        });
        $("#tablaIdiomas").hide();
        $("#agregarIdioma").hide();
        $("#datosAgregarIdioma").show();
    }else{
        $("#datosAgregarIdioma").hide();
        $("#agregarIdioma").show();
        $("#tablaIdiomas").show();
    }
}

$(document).ready(function () {
    listarIdiomas();
});
function listarTabla() {
    let dataTableOpciones = {
        lengthMenu: [5, 10, 20, 40, 80, 100],
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
            "url": "../ajax/asignaciones.php?op=asignacionesEstudiante"
        },
        columnDefs: [
            { orderable: false, targets: [4] },
            { searchable: false, targets: [4] },
            { width: "30%", targets: [0] },
            { width: "30%", targets: [1] },
            { width: "10%", targets: [2,3,4] }
        ],
        columns: [
            { "data": "usu_nombre" },
            { "data": "asig_fecha" },
            {"data": "idio_idioma"},
            {"data": "cat_estado"},
            {"data": "asig_id", "render": function (data) {
                    return `<button class="btn btn-primary" onclick="datosRevision(true, ${data})">Abrir</button>`;
                }
            }
        ]
    };

    window.tablaDocentes = $('#tablaAsignaciones').DataTable(dataTableOpciones);
}

function datosRevision(valor, asig_id){
    if(valor==true){
        $.ajax({
            type: "POST",
            url: "../ajax/asignaciones.php?op=datosRevisionEst",
            data: { asig_id: asig_id },
            success: function (datos) {
                $("#datosRevision").html(datos);
            }
        });
        $('#datosRevision').show();
        $('#datatablaAsignaciones').hide();
    }else{
        location.reload();
    }
}

$(document).ready(function () {
    listarTabla();
});
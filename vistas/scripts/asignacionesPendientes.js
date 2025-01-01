function listarDocentesIngresados() {
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
            "url": "../ajax/asignaciones.php?op=asignacionesPendientes"
        },
        columnDefs: [
            { orderable: false, targets: [3] },
            { searchable: false, targets: [3] },
            { width: "50%", targets: [0] },
            { width: "10%", targets: [3] },
            { width: "20%", targets: [1,2] }
        ],
        columns: [
            { "data": "est_nombre" },
            { "data": "asig_fecha" },
            {"data": "cat_estado"},
            {"data": "res_id", "render": function (data) {
                    return `<button class="btn btn-primary" onclick="datosResumen(${data})">Calificar</button>`;
                }
            }
        ]
    };

    window.tablaDocentes = $('#tablaAsignaciones').DataTable(dataTableOpciones);
}

function recargarTabla() {
    if (window.tablaAsignaciones) {
        window.tablaAsignaciones.ajax.reload(null, false);
    }
}

function mostrarDatos(valor){
    if (valor == true){
        $("#datosResumen").show();
        $("#datatablaAsignaciones").hide();
    }else{
        $("#datosResumen").hide();
        $("#datatablaAsignaciones").show();
    }
}

function vistaObservaciones(){
    $("#datos").hide();
    $("#botones").hide();
    $("#observaciones").show();
}

function datosResumen(res_id){
    $.post("../ajax/asignaciones.php?op=datosResumen",
        {res_id: res_id},
        function (data) {
            mostrarDatos(true);
            $("#datosResumen").html(data);
            $("#enviar").hide();
        }
    );
}

function enviarCalificacion(valor, asig_id){
    if(valor==true){
        $.post("../ajax/asignaciones.php?op=enviarCalificacion",
            {asig_id: asig_id,
                calificacion: 'Aprobado',
                observaciones: 'Aprobado'
            },
            function (data) {
                if(data==true){
                    alert("Calificación enviada exitosamente");
                }else{
                    alert("Error en el envío de la calificación");
                }
                location.reload();
            }
        );
    }else{
        let observaciones=$("#txtObservaciones").val();
        $.post("../ajax/asignaciones.php?op=enviarCalificacion",
            {asig_id: asig_id,
                calificacion: 'Rechazado',
                observaciones: observaciones
            },
            function (data) {
                if(data==true){
                    alert("Calificación enviada exitosamente");
                }else{
                    alert("Error en el envío de la calificación");
                }
                location.reload();
            }
        );
    }
}

$(document).ready(function () {
    listarDocentesIngresados();
});
function listarDocentesIngresados() {
    let dataTableOpciones = {
        lengthMenu: [5, 10, 20, 40, 80],
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
            "url": "../ajax/docentes.php?op=listarDocentesIngresados"
        },
        columnDefs: [
            { orderable: false, targets: [3] },
            { searchable: false, targets: [3] },
            { width: "50%", targets: [0] },
            { width: "10%", targets: [2] },
            { width: "20%", targets: [1] }
        ],
        columns: [
            { "data": "usu_nombre" },
            { "data": "usu_cedula" },
            {
                "data": "doc_permiso", "render": function (data) {
                    return data == 1 ? 'Admin' : 'Docente';
                }
            },
            {
                "data": "usu_id", "render": function (data) {
                    return `<button type="button" onClick="mostrarDatos(true, ${data})" class="btn btn-primary">Editar</button>`;
                }
            }
        ]
    };

    window.tablaDocentes = $('#tablaDocentes').DataTable(dataTableOpciones);
}

function recargarTabla() {
    if (window.tablaDocentes) {
        window.tablaDocentes.ajax.reload(null, false);
    }
}

function mostrarDatos(valor, usu_id) {
    if (valor == true) {
        $.ajax({
            type: "POST",
            url: "../ajax/docentes.php?op=buscarDocente",
            data: { usu_id: usu_id },
            success: function (datos) {
                $("#datosDocente").html(datos);
            }
        });
        $("#datatablaDocentes").hide();
        $("#datosDocente").show();
    } else {
        $("#datosDocente").hide();
        $("#datatablaDocentes").show();
    }
}

function editarDocente(usu_id) {
    let permiso = $('#permiso').val();
    $.post("../ajax/docentes.php?op=editarDocente",
        { usu_id: usu_id, doc_permiso: permiso },
        function (data) {
            if (data == true) {
                
                recargarTabla();
                mostrarDatos(false, 0);
                //bootbox.alert("Docente editado correctamente");
                alert("Docente editado correctamente");
            } else {
                alert("Error al editar docente: " + data);
            }
        }
    );
}

$(document).ready(function () {
    listarDocentesIngresados();
});

function documentosEnviados(){
    
    let dataTableOpciones={
        lengthMenu: [5, 10],
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
        ajax:{
            method:"POST",
            url:"../ajax/drive.php?op=listarArchivos",
            dataSrc: 'data'
        },
        columnDefs: [
            {orderable: false, targets: [1]},
            {searchable: false, targets: [1]},
            {width: "20%", targets: [1,2]}
        ],
        columns: [
            {data: "rev_archivo"},
            {data: "rev_archivo", render: function(data){
                return `<button onClick="descargarDocumento('`+data+`')">Descargar</button>`;
            }},
            {data: "rev_archivo", render: function(data){
                return `<button onClick="eliminarDocumento('`+data+`')">Eliminar</button>`;
            }}
        ]
    };

    //Se cargan las opciones de la tabla
    $('#documentosEnviadosTabla').DataTable(dataTableOpciones);
}

function comboIdiomas(){
    $.ajax({
        type: "POST",
        url: "../ajax/idioma.php?op=selectIdiomas",
        success: function (datos) {
            $("#idioma").html(datos);
           }
    });
}

function nombreTemporal(){
    let valor=$('#idioma option:selected').text()
    $.ajax({
        type: "POST",
        url: "../ajax/asignaciones.php?op=nombreArchivo",
        data: { idioma: valor},
        success: function (datos) {
            $("#nombreArchivo").val(datos);
           }
    });
}

function enviarDocumento(){
    let parametros = new FormData($("#formulario")[0]);
    parametros.append('idioma', $('#idioma').val());
    parametros.append('nombreArchivo', $('#nombreArchivo').val());

    $.ajax({
        type: "POST",
        url: "../ajax/drive.php?op=subirArchivo",
        data: parametros,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $("#btnGuardar").val("Enviando...");
            $("#btnGuardar").prop('disabled', true);
            
        },
        success: function (datos) {
            $("#btnGuardar").prop('disabled', false);
            alert(datos);
            location.reload();
        },
        error: function (xhr, status, error) {
            console.error("Error:", error); // Mostrar errores en la consola
            $("#btnGuardar").prop('disabled', false);
        }
    });
}

function eliminarDocumento(nombreArchivo){
    $.ajax({
        type: "POST",
        url: "../ajax/drive.php?op=eliminarArchivo",
        data: {nombreArchivo: nombreArchivo},
        success: function (datos) {
            alert(datos);
            location.reload();
        }
    });
}

function descargarDocumento(nombreArchivo){
    window.location.href = '../ajax/drive.php?op=descargarArchivo&nombreArchivo=' + encodeURIComponent(nombreArchivo);
}

$(document).ready(function () {
    comboIdiomas();
    documentosEnviados();
});
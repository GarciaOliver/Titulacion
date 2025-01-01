function listarDocentesTodos(){
    let dataTableOpciones={
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
        ajax:{
            "method":"POST",
            "url":"../ajax/docentes.php?op=listarDocentesTodos"
        },
        columnDefs: [
            {orderable: false, targets: [2,3]},
            {searchable: false, targets: [3]},
            {width: "30%", targets: [0]},
            {width: "10%", targets: [1,2,3]}
        ],
        //Datos de la tabla con resultado del ajax, se recibe en JSON
        columns:[
            {"data":"usu_nombre"},
            {"data":"usu_cedula"},
            {"data":"usu_telefono"},
            //Botón para añadir docente
            {"data":"usu_id","render":function(data){
                return '<button type="button" class="btn btn-primary" onClick=añadirDocente('+data+')>Añadir</button>'
            }},
        ]
    };

    //Se cargan las opciones de la tabla
    $('#tablaDocentes').DataTable(dataTableOpciones);
}

function recargarTabla() {
    if (window.tablaDocentes) {
        window.tablaDocentes.ajax.reload(null, false);
    }
}

function añadirDocente(data){
    $.ajax({
        type:"POST",
        url:"../ajax/docentes.php?op=agregarDocente",
        data:{usu_id:data},
        success: function(datos) {
            if(datos==true){
                alert("Servicio registrado exitosamente");
                location.reload();
            }else{
                alert("Error en el registro de servicio");
            }
        }
    });

}

$(document).ready(function(){
    listarDocentesTodos()
});
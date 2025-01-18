function comboIdiomas(){
    
    $.ajax({
        type: "POST",
        url: "../ajax/idioma.php?op=selectIdiomas",
        success: function (datos) {
            $("#idioma").html(datos);
           }
    });
}

function enviarDocumento(){
    let valor=$("#idioma").val();
    let archivo=$("#archivo").val();

    alert("Idioma: " + valor + " Archivo:  " + archivo);
}

$(document).ready(function () {
    comboIdiomas();
});
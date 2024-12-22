function comboIdiomas(){
    
    $.ajax({
        type: "POST",
        url: "../ajax/idioma.php?op=selectIdiomas",
        success: function (datos) {
            $("#idioma").html(datos);
           }
    });
}

function mostrarVistaResumen(){
    let valor=$("#idioma").val();
    $('#palabras').val("");
    $('#resumen').val("");

    if(valor!=0){
        $("#datosResumen").show();
    }else{
        $("#datosResumen").hide();
    }
}

function contarPalabras(){
    let texto = $('#resumen').val();
    let palabras = texto.trim().split(/\s+/); 
    let totalPalabras = palabras[0] === "" ? 0 : palabras.length;

    if (totalPalabras > 250) {
        $("#alertaIdioma").show();
        return false;
    }else{
        $("#alertaIdioma").hide();
        return true;
    }
}

function guardarResumen(){
    let resumen=$('#resumen').val();
    let palabras= $('#palabras').val();

    if(resumen && palabras){
        if(contarPalabras){
            resumen=resumen.trim();
            palabras=palabras.trim();
            let idioma=$('#idioma').val();

            $.ajax({
                type: "POST",
                data: {resumen: resumen,
                    palabras: palabras,
                    idioma: idioma
                },
                url: "../ajax/estudiantes.php?op=guardarResumen",
                success: function (datos) {
                    if(datos==true){
                        alert("Resumen Enviado Correctamente");
                    }else{
                        alert("Error en el Envio de Datos");
                    }
                   }
            });
        }
    }else{
        alert("Ingrese todos lo campos");
    }

}

$(document).ready(function () {
    comboIdiomas();
});
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
    valor=parseInt(valor);
    $('#palabras').val("");
    $('#resumen').val("");

    if(valor==0){
        $("#resumen").prop('disabled', true);
        $("#palabras").prop('disabled', true);
        $("#guardar").prop('disabled', true);
    }

    if(valor!=0){
        
        $.ajax({
            type: "POST",
            url: "../ajax/estudiantes.php?op=resumenPendiente",
            data: { idioma: valor},
            success: function (datos) {
                if(datos==true){
                    $("#resumen").prop('disabled', true);
                    $("#palabras").prop('disabled', true);
                    $("#guardar").prop('disabled', true);
                }else{
                    $.ajax({
                        type: "POST",
                        url: "../ajax/estudiantes.php?op=verificacionDependencia",
                        data: { idioma: valor},
                        success: function (datos) {
                            if(datos!=true){
                                $("#resumen").prop('disabled', true);
                                $("#palabras").prop('disabled', true);
                                $("#guardar").prop('disabled', true);
                            }else{
                                $("#resumen").prop('disabled', false);
                                $("#palabras").prop('disabled', false);
                                $("#guardar").prop('disabled', false);
                            }
                        }
                    });
                }
            }
        });
    }
}

function contarPalabras(){
    let texto = $('#resumen').val();
    let palabras = texto.trim().split(/\s+/); 
    let totalPalabras = palabras[0] === "" ? 0 : palabras.length;

    let caracteresEspeciales = /[^\w\sáéíóúÁÉÍÓÚñÑ.,;:!?()¿¡-]/;

    if (caracteresEspeciales.test(texto)) {
        $("#textoAlerta").text("El resumen contiene caracteres especiales no permitidos");
        $("#alerta").show();
        $("#guardar").prop('disabled', true);
    }else if (totalPalabras > 250) {
        $("#textoAlerta").text("El resumen no puede tener más de 250 palabras");
        $("#alerta").show();
        $("#guardar").prop('disabled', true);
    }else if(totalPalabras < 150){
        $("#textoAlerta").text("El resumen no puede tener menos de 150 palabras");
        $("#alerta").show();
        $("#guardar").prop('disabled', true);
    }else{
        $("#alerta").hide();
        $("#guardar").prop('disabled', false);
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
                        location.reload();
                    }else{
                        alert("Error en el envio de Datos");
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
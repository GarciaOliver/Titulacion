<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['usu_nombre'])) {
  header("Location: login.html");
}else{

 
require 'header.php';

if ($_SESSION['Estudiantes']==1) {

?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="row">
            <div class="col-md-12">
                <h1 class="box-title mb-4">Subir Resúmenes</h1>
                <div class="box">
                    <div class="box-header with-border">
                        <table style="width: 100%; max-width: 1100px;">
                            <tr>
                                <td style="width: 20%; padding-top: 5px;">
                                    <label for="idioma" class="form-label">Elegir Idioma:</label>
                                    <select id="idioma" onchange="mostrarVistaResumen()" class="form-select"></select>
                                </td>
                                <td rowspan="2" style="padding-top: 5px;">
                                    <label for="palabras" class="form-label">Palabras clave:</label>
                                    <input id="palabras" type="text" class="form-control" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td rowspan="3" style="padding-top: 5px; padding-right: 10px; vertical-align: top;">
                                    <div class="alert alert-danger" role="alert" id="alerta" hidden>
                                        <h4 class="alert-heading">Alerta!</h4>
                                        <p id="textoAlerta">Error</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 5px;">
                                    <label for="resumen" class="form-label">Contenido del resumen:</label>
                                    <textarea oninput="contarPalabras()" class="form-control" id="resumen" rows="13" disabled></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 20px; padding-left: 10px;">
                                    <button id="guardar" type="button" class="btn btn-primary me-3" onclick="guardarResumen()" disabled>Guardar Resumen</button>
                                </td>
                            </tr>
                        </table>
                        
                    </div>
                    <!-- /.box-header -->

                    <!-- Botón en la parte inferior derecha -->
                    
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
</div>



<script src="scripts/subirResumen.js"></script>


<?php 
}

require 'footer.php';
 ?>

 <?php 
}

ob_end_flush();
?>
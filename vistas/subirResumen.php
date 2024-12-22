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
                        <div class="row">
                            <!-- Selector de idioma -->
                            <div class="col-md-3">
                                <div id="elegirIdioma" class="mb-2"> 
                                    <div class="mb-3">
                                        <label for="idioma" class="form-label">Elegir Idioma</label>
                                        <select id="idioma" onchange="mostrarVistaResumen()" class="form-select"></select>
                                    </div>
                                </div>

                                <div id="alertaIdioma" class="alert alert-danger d-flex align-items-center mt-2" role="alert" style="display: none;"> 
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                                        <use xlink:href="#exclamation-triangle-fill"></use>
                                    </svg>
                                    <div>
                                        Por favor, selecciona un idioma antes de continuar.
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contenido del resumen -->
                            <div class="col-md-9">
                                <div id="datosResumen" hidden>
                                    <div class="mb-3">
                                        <label for="resumen" class="form-label">Contenido del resumen</label>
                                        <textarea oninput="contarPalabras()" class="form-control" id="resumen" rows="13"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="palabras" class="form-label">Palabras clave</label>
                                        <input id="palabras" type="text" class="form-control">
                                    </div>
                                    <!-- Espaciado adicional -->
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-primary me-3" onclick="guardarResumen()">Guardar Resumen</button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
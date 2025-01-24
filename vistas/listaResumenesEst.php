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
                <h1>Lista de solicitudes de revisión</h1>
                <div class="box">
                    <div class="box-header with-border">

                        <!-- Contenido de recuadro Tabla de docentes -->
                        <div id="datatablaAsignaciones" name="datatablaDocentes" class="table-responsive">
                            <table id="tablaAsignaciones" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Docente asignado</th>
                                        <th>Fecha de envio</th>
                                        <th>Idioma</th>
                                        <th>Estado</th>
                                        <th>Información</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /Fin Contenido de recuadro Tabla de docentes -->

                        <div hidden id="datosRevision">
                            
                        </div>
                        
                    </div>
                </div>
<!--box-header-->
<!--centro-->

<!--fin centro-->
                </div>
            </div>
        </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
</div>

<script src="scripts/listaResumenesEst.js"></script>

<?php 
}

require 'footer.php';
 ?>

 <?php 
}

ob_end_flush();
?>
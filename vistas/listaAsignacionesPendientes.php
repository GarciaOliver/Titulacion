<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['usu_nombre'])) {
  header("Location: login.html");
}else{

 
require 'header.php';

if ($_SESSION['Docentes']==1) {

?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="row">
            <div class="col-md-12">
            <h1 class="box-title">Lista de docentes</h1>
                <div class="box">
                    
                    <div class="box-header with-border" align="center">

                        <!-- Contenido de recuadro Tabla de docentes -->
                        <div id="datatablaDocentes" name="datatablaDocentes" class="box-body table-responsive">
                            <table id="tablaDocentes" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /Fin Contenido de recuadro Tabla de docentes -->

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



<script src=""></script>

<?php 
}

require 'footer.php';
 ?>

 <?php 
}

ob_end_flush();
?>
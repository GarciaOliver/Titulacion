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
                <h1>Lista de docentes registrados</h1>
                <div class="box">
                    
                    <div class="box-header with-border" align="center">

                        <!-- Contenido de recuadro Tabla de docentes -->
                        <div id="datatablaDocentes" name="datatablaDocentes" class="table-responsive">
                            <table id="tablaDocentes" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Cédula</th>
                                        <th>Permisos</th>
                                        <th>Estado</th>
                                        <th>Editar</th>
                                        <th>Desactivar</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /Fin Contenido de recuadro Tabla de docentes -->

                        <div id="datosDocente">
                            <h1>Hola Mundo</h1>
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

<script src="scripts/docentes.js"></script>
<script>
    $(document).ready(function(){
        $("#datosDocente").hide();
        listarDocentesIngresados();
        
    });
</script>
<?php 
}else{
 require 'noacceso.php'; 
}

require 'footer.php';
 ?>

 <?php 
}

ob_end_flush();
?>
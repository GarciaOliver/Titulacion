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
            <h1 class="box-title">Idiomas</h1>
                <div class="box">
                    
                    <div class="box-header with-border" align="center">

                        <div class="table-responsive">
                            <table id="tablaIdiomas" class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Idioma</th>
                                        <th>Dependencia</th>
                                        <th>Estado</th>
                                        <th>Editar</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <button id="agregarIdioma" onClick="vistaAgregarIdioma(true)" class="btn btn-success">Agregar Idioma</button>

                        <div id="datosAgregarIdioma" hidden>
                            <label for="descripcion">Idioma</label>
                            <input id="descripcion" type="text">
                            <label for="dependencias">Dependencia</label>
                            <select id="dependencias" class="form-select" aria-label="Default select example">
                                <option selected>Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
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


<script src="scripts/idiomas.js"></script>


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
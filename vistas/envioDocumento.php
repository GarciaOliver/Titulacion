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
                <h1>Subir Documentación Firmada</h1>
                <div class="box">
                    
                    <div class="box-header with-border text-center">

                        <table>
                            <tr>
                                <td>
                                    <select id="idioma" class="form-select" aria-label="Default select example">
                                    </select>
                                </td>
                                <td>
                                    <label for="formFile" class="form-label">Default file input example</label>
                                    <input class="form-control" type="file" id="archivo">
                                </td>
                            </tr>
                        </table>
                        
                        <button onclick="enviarDocumento()">Enviar Documento</button>
                
                        
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

<script src="scripts/envioDocumento.js"></script>

<?php 
}

require 'footer.php';
 ?>

 <?php 
}

ob_end_flush();
?>
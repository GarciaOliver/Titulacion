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
                <h1>Documentación Firmada</h1>
                <div class="box">
                    
                    <div class="box-header with-border text-center">
                        <h3 style="text-align: left;">Subir documentación</h3>
                        <table style="width: 100%;">
                                <tr>
                                <td colspan="2">
                                    <label for="nombreArchivo" class="form-label">Nombre del Archivo</label>
                                    <input type="text" class="form-control" id="nombreArchivo" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">
                                    <label for="idioma" class="form-label">Idioma</label>
                                    <select id="idioma" class="form-select" onchange="nombreTemporal()">
                                    </select>
                                </td>
                                <td style="text-align: left;">
                                    <form id="formulario" method="post">
                                        <label for="archivo" class="form-label">Seleccione el documento</label>
                                        <input class="form-control" type="file" name="archivo" id="archivo" accept=".pdf">
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding-top: 20px;">
                                    <button class="btn btn-primary" id="btnGuardar" onclick="enviarDocumento()">Enviar</button>
                                </td>
                            </tr>
                        </table>
                        
                        <!--Tabla de documentos enviados-->
                        <h3 style="text-align: left;">Documentos Enviados</h3>
                        <table id="documentosEnviadosTabla">
                            <thead>
                                <tr>
                                    <th>Nombre del archivo</th>
                                    <th>Descargar</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                        </table>
                
                        
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
 <?php 
if (strlen(session_id())<1) 
  session_start();

  ?>
 <!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>RESUMENES</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../public/css/bootstrap.min.css">

  <!-- Font Awesome -->

  <link rel="stylesheet" href="../public/css/font-awesome.min.css">

  <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../public/css/_all-skins.min.css">
  <!-- Morris chart --><!-- Daterange picker -->



<!-- DATATABLES-->
<link rel="stylesheet" href="../public/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="../public/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="../public/datatables/responsive.dataTables.min.css">
<link rel="stylesheet" href="../public/css/bootstrap-select.min.css">
<link rel="stylesheet" href="../public/css/daterangepicker.css">

<!-- jQuery 3 -->

<script src="../public/js/jquery.min.js"></script>
<script src="../public/js/moment.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<!-- Bootstrap 3.3.7 -->
<script src="../public/js/bootstrap.bundle.min.js"></script>
<script src="../public/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<!-- AdminLTE App -->
<script src="../public/js/adminlte.min.js"></script>
<script src="../public/js/daterangepicker.js"></script>

<script src="../public/datatables/buttons.colVis.min.js"></script>
<script src="../public/datatables/buttons.html5.min.js"></script>
<script src="../public/datatables/dataTables.buttons.min.js"></script>
<script src="../public/datatables/jquery.dataTables.min.js"></script>
<script src="../public/datatables/jszip.min.js"></script>

<script src="../public/datatables/pdfmake.min.js"></script>
<script src="../public/datatables/vfs_fonts.js"></script>
<script src="../public/datatables/datatables.min.js"></script>
<script src="../public/js/bootbox.min.js"></script>
<script src="../public/js/bootstrap-select.min.js"></script>



</head>
<body class="hold-transition skin-blue sidebar-mini " >

<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="escritorio.php" class="logo" >
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><p style="color: white; font-size: 16px; font-family: Arial;">MENÚ</p></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><p style="color: white; font-size: 16px; font-family: Arial;">INICIO</p></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span style="color: white; font-size: 16px; font-family: Arial;"><<</span>
        
      </a>
      
      <div class="navbar-custom-menu">
       
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="hidden-xs"><?php echo $_SESSION['usu_nombre']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
             
                <p>
                  IST17J
                  <small>Noviembre 2021</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
  
                </div>
                <div class="pull-right">
                  <a href="../ajax/usuario.php?op=salir" class="btn btn-default btn-flat">Salir</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->

        </ul>
      </div>
    </nav>
</header>

  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
  
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
     
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      
      <ul class="sidebar-menu" data-widget="tree">

<br>
               <?php 

if ($_SESSION['Admin']==1) {
  echo ' <li class="treeview">
          <a href="#">
            <i class="fa fa-folder"></i> <span>Opciones de Admin</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="listaIdiomas.php"><i class="fa fa-circle-o"></i>Catálogo de Idiomas</a></li>
            <li><a href="listaDocentesTodos.php"><i class="fa fa-circle-o"></i> Añadir Docente</a></li>
            <li><a href="listaDocentesIngresados.php"><i class="fa fa-circle-o"></i> Lista de Docentes</a></li>
            <li><a href="estudiantesTodos.php"><i class="fa fa-circle-o"></i> Estudiantes</a></li>  
            <li><a href=""><i class="fa fa-circle-o"></i> Cambios Docentes</a></li>    
          </ul>
        </li>';
}
        ?>
               <?php 
if ($_SESSION['Docentes']==1) {
  echo ' <li class="treeview">
          <a href="#">
            <i class="fa fa-th"></i> <span>Opciones de Docente</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="listaAsignacionesPendientes.php"><i class="fa fa-circle-o"></i>Calificar resumenes</a></li>
            <li><a href=""><i class="fa fa-circle-o"></i>Estudiantes aprobados</a></li>
            <li><a href=""><i class="fa fa-circle-o"></i>Estudiantes pendientes</a></li>
          </ul>
        </li>';
}
        ?>
        
               <?php 
if ($_SESSION['Estudiantes']==1) {
  echo '<li class="treeview">
          <a href="#">
            <i class="fa fa-folder"></i> <span>Resúmenes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="subirResumen.php"><i class="fa fa-circle-o"></i>Subir resúmenes</a></li>
            <li><a href=""><i class="fa fa-circle-o"></i>Subir documentación</a></li>
          </ul>
        </li>';
}
        ?>

      </ul>
    </section>
    <!-- /.sidebar -->
 
  </aside>
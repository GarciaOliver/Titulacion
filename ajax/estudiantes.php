<?php
    require_once "../modelos/Estudiantes.php";

    $estudiante=new Estudiante();

    session_start();

    switch ($_GET["op"]){
        case 'guardarResumen':
            $resumen=$_POST['resumen'];
            $palabras=$_POST['palabras'];;
            $idioma=$_POST['idioma'];

            $data=$estudiante->guardarResumen($_SESSION['usu_id'], $resumen, $palabras, $idioma);
            echo $data;
        break;
        case 'resumenPendiente':
            $data=$estudiante->resumenPendiente($_SESSION['usu_id'], $_POST['idioma']);
            if ($data->num_rows>0) {
                echo true;
            }else{
                echo false;
            }
            
        break;
    }
?>
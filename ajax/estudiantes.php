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
    }
?>
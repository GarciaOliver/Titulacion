<?php
    require "../modelos/Docentes.php";

    $docente=new Docente();

    session_start();


    switch ($_GET["op"]) {
        case 'listarDocentesIngresados':
            $data=$docente->listarDocentesIngresados($_SESSION['usu_id'],$_SESSION['cat_id']);

            echo json_encode($data,JSON_UNESCAPED_UNICODE);
            
        break;
        case 'listarDocentesTodos':
            $data=$docente->listarDocentesTodos($_SESSION['usu_id']);

            echo json_encode($data,JSON_UNESCAPED_UNICODE);
        break;
        case 'agregarDocente':
            $resp=$docente->agregarDocente($_POST["usu_id"],$_SESSION['cat_id']);
            echo $resp;
        break;
    }
?>
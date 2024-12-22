<?php
    require "../modelos/Idioma.php";

    $idioma=new Idioma();

    session_start();

    switch ($_GET["op"]) {
        case 'listarIdiomas':
            $data=$idioma->listarIdiomas();

            echo json_encode($data,JSON_UNESCAPED_UNICODE);
        break;
        case 'selectIdiomas':
            $data=$idioma->selectIdiomas();
            $resultado='<option value="0">Seleccione un Idioma</option>';
            while ($orden=$data->fetch_object()) {
                $resultado.='<option value="'.$orden->idio_id.'">'.$orden->idio_idioma.'</option>';

            }

            echo $resultado;
        break;
    }
?>
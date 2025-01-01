<?php
    require_once '../modelos/Asignaciones.php';

    $asignacion = new Asignacion();

    session_start();

    switch($_GET["op"]){
        case 'asignacionesPendientes':
            $data = $asignacion->asignacionesPendientes($_SESSION['usu_id']);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        break;
        case 'datosResumen':
            $dataResumen=$asignacion->datosResumen($_POST['res_id']);

            $orden=$dataResumen->fetch_object();
            $resultado='<label for="estudiante" class="form-label">Estudiante</label>
                <input class="form-control" id="estudiante" type="text" value="'.$orden->est_nombre.'" readonly>
                <div id="datos">
                    <label for="resumen" class="form-label">Resumen</label>
                    <textarea class="form-control" id="resumen" rows="5" readonly>'.$orden->res_resumen.'</textarea>
                    <label for="palabras" class="form-label">Palabras clave</label>
                    <input class="form-control" id="palabras" type="text" value="'.$orden->res_palabras_clave.'" readonly>
                </div>
                <div id="observaciones" hidden>
                    <label for="txtObservaciones" class="form-label">Observaciones</label>
                    <textarea class="form-control" id="txtObservaciones" rows="5"></textarea>
                    <input type="button" class="btn btn-primary" value="Enviar" onclick="enviarCalificacion(false, '.$orden->asig_id.')">
                </div>
                <div id="botones">
                    <input type="button" class="btn btn-primary" value="Aprobar" onclick="enviarCalificacion(true, '.$orden->asig_id.')">
                    <input type="button" class="btn btn-primary" value="Rechazar" onclick="vistaObservaciones()">
                </div>
                <input type="button" class="btn btn-primary" value="Cancelar" onclick="mostrarDatos(false)">';
            echo $resultado;
        break;
        case 'enviarCalificacion':
            if($_POST['calificacion']=='Aprobado'){
                $data = $asignacion->enviarCalificacion($_POST['asig_id'], 4, $_POST['observaciones']);
            }else{
                $data = $asignacion->enviarCalificacion($_POST['asig_id'], 7, $_POST['observaciones']);
            }
            
            echo $data;
        break;
    }
?>
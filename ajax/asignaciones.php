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
        case 'asignacionesEstudiante':
            $data = $asignacion->asignacionesEstudiante($_SESSION['usu_id']);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        break;
        case 'datosRevisionEst':
            $data=$asignacion->datosRevision($_POST['asig_id']);
            $orden=$data->fetch_object();
            
            $resultado = '
                    <div id="datos" class="container">
                        <div class="row">
                            <label for="docente" class="form-label">Docente responsable de la revisión</label>
                            <input class="form-control" style="max-width: 500px;" id="docente" type="text" value="' . $orden->usu_nombre . '" readonly>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="fechaEnvio" class="form-label">Fecha y hora de envío</label>
                                <input class="form-control" id="fechaEnvio" type="text" value="' . $orden->asig_fecha . '" readonly>
                            </div>
                            <div class="col">
                                <label for="idioma" class="form-label">Idioma</label>
                                <input class="form-control" id="idioma" type="text" value="' . $orden->idio_idioma . '" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="resumen" class="form-label">Resumen</label>
                            <textarea class="form-control" id="resumen" rows="4" readonly>' . $orden->res_resumen . '</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="palabras" class="form-label">Palabras clave</label>
                            <input class="form-control" id="palabras" type="text" value="' . $orden->res_palabras_clave . '" readonly>
                        </div>
                    </div>';

            if (!$orden->cat_id == null) {
                $resultado .= '
                    <div id="observaciones" class="mb-4">
                        <div class="mb-3">
                            <label for="fechaRevision" class="form-label">Fecha y hora de revisión</label>
                            <input class="form-control" id="fechaRevision" type="text" value="' . $orden->rev_fecha . '" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" rows="4" readonly>' . $orden->rev_observaciones . '</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <input class="form-control" id="estado" type="text" value="' . $orden->cat_estado . '" readonly>
                        </div>
                        <div class="mb-3">
                            <a href="../reportes/docResumen.php?id='.$_POST['asig_id'].'" class="btn btn-primary mb-3">Descargar archivo</a>
                        </div>
                    </div>';
            } else {
                $resultado .= '
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <input class="form-control" id="estado" type="text" value="Aún no se ha revisado" readonly>
                    </div>';
            }

            $resultado .= '
                    <div class="text-center mb-3">
                        <input type="button" class="btn btn-primary mb-3" value="Volver" onclick="datosRevision(false)">
                    </div>';
            echo $resultado;
        break;
    }
?>
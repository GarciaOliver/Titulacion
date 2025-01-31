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
            $datos = $asignacion->asignacionesEstudiante($_SESSION['usu_id']);
            $data = []; // Inicializar el arreglo
            
            while ($orden = $datos->fetch_object()) {
                $data[] = [
                    "usu_nombre" =>$orden->usu_nombre,
                    "asig_fecha" =>$orden->asig_fecha,
                    "idio_idioma" =>$orden->idio_idioma,
                    "cat_estado" =>$orden->cat_estado,
                    "asig_id" =>$orden->asig_id
                ];
            }
            $response = ["data" => $data];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        break;
        case 'datosRevisionEst':
            $data=$asignacion->datosRevision($_POST['asig_id']);
            $orden=$data->fetch_object();
            
            $resultado = '<table style="width: 100%;">
                <tr>
                    <td style="padding-right: 15px;">
                        <label for="docente" class="form-label">Docente responsable de la revisión</label>
                        <input class="form-control" id="docente" type="text" value="' . $orden->usu_nombre . '" readonly>
                    </td>
                    <td>
                        <label for="fechaEnvio" class="form-label">Fecha y hora de envío</label>
                        <input class="form-control" id="fechaEnvio" type="text" value="' . $orden->asig_fecha . '" readonly>
                    </td>
                </tr>
                <tr>
                    <td style="padding-right: 15px;">
                        <label for="idioma" class="form-label">Idioma</label>
                        <input class="form-control" id="idioma" type="text" value="' . $orden->idio_idioma . '" readonly>
                    </td>';
            if (!$orden->cat_id == null){
                $resultado.='<td>
                    <label for="estado" class="form-label">Estado</label>
                    <input class="form-control" id="estado" type="text" value="' . $orden->cat_estado . '" readonly>
                </td>';
            }else{
                $resultado.='<td>
                    <label for="estado" class="form-label">Estado</label>
                    <input class="form-control" id="estado" type="text" value="Aún no se ha revisado" readonly>
                </td>';
            }
            $resultado.='</tr>
                <tr>
                    <td style="padding-right: 15px;">
                        <label for="palabras" class="form-label">Palabras clave</label>
                        <input class="form-control" id="palabras" type="text" value="' . $orden->res_palabras_clave . '" readonly>
                    </td>';
            if (!$orden->cat_id == null){
                $resultado.='<td>
                    <label for="fechaRevision" class="form-label">Fecha y hora de revisión</label>
                    <input class="form-control" id="fechaRevision" type="text" value="' . $orden->rev_fecha . '" readonly>
                </td>';
            }else{
                $resultado.='<td></td>';
            }
            $resultado.='</tr>
                <tr>
                    <td colspan=2>
                        <label for="resumen" class="form-label">Resumen</label>
                        <textarea class="form-control" id="resumen" rows="8" readonly>' . $orden->res_resumen . '</textarea>
                    </td>
                </tr>';
            if (!$orden->cat_id == null){
                $resultado.='<tr>
                    <td colspan=2>
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" rows="4" readonly>' . $orden->rev_observaciones . '</textarea>
                    </td>
                </tr>';
            }
            $resultado.='<tr>
                <td style="text-align: right; padding-top: 10px; padding-right: 15px;">
                    <input type="button" class="btn btn-primary mb-3" value="Volver" onclick="datosRevision(false)">
                </td>';
            if (!$orden->cat_id == null){
                $resultado.='<td style="padding-top: 10px;">
                    <a href="../reportes/docResumen.php?id='.$_POST['asig_id'].'" class="btn btn-primary">Descargar archivo</a>
                </td>';
            }else{
                $resultado.='<td></td>';
            }
            $resultado.='</tr>';
            $resultado.='</table>';
            /*'
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
                    </div>';*/
            echo $resultado;
        break;
        case 'nombreArchivo':
            $idioma=$_POST['idioma'];
            $est_nombre=str_replace(' ', '-', $_SESSION['usu_nombre']);
            $nombre=$est_nombre.'_'.$_SESSION['usu_cedula'].'_'.$idioma;
            echo "$nombre";
        break;
        
    }
?>
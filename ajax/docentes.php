<?php
    require_once "../modelos/Docentes.php";

    $docente=new Docente();

    session_start();


    switch ($_GET["op"]) {
        case 'listarDocentesIngresados':
            $data=$docente->listarDocentesIngresados($_SESSION['usu_id'],$_SESSION['idio_id']);

            echo json_encode($data,JSON_UNESCAPED_UNICODE);
            
        break;
        case 'listarDocentesTodos':
            $data=$docente->listarDocentesTodos($_SESSION['usu_id']);

            echo json_encode($data,JSON_UNESCAPED_UNICODE);
        break;
        case 'agregarDocente':
            $data=$docente->agregarDocente($_POST['usu_id'],$_SESSION['idio_id']);
            echo $data;
        break;
        case 'buscarDocente':
            $dataDocente=$docente->buscarDocente($_POST['usu_id']);
            
            $orden=$dataDocente->fetch_object();
            $resultado = '
                <div class="container mt-5">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="mb-4 text-start">Edición de docente</h2>
                            
                            <!-- Información básica -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre:</label>
                                    <input id="nombre" class="form-control" type="text" 
                                        value="' . $orden->usu_nombre . '" aria-label="Disabled input example" 
                                        disabled readonly>
                                </div>
                                <div class="col-md-6 d-flex align-items-center">
                                    <div style="width: 70%;">
                                        <label for="cedula" class="form-label">Cédula:</label>
                                        <input id="cedula" class="form-control" style="max-width: 200px;" type="text" 
                                            value="' . $orden->usu_cedula . '" aria-label="Disabled input example" 
                                            disabled readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="idioma" class="form-label">Idioma de revisión:</label>
                                    <input id="idioma" class="form-control" type="text" 
                                        value="' . $orden->idio_idioma . '" aria-label="Disabled input example" 
                                        disabled readonly>
                                </div>
                            </div>

                            <!-- Sección separada para Permiso y Estado -->
                            <hr>
                            <h4 class="mb-3 text-start">Configuraciones</h4>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="permiso" class="form-label">Permiso:</label>';
                if ($orden->doc_permiso == 0) {
                    $resultado .= '
                                    <select id="permiso" class="form-select">
                                        <option value="0" selected>Docente</option>
                                        <option value="1">Administrador</option>
                                    </select>';
                } else {
                    $resultado .= '
                                    <select id="permiso" class="form-select">
                                        <option value="0">Docente</option>
                                        <option value="1" selected>Administrador</option>
                                    </select>';
                }
                $resultado .= '
                                </div>
                                <div class="col-md-6">
                                    <label for="estado" class="form-label">Estado:</label>';
                if ($orden->cat_estado == "activo") {
                    $resultado .= '
                                    <select id="estado" class="form-select">
                                        <option value="1" selected>Activo</option>
                                        <option value="2">Inactivo</option>
                                    </select>';
                } else if ($orden->cat_estado == "inactivo") {
                    $resultado .= '
                                    <select id="estado" class="form-select">
                                        <option value="1">Activo</option>
                                        <option value="2" selected>Inactivo</option>
                                    </select>';
                }
                $resultado .= '
                                </div>
                            </div>
                            <!-- Botones principales con espacio adicional -->
                            <div class="d-flex justify-content-end gap-3 mt-4">
                                <button type="button" onClick="eliminarDocente('.$_POST['usu_id'].',3)" class="btn btn-danger">Eliminar docente</button>
                                <button type="button" onClick="editarDocente('.$_POST['usu_id'].')" class="btn btn-primary">Guardar cambios</button>
                                <button type="button" onClick="mostrarDatos(false,0)" class="btn btn-secondary">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>';

                echo $resultado;




        break;
        case 'editarDocente':
            $data=$docente->editarDocente($_POST['usu_id'],$_POST['doc_permiso'],$_POST['cat_id']);
            echo $data;
        break;
        
    }
?>
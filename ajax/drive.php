<?php

session_start();

use League\OAuth2\Client\Provider\Google;

    include '../public/plugins/upload-php/api-google/vendor/autoload.php';
    include '../modelos/Asignaciones.php';

    $client = new Google_Client();
    $asignacion = new Asignacion();

    $jsonKeyFilePath = '../files/resumenes-448016-09ebc70ecb81.json';
    $client->setAuthConfig($jsonKeyFilePath);
    $client->setScopes(Google_Service_Drive::DRIVE);

    switch ($_GET['op']) {
        case 'subirArchivo':
            if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
                echo "Error en el archivo";
                exit;
            }
        
            //Obtener variables
            $archivo=$_FILES['archivo'];
            $nombreArchivo=$_POST['nombreArchivo'];
            $idioma=$_POST['idioma'];
        
            if ($archivo['type'] != 'application/pdf') {
                echo "El archivo no es un PDF";
                exit;
            }
        
            try {
                $service = new Google_Service_Drive($client);
                $file = new Google_Service_Drive_DriveFile();
        
                // Verificar si ya existe un archivo con el mismo nombre en la carpeta
                $query = sprintf(
                    "name = '%s' and '%s' in parents and trashed = false",
                    addslashes($nombreArchivo),
                    '190OPK-chVrlkMNKupf7y3sauH5xHs0Kh' // ID de la carpeta de destino
                );
        
                //Verificación de archivo existente
                $response = $service->files->listFiles(array(
                    'q' => $query,
                    'fields' => 'files(id, name)'
                ));
        
                if (count($response->getFiles()) > 0) {
                    echo "Ya existe un archivo con el mismo nombre en la carpeta.";
                    exit;
                }
        
        
                //Datos del archivo
                $file_path = $archivo['tmp_name'];
                //Nombre del archivo enviado
                $file->setName($nombreArchivo);
                //Identificador de la carpeta
                $file->setParents(array('190OPK-chVrlkMNKupf7y3sauH5xHs0Kh'));
                //Tipo de archivo
                $file->setMimeType($archivo['type']);
        
                //Subir archivo
                $resultado=$service ->files->create(
                    $file,
                    array(
                        //Dirección del archivo
                        'data' => file_get_contents($file_path),
                        'mimeType' => $archivo['type'],
                        'uploadType' => 'multipart'
                    )
                );
                
                //Verificación de subida
                if ($resultado) {
                    //Guardar en la base de datos el nombre del archivo
                    $consulta=$asignacion->subirDocumento($_SESSION['usu_id'], $idioma, $nombreArchivo);
                    echo "Archivo subido correctamente";
                } else {
                    echo "Error al subir el archivo ";
                    exit;
                }
        
            } catch (Google_Service_Exception $gs) {
                echo "Error en Google Drive " /*. $gs->getMessage()*/;
            } catch(Exception $e) {
                echo $e->getMessage();
            }
            break;
        case 'listarArchivos':
            $datos = $asignacion->listaDocumentos($_SESSION['usu_id']);
            $data = []; // Inicializar el arreglo

            while ($orden = $datos->fetch_object()) {
                $data[] = [
                    "rev_id" =>$orden->rev_id,
                    "rev_archivo" =>$orden->rev_archivo
                ];
            }
            $response = ["data" => $data];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            break;
        case 'eliminarArchivo':
            if(!isset($_POST['nombreArchivo'])){
                echo "Error al eliminar el archivo";
                exit;
            }

            $nombreArchivo=$_POST['nombreArchivo'];
            try {
                $service = new Google_Service_Drive($client);

                //Buscar archivo
                $query = sprintf("name='%s' and trashed=false", $nombreArchivo);
                $response = $service->files->listFiles([
                    'q' => $query,
                    'spaces' => 'drive',
                    'fields' => 'files(id, name)'
                ]);

                $files = $response->getFiles();

                //Verificar si existe el archivo
                if (count($files) === 0) {
                    echo "Error: No se encontró el archivo.";
                    exit;
                }

                // Usar el ID del archivo para eliminarlo
                $fileId = $files[0]->getId();
                $resultado=$service->files->delete($fileId);

                if($resultado){
                    $consulta=$asignacion->eliminarDocumento($_SESSION['usu_id'], $nombreArchivo);
                    echo "Archivo eliminado correctamente";
                }else{
                    echo "Error aerror en la eliminación del archivo ";
                    exit;
                }

            } catch (Google_Service_Exception $gs) {
                echo "Error en Google Drive: " . $gs->getMessage();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            break;
        case 'descargarArchivo':
            $service = new Google_Service_Drive($client);
        
            $nombreArchivo = $_GET['nombreArchivo'];
            
            // Buscar archivo
            $query = sprintf("name='%s' and trashed=false", $nombreArchivo);
            $response = $service->files->listFiles([
                'q' => $query,
                'spaces' => 'drive',
                'fields' => 'files(id, name, mimeType, size)'
            ]);
        
            $files = $response->getFiles();
        
            // Verificar si existe el archivo
            if (count($files) === 0) {
                echo "Error: No se encontró el archivo.";
                exit;
            }
        
            $fileId = $files[0]->getId();
            $fileSize = $files[0]->getSize();
        
            try {
                // Obtener el contenido del archivo
                $fileContent = $service->files->get($fileId, array(
                    'alt' => 'media'
                ));

                // Forzar nombre del archivo con extensión .pdf
                $nombreArchivoDescarga = $nombreArchivo.".pdf";

                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . $nombreArchivoDescarga . '"');
                header('Content-Length: ' . $fileSize);

                while (!$fileContent->getBody()->eof()) {
                    echo $fileContent->getBody()->read(1024); // Enviar bloques de 1 KB
                }
                
        
                
            } catch (Exception $e) {
                echo "Error al descargar el archivo: " . $e->getMessage();
            }
            break;
            
    }
?>
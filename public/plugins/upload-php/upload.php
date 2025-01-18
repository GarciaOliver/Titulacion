<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'conexion.php';
include 'api-google/vendor/autoload.php';


putenv('GOOGLE_APPLICATION_CREDENTIALS=cargaarchivos-407219-3a54d466a519.json');

$client = new Google_Client();

$client->useApplicationDefaultCredentials();

$client->setScopes(['https://www.googleapis.com/auth/drive.file']);

try {
    $nombre = $_FILES['doce_archivo']['name'];
    $extension = "." . pathinfo($nombre, PATHINFO_EXTENSION);


    $service = new Google_Service_Drive($client);
    $file_path = $_FILES['doce_archivo']['tmp_name'];
    $file = new Google_Service_Drive_DriveFile();
    $file->setName($nombre);

    //$finfo = finfo_open(FILEINFO_MIME_TYPE);
    //$mime_type = finfo_file($finfo, $file_path);

    $file->setParents(array("11qljiHv1fhDjiiexpxFL1SoRhqrvtcFT"));
    $file->setDescription("Archivo cargado desde php");
    //$file->setMimeType($mime_type);

    $resultado = $service->files->create(
        $file,
        array(
            'data' => file_get_contents($file_path),
            'mimeType' => 'application/pdf',
            'uploadType' => 'media'
        )
    );

    $ruta = 'https://drive.google.com/open?id='.$resultado->id;

    $sql = "INSERT INTO `bd_practicas`.`documentoestudiante`
   (
   doce_nombre,
   doce_url,
   doce_fecha,
   cat_tipo,
   est_cedula,
   doce_extencion)
   VALUES
   (
   '$nombre',
   '$ruta',
   '2023-10-19',
   1004,
   18,
   '$extension');";
   echo $sql;
    $mysqli->query($sql);

    echo '<a href="'.$ruta.'" target="_blank">'.$resultado->name.'</a>';


} catch (Google_Service_Exception $gs) {

    $mensaje = json_decode($gs->getMessage());
    echo $mensaje->error->message();
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
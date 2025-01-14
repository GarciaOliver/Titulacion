<?php
require('../public/plugins/PHPWord-master/src/PhpWord/Autoloader.php');
require('../public/plugins/PHPWord-master/src/PhpWord/TemplateProcessor.php');
require('../modelos/Asignaciones.php');

$asignacion = new Asignacion();

$data=$asignacion->datosRevision($_GET['id']);

$orden=$data->fetch_object();

use PhpOffice\PhpWord\TemplateProcessor;

\PhpOffice\PhpWord\Autoloader::register();

$documento= new  TemplateProcessor('plantilla.docx');

$resumen=$orden->res_resumen;
$palabrasC=$orden->res_palabras_clave;
$docente=$orden->usu_nombre;

$documento->setValue('resumen', $resumen);
$documento->setValue('palabrasC', $palabrasC);
$documento->setValue('docente', $docente);

// Guardar el archivo en una ubicación temporal en el servidor
$tempFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Resumen.docx';
$documento->saveAs($tempFile);

// Enviar el archivo como descarga al cliente
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=Resumen.docx");
header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
header("Content-Length: " . filesize($tempFile));
header("Cache-Control: must-revalidate");
header("Pragma: public");

// Leer y enviar el archivo
readfile($tempFile);

// Eliminar el archivo temporal después de enviarlo
unlink($tempFile);
?>
<?php
header('Content-Type: text/html; charset=UTF-8');  
//activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id())<1) 
  session_start();

if (!isset($_SESSION['usu_nombre'])) {
  echo "debe ingresar al sistema correctamente para vosualizar el reporte";
}else{


if ($_SESSION['Activos']==1 or $_SESSION['Actas']==1 ){

	require_once "../modelos/vacaciones.php";
$info=new Asistencias();	
$rspta=$info->obten_detallevacaciones(2,$_GET["inf"]); //0 entradas
while ($reg=$rspta->fetch_object()) {
	$fechasolicitud=$reg->det_fechasolicitud;
	$fechaini=$reg->det_fechaini;
	$fechafin=$reg->det_fechafin;
	$horaini=$reg->det_horaini;
	$horafin=$reg->det_horafin;
	$dias=$reg->dias;
	$horas=$reg->horas;
	$obj=$reg->det_obj;	
}
$detalle = explode("/", $obj);
$ban=0;	
$ban=0;
	
require('PDF_MC_Table.php');

//instanciamos la clase para generar el documento pdf
$pdf=new PDF_MC_Table('L','mm','letter');

//agregamos la primera pagina al documento pdf
$pdf->AddPage();

//seteamos el inicio del margen superior en 25 pixeles
$y_axis_initial=5;

$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->setXy(10,20);
$pdf->Image("../files/img/permiso.png", 10, 20, 210);
$pdf->MultiCell(210,18,"",1,'L',0);
$pdf->setXy(220,20);
$pdf->MultiCell(50,6,utf8_decode("DIRECCIÓN DE TALENTO HUMANO FORMULARIO DE LICENCIAS Y PERMISOS"),1,'L',0);
$x=$pdf->getx();
$y=$pdf->gety();
$pdf->SetFillColor(155,155,155);
$pdf->SetFont('Arial','B',7);
$pdf->MultiCell(40,6,"FECHA DE SOLICITUD",1,'L',1);
$pdf->setXy($x+40,$y);	
$pdf->MultiCell(40,6,date("d/m/Y", strtotime($fechasolicitud)),1,'L',0);
$pdf->setXy($x+80,$y);	
$pdf->MultiCell(40,6,"PROVINCIA",1,'L',1);
$pdf->setXy($x+120,$y);	
$pdf->MultiCell(40,6,"IMBABURA",1,'L',0);
$pdf->setXy($x+160,$y);	
$pdf->MultiCell(50,6,"REGIMEN",1,'L',1);
$pdf->setXy($x+210,$y);	
$pdf->MultiCell(50,6,"LOSEP",1,'L',0);
$pdf->MultiCell(260,6,"DATOS DEL SERVIDOR / TRABAJADOR",1,'C',1);

	$x=$pdf->getx();
$y=$pdf->gety();
$pdf->MultiCell(80,6,"APELLIDOS Y NOMBRES:",1,'L',0);
$pdf->setXy($x+80,$y);		
$pdf->MultiCell(80,6,utf8_decode($_SESSION['usu_nombre']),1,'L',0);	
$pdf->setXy($x+160,$y);
$pdf->MultiCell(50,6,"CEDULA:",1,'L',0);	
$pdf->setXy($x+210,$y);
$pdf->MultiCell(50,6,utf8_decode($_SESSION['usu_cedula']),1,'L',0);	
$x=$pdf->getx();
$y=$pdf->gety();
$pdf->MultiCell(160,6,"COORDINACION / GERENCIA / PROYECTO
COORDINACION ZONAL 1",1,'L',0);
$pdf->setXy($x+160,$y);		
$pdf->MultiCell(100,6,utf8_decode("DIRECCION O UNIDAD
INSTITUTO SUPERIOR TECNOLÓGICO DANIEL REYES"),1,'L',0);
$x=$pdf->getx();
$y=$pdf->gety();
$pdf->MultiCell(160,6,"MOTIVO",1,'C',1);
$pdf->setXy($x+160,$y);		
$pdf->MultiCell(100,6,utf8_decode("FECHA DEL PERMISO"),1,'C',1);
$x=$pdf->getx();
$y=$pdf->gety();

if (utf8_decode(trim($detalle[0]))==utf8_decode('LICENCIA POR CALAMIDAD DOMÉSTICA') and $ban==0){	
    $pdf->MultiCell(15,6,"X",1,'C',0);
	$ban=1;
}
else
	$pdf->MultiCell(15,6,"",1,'C',0);		
$pdf->SETxy($x+15,$y);		
$pdf->MultiCell(60,6,utf8_decode("LICENCIA POR CALAMIDAD DOMÉSTICA"),0,'L',0);
$pdf->SETxy($x+85,$y);	
if (utf8_decode(trim($detalle[0]))=='PERMISO PARA ESTUDIOS REGULARES' and $ban==0){	
    $pdf->MultiCell(15,6,"X",1,'C',0);
	$ban=1;
}
else
	$pdf->MultiCell(15,6,"",1,'C',0);	

$pdf->SETxy($x+100,$y);	
$pdf->MultiCell(60,6,utf8_decode("PERMISO PARA ESTUDIOS REGULARES"),0,'L',0);
$pdf->SETxy($x+160,$y);	
$pdf->MultiCell(50,6,utf8_decode("DESDE (dd/mm/aaaa)"),1,'C',0);
$pdf->SETxy($x+210,$y);	
$pdf->MultiCell(50,6,utf8_decode("HASTA (dd/mm/aaaa)"),1,'C',0);
$x=$pdf->getx();
$y=$pdf->gety();
if (utf8_decode(trim($detalle[0]))==utf8_decode('LICENCIA POR ENFERMEDAD') and $ban==0){
    $pdf->MultiCell(15,6,"X",1,'C',0);
	$ban=1;
}
else
	$pdf->MultiCell(15,6,"",1,'C',0);	

$pdf->SETxy($x+15,$y);	
$pdf->MultiCell(60,6,utf8_decode("LICENCIA POR ENFERMEDAD"),0,'L',0);
$pdf->SETxy($x+85,$y);
if (utf8_decode(trim($detalle[0]))==utf8_decode('PERMISO DE DÍAS CON CARGO A VACACIONES') and $ban==0){	
    $pdf->MultiCell(15,6,"X",1,'C',0);
	$ban=1;
}
else
	$pdf->MultiCell(15,6,"",1,'C',0);	

$pdf->SETxy($x+100,$y);	
$pdf->MultiCell(60,6,utf8_decode("PERMISO DE DÍAS CON CARGO A VACACIONES"),0,'L',0);
$pdf->SETxy($x+160,$y);	
$pdf->MultiCell(50,6,date("d/m/Y", strtotime($fechaini)),1,'C',0);
$pdf->SETxy($x+210,$y);	
$pdf->MultiCell(50,6,date("d/m/Y", strtotime($fechafin)),1,'C',0);
$x=$pdf->getx();
$y=$pdf->gety();
if (utf8_decode(trim($detalle[0]))==utf8_decode('LICENCIA POR MATERNIDAD') and $ban==0){	
    $pdf->MultiCell(15,6,"X",1,'C',0);
	$ban=1;
}
else
	$pdf->MultiCell(15,6,"",1,'C',0);	
$pdf->SETxy($x+15,$y);	
$pdf->MultiCell(60,6,utf8_decode("LICENCIA POR MATERNIDAD"),0,'L',0);
$pdf->SETxy($x+85,$y);
if (utf8_decode(trim($detalle[0]))=='PERMISO POR ASUNTOS OFICIALES' and $ban==0){	
    $pdf->MultiCell(15,6,"X",1,'C',0);
	$ban=1;
}
else
	$pdf->MultiCell(15,6,"",1,'C',0);	
$pdf->SETxy($x+100,$y);	
$pdf->MultiCell(60,6,utf8_decode("PERMISO POR ASUNTOS OFICIALES"),0,'L',0);
$pdf->SETxy($x+160,$y);	
$pdf->MultiCell(100,6,utf8_decode("EN CASO DE HORAS"),1,'C',1);
$x=$pdf->getx();
$y=$pdf->gety();
if (utf8_decode(trim($detalle[0]))==utf8_decode('LICENCIA POR MATRIMONIO/UNIÓN DE HECHO') and $ban==0){	
    $pdf->MultiCell(15,6,"X",1,'C',0);
	$ban=1;
}
else
	$pdf->MultiCell(15,6,"",1,'C',0);		
$pdf->SETxy($x+15,$y);	
$pdf->MultiCell(60,6,utf8_decode("LICENCIA POR MATRIMONIO/UNIÓN DE HECHO"),0,'L',0);
$pdf->SETxy($x+85,$y);	
if (utf8_decode(trim($detalle[0]))==utf8_decode('PERMISO PARA ATENCIÓN MÉDICA') and $ban==0){	
    $pdf->MultiCell(15,6,"X",1,'C',0);
	$ban=1;
}
else
	$pdf->MultiCell(15,6,"",1,'C',0);	
$pdf->SETxy($x+100,$y);	
$pdf->MultiCell(60,6,utf8_decode("PERMISO PARA ATENCIÓN MÉDICA"),0,'L',0);
$pdf->SETxy($x+160,$y);	
$pdf->MultiCell(50,6,utf8_decode("DESDE (hh:mm)"),1,'C',0);
$pdf->SETxy($x+210,$y);	
$pdf->MultiCell(50,6,utf8_decode("HASTA (hh:mm)"),1,'C',0);
$x=$pdf->getx();
$y=$pdf->gety();
if (utf8_decode(trim($detalle[0]))==utf8_decode('LICENCIA POR PATERNIDAD') and $ban==0){	
    $pdf->MultiCell(15,6,"X",1,'C',0);
	$ban=1;
}
else
	$pdf->MultiCell(15,6,"",1,'C',0);	
$pdf->SETxy($x+15,$y);	
$pdf->MultiCell(60,6,utf8_decode("LICENCIA POR PATERNIDAD"),0,'L',0);
$pdf->SETxy($x+85,$y);
if ($ban==0){	
    $pdf->MultiCell(15,6,"X",1,'C',0);
	$ban=1;
}
else
	$pdf->MultiCell(15,6,"",1,'C',0);		

$pdf->SETxy($x+100,$y);	
$pdf->MultiCell(60,6,utf8_decode("OTRO"),0,'L',0);
$pdf->SETxy($x+160,$y);	
$pdf->MultiCell(50,6,$horaini,1,'C',0);
$pdf->SETxy($x+210,$y);	
$pdf->MultiCell(50,6,$horafin,1,'C',0);
$pdf->MultiCell(160,6,"OBSERVACIONES O JUSTIFICATIVOS",1,'C',1);
$pdf->setXy($x+160,$y+6);		
$pdf->MultiCell(100,6,utf8_decode("TIEMPO SOLICITADO"),1,'C',1);
$x=$pdf->getx();
$y=$pdf->gety();
if (isset($detalle[1]))	
    $pdf->MultiCell(160,12,$detalle[1],1,'L',0);
else
	$pdf->MultiCell(160,12,"",1,'L',0);

$pdf->setXy($x+160,$y);			
$pdf->MultiCell(50,6,utf8_decode("DIAS"),1,'C',0);
$pdf->SETxy($x+210,$y);	
$pdf->MultiCell(50,6,utf8_decode("HORAS"),1,'C',0);
$pdf->setXy($x+160,$y+6);
if ($horas==0)
    $pdf->MultiCell(50,6,$dias,1,'C',0);
else{
	if ($dias>1)	
		$pdf->MultiCell(50,6,$dias,1,'C',0);
	else	
		$pdf->MultiCell(50,6,'0',1,'C',0);
}
$pdf->SETxy($x+210,$y+6);	
$pdf->MultiCell(50,6,$horas,1,'C',0);
$x=$pdf->getx();
$y=$pdf->gety();
$pdf->MultiCell(86,6,"SOLICITA",1,'C',0);
$pdf->setXy($x+86,$y);		
$pdf->MultiCell(86,6,"APRUEBA",1,'C',0);	
$pdf->setXy($x+172,$y);
$pdf->MultiCell(88,6,"REGISTRA",1,'C',0);	
$x=$pdf->getx();
$y=$pdf->gety();
$pdf->MultiCell(86,24,"",'LR','C',0);
$pdf->setXy($x+86,$y);		
$pdf->MultiCell(86,24,"",'LR','C',0);	
$pdf->setXy($x+172,$y);
$pdf->MultiCell(88,24,"",'LR','C',0);	
$x=$pdf->getx();
$y=$pdf->gety();
$pdf->MultiCell(86,6,utf8_decode($_SESSION['usu_nombre']),'LRB','C',0);
$pdf->setXy($x+86,$y);		
$pdf->MultiCell(86,6,utf8_decode(""),'LRB','C',0);	
$pdf->setXy($x+172,$y);
$pdf->MultiCell(88,6,utf8_decode(""),'LRB','C',0);	
$x=$pdf->getx();
$y=$pdf->gety();
$pdf->MultiCell(86,6,"SERVIDOR/TRABAJADOR",1,'C',0);
$pdf->setXy($x+86,$y);		
$pdf->MultiCell(86,6,"JEFE INMEDIATO",1,'C',0);	
$pdf->setXy($x+172,$y);
$pdf->MultiCell(88,6,"TALENTO HUMANO",1,'C',0);	
$pdf->MultiCell(80,6,"TIPO DE PERMISO",1,'C',1);
$pdf->setXy($x+80,$y+6);		
$pdf->MultiCell(180,6,"DESCRIPCION",1,'C',1);
if($ban==0)
	$pdf->MultiCell(80,12,"OTRO",1,'C',0);
else
    $pdf->MultiCell(80,12,utf8_decode($detalle[0]),1,'C',0);
	

	
$pdf->setXy($x+80,$y+12);
if (isset($detalle[2]))	
	$pdf->MultiCell(180,12,utf8_decode($detalle[2]),1,'L',0);
else{
	if (isset($detalle[0]))
		$pdf->MultiCell(180,12,utf8_decode($obj),1,'L',0);
		
	else
		$pdf->MultiCell(180,12,"",1,'L',0);
}
	
$pdf->MultiCell(260,4,utf8_decode("Todo formulario de permiso / licencia, deberá ser presentado a la Dirección de Talento Humano con su respectiva justificación, máximo en los tres días posteriores a la emisión del mismo, caso contrario el formulario será nulo y se descontará directamente de vacaciones. ver1.0"),1,'L',1);	

$pdf->Output();

}else{
echo "No tiene permiso para visualizar el reporte";
}

}

ob_end_flush();
  ?>

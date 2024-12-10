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
require_once "../modelos/informes.php";
$info=new Informes();
$_SESSION["inf"]=$_GET["inf"];
$rspta=$info->obten_informes(2,$_SESSION["inf"], $_SESSION["peri"]);
$row = $rspta->fetch_row();
$anio=explode("PA", $row[8]);;
$_SESSION["AA"]=$anio[1];
//incluimos a la clase PDF_MC_Table
require('PDF_MC_Tablecabe.php');

//instanciamos la clase para generar el documento pdf
$pdf=new PDF_MC_Table('L','mm','letter');

//agregamos la primera pagina al documento pdf
$pdf->AddPage();

//seteamos el inicio del margen superior en 25 pixeles
$y_axis_initial=5;

$pdf->Ln();


$pdf->SetFont('Arial','B',10);
$pdf->setXy(17,48);
$pdf->Cell(80,0,'DATOS GENERALES DEL DOCENTE',0,0,'L');
$pdf->setXy(30,50);
$pdf->SetFont('Arial','B',7);
$pdf->MultiCell(35,6,"NOMBRE DEL DOCENTE",0,'L',0);
$pdf->setXy(65,50);
$pdf->SetFont('Arial','',7);	
$pdf->MultiCell(70,6,utf8_decode($_SESSION['usu_nombre']),'B','L',0);
$pdf->setXy(135,50);
$pdf->SetFont('Arial','B',7);
$pdf->MultiCell(35,6,"NO. DE CEDULA",0,'L',0);
$pdf->setXy(170,50);
$pdf->SetFont('Arial','',7);	
$pdf->MultiCell(80,6,utf8_decode($_SESSION['usu_cedula']),'B','L',0);


$pdf->setXy(30,56);
$pdf->SetFont('Arial','B',7);
$pdf->MultiCell(35,6,"DEDICACION DOCENTE",0,'L',0);
$pdf->setXy(65,56);
$pdf->SetFont('Arial','',7);	
$pdf->MultiCell(70,6,strtoupper(utf8_decode($row[6])),'B','L',0);
$pdf->setXy(135,56);
$pdf->SetFont('Arial','B',7);
$pdf->MultiCell(35,6,"TITULO PROFESIONAL",0,'L',0);
$pdf->setXy(170,56);
$pdf->SetFont('Arial','',7);	
$pdf->MultiCell(80,6,utf8_decode($row[7]),'B','L',0);

$pdf->setXy(30,62);
$pdf->SetFont('Arial','B',7);
$pdf->MultiCell(35,10,"PERIODO ACADEMICO",0,'L',0);
$pdf->setXy(65,62);
$pdf->SetFont('Arial','',7);	
//$_GET["peri"]
$pdf->MultiCell(70,10,utf8_decode($row[8]),'B','L',0);
$pdf->setXy(135,62);
$pdf->SetFont('Arial','B',7);
$pdf->MultiCell(35,10,"CARRERAS",0,'L',0);
$pdf->setXy(170,62);
$pdf->SetFont('Arial','',7);	
$pdf->MultiCell(80,5,utf8_decode($row[4]),'B','L',0);
$pdf->SetFont('Arial','B',10);
$pdf->Ln(2);
$pdf->setXY(17,80);
$pdf->Cell(80,0,'ACTIVIDADES DE DOCENCIA',0,0,'L');

$encay=$pdf->gety();
$encax=$pdf->getx();
$pdf->sety($encay+3);
$pdf->SetFont('Arial','B',7	);
$pdf->MultiCell(50,12,"CARRERA",1,'L',0);
$pdf->setXy(60,$encay+3);
$pdf->MultiCell(60,12,"ASIGNATURA",1,'L',0);
$pdf->setXy(120,$encay+3);
$pdf->MultiCell(17,4,"HORAS CLASE POR SEMANA",1,'L',0);
$pdf->setXy(137,$encay+3);
$pdf->MultiCell(58,12,"MEDIO/S DE VERIFICACION",1,'L',0);
$pdf->setXy(195,$encay+3);
$pdf->MultiCell(25,6,"PORCENTAJE DE AVANCE DEL PEA",1,'L',0);
$pdf->setXy(220,$encay+3);
$pdf->MultiCell(50,12,"OBSERVACIONES",1,'L',0);
$pdf->SetFont('Arial','',7);
$rspta=$info->obten_materias_inf($_SESSION["inf"],$_SESSION['usu_id'], $_SESSION["peri"]); // $_SESSION['usu_id'], $_SESSION["peri"]

$encay=$encay+15;
$encay1=$encay;
$encay2=$encay;
$u=0;
//$_SESSION['tmaterias']
$pdf->SetFont('Arial','',7);
$horasclase=0;
while ($reg=$rspta->fetch_object()) {
	$pdf->setXy(10,$encay);
		$y=str_pad($reg->carrera,81,'.');
	$pdf->MultiCell(50,4,utf8_decode($reg->carrera),'T','L',0);
   // $pdf->MultiCell(40,4,'123456789012345678901234567890123456789012345678901234567890123456789012345678901',1,'L',0);
	$pdf->setXy(60,$encay);
	$pdf->MultiCell(60,4,utf8_decode($reg->materia." PARALELO ".$reg->iact_paralelo),'T','L',0);
	$pdf->setXy(120,$encay);
	$horasclase=$horasclase+$reg->iact_hora;
	$pdf->MultiCell(17,4,utf8_decode($reg->iact_hora),'T','C',0);
  	$encay=$encay+8;
	$pdf->setXy(38,$encay1);
	$pdf->setXy(137,$encay1);
	$pdf->MultiCell(58,4,utf8_decode(str_pad($reg->iact_medio,81," ")),'T','L',0);

	$pdf->setXy(195,$encay1);
	$pdf->MultiCell(25,4,utf8_decode($reg->iact_porce),'T','C',0);
	$pdf->setXy(220,$encay1);
	$pdf->MultiCell(50,4,utf8_decode(str_pad($reg->iact_obj,81," ")),'T','L',0);
  	$encay1=$encay1+8;

	$u++;
  }
//otras actividades
$pdf->AddPage();
$encay1=43;
$pdf->SetFont('Arial','B',10);
$pdf->setXY(17,$encay1);
$pdf->Cell(80,0,'OTRAS ACTIVIDADES DE DOCENCIA',0,0,'L');
$encay1=$encay1+2;
$pdf->sety($encay1);
$pdf->SetFont('Arial','B',7);
$pdf->MultiCell(80,4,"DETALLE",1,'L',0);
$pdf->setXy(90,$encay1);
$pdf->MultiCell(25,4,"HORAS/SEMANA",1,'L',0);
$pdf->setXy(115,$encay1);
$pdf->MultiCell(80,4,utf8_decode("MEDIO/S DE VERIFICACIÓN"),1,'L',0);
$pdf->setXy(195,$encay1);
$pdf->MultiCell(75,4,"OBSERVACIONES",1,'L',0);
$rspta=$info->obten_oact(1,$_SESSION["inf"]);//$_SESSION["inf"]);
$data = array();
$i=0; 
$horasotras=0;
while ($reg=$rspta->fetch_object()) {
	$horasotras=$horasotras+$reg->iot_horas;
	$data[$i][0]=$reg->iot_horas;
	$data[$i][1]=$reg->iot_medios;
	$data[$i][2]=$reg->iot_obj;
	$i++;
  }

$pdf->SetFont('Arial','',6);
$pdf->setY($encay1+4);
$pdf->MultiCell(80,4,utf8_decode("PREPARACIÓN Y ACTUALIZACIÓN DE CLASES, SEMINARIOS, TALLERES, ENTRE OTROS"),'T','L',0);
$pdf->setxy(90,$encay1+4);
$pdf->MultiCell(25,4,utf8_decode($data[0][0]),'T','C',0);
$pdf->setxy(115,$encay1+4);
$pdf->MultiCell(80,4,utf8_decode($data[0][1]),'T','L',0);
$pdf->setxy(195,$encay1+4);
$pdf->MultiCell(75,4,utf8_decode($data[0][2]),'T','L',0);
$encay1=$encay1+12;
$pdf->setY($encay1);
$pdf->MultiCell(80,4,utf8_decode("DISEÑO Y ELABORACIÓN DE LIBROS, MATERIAL DIDÁCTICO, GUÍAS DOCENTES O PEA"),'T','L',0);
$pdf->setxy(90,$encay1);
$pdf->MultiCell(25,4,utf8_decode($data[1][0]),'T','C',0);
$pdf->setxy(115,$encay1);
$pdf->MultiCell(80,4,utf8_decode($data[1][1]),'T','L',0);
$pdf->setxy(195,$encay1);
$pdf->MultiCell(75,4,utf8_decode($data[1][2]),'T','L',0);
$encay1=$encay1+12;
$pdf->setY($encay1);
$pdf->MultiCell(80,4,utf8_decode("ORIENTACIÓN Y ACOMPAÑAMIENTO A TRAVÉS DE TUTORÍAS PRESENCIALES O VIRTUALES, INDIVIDUALES O GRUPALES"),'T','L',0);
$pdf->setxy(90,$encay1);
$pdf->MultiCell(25,4,utf8_decode($data[2][0]),'T','C',0);
$pdf->setxy(115,$encay1);
$pdf->MultiCell(80,4,utf8_decode($data[2][1]),'T','L',0);
$pdf->setxy(195,$encay1);
$pdf->MultiCell(75,4,utf8_decode($data[2][2]),'T','L',0);
$encay1=$encay1+12;
$pdf->setY($encay1);
$pdf->MultiCell(80,4,utf8_decode("VISITAS DE CAMPO, TUTORÍAS, DOCENCIA EN SERVICIO Y FORMACIÓN DUAL"),'T','L',0);
$pdf->setxy(90,$encay1);
$pdf->MultiCell(25,4,utf8_decode($data[3][0]),'T','C',0);
$pdf->setxy(115,$encay1);
$pdf->MultiCell(80,4,utf8_decode($data[3][1]),'T','L',0);
$pdf->setxy(195,$encay1);
$pdf->MultiCell(75,4,utf8_decode($data[3][2]),'T','L',0);
$encay1=$encay1+12;
$pdf->setY($encay1);
$pdf->MultiCell(80,4,utf8_decode("DIRECCIÓN, TUTORÍAS, SEGUIMIENTO Y EVALUACIÓN DE PRÁCTICAS O PASANTÍAS PRE PROFESIONALES"),'T','L',0);
$pdf->setxy(90,$encay1);
$pdf->MultiCell(25,4,utf8_decode($data[4][0]),'T','C',0);
$pdf->setxy(115,$encay1);
$pdf->MultiCell(80,4,utf8_decode($data[4][1]),'T','L',0);
$pdf->setxy(195,$encay1);
$pdf->MultiCell(75,4,utf8_decode($data[4][2]),'T','L',0);
$encay1=$encay1+12;
$pdf->setY($encay1);

$pdf->MultiCell(80,4,utf8_decode("PREPARACIÓN, ELABORACIÓN, APLICACIÓN Y CALIFICACIÓN DE EXÁMENES, TRABAJOS Y PRÁCTICAS"),'T','L',0);
$pdf->setxy(90,$encay1);
$pdf->MultiCell(25,4,utf8_decode($data[5][0]),'T','C',0);
$pdf->setxy(115,$encay1);
$pdf->MultiCell(80,4,utf8_decode($data[5][1]),'T','L',0);
$pdf->setxy(195,$encay1);
$pdf->MultiCell(75,4,utf8_decode($data[5][2]),'T','L',0);
$encay1=$encay1+12;
$pdf->setY($encay1);
$pdf->MultiCell(80,4,utf8_decode("DIRECCIÓN Y TUTORÍA DE TRABAJOS PARA LA OBTENCIÓN DEL TÍTULO, CON EXCEPCIÓN DE TESIS DOCTORALES O DE MAESTRÍAS DE INVESTIGACIÓN"),'T','L',0);
$pdf->setxy(90,$encay1);
$pdf->MultiCell(25,4,utf8_decode($data[6][0]),'T','C',0);
$pdf->setxy(115,$encay1);
$pdf->MultiCell(80,4,utf8_decode($data[6][1]),'T','L',0);
$pdf->setxy(195,$encay1);
$pdf->MultiCell(75,4,utf8_decode($data[6][2]),'T','L',0);
$encay1=$encay1+12;
$pdf->setY($encay1);
$pdf->MultiCell(80,4,utf8_decode("DIRECCIÓN Y PARTICIPACIÓN DE PROYECTOS DE EXPERIMENTACIÓN E INNOVACIÓN DOCENTE"),'T','L',0);
$pdf->setxy(90,$encay1);
$pdf->MultiCell(25,4,utf8_decode($data[7][0]),'T','C',0);
$pdf->setxy(115,$encay1);
$pdf->MultiCell(80,4,utf8_decode($data[7][1]),'T','L',0);
$pdf->setxy(195,$encay1);
$pdf->MultiCell(75,4,utf8_decode($data[7][2]),'T','L',0);
$encay1=$encay1+12;
$pdf->setY($encay1);
$pdf->MultiCell(80,4,utf8_decode("DISEÑO E IMPARTICIÓN DE CURSOS DE EDUCACIÓN CONTINUA O DE CAPACITACIÓN Y ACTUALIZACIÓN"),'T','L',0);
$pdf->setxy(90,$encay1);
$pdf->MultiCell(25,4,utf8_decode($data[8][0]),'T','C',0);
$pdf->setxy(115,$encay1);
$pdf->MultiCell(80,4,utf8_decode($data[8][1]),'T','L',0);
$pdf->setxy(195,$encay1);
$pdf->MultiCell(75,4,utf8_decode($data[8][2]),'T','L',0);
$encay1=$encay1+12;
$pdf->setY($encay1);
$pdf->MultiCell(80,4,utf8_decode("PARTICIPACIÓN EN ACTIVIDADES DE PROYECTOS SOCIALES, ARTÍSTICOS, PRODUCTIVOS Y EMPRESARIALES DE VINCULACIÓN CON LA SOCIEDAD ARTICULADOS A LA DOCENCIA E INNOVACIÓN EDUCATIVA;"),'T','L',0);
$pdf->setxy(90,$encay1);
$pdf->MultiCell(25,4,utf8_decode($data[9][0]),'T','C',0);
$pdf->setxy(115,$encay1);
$pdf->MultiCell(80,4,utf8_decode($data[9][1]),'T','L',0);
$pdf->setxy(195,$encay1);
$pdf->MultiCell(75,4,utf8_decode($data[9][2]),'T','L',0);
$encay1=$encay1+12;
$pdf->setY($encay1);
$pdf->MultiCell(80,4,utf8_decode("PARTICIPACIÓN Y ORGANIZACIÓN DE COLECTIVOS ACADÉMICOS DE DEBATE, CAPACITACIÓN O INTERCAMBIO DE METODOLOGÍAS Y EXPERIENCIAS DE ENSEÑANZA;"),'T','L',0);
$pdf->setxy(90,$encay1);
$pdf->MultiCell(25,4,utf8_decode($data[10][0]),'T','C',0);
$pdf->setxy(115,$encay1);
$pdf->MultiCell(80,4,utf8_decode($data[10][1]),'T','L',0);
$pdf->setxy(195,$encay1);
$pdf->MultiCell(75,4,utf8_decode($data[10][2]),'T','L',0);
$encay1=$encay1+12;
$pdf->setY($encay1);
$pdf->MultiCell(80,4,utf8_decode("USO PEDAGÓGICO DE LA INVESTIGACIÓN Y LA SISTEMATIZACIÓN COMO SOPORTE O PARTE DE LA ENSEÑANZA;"),'T','L',0);
$pdf->setxy(90,$encay1);
$pdf->MultiCell(25,4,utf8_decode($data[11][0]),'T','C',0);
$pdf->setxy(115,$encay1);
$pdf->MultiCell(80,4,utf8_decode($data[11][1]),'T','L',0);
$pdf->setxy(195,$encay1);
$pdf->MultiCell(75,4,utf8_decode($data[11][2]),'T','L',0);
$encay1=$encay1+12;
$pdf->setY($encay1);

$pdf->AddPage();
$encay1=45;
$pdf->SetFont('Arial','B',10);
$pdf->setXY(17,$encay1);
$pdf->Cell(80,0,'ACTIVIDADES DE INVESTIGACION',0,0,'L');
$pdf->SetFont('Arial','B',7);
$encay=$pdf->gety();
$rspta=$info->obt_ract(1,$_SESSION["inf"],0);//$_SESSION["inf"]);
$pdf->SetFont('Arial','',7);
$pdf->setY($encay1+4);
$pdf->MultiCell(60,4,utf8_decode("CARRERA"),1,'L',0);	
$pdf->setxY(70,$encay1+4);
$pdf->MultiCell(100,4,utf8_decode("PROYECTO"),1,'L',0);	
$pdf->setxY(170,$encay1+4);
$pdf->MultiCell(12,4,utf8_decode("HORAS"),1,'L',0);	
$pdf->setxY(182,$encay1+4);
$pdf->MultiCell(87,4,utf8_decode("OBSERVACION"),1,'L',0);	
$pdf->SetFont('Arial','',6);
$pdf->setY($encay1+8);
$horasinv=0;
while ($reg=$rspta->fetch_object()) {
	$pdf->MultiCell(60,4,utf8_decode($reg->ige_carrera),0,'L',0);	
	$pdf->setxY(70,$encay1+8);
	$pdf->MultiCell(100,4,utf8_decode($reg->ige_proyecto),0,'L',0);	
	$pdf->setxY(170,$encay1+8);
	$horasinv=$reg->ige_horas;
	$pdf->MultiCell(12,4,utf8_decode($reg->ige_horas),0,'C',0);	
	$pdf->setxY(182,$encay1+8);
	$pdf->MultiCell(87,4,utf8_decode($reg->ige_obj),0,'L',0);	
  }
$encay1=$pdf->gety();
$pdf->SetFont('Arial','B',10);
$encay1=$encay1+4;
$pdf->setXY(17,$encay1);
$pdf->Cell(80,0,'ACTIVIDADES DE DIRECCION / GESTION / OTRAS',0,0,'L');
$pdf->SetFont('Arial','B',7);
$pdf->setY($encay1);
$rspta=$info->obt_ract(1,$_SESSION["inf"],1);//$_SESSION["inf"]);
$pdf->SetFont('Arial','',7);

$pdf->setY($encay1+4);
$pdf->MultiCell(60,4,utf8_decode("CARRERA"),1,'L',0);	
$pdf->setxY(70,$encay1+4);
$pdf->MultiCell(100,4,utf8_decode("GESTION / DIRECCION / OTRAS"),1,'L',0);	
$pdf->setxY(170,$encay1+4);
$pdf->MultiCell(12,4,utf8_decode("HORAS"),1,'L',0);	
$pdf->setxY(182,$encay1+4);
$pdf->MultiCell(87,4,utf8_decode("OBSERVACION"),1,'L',0);	
$pdf->SetFont('Arial','',6);
$pdf->setY($encay1+8);
$horasgest=0;
$encayf=$pdf->gety();
while ($reg=$rspta->fetch_object()) {
	$pdf->MultiCell(60,4,utf8_decode($reg->ige_carrera),0,'L',0);	
	$pdf->setxY(70,$encay1+8);
	$pdf->MultiCell(100,4,utf8_decode($reg->ige_proyecto),0,'L',0);	
	$encayf=$pdf->gety()+8;
	$pdf->setxY(170,$encay1+8);
	$horasget=$reg->ige_horas;
	$pdf->MultiCell(12,4,utf8_decode($reg->ige_horas),0,'C',0);	
	$horasgest=$reg->ige_horas;
	$pdf->setxY(182,$encay1+8);
	$pdf->MultiCell(87,4,utf8_decode($reg->ige_obj),0,'L',0);	
  }
if ($encayf>$pdf->gety())
    $pdf->setXY(17,$encayf);	
else
	$pdf->setX(17);
$encay1=$pdf->gety()+8;
$pdf->SetFont('Arial','B',10);
$pdf->Ln();


$pdf->Cell(80,0,utf8_decode("SITUACIONES O PROBLEMAS DETECTADOS"),0,0,'C');
$encay1=$pdf->gety();
$rspta=$info->obt_ract(1,$_SESSION["inf"],2);//$_SESSION["inf"]);
$pdf->SetFont('Arial','',7);
$pdf->setY($encay1+4);
$pdf->SetFont('Arial','',6);
while ($reg=$rspta->fetch_object()) {
	$pdf->MultiCell(260,4,utf8_decode($reg->ige_carrera),1,'L',0);	
  }
$encay1=$pdf->gety();
$rspta=$info->obt_ract(1,$_SESSION["inf"],3);//$_SESSION["inf"]);
$pdf->SetFont('Arial','B',10);
$pdf->Ln(4);
$encay1=$encay1+8;
$pdf->setXY(17,$encay1);
$pdf->Cell(80,0,utf8_decode("OBSERVACIONES"),0,0,'L');
//$pdf->MultiCell(260,4,utf8_decode("SUGERENCIA DE ACCIONES A TOMAR PARA ATENCIÓN A LAS SITUACIONES O PROBLEMAS DETECTADOS"),1,'L',0);	

$pdf->setY($encay1+4);
$pdf->SetFont('Arial','',6);
while ($reg=$rspta->fetch_object()) {
	$pdf->MultiCell(260,4,utf8_decode($reg->ige_carrera),1,'L',0);	
  }
$pdf->SetFont('Arial','B',10);
$pdf->Ln();
$encay1=$encay1+8;
$pdf->setX(17,$encay1);
$pdf->Cell(80,0,utf8_decode("RESUMEN"),0,0,'C');
$encay1=$pdf->gety();
$pdf->SetFont('Arial','',7);
$pdf->setXY(60,$encay1+4);

$pdf->MultiCell(100,4,utf8_decode("IMPARTICIÓN DE CLASES PRESENCIALES, VIRTUALES O EN LÍNEA, DE CARÁCTER TEÓRICO O PRÁCTICO, EN LA INSTITUCIÓN O FUERA DE ELLA, BAJO
RESPONSABILIDAD Y DIRECCIÓN DE LA MISMA."),1,'L',0);
$pdf->setXY(160,$encay1+4);
$encay1=$pdf->gety();
$pdf->MultiCell(20,12,utf8_decode($horasclase),1,'C',0);

$encay1=$pdf->gety();
$pdf->setXY(60,$encay1);	
$pdf->MultiCell(100,4,utf8_decode("HORAS DEDICADAS A LAS DEMÁS ACTIVIDADES DE DOCENTE"),1,'L',0);
$pdf->setXY(160,$encay1);
$pdf->MultiCell(20,4,utf8_decode($horasotras),1,'C',0);
$pdf->setxY(60,$encay1+4);
$pdf->MultiCell(100,4,utf8_decode("HORAS DE INVESTIGACIÓN"),1,'L',0);
$pdf->setXY(160,$encay1+4);
$pdf->MultiCell(20,4,utf8_decode($horasinv),1,'C',0);
$pdf->setxY(60,$encay1+8);
$pdf->MultiCell(100,4,utf8_decode("ACTIVIDADES DE DIRECCIÓN ACADÉMICA Y GESTIÓN"),1,'L',0);
$pdf->setXY(160,$encay1+8);
$pdf->MultiCell(20,4,utf8_decode($horasgest),1,'C',0);
$pdf->setxY(60,$encay1+12);
$pdf->MultiCell(100,4,utf8_decode("TOTAL"),0,'R',0);
$pdf->setXY(160,$encay1+12);
$pdf->MultiCell(20,4,utf8_decode($horasgest+$horasclase+$horasotras+$horasinv),1,'C',0);

//firmas
$pdf->AddPage();
$pdf->SetFont('Arial','',6);
$fir=$pdf->gety()+8	;
$pdf->setxy(17,$fir);
$pdf->MultiCell(45,4,utf8_decode("Elaborado por:"),'LTR','C',0);
$pdf->setx(17);
$pdf->MultiCell(45,15,utf8_decode(""),'LR','C',0);
$pdf->setx(17);
$pdf->MultiCell(45,4,utf8_decode($_SESSION['usu_nombre']),'LR','C',0);
$pdf->setx(17);
$pdf->SetFont('Arial','B',5);
$pdf->MultiCell(45,4,utf8_decode("DOCENTE"),'LBR','C',0);
$tope=$pdf->getx();
//$rspta=$info->obt_firmas($_SESSION["usu_id"],$_SESSION["peri"]);//$_SESSION["inf"]);
$pdf->SetFont('Arial','',6	);
//while ($reg=$rspta->fetch_object()) {
    $pdf->setxy($tope+53,$fir);
	$pdf->MultiCell(45,4,utf8_decode("Verificado por:"),'LTR','C',0);
    $pdf->setxy($tope+53,$fir);
	$pdf->MultiCell(45,19,utf8_decode(""),'LR','C',0);
    $pdf->setx($tope+53);
	$pdf->MultiCell(45,4,utf8_decode("Mgs Carmen Ávila"),'LR','C',0);
    $pdf->setx($tope+53);
	$pdf->SetFont('Arial','B',5);
	$pdf->MultiCell(45,2,utf8_decode("COORDINADORA  "),'LR','C',0);
	$pdf->setx($tope+53);
	$pdf->MultiCell(45,2,utf8_decode("DE CARRERAS"),'LBR','C',0);
	$pdf->SetFont('Arial','',6);
	$tope=$tope+46;

//}
$pdf->SetFont('Arial','',6);
$fir=$pdf->gety()+8	;
$pdf->setxy(120,$fir);
$pdf->MultiCell(45,4,utf8_decode("Verificado por:"),'LTR','C',0);
$pdf->setx(120);
$pdf->MultiCell(45,15,utf8_decode(""),'LR','C',0);
$pdf->setx(120);
$pdf->MultiCell(45,4,utf8_decode(" Ab. Mercy Haro Mgs. "),'LR','C',0);
$pdf->setx(120);
$pdf->SetFont('Arial','B',5);
$pdf->MultiCell(45,4,utf8_decode("RECURSOS HUMANOS"),'LBR','C',0);
$tope=$pdf->getx();

$pdf->SetFont('Arial','',6);
$fir=$pdf->gety()+8	;
$pdf->setxy(120,$fir);
$pdf->MultiCell(45,4,utf8_decode("Validado por:"),'LTR','C',0);
$pdf->setx(120);
$pdf->MultiCell(45,15,utf8_decode(""),'LR','C',0);
$pdf->setx(120);
$pdf->MultiCell(45,4,utf8_decode("Dr. José Antonio Pijal"),'LR','C',0);
$pdf->setx(120);
$pdf->SetFont('Arial','B',5);
$pdf->MultiCell(45,4,utf8_decode("RECTOR"),'LBR','C',0);
$tope=$pdf->getx();

$pdf->Output();

}else{
echo "No tiene permiso para visualizar el reporte";
}

}

ob_end_flush();
  ?>

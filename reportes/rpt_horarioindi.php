<?php 
//activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id())<1) 
  session_start();

if (!isset($_SESSION['usu_nombre'])) {
  echo "debe ingresar al sistema correctamente para vosualizar el reporte";
}else{

if ($_SESSION['Activos']==1 or $_SESSION['Actas']==1 ){

//incluimos a la clase PDF_MC_Table
require('PDF_MC_Table.php');

//instanciamos la clase para generar el documento pdf
$pdf=new PDF_MC_Table('L','mm','letter');

//agregamos la primera pagina al documento pdf
$pdf->AddPage();

//seteamos el inicio del margen superior en 25 pixeles
$y_axis_initial=5;

$pdf->Ln();
$pdf->SetFont('Arial','B',14);


require_once "../modelos/Ingreso.php";
$ingreso=new Ingreso();
//$pdf->Cell(280,0,'HORARIO DE TRABAJO DOCENTE',0,0,'C');
if (!isset($_GET['idhor'])) {
 $docente=$_SESSION['usu_id'];
 $nombredoc=$_SESSION['usu_nombre'];
}
else
{
 $ii=$ingreso->obten_id($_GET['idhor']);
 $docente=$_SESSION['docenteh'];
 $nombredoc=$_SESSION["docente"]; 
}
$rspta=$ingreso->encabezado(0,$docente);
$reg = $rspta->fetch_object();
$nlunes=$reg->lunes;
$nmartes=$reg->martes;
$nmiercoles=$reg->miercoles;
$njueves=$reg->jueves;
$nviernes=$reg->viernes;
$nsabado=$reg->sabado;
$periodo=$reg->periodo;
$totalhc=$nlunes+$nmartes+$nmiercoles+$njueves+$nviernes+$nsabado;

$pdf->Ln(3);
$pdf->Image('../files/img/ist.png',10,5,50);
$pdf->Image('../files/img/ist17j.png',260,5,14);
$pdf->SetFont('Arial','B',14);
$pdf->Ln();
$pdf->Cell(280,0,'HORARIO DE TRABAJO DOCENTE',0,0,'C');
$pdf->Ln();
$pdf->Cell(280,10,'PERIODO ACADEMICO: '.$periodo,0,0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,5,'DOCENTE: ',0,0,'L');
$pdf->Cell(150,5,$nombredoc,0,0,'L');
$pdf->Cell(80,5,'HORAS DOCENTE:  '.$totalhc,0,0,'L');
$pdf->Ln();

//creamos las celdas para los titulos de cada columna y le asignamos un fondo gris y el tipo de letra
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(13,6,utf8_decode('Hora'),1,0,'C',1);
$pdf->Cell(42,6,utf8_decode('Lunes'),1,0,'C',1);
$pdf->Cell(42,6,utf8_decode('Martes'),1,0,'C',1);
$pdf->Cell(42,6,utf8_decode('Miércoles'),1,0,'C',1);
$pdf->Cell(42,6,utf8_decode('Jueves'),1,0,'C',1);
$pdf->Cell(42,6,utf8_decode('Viernes'),1,0,'C',1);
$pdf->Cell(42,6,utf8_decode('Sabado'),1,0,'C',1);
//$pdf->Cell(35,6,utf8_decode('Foto'),1,0,'C',1);
$pdf->Ln(6);
$pdf->SetFillColor(255,255,255);
$rspta=$ingreso->presentar_horario(1,$docente);
$i=0;
while ($reg= $rspta->fetch_object()) {
	    $pdf->SetFont('Arial','B',5);
		$pdf->MultiCell(20,9,utf8_decode($reg->deth_hora),1,'L',1);
		$pdf->SetFont('Arial','B',5);
		
		$sig = $pdf->GetY();
		$ori = $pdf->GetX();
		$pdf->GetY();
		//if ($i<135)
			$pdf->SetY($i+34);
		//else	
		//	$pdf->SetY(34);
		$pdf->SetX(23);
		if (is_null($reg->deth_lunes) or $reg->deth_lunes=='' )
			$pdf->MultiCell(42,9,'',1,0,'C',1);
		else{
			$inf=explode("/", $reg->deth_lunes);
			$rspta1=$ingreso->presentar_info($inf[0],$inf[2]);
			$reg1=$rspta1->fetch_object();
			$pdf->MultiCell(42,3,utf8_decode(str_pad($reg1->nom_carrera.' / '.$reg1->materia.' / '.$inf[1].' / '.$reg1->aula,150," ")),1,'L',1);
			
		}
		$pdf->SetY($sig);
		$sig = $pdf->GetY();
		$pdf->GetY();
		$pdf->SetY($i+34);
		$pdf->SetX(65);
		if (is_null($reg->deth_martes) or $reg->deth_martes=='' )
			$pdf->MultiCell(42,9,'',1,0,'C',1);
		else{
			$inf=explode("/", $reg->deth_martes);
			$rspta1=$ingreso->presentar_info($inf[0],$inf[2]);
			$reg1=$rspta1->fetch_object();
			$pdf->MultiCell(42,3,utf8_decode(str_pad($reg1->nom_carrera.' / '.$reg1->materia.' / '.$inf[1].' / '.$reg1->aula,150," ")),1,'L',1);
			
		}
		$pdf->SetY($sig);
		$sig = $pdf->GetY();
		$pdf->GetY();
		$pdf->SetY($i+34);
		$pdf->SetX(107);
		if (is_null($reg->deth_miercoles) or $reg->deth_miercoles=='' )
			$pdf->MultiCell(42,9,utf8_decode(''),1,0,'C',1);
		else{
			$inf=explode("/", $reg->deth_miercoles);
			$rspta1=$ingreso->presentar_info($inf[0],$inf[2]);
			$reg1=$rspta1->fetch_object();
			$pdf->MultiCell(42,3,utf8_decode(str_pad($reg1->nom_carrera.' / '.$reg1->materia.' / '.$inf[1].' / '.$reg1->aula,150," ")),1,'L',1);
			
		}
		$pdf->SetY($sig);
		$sig = $pdf->GetY();
		$pdf->GetY();
		$pdf->SetY($i+34);
		$pdf->SetX(149);
		if (is_null($reg->deth_jueves) or $reg->deth_jueves=='' )
			$pdf->MultiCell(42,9,'',1,0,'C',1);
		else{
			$inf=explode("/", $reg->deth_jueves);
			$rspta1=$ingreso->presentar_info($inf[0],$inf[2]);
			$reg1=$rspta1->fetch_object();
			$pdf->MultiCell(42,3,utf8_decode(str_pad($reg1->nom_carrera.' / '.$reg1->materia.' / '.$inf[1].' / '.$reg1->aula,150," ")),1,'L',1);
			
		}
		$pdf->SetY($sig);
		$sig = $pdf->GetY();
		$pdf->GetY();
		$pdf->SetY($i+34);
		$pdf->SetX(191);
		if (is_null($reg->deth_viernes) or $reg->deth_viernes=='' )
			$pdf->MultiCell(42,9,'',1,0,'C',1);
		else{
			$inf=explode("/", $reg->deth_viernes);
			$rspta1=$ingreso->presentar_info($inf[0],$inf[2]);
			$reg1=$rspta1->fetch_object();
			$pdf->MultiCell(42,3,utf8_decode(str_pad($reg1->nom_carrera.' / '.$reg1->materia.' / '.$inf[1].' / '.$reg1->aula,150," ")),1,'L',1);
			
		}
		$pdf->SetY($sig);
		$sig = $pdf->GetY();
		$pdf->GetY();
		$pdf->SetY($i+34);
		$pdf->SetX(233);
		if (is_null($reg->deth_sabado) or $reg->deth_sabado=='' )
			$pdf->MultiCell(42,9,'',1,0,'C',1);
		else{
			$inf=explode("/", $reg->deth_sabado);
			$rspta1=$ingreso->presentar_info($inf[0],$inf[2]);
			$reg1=$rspta1->fetch_object();
			$pdf->MultiCell(42,3,utf8_decode(str_pad($reg1->nom_carrera.' / '.$reg1->materia.' / '.$inf[1].' / '.$reg1->aula,150," ")),1,'L',1);
			
		}
		
			$i=$i+9;
			
		$pdf->SetY($sig);

}
$rspta=$ingreso->encabezado(1,$docente);
$reg = $rspta->fetch_object();
$olunes=$reg->lunes;
$omartes=$reg->martes;
$omiercoles=$reg->miercoles;
$ojueves=$reg->jueves;
$oviernes=$reg->viernes;
$osabado=$reg->sabado;
$sig=$pdf->GetY();
$pdf->SetFont('Arial','B',8);
$pdf->MultiCell(13,9,'TOTAL',1,0,'C',1);
$pdf->SetY($i+34);
$pdf->SetX(23);
$pdf->MultiCell(42,9,'THD: '.$nlunes.' THC: '.$olunes.' T: '.intval($nlunes+$olunes),1,'C','L',1);
$pdf->SetY($sig);
$sig = $pdf->GetY();
$pdf->GetY();
$pdf->SetY($i+34);
$pdf->SetX(65);
$pdf->MultiCell(42,9,'THD: '.$nmartes.' THC: '.$omartes.' T: '.intval($nmartes+$omartes),1,'C','L',1);
$pdf->SetY($sig);
$sig = $pdf->GetY();
$pdf->GetY();
$pdf->SetY($i+34);
$pdf->SetX(107);
$pdf->MultiCell(42,9,'THD: '.$nmiercoles.' THC: '.$omiercoles.' T: '.intval($nmiercoles+$omiercoles),1,'C','L',1);
$pdf->SetY($sig);
$sig = $pdf->GetY();
$pdf->GetY();
$pdf->SetY($i+34);
$pdf->SetX(149);
$pdf->MultiCell(42,9,'THD: '.$njueves.' THC: '.$ojueves.' T: '.intval($njueves+$ojueves),1,'C','L',1);
$pdf->SetY($sig);
$sig = $pdf->GetY();
$pdf->GetY();
$pdf->SetY($i+34);
$pdf->SetX(191);
$pdf->MultiCell(42,9,'THD: '.$nviernes.' THC: '.$oviernes.' T: '.intval($nviernes+$oviernes),1,'C','L',1);
$pdf->SetY($sig);
$sig = $pdf->GetY();
$pdf->GetY();
$pdf->SetY($i+34);
$pdf->SetX(233);
$pdf->MultiCell(42,9,'THD: '.$nsabado.' THC: '.$osabado.' T: '.intval($nsabado+$osabado),1,'C','L',1);


$pdf->SetY($i+51);
$pdf->SetX(13);
$pdf->MultiCell(300,9,'Firma Docente                                                                               Firma Coordinador                                                                                           Firma Secretaria',0,'L','L',1);

$pdf->SetY($i+51);
$pdf->SetX(38);
$pdf->Line(38, $i+60, 88, $i+60);

$pdf->SetY($i+51);
$pdf->SetX(124);
$pdf->Line(124,$i+60, 174, $i+60);
$pdf->SetY($i+51);
$pdf->SetX(220);
$pdf->Line(220, $i+60, 270, $i+60);

$pdf->Output();

}else{
echo "No tiene permiso para visualizar el reporte";
}

}

ob_end_flush();
  ?>

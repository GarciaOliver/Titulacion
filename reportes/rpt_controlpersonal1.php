<?php 
//activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id())<1) 
  session_start();

if (!isset($_SESSION['usu_nombre'])) {
  echo "debe ingresar al sistema correctamente para vosualizar el reporte";
}else{

if ($_SESSION['Generación']==1 ){

//incluimos a la clase PDF_MC_Table
require('PDF_MC_Table.php');

//instanciamos la clase para generar el documento pdf
$pdf=new PDF_MC_Table('P','mm','letter');

//agregamos la primera pagina al documento pdf
$pdf->AddPage();

//seteamos el inicio del margen superior en 25 pixeles
$y_axis_initial=5;

$pdf->Ln();
$pdf->SetFont('Arial','B',14);


require_once "../modelos/aulas.php";
$aula=new aulas();
//$pdf->Cell(280,0,'HORARIO DE TRABAJO DOCENTE',0,0,'C');
 $hora=$_GET['hora'];
 $dia=$_GET['dia'];

$rspta=$aula->obten_control($hora,$dia);
$pdf->Ln(3);
$pdf->Image('../files/img/ist.png',10,5,50);
$pdf->Image('../files/img/ist17j.png',190,5,14);
$pdf->SetFont('Arial','B',12);
$pdf->Ln();
if ($dia==1){ $dia= "LUNES"; }
if ($dia==2){ $dia= "MARTES"; }
if ($dia==3){ $dia= "MIERCOLES"; }
if ($dia==4){ $dia= "JUEVES"; }
if ($dia==5){ $dia= "VIERNES"; }
if ($dia==6){ $dia= "SABADO"; }

$pdf->Cell(220,0,'Control de Personal del dia: '.$dia.' hora: '.$hora  ,0,0,'C');
$pdf->Ln(15);
//creamos las celdas para los titulos de cada columna y le asignamos un fondo gris y el tipo de letra
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,6,utf8_decode('Nro'),1,0,'C',1);
$pdf->Cell(100,6,utf8_decode('Docente'),1,0,'C',1);
$pdf->Cell(52,6,utf8_decode('Firma'),1,0,'C',1);
$pdf->Cell(30,6,utf8_decode('Hora'),1,0,'C',1);
//$pdf->Cell(35,6,utf8_decode('Foto'),1,0,'C',1);
$pdf->Ln(6);
$pdf->SetFillColor(255,255,255);
$i=0;
while ($reg= $rspta->fetch_object()) {
	    $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,15,utf8_decode($reg->usu_id),1,0,'C',1);
		$pdf->Cell(100,15,utf8_decode($reg->usu_nombre),1,0,'L',1);
		$pdf->Cell(52,15,utf8_decode(''),1,0,'C',1);
		$pdf->Cell(30,15,utf8_decode(''),1,0,'C',1);
//$pdf->Cell(35,6,utf8_decode('Foto'),1,0,'C',1);
$pdf->Ln(15);
	 
}
$pdf->Output();

}else{
echo "No tiene permiso para visualizar el reporte";
}

}

ob_end_flush();
  ?>

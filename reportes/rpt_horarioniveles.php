<?php 
//activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id())<1) 
  session_start();

if (!isset($_SESSION['usu_nombre'])) {
  echo "debe ingresar al sistema correctamente para vosualizar el reporte";
}else{

if ($_SESSION['Actas']==1){

//incluimos a la clase PDF_MC_Table
require('PDF_MC_Table.php');

//instanciamos la clase para generar el documento pdf
$pdf=new PDF_MC_Table('L','mm','letter');

require_once "../modelos/aulas.php";
$ingreso=new aulas();
//$pdf->Cell(280,0,'HORARIO DE TRABAJO DOCENTE',0,0,'C');
$carrera=$_SESSION['coor'];
$data[0][0]='7:00 - 8:00';
$data[1][0]='8:00 - 9:00';
$data[2][0]='9:00 - 10:00';
$data[3][0]='10:00 - 11:00';
$data[4][0]='11:00 - 12:00';
$data[5][0]='12:00 - 13:00';
$data[6][0]='13:00 - 14:00';
$data[7][0]='14:00 - 15:00';
$data[8][0]='15:00 - 16:00';
$data[9][0]='16:00 - 17:00';
$data[10][0]='17:00 - 18:00';
$data[11][0]='18:00 - 19:00';
$data[12][0]='19:00 - 20:00';
$data[13][0]='20:00 - 21:00';
$data[14][0]='21:00 - 22:00';




$rspta=$ingreso->niveles($carrera);
while ($reg=$rspta->fetch_object()) {
	$data[0][1]=$data[0][2]=$data[0][3]=$data[0][4]=$data[0][5]=$data[0][6]='';
	$data[1][1]=$data[1][2]=$data[1][3]=$data[1][4]=$data[1][5]=$data[1][6]='';
	$data[2][1]=$data[2][2]=$data[2][3]=$data[2][4]=$data[2][5]=$data[2][6]='';
	$data[3][1]=$data[3][2]=$data[3][3]=$data[3][4]=$data[3][5]=$data[3][6]='';
	$data[4][1]=$data[4][2]=$data[4][3]=$data[4][4]=$data[4][5]=$data[4][6]='';
	$data[5][1]=$data[5][2]=$data[5][3]=$data[5][4]=$data[5][5]=$data[5][6]='';
	$data[6][1]=$data[6][2]=$data[6][3]=$data[6][4]=$data[6][5]=$data[6][6]='';
	$data[7][1]=$data[7][2]=$data[7][3]=$data[7][4]=$data[7][5]=$data[7][6]='';
	$data[8][1]=$data[8][2]=$data[8][3]=$data[8][4]=$data[8][5]=$data[8][6]='';
	$data[9][1]=$data[9][2]=$data[9][3]=$data[9][4]=$data[9][5]=$data[9][6]='';
	$data[10][1]=$data[10][2]=$data[10][3]=$data[10][4]=$data[10][5]=$data[10][6]='';
	$data[11][1]=$data[11][2]=$data[11][3]=$data[11][4]=$data[11][5]=$data[11][6]='';
	$data[12][1]=$data[12][2]=$data[12][3]=$data[12][4]=$data[12][5]=$data[12][6]='';
	$data[13][1]=$data[13][2]=$data[13][3]=$data[13][4]=$data[13][5]=$data[13][6]='';
	$data[14][1]=$data[14][2]=$data[14][3]=$data[14][4]=$data[14][5]=$data[14][6]='';
	//agregamos la primera pagina al documento pdf
	$pdf->AddPage();
	//seteamos el inicio del margen superior en 25 pixeles
	$pdf->SetY(12);
	$rspta1=$ingreso->cursos($carrera,1,$reg->nivel,$reg->paralelo);
	while ($reg1=$rspta1->fetch_object()) {
			for ($i=0; $i<15; $i++){
				  if ($data[$i][0]==$reg1->deth_hora){
					   $data[$i][1]=$reg1->cat_nombre;
					  }
				
				}
		}
	$rspta1=$ingreso->cursos($carrera,2,$reg->nivel,$reg->paralelo);
	while ($reg1=$rspta1->fetch_object()) {
			for ($i=0; $i<15; $i++){
				  if ($data[$i][0]==$reg1->deth_hora){
					   $data[$i][2]=$reg1->cat_nombre;
					  }
				  
				}
		}
	$rspta1=$ingreso->cursos($carrera,3,$reg->nivel,$reg->paralelo);
	while ($reg1=$rspta1->fetch_object()) {
			for ($i=0; $i<15; $i++){
				if ($data[$i][0]==$reg1->deth_hora){
					   $data[$i][3]=$reg1->cat_nombre;
					  }
				
				}
		}
	$rspta1=$ingreso->cursos($carrera,4,$reg->nivel,$reg->paralelo);
	while ($reg1=$rspta1->fetch_object()) {
			for ($i=0; $i<15; $i++){
				if ($data[$i][0]==$reg1->deth_hora){
					   $data[$i][4]=$reg1->cat_nombre;
					  }
					
				}
		}		
	$rspta1=$ingreso->cursos($carrera,5,$reg->nivel,$reg->paralelo);
	while ($reg1=$rspta1->fetch_object()) {
			for ($i=0; $i<15; $i++){
				 if ($data[$i][0]==$reg1->deth_hora){
					   $data[$i][5]=$reg1->cat_nombre;
					  }
					
				}
		}
	$rspta1=$ingreso->cursos($carrera,6,$reg->nivel,$reg->paralelo);
	while ($reg1=$rspta1->fetch_object()) {
			for ($i=0; $i<15; $i++){
				  if ($data[$i][0]==$reg1->deth_hora){
					   $data[$i][6]=$reg1->cat_nombre;
					  }
					 
				}
		}

	$pdf->Ln();
	$pdf->SetFont('Arial','B',14);

	$pdf->Ln(3);
	$pdf->Image('../files/img/ist.png',10,5,50);
	$pdf->Image('../files/img/ist17j.png',260,5,14);
	$pdf->SetFont('Arial','B',14);
	$pdf->Ln();
	$pdf->SetY(12);
	$pdf->Cell(280,0,'HORARIO DE TRABAJO DOCENTE',0,0,'C');
	$pdf->Ln();
	$pdf->Cell(280,10,'PERIODO ACADEMICO: '.$reg->periodo,0,0,'C');
	$pdf->Ln();
	$pdf->SetFont('Arial','B',14);
	$pdf->Cell(20,5,'Nivel: '.$reg->nivel,0,0,'L');
	$pdf->Cell(150,5,'Paralelo: '.$reg->paralelo,0,0,'L');
	$pdf->Cell(80,5,'',0,0,'L');
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
	$i=0;
	$pdf->SetFont('Arial','B',5);
	$ant1=$pdf->SetX(10);
	$ant=$pdf->GetY(); 		    
    for($j=0;$j<15; $j++){
			$pdf->MultiCell(13,9.5,utf8_decode($data[$j][0]),'LTR','L',1);
			$pdf->Sety($ant+$i);
			$pdf->SetX(23);
			if($data[$j][1]=='')
				$pdf->MultiCell(42,9.5,'','LTR','L',0);
			else
				$pdf->MultiCell(42,3,utf8_decode(str_pad($data[$j][1],160,' ')),'LTR','L',0);
			$pdf->Sety($ant+$i);
			$pdf->SetX(65);
			if($data[$j][2]=='')
				$pdf->MultiCell(42,9.5,'','LTR','L',0);
			else
				$pdf->MultiCell(42,3,utf8_decode(str_pad($data[$j][2],160,' ')),'LTR','L',0);
			$pdf->Sety($ant+$i);
			$pdf->SetX(107);
			if($data[$j][3]=='')
				$pdf->MultiCell(42,9.5,'','LTR','L',0);
			else
				$pdf->MultiCell(42,3,utf8_decode(str_pad($data[$j][3],160,' ')),'LTR','L',0);
			$pdf->Sety($ant+$i);
			$pdf->SetX(149);
			if($data[$j][4]=='')
				$pdf->MultiCell(42,9.5,'','LTR','L',0);
			else
				$pdf->MultiCell(42,3,utf8_decode(str_pad($data[$j][4],160,' ')),'LTR','L',0);
			$pdf->Sety($ant+$i);
			$pdf->SetX(191);
			if($data[$j][5]=='')
				$pdf->MultiCell(42,9.5,'','LTR','L',0);
			else
				$pdf->MultiCell(42,3,utf8_decode(str_pad($data[$j][5],160,' ')),'LTR','L',0);

			$pdf->Sety($ant+$i);
			$pdf->SetX(233);
			if($data[$j][6]=='')
				$pdf->MultiCell(42,9.5,'','LTR','L',0);
			else
				$pdf->MultiCell(42,3,utf8_decode(str_pad($data[$j][6],160,' ')),'LTR','L',0);
			
			$pdf->SetX(10);
			$i=$i+9.5;
		   }
   
}
$pdf->Output();

}
	else{
		echo "No tiene permiso para visualizar el reporte";
	}

}

ob_end_flush();
  ?>

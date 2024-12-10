<?php 
//activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id())<1) 
  session_start();

if (!isset($_SESSION['usu_nombre'])) {
  echo "debe ingresar al sistema correctamente para vosualizar el reporte";
}else{

if ($_SESSION['Generación']==1){

//incluimos a la clase PDF_MC_Table
require('PDF_MC_Table.php');

//instanciamos la clase para generar el documento pdf
$pdf=new PDF_MC_Table();

//agregamos la primera pagina al documento pdf
$pdf->AddPage();

//seteamos el inicio del margen superior en 25 pixeles
$y_axis_initial=25;

$actatemp=$_SESSION['act_ist'];

require_once "../modelos/informes.php";
$venta = new actasist();
$rspta=$venta->acta_ist($actatemp);
$reg = $rspta->fetch_object();
$tipo=$reg->cat_id_tipo;
$cabecera=$reg->t;
$ciudad=$reg->ciudad;
$fecha=$reg->ist_fecha;
$custodio=$reg->usu_nombre;
$cargocus=$reg->usu_cargo;
$cedula=$reg->usu_cedula;
$pie=$reg->textob;
$f1=$reg->f1;
$c1=$reg->f1t;
$f2=$reg->f2;
$c2=$reg->f2t;
//seteamos el tipo de letra y creamos el titulo de la pagina. No se repetira como encabezado

$pdf->Ln(5);
$pdf->Image('../files/img/ist17j.png',25,15,14);
$pdf->Image('../files/img/umi.jpeg',170,15,22);
	$pdf->SetFont('Arial','B',10);
$pdf->Cell(200,6,'ACTA ENTREGA RECEPCION DE BIENES',0,0,'C');
$pdf->Ln();
$pdf->Cell(200,6,'ENTRE EL INSTITUTO SUPERIOR TECNOLOGICO',0,0,'C');
$pdf->Ln();
$pdf->Cell(200,6,'17 DE JULIO Y EL CUSTODIO FINAL',0,0,'C');
$pdf->Ln(20);

$pdf->SetFont('Arial','',8);

$pdf->Cell(190,6,'ACTA: '.$actatemp,0,0,'R');
//$pdf->Cell(60,6,$_GET["ist_id"],0,0,'L');
$pdf->Ln(10);

$pdf->Cell(60,6,'Comparecientes: ',0,0,'L');
$pdf->Ln(10);
$porciones = explode("***", $cabecera);
$cabecera=$porciones[0].$ciudad.$porciones[1];
$porciones = explode("---", $cabecera);
$cabecera=$porciones[0].$fecha.$porciones[1];
$porciones = explode("&&&", $cabecera);
$cabecera=$porciones[0].$custodio.$porciones[1];
$porciones = explode("###", $cabecera);
$cabecera=$porciones[0].$cedula.$porciones[1];
$pdf->MultiCell(190,6,utf8_decode($cabecera), 0, 'J', 0);

$pdf->Ln(5);


//creamos las celdas para los titulos de cada columna y le asignamos un fondo gris y el tipo de letra
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(7,6,utf8_decode('Item.'),1,0,'C',1);
$pdf->Cell(90,6,utf8_decode('Descripción'),1,0,'C',1);
$pdf->Cell(15,6,utf8_decode('Cód. IST'),1,0,'C',1);
$pdf->Cell(15,6,utf8_decode('Cód. ALT'),1,0,'C',1);
$pdf->Cell(60,6,utf8_decode('Ubicación'),1,0,'C',1);
//$pdf->Cell(10,6,utf8_decode('Acta'),1,0,'C',1);

//$pdf->Cell(35,6,utf8_decode('Foto'),1,0,'C',1);
$pdf->Ln(6);




$rspta = $venta->listaracta(3,$actatemp);

//implementamos las celdas de la tabla con los registros a mostrar
$pdf->SetWidths(array(95,30,30,35));

$salto=10;
$cont=1;
while ($reg= $rspta->fetch_object()) {
	$descripcion=substr($reg->act_detalle, 0, 50);
	$codigoi= $reg->act_id;
	$codigoy=$reg->act_cyachay;
	$foto=$reg->act_foto;
	$ubica=$reg->cat_nombre;
//	$actae=$reg->acta_id;
	$pdf->SetFont('Arial','',7);
	if(!empty($foto))
		$pdf->Image($foto, 175, 100+$salto, 16);
	//$pdf->Image("../files/img/ist17j.png", 175, 100+$salto, 16);
	$salto=$salto+25;
	$pdf->Cell(7,5,utf8_decode($cont),1,0,'L',0);
	$pdf->Cell(90,5,utf8_decode($descripcion),1,0,'L',0);
	$pdf->Cell(15,5,utf8_decode(utf8_decode($codigoi)),1,0,'C',0);
	$pdf->Cell(15,5,utf8_decode(utf8_decode($codigoy)),1,0,'C',0);
	$pdf->Cell(60,5,utf8_decode(utf8_decode($ubica)),1,0,'L',0);
//	$pdf->Cell(10,5,utf8_decode(utf8_decode($actae)),1,0,'C',0);
/*	if(!empty($foto))
		$pdf->Cell(35,25,$pdf->Image($foto, 175, $pdf->GetY()+$salto, 16),1,0,'C',0);
	else	
		$pdf->Cell(35,25,$pdf->Image("../files/img/ist17j.png", 175, $pdf->GetY()+1, 16),1,0,'C',0);
	*/
	$pdf->Ln();
	$cont=$cont+1;
	
}

	$cont=$cont-1;
if($cont>8){
	$ph=$cont-32; //primera hoja se llena con 27 
    if ($ph>0){
	  $hh = ($ph/53); //nro de hojas que cubro
	    if($hh>1){
		  $fh=floor($hh); //entero
		  $phf=$ph-($fh*53);
				  
		}
		else
		  $phf=$ph;				
	}
	else
	  $phf=30;   	 
}
else
   $phf=0;
   
if ($phf>=30)
	 $pdf->AddPage();		  
		
/*if ($phf>0.50)
	$pdf->AddPage(); */
//$pdf->Cell(200,6,$cont.'--'.$ph.'--'.$hh.'--'.$fh.'--'.$phf ,0,0,'C',0);	 
$pdf->Ln(5);
$pdf->MultiCell(190,6,utf8_decode($pie), 0, 'J', 0);
$pdf->Ln(5);
$pdf->MultiCell(190,6,utf8_decode("Para constancia de lo actuado, firman las partes en original y copia del mismo contenido"), 0, 'J', 0);
$porciones = explode("*", $c1);
$porciones1 = explode("*", $c2);

if ($tipo==1947){	
	$pdf->Ln(20);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(200,6,utf8_decode($custodio),0,0,'C',0);
	$pdf->Ln();
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(200,6,utf8_decode('EX - CUSTODIO'),0,0,'C',0);
	$pdf->Ln(30);
	
	$pdf->Cell(95	,6,utf8_decode($f1),0,0,'C',0);
	$pdf->Cell(110,6,utf8_decode($f2),0,0,'C',0);
	$pdf->Ln();
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(90,6,utf8_decode($porciones[0]),0,0,'C',0);
	$pdf->Cell(120,6,utf8_decode($porciones1[0]),0,0,'C',0);
	$pdf->Ln();	
	$pdf->Cell(90,6,utf8_decode($porciones[1]),0,0,'C',0);
	$pdf->Cell(120,6,utf8_decode($porciones1[1]),0,0,'C',0);
	}
else{
	
	$pdf->Ln(20);
	$pdf->Cell(95	,6,utf8_decode($f1),0,0,'C',0);
	$pdf->Cell(110,6,utf8_decode($f2),0,0,'C',0);
	$pdf->Ln();
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(90,6,utf8_decode($porciones[0]),0,0,'C',0);
	$pdf->Cell(120,6,utf8_decode($porciones1[0]),0,0,'C',0);
	$pdf->Ln();	
	$pdf->Cell(90,6,utf8_decode($porciones[1]),0,0,'C',0);
	$pdf->Cell(120,6,utf8_decode($porciones1[1]),0,0,'C',0);
	$pdf->Ln(30);

    $pdf->SetFont('Arial','',8);
	$pdf->Cell(200,6,utf8_decode($custodio),0,0,'C',0);
	$pdf->Ln();
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(200,6,utf8_decode('CUSTODIO FINAL'),0,0,'C',0);
	

	}



//mostramos el documento pdf

$pdf->Output();

}else{
echo "No tiene permiso para visualizar el reporte";
}

}

ob_end_flush();
  ?>

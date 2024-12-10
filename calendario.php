<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<?php
echo'
<h1 class="horario-name"><i class="fa fa-calendar" aria-hidden="true"></i> nombre</h1>
<table id="thetable" class="table table-bordered">
<thead class="thead">
<th class="horarioheader"><i class="fa fa-clock-o"></i> Horario</th>
<th><i class="fa fa-angle-right"></i> Lunes</th>
<th><i class="fa fa-angle-right"></i> Martes</th>
<th><i class="fa fa-angle-right"></i> Miercoles</th>
<th><i class="fa fa-angle-right"></i> Jueves</th>
<th><i class="fa fa-angle-right"></i> Viernes</th>
<th><i class="fa fa-angle-right"></i> Sabado</th>';
echo '<tr id="tr">
<td class="td-time">
<div id="parent" class="timeblock">
<strong id="data"></strong>
<button data-time="sha1(in)" class="changethetime btn btn-primary btn-xs pull-right"><i class="fa fa-pencil"></i></button>
</div>

<div id="edit" class="hideedittime text-center">
  <input id="input" type="text" class="form-control" value=""><p></p><button data-save="sha1(in)" class="savetime btn btn-block btn-xs btn-primary"><i class="fa fa-floppy-o"></i> Guardar</buttton><button data-block="sha1($in)" class="deleteblock btn btn-block btn-xs btn-warning"><i class="fa fa-ban"></i> Eliminar </button><button class="canceledit btn btn-block btn-xs btn-danger"><i class="fa fa-times"></i> Cancelar</buttton>
</div>

</td>';
for ($i=1; $i < 6; $i++) { 
	echo'
       <td class="td-line">
         <div id="1" class="col-sm-12 nopadding"></div>
         <div class="col-sm-12 text-center">
            <button id="edit-1" data-row="1" class="addinfo btn btn-xs btn-primary"><i class="fa fa-plus"></i></button>
         </div>
      </td>
	';
}

echo '</tr>';
echo '<tr id="tr">

<td class="td-time">

<div id="parent" class="timeblock">
<strong id="data"></strong>
<button data-time="1" class="changethetime btn btn-primary btn-xs pull-right"><i class="fa fa-pencil"></i></button>
</div>

<div id="edit" class="hideedittime text-center">
  <input id="input" type="text" class="form-control" value=" - "><p></p><button data-save="1" class="savetime btn btn-block btn-xs btn-primary"><i class="fa fa-floppy-o"></i> Guardar</buttton><button data-block="1" class="deleteblock btn btn-block btn-xs btn-warning"><i class="fa fa-ban"></i> Eliminar </button><button class="canceledit btn btn-block btn-xs btn-danger"><i class="fa fa-times"></i> Cancelar</buttton>
</div>

</td>';
for ($i=1; $i < 7; $i++) { 
	echo'
       <td class="td-line">
         <div  id="'.$i.'" class="col-sm-12 nopadding"></div>
         <div class="col-sm-12 text-center">
            <button id="edit" data-row="'.$i.'" class="addinfo btn btn-xs btn-primary"><i class="fa fa-plus"></i></button>      
         </div>
      </td>
	';
}
echo'</tr>';
?>
</body>
</html>

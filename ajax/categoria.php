<?php 
require_once "../modelos/Categoria.php";

$categoria=new Categoria();

$cat_id=isset($_POST["cat_id"])? limpiarCadena($_POST["cat_id"]):"";
$cat_nombre=isset($_POST["cat_nombre"])? limpiarCadena($_POST["cat_nombre"]):"";
$cat_descripcion=isset($_POST["cat_descripcion"])? limpiarCadena($_POST["cat_descripcion"]):"";
$cat_padre=isset($_POST["cat_padre"])? limpiarCadena($_POST["cat_padre"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idcategoria)) {
		$rspta=$categoria->insertar($cat_nombre,$cat_descripcion,$cat_padre);
        echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
         $rspta=$categoria->editar($idcategoria,$nombre,$descripcion);
		echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
	}
		break;
	

	case 'desactivar':
		$rspta=$categoria->desactivar($idcategoria);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;
	case 'activar':
		$rspta=$categoria->activar($idcategoria);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;
	
	case 'padres':
		$rspta=$categoria->mostrar();
		while ($reg=$rspta->fetch_object()) {
				echo '<option value=' . $reg->cat_id.'>'.$reg->cat_nombre.'</option>';
		}		
		break;


    case 'listar':
		$rspta=$categoria->listar();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>$reg->cat_nombre,
            "1"=>$reg->cat_detalle,
            "2"=>$reg->padre
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);
		break;
}
 ?>
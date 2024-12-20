<?php 
session_start();
require_once "../modelos/Usuario.php";

$usuario=new Usuario();
$claveu=isset($_POST["claveu"])? limpiarCadena($_POST["claveu"]):"";
$usu_id=isset($_POST["usu_id"])? limpiarCadena($_POST["usu_id"]):"";
$usu_nombre=isset($_POST["usu_nombre"])? limpiarCadena($_POST["usu_nombre"]):"";
$usu_cedula=isset($_POST["usu_cedula"])? limpiarCadena($_POST["usu_cedula"]):"";
$usu_telefono=isset($_POST["usu_telefono"])? limpiarCadena($_POST["usu_telefono"]):"";
$usu_correo=isset($_POST["usu_correo"])? limpiarCadena($_POST["usu_correo"]):"";
$usu_cargo=isset($_POST["usu_cargo"])? limpiarCadena($_POST["usu_cargo"]):"";
$usu_login=isset($_POST["usu_login"])? limpiarCadena($_POST["usu_login"]):"";
$usu_clave=isset($_POST["usu_clave"])? limpiarCadena($_POST["usu_clave"]):"";


switch ($_GET["op"]) {
	case 'guardaryeditar':
	//Hash SHA256 para la contraseña
	if ($claveu==$usu_clave){
		$usu_clavehash=$usu_clave;
		}
	else{
		$usu_clavehash=hash("SHA256", $usu_clave);
		}
	
	if (empty($usu_id)) {
		$rspta=$usuario->insertar($usu_nombre,$usu_cedula,$usu_telefono,$usu_correo,$usu_cargo,$usu_login,$usu_clavehash,$_POST['permiso']);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar Login existente";
	}else{
		$rspta=$usuario->editar($usu_id,$usu_nombre,$usu_cedula,$usu_telefono,$usu_correo,$usu_cargo,$usu_login,$usu_clavehash,$_POST['permiso']);
		//echo $usu_clave;
		echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar Login existente";
	}
	break;
	

	case 'desactivar':
	$rspta=$usuario->desactivar($usu_id);
	echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
	break;

	case 'activar':
	$rspta=$usuario->activar($usu_id);
	echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
	break;
	
	case 'mostrar':
		//echo $_POST["usu_id"];
		$rspta=$usuario->mostrar($usu_id);
		echo json_encode($rspta);
	break;

	case 'listar':
	$rspta=$usuario->listar();
	$data=Array();
	while ($reg=$rspta->fetch_object()) {
		$data[]=array(
			"0"=>($reg->usu_condicion)?'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->usu_id.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->usu_id.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->usu_id.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-primary btn-xs" onclick="activar('.$reg->usu_id.')"><i class="fa fa-check"></i></button>',
			"1"=>$reg->usu_nombre,
			"2"=>$reg->usu_login,
			"3"=>$reg->usu_cedula,
			"4"=>$reg->usu_telefono,
			"5"=>$reg->usu_correo,
			"6"=>$reg->usu_cargo,
			"7"=>($reg->usu_condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
		);
	}

	$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
	echo json_encode($results);
	break;

	case 'verificar':
	
		//validar si el usuario tiene acceso al sistema
		$logina=$_POST['logina'];
		$clavea=$_POST['clavea'];
		
		//Hash SHA256 en la contraseña
		$clavehash=hash("SHA256", $clavea);

		//$rspta=$usuario->verificarDocente($logina, $clavehash);
		$docente=$usuario->verificarDocente($logina, $clavehash);
		$estudiante=$usuario->verificarEstudiante($logina, $clavehash);

		$_SESSION['Escritorio']=0;
		$_SESSION['Docentes']=0;
		$_SESSION['Estudiantes']=0;
		$_SESSION['Resumenes']=0;

		if($docente->num_rows > 0){
			$_SESSION['Escritorio']=1;

			$fetch=$docente->fetch_object();
			$_SESSION['usu_id']=$fetch->usu_id;
			$_SESSION['usu_nombre']=$fetch->usu_nombre;
			$_SESSION['usu_telefono']=$fetch->usu_telefono;
			$_SESSION['Estudiantes']=1;

			$docente="";
			$docente=$usuario->datosDocente($_SESSION['usu_id']);
			$fetch="";

			//Idioma
			$fetch=$docente->fetch_object();
			$_SESSION['idio_id']=$fetch->idio_id;

			//Permiso
			if($fetch->doc_permiso ==1){
				$_SESSION['Admin']=1;
			}
		}elseif($estudiante->num_rows > 0){
			$_SESSION['Escritorio']=1;


			
		}else{
			echo null;
		}
		//$fetch=$rspta->fetch_object();
			
		/*if (isset($fetch)) {

			$_SESSION['Escritorio']=1;
			$_SESSION['Docentes']=0;
			$_SESSION['Estudiantes']=0;
			$_SESSION['Resumenes']=0;

			if($fetch->rol==1){
				

			}else if($fetch->rol==0){
				# Declaramos la variables de sesion
				$_SESSION['usu_id']=$fetch->est_id;
				$_SESSION['usu_nombre']=$fetch->est_nombre;
				$_SESSION['usu_login']=$fetch->est_login;
				$_SESSION['usu_telefono']=$fetch->est_celular;

				#Menu
				$_SESSION['Resumenes']=1;
			}

		}
		echo json_encode($fetch);*/
	
	break;
	case 'salir':
	   //limpiamos la variables de la secion
	session_unset();

	  //destruimos la sesion
	session_destroy();
		  //redireccionamos al usu_login
	header("Location: ../index.php");
	break;
	
	
}
?>


<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Usuario{


	//implementamos nuestro constructor
public function __construct(){

}

public function verificarDocente($usu_login,$usu_clave){
	$sql="call activos_ist17j.sp_logeo('$usu_login','$usu_clave');";

	return ejecutarConsultaSP($sql);	
}

public function verificarEstudiante($usu_login,$usu_clave){
	$sql="call sp_loginEst('$usu_login','$usu_clave');";

	return ejecutarConsultaSP($sql);	
}

public function datosDocente($usu_id){
	$sql="call resumenes_ist17j.sp_docentes(1, $usu_id, 0, 0);";

	return ejecutarConsultaSP($sql);
}

}

?>

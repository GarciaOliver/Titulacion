<?php
    require_once "../config/Conexion.php";

    class Estudiante{
        public function __construct(){}

        public function guardarResumen($usu_id,$res_resumen, $res_palabras, $idio_id){
            $consulta = "call resumenes_ist17j.sp_resumenes(0, 0, $usu_id, $idio_id, '$res_resumen', '$res_palabras', CURDATE(), 1);";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }
    }
?>
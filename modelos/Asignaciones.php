<?php
    require_once "../config/Conexion.php";
    class Asignacion{
        public function __construct(){}

        public function asignacionesPendientes($usu_id){
            $consulta = "call resumenes_ist17j.sp_asignaciones(1, 0, 0, $usu_id, '1999-01-01', 0);";
            $resultado = ejecutarConsultaSP($consulta);
            if ($resultado->num_rows > 0) {
                $data = [];

                while ($fila = $resultado->fetch_assoc()) {
                    $data['data'][] = $fila;
                }
                return $data;
            } else {
                return null;
            }
        }

        public function datosResumen($res_id){
            $consulta = "call resumenes_ist17j.sp_resumenes(1, $res_id, 0, 0, '', '', '1999-01-01', 0);";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }
    }
?>
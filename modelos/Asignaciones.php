<?php
    require_once "../config/Conexion.php";
    class Asignacion{
        public function __construct(){}

        public function asignacionesPendientes($usu_id){
            $consulta = "call resumenes_ist17j.sp_asignaciones(1, 0, 0, $usu_id, 0, 0);";
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
            $consulta = "call resumenes_ist17j.sp_resumenes(1, $res_id, 0, 0, '', '', 0);";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }

        public function enviarCalificacion($asig_id, $cat_id, $rev_observaciones){
            $consulta = "call resumenes_ist17j.sp_revisiones(0, 0, $asig_id, '$rev_observaciones', '', $cat_id);";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }

        public function asignacionesEstudiante($est_id){
            $consulta = "call resumenes_ist17j.sp_asignaciones(3, 0, 0, 0, $est_id, 0);";
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

        public function datosRevision($asig_id){
            $consulta = "call resumenes_ist17j.sp_asignaciones(4, $asig_id, 0, 0, 0, 0);";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }
    }
?>
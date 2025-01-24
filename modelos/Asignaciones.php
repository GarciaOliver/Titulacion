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

        public function asignacionesEstudiante($est_id){
            $consulta = "call resumenes_ist17j.sp_asignaciones(3, 0, 0, 0, $est_id, 0);";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }

        public function datosRevision($asig_id){
            $consulta = "call resumenes_ist17j.sp_asignaciones(4, $asig_id, 0, 0, 0, 0);";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }

        public function enviarCalificacion($asig_id, $cat_id, $rev_observaciones){
            $consulta = "call resumenes_ist17j.sp_revisiones(0, 0, 0, 0, 0, $asig_id, '$rev_observaciones', '', $cat_id);";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }

        public function revisionesAprobadasId($est_id,$idio_id){
            $consulta = "call resumenes_ist17j.sp_revisiones(1, $est_id, 0, $idio_id, 0, 0, '', '', 0);";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }

        public function subirDocumento($est_id, $idio_id, $nombreArchivo){
            $consulta = "call resumenes_ist17j.sp_documentacion(0, $est_id, $idio_id, 0, '$nombreArchivo');";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }

        public function listaDocumentos($est_id){
            $consulta = "call resumenes_ist17j.sp_documentacion(2, $est_id, 0, 0, '');";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }

        public function eliminarDocumento($est_id, $nombreArchivo){
            $consulta = "call resumenes_ist17j.sp_documentacion(1, $est_id, 0, 0, '$nombreArchivo');";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }
    }
?>
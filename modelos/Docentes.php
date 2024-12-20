<?php
    require "../config/Conexion.php";
    class Docente{
        public function __construct(){}


        // Devuelve los docentes que estan registrados en el sistema
        public function listarDocentesIngresados($usu_id,$idio_id){
            $consulta = "call resumenes_ist17j.sp_docentes(3, $usu_id, $idio_id, 0, 0);";
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

        public function listarDocentesTodos($usu_id){
            $consulta = "call resumenes_ist17j.sp_docentes(4, $usu_id, 0, 0, 0);";
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

        public function agregarDocente($usu_id,$idio_id){
            $consulta = "call resumenes_ist17j.sp_docentes(0, $usu_id, $idio_id, 0, 1);";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }

        public function buscarDocente($usu_id){
            $consulta = "call resumenes_ist17j.sp_docentes(1, $usu_id, 0, 0, 0);";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }

        public function editarDocente($usu_id,$doc_permiso,$cat_id){
            $consulta = "call resumenes_ist17j.sp_docentes(2, $usu_id, 0, $doc_permiso, $cat_id);";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }

    }
    
?>
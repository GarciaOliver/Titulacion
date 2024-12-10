<?php
    require "../config/Conexion.php";
    class Docente{
        public function __construct(){}


        // Devuelve los docentes que estan registrados en el sistema
        public function listarDocentesIngresados($usu_id,$cat_id){
            $consulta = "call resumenes_ist17j.sp_docentes(3, $usu_id, $cat_id, 0, '');";
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
            $consulta = "call resumenes_ist17j.sp_docentes(4, $usu_id, 0, 0, '');";
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

        public function agregarDocente($usu_id,$cat_id){
            $consulta = "call resumenes_ist17j.sp_docentes(0, $usu_id, $cat_id, 0, 'activo');";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }
    }
    
?>
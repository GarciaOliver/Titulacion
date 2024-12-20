<?php
    require "../config/Conexion.php";

    class Idioma{
        public function __construct(){}

        public function listarIdiomas(){
            $consulta = "call resumenes_ist17j.sp_idiomas(1, 0, '0', 0, 0);";
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

        public function selectIdiomas(){
            $consulta = "call resumenes_ist17j.sp_idiomas(1, 0, '0', 0, 0);";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }

        public function agregarIdioma($idio_idioma,$idio_dependencia){
            $consulta = "";
            $resultado = ejecutarConsultaSP($consulta);
            return $resultado;
        }
    }
?>
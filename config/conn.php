<?php 
require_once "global.php";

class Cls_DataConnection
   {
    function Fn_getConnect()
     {
        if (!($conexion1=new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME_RESUMENES)))
        {
           echo "Error Conectando la base de Datos";
           exit();
        }
        return $conexion1;
     }
  }
?>
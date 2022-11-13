<?php
include "../conexion.php";
include "barcode/barcode.php";

$filepath = $_POST['filepath'];
$text = $_POST['text'];
$descripcion = descripcion_producto($text);
$precio = precio_producto($text);

barcode( $filepath, $text.'\n'.$descripcion.'   $'.$precio ,'70','horizontal','code128',true,1);
       
?>
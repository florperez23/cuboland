<?php
include "../conexion.php";
include "barcode/barcode.php";

$filepath = $_POST['filepath'];
$text = $_POST['text'];

barcode( $filepath, $text ,'70','horizontal','code128',true,1);
       
?>
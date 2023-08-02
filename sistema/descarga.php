<?php

$codcubo = $_GET['cubo'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
//$desde = date("Y-m-d",strtotime($desde."- 1 day"));
$hasta =  date("Y-m-d",strtotime($hasta."+ 1 day"));

header("Content-disposition: attachment; filename=Hipercubo.pdf");
header("Content-type: application/pdf");
readfile("Hipercubo.pdf");
?>
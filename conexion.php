<?php

//PUNTO DE VENTA
//ALGO

date_default_timezone_set('Mexico/General');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
$urlsite = 'https://plataformaitavu.tamaulipas.gob.mx'; global $urlsite;

//DATOS DE CONECCION
//$dbhost = '192.168.159.5';	
$dbhost = 'localhost';	
//$dbuser = 'cubos';
$dbuser = 'root';
//$dbpass = '515t3m4'; // PmTwrXcxUZ3oML9V
$dbpass = ''; // PmTwrXcxUZ3oML9V
$dbname = 'cubos';


//DATOS DE CONECCION
	
// $dbhost = 'localhost';	
// $dbuser = 'root';
// $dbpass = ''; // PmTwrXcxUZ3oML9V
// $dbname = 'cubos';

	if (function_exists('mysqli_connect')) {
//mysqli está instalado
	//echo 'Si';
	$conexion = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	$acentos = $conexion->query("SET NAMES 'utf8'"); // para los acentos
	global $conexion;
}else{
	mensaje("ERROR: Hay un problema con la coneccion",'');


	
}


//PARAMETROS DE PREFERENCIA
 	global $moneda, $moneda_sufijo;
 	$fecha = date('Y-m-d');
	$hora =  date ("H:i:s");

	global $fecha, $hora, $tolerancia;
	
// CONFIGURACION DEL CORREO
	$correo_limite=1500; global $correo_limite;

	ini_set('max_execution_time', 0);
	


?>

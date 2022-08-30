<?php
	date_default_timezone_set('America/Monterrey');

	function fechaMexico(){
		$mes = array("","Enero",
					  "Febrero",
					  "Marzo",
					  "Abril",
					  "Mayo",
					  "Junio",
					  "Julio",
					  "Agosto",
					  "Septiembre",
					  "Octubre",
					  "Noviembre",
					  "Diciembre");
		return date('d')." de ". $mes[date('n')] . " de " . date('Y');
	}

	function narchivo($consulta){
		require("..\conexion.php");
		$sql = "SELECT * FROM contadores WHERE id='1'";					
		$rc= $conexion -> query($sql);
		if($f = $rc -> fetch_array())
		{
			if ($consulta==TRUE)
			{
				return $f['narchivo'];
			}
			else
			{ // sino es consulta entonces aumentarle y aumentar el contador de ceropapel
			// la diferencia entre ceropapel y este, es que cero papel se multiplica
			// por las copias que se entregan o con copia, para estadistica de cuanto se ha ahorrado
				$n2 = $f['narchivo'] + 1;
				$sql="UPDATE contadores SET narchivo='".$n2."' WHERE id='1'";
				$resultado = $conexion -> query($sql);
					if ($conexion->query($sql) == TRUE) 
					{
						return $f['narchivo'];
					}
					else {
						return  FALSE;}
					}
				}
				else
				{
						return FALSE;
				}
	}
	
	// funtion existenciaProducto($idproducto)
	// {
	// 	include "../conexion.php";
	// $query = mysqli_query($conexion, "SELECT existencia FROM producto where id=".$idproducto.";");
    // $result = mysqli_num_rows($query);
    // if ($result > 0) {
    //     while ($data = mysqli_fetch_assoc($query)) { 
    //         $existencia= $data['existencia'];
    //     }
    // }else{

    //     $existencia =0;
    // }
	// return $existencia;
	// }

	function revisarCortesAbiertos(){
		require("..\conexion.php");
		$sql = "SELECT * FROM cortecaja WHERE Estado=0";	
		$r = $conexion -> query($sql);
		if ($r -> num_rows >0){
			return 1;
		}else{
			return 0;
		}			
					
	}


	function fechaCorte(){
		require("..\conexion.php");
		$sql = "SELECT FechaApertura FROM cortecaja WHERE Estado=0";	
		$r = $conexion -> query($sql);
		if ($r -> num_rows >0) {
			while($f = $r -> fetch_array())
			{
				return $f['FechaApertura'];
			}
		     
		}else{
			return 0;
		}
		    			
	}

	function montoInicial(){
		require("..\conexion.php");
		$sql = "SELECT MontoInicial FROM cortecaja WHERE Estado=0";	
		$r = $conexion -> query($sql);
		if ($r -> num_rows >0) {
			while($f = $r -> fetch_array())
			{
				return $f['MontoInicial'];
			}
		     
		}else{
			return 0;
		}
		    			
	}

	function idCorteAbierto(){
		require("..\conexion.php");
		$sql = "SELECT Id FROM cortecaja WHERE Estado=0";	
		$r = $conexion -> query($sql);
		if ($r -> num_rows >0) {
			while($f = $r -> fetch_array())
			{
				return $f['Id'];
			}
		     
		}else{
			return 0;
		}
		    			
	}

	function fechaCorteAperturaId($id){
		require("..\conexion.php");
		$sql = "SELECT FechaApertura FROM cortecaja WHERE id=".$id."";	
		$r = $conexion -> query($sql);
		if ($r -> num_rows >0) {
			while($f = $r -> fetch_array())
			{
				return $f['FechaApertura'];
			}
		     
		}else{
			return 0;
		}
		    			
	}

	function fechaCorteCierreId($id){
		require("..\conexion.php");
		$sql = "SELECT FechaCierre FROM cortecaja WHERE id=".$id."";	
		$r = $conexion -> query($sql);
		if ($r -> num_rows >0) {
			while($f = $r -> fetch_array())
			{
				return $f['FechaCierre'];
			}
		     
		}else{
			return 0;
		}
		    			
	}

	function existeEnAjusteelProducto($codproducto){
		require("..\conexion.php");
		$sql = "SELECT id FROM ajuste_inventario WHERE codproducto='".$codproducto."' and fecha  = CURRENT_DATE()";	
		echo $sql;
		$r = $conexion -> query($sql);
		if ($r -> num_rows >0) {
			while($f = $r -> fetch_array())
			{
				return $f['id'];
			}
		     
		}else{
			return 0;
		}
		    			
	}

	function entradasQueTenia($id){
		require("..\conexion.php");
		$sql = "SELECT entradas FROM ajuste_inventario WHERE id='".$id."' ";	
		$r = $conexion -> query($sql);
		if ($r -> num_rows >0) {
			while($f = $r -> fetch_array())
			{
				return $f['entradas'];
			}
		     
		}else{
			return 0;
		}
		    			
	}

	function salidasQueTenia($id){
		require("..\conexion.php");
		$sql = "SELECT salidas FROM ajuste_inventario WHERE id='".$id."' ";	
		$r = $conexion -> query($sql);
		if ($r -> num_rows >0) {
			while($f = $r -> fetch_array())
			{
				return $f['salidas'];
			}
		     
		}else{
			return 0;
		}
		    			
	}

	function actualizarExistenciasenProducto($id, $cantidadactual){
		require("..\conexion.php");
		$sql = "UPDATE producto SET existencia = ".$cantidadactual." WHERE codigo='".$id."' ";
		echo $sql;	
		$query = mysqli_query($conexion, $sql);
		if ($query) {
			return TRUE;
		}else {
			return FALSE;
		}
		    			
	}

	function nabonos($numcredito){
		include "../../conexion.php";
		$sql = "select count(*) as numabono from factura where numcredito=".$numcredito;					
		$rc= $conexion -> query($sql);
		if($f = $rc -> fetch_array())
		{
			// echo $sql;
			$rc= $conexion -> query($sql);
			if($f = $rc -> fetch_array())
				{		
							
					return $f['numabono'];
				}
			 else {return FALSE;}
		}
	}
 



function mensajeicono($mensaje, $link,$tipo,$tipob){


if ($link==""){
	$link = "index.php";
}
$tipo = substr($mensaje, 0,5);    // devuelve "ef"
// if ($tipo=='ERROR'){
// 	Problema_create("PLATAFORMA", "fn mensaje: ".$mensaje."=>".$link, $IdEmpleado);
// }
	if ($tipo=='ERROR')
	{	
		echo '<div id="modal_error"></div>';
	}
	else
	{
		echo '<div id="modal_oscuro"></div>';
	}

	if ($tipo=='ERROR'){echo '<div id="msg_error">';}
	else
	{
		echo '<div id="mensaje">';}
	
		

		switch ($tipob ) {
			case "normal":		
				echo '<p class="msmgmodal">'.$mensaje.'</p>';
				echo '<a class="btn btn-primary" href="'.$link.'">Aceptar</a>  ';
				  break;
			case "secundario":
				echo '<p class="msmgmodal">'.$mensaje.'</p>';
				echo '<a class="btn btn-secondary" href="'.$link.'">Aceptar</a>  ';
				break;
			 case "exito":
				echo '<div class="swal2-icon swal2-success">
				<div class="swal2-success-circular-line-left"></div>
				<span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span>
				<div class="swal2-success-ring"></div> <div class="swal2-success-fix"></div>
				<div class="swal2-success-circular-line-right"></div>
			   </div>';	
			    echo '<p class="msmgmodal">'.$mensaje.'</p>';
				echo '<a class="btn btn-success" href="'.$link.'">Aceptar</a>  ';
				break;
			case "error":				
				echo'<div class="swal2-icon swal2-error">				
				<div class="swal2-icon-content" style="display:block;">X</div>						
				</div>';
				echo '<p class="msmgmodal">'.$mensaje.'</p>';
				echo '<a class="btn btn-danger" href="'.$link.'">Aceptar</a>  ';
				break;						
			case "advertencia":
				echo '<div class="swal2-icon swal2-warning swal2-icon-show" style="display: flex;"><div class="swal2-icon-content">!</div></div>';
				echo '<p class="msmgmodal">'.$mensaje.'</p>';
				echo '<a class="btn btn-warning" href="'.$link.'">Aceptar</a>  ';
				break;
			case "info":
				echo '<div class="swal2-icon swal2-info"><div class="swal2-icon-content" style="display:block;">i</div></div>';
				echo '<p class="msmgmodal">'.$mensaje.'</p>';
				echo '<a class="btn btn-info" href="'.$link.'">Aceptar</a>  ';				
				break;
			case "light":
				echo '<p class="msmgmodal">'.$mensaje.'</p>';;
				echo '<a class="btn btn-light" href="'.$link.'">Aceptar</a>  ';
				break;
				case "dark":
					echo '<p class="msmgmodal">'.$mensaje.'</p>';;
			echo '<a class="btn btn-dark" href="'.$link.'">Aceptar</a>  ';
				break;
		}
		
		echo '</div>';
}


function mensaje( $titulo,$mensaje,$tipo,$link)
{ 
	
if ($link==""){
	$link = "index.php";
}
echo '<div class="modal" tabindex="-1" role="dialog"  style="padding-right: 17px; display: block;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">'.$titulo.'</h5>
      
      </div>
      <div class="modal-body">';
	    echo '<p>'.$mensaje.'</p>';
        echo '</div>
    	<div class="modal-footer">';
        		switch ($tipo ) {
			case "normal":
				echo '<a class="btn btn-primary" href="'.$link.'">Aceptar</a>  ';
				  break;
			case "secundario":
				echo '<a class="btn btn-secondary" href="'.$link.'">Aceptar</a>  ';
				break;
			 case "exito":
				echo '<a class="btn btn-success" href="'.$link.'">Aceptar</a>  ';
				break;
			case "advertancia":
				echo '<a class="btn btn-warning" href="'.$link.'">Aceptar</a>  ';
				break;
			case "info":
				echo '<a class="btn btn-info" href="'.$link.'">Aceptar</a>  ';
				break;
			case "light":
				echo '<a class="btn btn-light" href="'.$link.'">Aceptar</a>  ';
				break;
				case "dark":
			echo '<a class="btn btn-dark" href="'.$link.'">Aceptar</a>  ';
				break;
		}
      echo '</div>
    </div>
  </div>
</div>';
}

function HayCajaAbierta(){
	include "../conexion.php";
	$sql = "select count(*) as existe from cortecaja where estado=0";					
	$rc= $conexion -> query($sql);
	if($f = $rc -> fetch_array())
	{
		// echo $sql;
		$rc= $conexion -> query($sql);
		if($f = $rc -> fetch_array())
			{		
						
				return $f['existe'];
			}
		 else {return FALSE;}
	}
}

function historia($descripcion){
	include "../conexion.php";
	$usuario_id = $_SESSION['idUser'];	
	$sql = "INSERT INTO historia
	(user, fecha, descripcion)
	VALUES
	('$usuario_id', now(), '$descripcion')";
	//echo $sql;
	$query_insert = mysqli_query($conexion, $sql);
	
	if ($query_insert) { 
		
	} 
	else {

	}

}
		


function nextDni(){
	include "../conexion.php";
	$sql = "select Max(idcliente+1) as idcliente from cliente";			
	//echo $sql;		
	$rc= $conexion -> query($sql);
	if($f = $rc -> fetch_array())
	{
		// echo $sql;
		$rc= $conexion -> query($sql);
		if($f = $rc -> fetch_array())
			{		
						
				return $f['idcliente'];
			}
		 else {return FALSE;}
	}
}
function obtenerUltimoNoFactura(){
	include "../conexion.php";
	$sql = "select Max(nofactura) as nofactura from factura";			
	//echo $sql;		
	$rc= $conexion -> query($sql);
	if($f = $rc -> fetch_array())
	{
		// echo $sql;
		$rc= $conexion -> query($sql);
		if($f = $rc -> fetch_array())
			{		
						
				return $f['nofactura'];
			}
		 else {return FALSE;}
	}
}
function obtenerDatosFacturaNva($id){
	include "../conexion.php";
	$sql="select * from factura where referencia like CONCAT('DEV.#','".$id."')";	
	echo $sql;			
	$rc= $conexion -> query($sql);
	if($f = $rc -> fetch_array())
	{
		// echo $sql;
		$rc= $conexion -> query($sql);
		if($f = $rc -> fetch_array())
			{		
				$link='factura/generaFactura.php?cl='. $f['codcliente'].'&f='. $f['nofactura'].'';	
				return $link;
			}
		 else {return '';}
	}
}

function nrentero($consulta){
	require("..\conexion.php");
	$sql = "SELECT * FROM contadores WHERE id='1'";		
	//echo $sql;			
	$rc= $conexion -> query($sql);
	if($f = $rc -> fetch_array()){
		if ($consulta==TRUE){
			return $f['nrentero'];
		}else{
		 	// sino es consulta entonces aumentarle
			$n2 = $f['nrentero'] + 1;
			$sql="UPDATE contadores SET nrentero='".$n2."' WHERE id='1'";
			$resultado = $conexion -> query($sql);
			if ($conexion->query($sql) == TRUE) {
				return $f['nrentero'];
			}else{
				return  FALSE;
			}
		}
	}else{
		return FALSE;
	}

}

function idcuboanterior($id){
	include "../conexion.php";
	$sql = "select idcubo from rentas where id = '$id'";			
	//echo $sql;		
	// echo $sql;
		$rc= $conexion -> query($sql);
		if($f = $rc -> fetch_array())
			{		
						
				return $f['idcubo'];
			}
		 else {return FALSE;}
	
}

function ExiteUnaPromocion($codproducto){
	require("..\conexion.php");
	
	$sql="select * from promociones inner join producto on promociones.ididentificador=producto.codproducto 
	or promociones.ididentificador= producto.codcubo	
	where (promociones.ididentificador='".$codproducto."' or  promociones.ididentificador= ( select codcubo from producto where codproducto='".$codproducto."'))
	and producto.codproducto='".$codproducto."'
	AND (DATE(promociones.fechainicio) <=CURRENT_DATE() AND DATE(promociones.fechatermino) >DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY))";
	//echo $sql;
	$r = $conexion -> query($sql);
	if ($r -> num_rows >0) {
		while($f = $r -> fetch_array())
		{
			$idclasificacion= $f['idclasificacion'];
			$tipo= $f['idtipo'];
			$promocion=$f['promocion'];
			$precio=$f['precio'];
			if($tipo==1)
			{
				//cantidad/total				
				$newPrecio=$precio-floatval (($precio*$promocion)/100);
			}else
			{
				$newPrecio=$promocion;

			}
			$newPrecio=number_format($newPrecio, 2, '.', ',');
			return $newPrecio."|".$idclasificacion."|".$tipo."|".$promocion."|".$f['descripcion']."|".$f['existencia']."|".$f['precio']."|".$f['codproducto'];
		}
		 
	}else{
		return 0;
	}
			
}




function ValidarExiteUnaPromocion($ididentificador){
	require("..\conexion.php");
	
	$sql="select * from promociones where ididentificador='".$ididentificador."' AND (DATE(promociones.fechainicio) <=CURRENT_DATE() AND DATE(promociones.fechatermino) >DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY))";
	//echo $sql;
	$r = $conexion -> query($sql);
	if ($r -> num_rows >0) {
		while($f = $r -> fetch_array())
		{
			return 'Existe';
		}
		 
	}else{
		return 'No Existe';
	}
			
}

function PrecioPromocionRenta($ididentificador){
	require("..\conexion.php");
	
	$sql="select * from promociones inner join cubos on cubos.codcubo=promociones.ididentificador where ididentificador='".$ididentificador."' AND (DATE(promociones.fechainicio) <=CURRENT_DATE() AND DATE(promociones.fechatermino) >DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY))";
	//echo $sql;
	$r = $conexion -> query($sql);
	if ($r -> num_rows >0) {
		while($f = $r -> fetch_array())
		{
		
			$tipo= $f['idtipo'];
			$promocion=$f['promocion'];
			$renta=$f['renta'];
			if($tipo==1)
			{
				//cantidad/total				
				$newPrecio=$renta-floatval (($renta*$promocion)/100);
			}else
			{
				$newPrecio=$promocion;

			}
			$newPrecio=number_format($newPrecio, 2, '.', ',');
		}
		return $newPrecio;
		 
	}else{
		
			return 0;
		}
	}
			



function cubo_producto_anterior(){
	include "../conexion.php";
	$sql = "select codcubo from detalle_temp_salidas limit 1";			
	//echo $sql;		

		// echo $sql;
		$rc= $conexion -> query($sql);
		if($f = $rc -> fetch_array())
			{		
						
				return $f['codcubo'];
			}
		 else {return FALSE;}
	
}

function cubo_producto($codproducto){
	include "../conexion.php";
	$sql = "select codcubo from producto where codproducto = '$codproducto'";			
	//echo $sql;		

	$rc= $conexion -> query($sql);
	if($f = $rc -> fetch_array())
		{		
					
			return $f['codcubo'];
		}
		else {return FALSE;}
	
}



function FechaProximoPago($probandofecha)
{
	include "../conexion.php";
	$sql = "SELECT DATE_FORMAT('$probandofecha', '%w') as dia";
	//echo $sql;
	$rc= $conexion -> query($sql);
	if($f = $rc -> fetch_array())
		{		
					
			$dia= $f['dia'];
		
		// $result = mysql_query($sql);
		// $fila = mysql_fetch_row($result);
		// $dia = $fila[0];
		if (($dia == 0) or ($dia == 6))
		{
			if($dia == 0)
			{
				$fechaproximopago=date("Y-m-d",strtotime($probandofecha."+ 1 day")); // si es domingo le sumamos 1 dia para el lunes
			}else
			{
				//echo date("Y-m-d",strtotime($probandofecha."+ 2 day"));
				$fechaproximopago=date("Y-m-d",strtotime($probandofecha."+ 2 day")); // si es sabado le sumamos 2 dias para el lunes
			}


		return $fechaproximopago;
		}
		else
		{
		return $fechaproximopago=$probandofecha;
		}
	}
}


function obtenerFechaUltimoPago($idcubo)
{
	include "../conexion.php";
	$sql = "SELECT * FROM rentas where idcubo='$idcubo' and cancelado=0";
	//echo $sql;
	$rc= $conexion -> query($sql);
	if($f = $rc -> fetch_array())
		{		
					
			return $f['fechaultimopago'];
		}
		else {return FALSE;}
}
function cubo_nombre($cod){
	include "../conexion.php";
	$sql = "select cubo from cubos where codcubo = '$cod'";			
	//echo $sql;		

	$rc= $conexion -> query($sql);
	if($f = $rc -> fetch_array())
		{		
					
			return $f['cubo'];
		}
		else {return FALSE;}
	
}

function rentero_cubo($cod){
	include "../conexion.php";
	$sql = "select a.nombre from rentas r
	inner join arrendatarios a on a.idarrendatario = r.idarrendatario
	where idcubo = '$cod'";			
	//echo $sql;		

	$rc= $conexion -> query($sql);
	if($f = $rc -> fetch_array())
		{		
					
			return $f['nombre'];
		}
		else {return FALSE;}
	
}

function total_rentas($desde, $hasta){
	include "../conexion.php";
	
//traer total rentas
$sql = 'select sum(totalfactura) as totalrentas
from factura f
WHERE f.idtipoventa = 5 and f.fecha BETWEEN "'.$desde.'" and "'.$hasta.'" and cancelado = 0';	
	//echo $sql;		

	$rc= $conexion -> query($sql);
	if($f = $rc -> fetch_array())
		{		
					
			return $f['totalrentas'];
		}
		else {return FALSE;}
	
}
?>





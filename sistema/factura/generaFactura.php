<?php
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}
	include "../../conexion.php";
	include("../includes/functions.php");
	if(empty($_REQUEST['cl']) || empty($_REQUEST['f']))
	{
		echo "No es posible generar la factura.";
	}else{
		$codCliente = $_REQUEST['cl'];
		$noFactura = $_REQUEST['f'];
	

		$consulta = mysqli_query($conexion, "SELECT * FROM configuracion");
		$resultado = mysqli_fetch_assoc($consulta);
		$ventas = mysqli_query($conexion, "SELECT * FROM factura WHERE nofactura = $noFactura");
		$result_venta = mysqli_fetch_assoc($ventas);

		$tipo = $result_venta['idtipoventa'];
		if($tipo ==5)
		{
			$sql="SELECT * FROM arrendatarios inner join cubos on cubos.idarrendatario=arrendatarios.idarrendatario WHERE arrendatarios.idarrendatario = ".$codCliente." and cubos.codcubo='".$result_venta['observaciones']."'";
			//
			$arrendatarios = mysqli_query($conexion, $sql);
			$result_cliente= mysqli_fetch_assoc($arrendatarios);		
		}else
		{	$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $codCliente");
			$result_cliente = mysqli_fetch_assoc($clientes);

			$credito = mysqli_query($conexion, "SELECT * FROM creditos WHERE idcliente = ".$codCliente." and numcredito=".$result_venta['numcredito']."");
			$result_credito = mysqli_fetch_assoc($credito);
		}

		
		

		if($result_venta['numcredito']!= '' and $result_venta['numcredito']!=0)
		{
			$sql="SELECT d.nofactura, d.codproducto, SUM(d.cantidad) AS cantidad, p.codproducto, p.cantidad_mayoreo,p.descripcion, p.precio,d.precio_promocion, d.promocion, d.idtipopromocion FROM detallefactura d INNER JOIN producto p ON d.nofactura = ".$result_credito['nofacturaorigen']." WHERE d.codproducto = p.codproducto GROUP BY p.codproducto";
			//echo $sql;
			$productos = mysqli_query($conexion, $sql);
		}else{
			$sql="SELECT d.nofactura, d.codproducto, SUM(d.cantidad) AS cantidad, p.codproducto,p.cantidad_mayoreo, p.descripcion, p.precio, d.precio_promocion, d.promocion , d.idtipopromocion FROM detallefactura d INNER JOIN producto p ON d.nofactura = $noFactura WHERE d.codproducto = p.codproducto GROUP BY p.codproducto";
			//echo $sql;
			$productos = mysqli_query($conexion, $sql);
		}

		
		require_once 'fpdf/fpdf.php';
		$pdf = new FPDF('P', 'mm', array(78, 200));
		$pdf->AddPage();
		$pdf->SetMargins(1, 0, 0);
		$pdf->SetTitle("Ventas");
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(62, 5, utf8_decode($resultado['nombre']), 0, 1, 'C');
		$pdf->Ln();
		
		
		
		$tipop = $result_venta['idtipopago'];
		$referencia = $result_venta['referencia'];
		$saldo = $result_venta['saldo'];
		$totalfactura = $result_venta['totalfactura'];
		$totalventa = $result_venta['totalventa'];
		$tipopago='';
		$pagocon=$result_venta['pagocon'];
		if($tipop=='2')
		{ 
		$tipopago='Tarjeta';
		}if($tipop=='3')
		{ 
			$tipopago='Transferencia';

		}
		if($tipop=='4')
		{ 
			$tipopago='Deposito';

		}

		$efectivo = $result_venta['efectivo'];
		$tarjeta = $result_venta['tarjeta'];
		$transferencia = $result_venta['transferencia'];
		$deposito = $result_venta['deposito'];

		$pdf->image("img/icono.jpg", 40, 15, 25, 20, 'JPG');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, "Ruc: ", 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(20, 5, $resultado['dni'], 0, 1, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(19, 5, $resultado['telefono'], 0, 1, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(16, 5, utf8_decode("Dirección: "), 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(20, 5, utf8_decode($resultado['direccion']), 0, 1, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, "Ticket: ", 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(20, 5, $noFactura, 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(10, 5, "Fecha: ", 0, 0, 'R');		
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(25, 5, $result_venta['fecha'], 0, 1, 'R');
		$pdf->Cell(78,5,'******************************************************************************', 0, 1, 'C');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(85,5, "Datos del cliente", 0, 1, 'C');
		$pdf->Cell(78,5,'******************************************************************************', 0, 1, 'C');
		$pdf->Cell(55, 5, "Nombre", 0, 0, 'L');
		$pdf->Cell(15, 5, utf8_decode("Teléfono"), 0, 0, 'R');
		// $pdf->Cell(25, 5, utf8_decode("Dirección"), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Ln();

		if($tipo!='5')
		{
			if ($_GET['cl'] == 1) {
			$pdf->Cell(55, 5, utf8_decode("Público en general"), 0, 0, 'L');
			$pdf->Cell(18, 5, utf8_decode("-------------------"), 0, 0, 'L');
			// $pdf->Cell(25, 5, utf8_decode("-------------------"), 0, 1, 'L');
			}else{
			
			$pdf->Cell(55, 5, utf8_decode($result_cliente['nombre']), 0, 0, 'L');
			$pdf->Cell(15, 5, utf8_decode($result_cliente['telefono']), 0, 0, 'R');
			// $pdf->Cell(25, 5, utf8_decode($result_cliente['direccion']), 0, 1, 'L');
			}
	    }else
		{
			$pdf->Cell(55, 5, utf8_decode($result_cliente['nombre']), 0, 0, 'L');
			$pdf->Cell(15, 5, utf8_decode($result_cliente['telefono']), 0, 0, 'R');
		}



		if($tipo=='3')
		{		$pdf->Ln();	
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, "Tipo Venta: ", 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(20, 5, 'DEVOLUCION', 0, 0, 'L');
		
		}
		if($tipo=='2')
		{		$pdf->Ln();	
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(18, 5, "Tipo Venta: ", 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(22, 5, 'Credito', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, "Vencimiento: ", 0, 0, 'R');		
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(15, 5, date_format( date_create($result_venta['fecha']), 'd/m/Y') , 0, 1, 'R');
		}
		if($tipo=='5')
		{		$pdf->Ln();	
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, "Tipo: ", 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(18, 5, 'Renta', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 7);

		}

		$pdf->Cell(79, 5,'', 0, 1, 'C');
		$pdf->Cell(79, 5,'********************************************************************************', 0, 1, 'C');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(85, 5, "Detalle de Productos", 0, 1, 'C');
		$pdf->Cell(79, 5,'********************************************************************************', 0, 1, 'C');
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(32, 5, 'Nombre', 0, 0, 'L');
		$pdf->Cell(9, 5, 'Cant', 0, 0, 'R');
		$pdf->Cell(9, 5, 'Precio', 0, 0, 'R');
		$pdf->Cell(9, 5, 'Prom.', 0, 0, 'R');	
		$pdf->Cell(9, 5, 'Total', 0, 1, 'R');
		$pdf->SetFont('Arial', '', 6);

	

		if($tipo=='5')
		{
			
			$pdf->Cell(32, 5, ($result_cliente['cubo']), 0, 0, 'L');
			$pdf->Cell(9, 5, '1', 0, 0, 'C');
			$pdf->Cell(9, 5, '$'.number_format($result_cliente['renta'] ,2, '.', ','), 0, 0, 'R');

			if($result_cliente['renta']!=$totalfactura)
			{
				$pdf->Cell(9, 5,  '$'.number_format($totalfactura ,2, '.', ','), 0, 0, 'R');
			}
			
		
			$pdf->Cell(9, 5, '$'.number_format($totalfactura ,2, '.', ','), 0, 1, 'R');
			
		}else
		{	
			while ($row = mysqli_fetch_assoc($productos)) {
				//$algo = ;
				$pdf->Cell(30, 5, utf8_decode($row['descripcion']), 0, 0, 'L');
				//
				$pdf->Cell(10, 5, $row['cantidad'] , 0, 0, 'C');
				$pdf->Cell(10, 5, '$'.number_format($row['precio'], 2, '.', ','), 0, 0, 'R');
			


				if($row['idtipopromocion']==1)
				{
					$pdf->Cell(10, 5, number_format($row['promocion'], 2, '.', ',').'%', 0, 0, 'R');
				}
				else 
				{
					if($row['cantidad']>=$row['cantidad_mayoreo'] ) 
					{
						$pdf->Cell(10, 5, '$'.number_format($row['precio_promocion'], 2, '.', ','), 0, 0, 'R');
						
					}else
					{
						
						$pdf->Cell(10, 5, '$'.number_format($row['promocion'], 2, '.', ','), 0, 0, 'R');
					}
				
				}

				$importe = number_format($row['cantidad'] * $row['precio_promocion'], 2, '.', ',');
				$pdf->Cell(10, 5, '$'.$importe, 0, 1, 'R');
			}
		}
		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 7);



		/*VENTA CONTADO*/
		if($tipo==1)
		{
			
		if($tipop==5) /* ES UN PAGO MIXO*/		
		{		
				
			$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(70, 5, 'Efectivo: $' . $efectivo, 0, 1, 'R');		
		 		$pdf->Cell(70, 5, 'Tarjeta: $' . $tarjeta, 0, 1, 'R');
				$pdf->Cell(70, 5, 'Transferencia: $' . $transferencia, 0, 1, 'R');
				$pdf->Cell(70, 5, 'Deposito: $' . $deposito, 0, 1, 'R');
				$pdf->Ln();	
				$pdf->Cell(70, 5, 'Total: $' . number_format($totalventa, 2, '.', ','), 0, 1, 'R');	
					if($tarjeta!="" and  $tarjeta!="0.00")
					{
					$pdf->Cell(70, 5,  '5% de comision por pago con tarjeta', 0, 1, 'R');	
					} 
		}else
		{
			$pdf->Cell(70, 5, 'Total: $' . number_format($totalventa, 2, '.', ','), 0, 1, 'R');	
			$pdf->Cell(70, 5, 'Pago: $' . number_format($pagocon, 2, '.', ','), 0, 1, 'R');		
			if($tipop!=2)
			{
				$pdf->Cell(70, 5,  'Cambio: $' .number_format(($pagocon-$totalventa), 2, '.', ','), 0, 1, 'R');	
			}
		}

		}
		
		else if($tipo==2)/*VENTA CREDITO*/
		{
			   $pdf->Cell(70, 5, 'Total: $' . number_format($totalventa, 2, '.', ','), 0, 1, 'R');	
				
				/*EVALUAMOS SI HAY MAS ABONOS, PARA MOSTRAR EL SALDO*/ 	
				if(nabonos($result_venta['numcredito'])>1){
					$pdf->Cell(70, 5,  'Saldo: $' .number_format($saldo, 2, '.', ','), 0, 1, 'R');
				}

				if($tipop==2)
				{
					$pagoTarjeta=$pagocon+floatval (($pagocon*5)/100);
					$pdf->Cell(70, 5,  'Pago: $' .number_format($pagoTarjeta, 2, '.', ','), 0, 1, 'R');	
				
				}
				else if($tipop==5) /* ES UN PAGO MIXO*/		
				{	
				
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(70, 5, 'Efectivo: $' . $efectivo, 0, 1, 'R');		
		 		$pdf->Cell(70, 5, 'Tarjeta: $' . $tarjeta, 0, 1, 'R');
				$pdf->Cell(70, 5, 'Transferencia: $' . $transferencia, 0, 1, 'R');
				$pdf->Cell(70, 5, 'Deposito: $' . $deposito, 0, 1, 'R');
				$pdf->Ln();	
				//$pdf->Cell(70, 5, 'Total: $' . number_format($totalventa, 2, '.', ','), 0, 1, 'R');	
					if($tarjeta!="" and  $tarjeta!="0.00")
					{
						$pdf->Cell(70, 5,  '5% de comision por pago con tarjeta', 0, 1, 'R');	
					} 
		
				}
				else
				{
					$pdf->Cell(70, 5,  'Pago: $' .number_format($pagocon, 2, '.', ','), 0, 1, 'R');
				}
					
				
				


				$pdf->Cell(70, 5, 'Resta: $' . number_format(($saldo), 2, '.', ','), 0, 1, 'R');			
			
		}else if($tipo==3) /*VENTA DEVOLUCION*/
		{			
			$pdf->Cell(70, 5, 'Total: $' . number_format($totalventa, 2, '.', ','), 0, 1, 'R');	
			$pdf->Cell(70, 5, 'Pago: $' . number_format($pagocon, 2, '.', ','), 0, 1, 'R');	
			$pdf->Cell(70, 5, 'Referencia:' . $referencia, 0, 1, 'R');	
			
		}else if($tipo=='5')
		{
			$pdf->Cell(70, 5, 'Total: $' . number_format($totalfactura, 2, '.', ','), 0, 1, 'R');	
			$pdf->Cell(70, 5, 'Pago: $' . number_format($pagocon, 2, '.', ','), 0, 1, 'R');		
			if($tipop!=2){
			$pdf->Cell(70, 5, 'Cambio: $' .number_format(($pagocon-$totalfactura), 2, '.', ','), 0, 1, 'R');
		     }	
		}		
		else{
			$pdf->Cell(70, 5, 'Total: $' . number_format($totalventa, 2, '.', ','), 0, 1, 'R');	
			$pdf->Cell(70, 5, 'Pago: $' . number_format($pagocon, 2, '.', ','), 0, 1, 'R');				
		}
		

		// if($tipop==5) /* ES UN PAGO MIXO*/		
		// {				
		// 		$pdf->SetFont('Arial', 'B', 8);
		// 		$pdf->Ln();
		// 		$pdf->Cell(70, 5, 'Efectivo:' . $efectivo, 0, 1, 'R');		
		//  		$pdf->Cell(70, 5, 'Tarjeta:' . $tarjeta, 0, 1, 'R');
		// 		$pdf->Cell(70, 5, 'Transferencia:' . $transferencia, 0, 1, 'R');
		// 		$pdf->Cell(70, 5, 'Deposito:' . $deposito, 0, 1, 'R');
				 

		// }	
		 if($tipop!=1 and $tipop!=5) /* EL PAGO SE REALIZO POR TRANSFERENCIA , TARJETA O DEPOSITO*/		
		{				
				$pdf->SetFont('Arial', 'B', 7);
				
				$pdf->Cell(70, 5, 'Tipo dePago:' . $tipopago, 0, 1, 'R');		
		 		$pdf->Cell(70, 5, 'Referencia:' . $referencia, 0, 1, 'R');
				 $pdf->SetFont('Arial', 'B', 7);
				 if($tipop==2)
				 {
				 $pdf->Cell(70, 5,  '5% de comision por pago con tarjeta', 0, 1, 'R');	
				 }

		}
			
		
		$pdf->Ln();
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(85, 5, utf8_decode("¡Gracias por su preferencia!"), 0, 1, 'C');
		$pdf->Output("compra.pdf", "I");
		}

?>
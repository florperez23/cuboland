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
		$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $codCliente");
		$result_cliente = mysqli_fetch_assoc($clientes);

		$credito = mysqli_query($conexion, "SELECT * FROM creditos WHERE idcliente = ".$codCliente." and numcredito=".$result_venta['numcredito']."");
		$result_credito = mysqli_fetch_assoc($credito);
		

		if($result_venta['numcredito']!= '' and $result_venta['numcredito']!=0)
		{
			$sql="SELECT d.nofactura, d.codproducto, SUM(d.cantidad) AS cantidad, p.codproducto, p.descripcion, p.precio FROM detallefactura d INNER JOIN producto p ON d.nofactura = ".$result_credito['nofacturaorigen']." WHERE d.codproducto = p.codproducto GROUP BY p.codproducto";
			//echo $sql;
			$productos = mysqli_query($conexion, $sql);
		}else{
			$productos = mysqli_query($conexion, "SELECT d.nofactura, d.codproducto, SUM(d.cantidad) AS cantidad, p.codproducto, p.descripcion, p.precio FROM detallefactura d INNER JOIN producto p ON d.nofactura = $noFactura WHERE d.codproducto = p.codproducto GROUP BY p.codproducto");
		}

		
		require_once 'fpdf/fpdf.php';
		$pdf = new FPDF('P', 'mm', array(80, 200));
		$pdf->AddPage();
		$pdf->SetMargins(1, 0, 0);
		$pdf->SetTitle("Ventas");
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(60, 5, utf8_decode($resultado['nombre']), 0, 1, 'C');
		$pdf->Ln();
		
		
		$tipo = $result_venta['idtipoventa'];
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

		$pdf->image("img/aguira.jpg", 50, 18, 25, 10, 'JPG');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, "Ruc: ", 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(20, 5, $resultado['dni'], 0, 1, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(20, 5, $resultado['telefono'], 0, 1, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, utf8_decode("Dirección: "), 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(20, 5, utf8_decode($resultado['direccion']), 0, 1, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, "Ticked: ", 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(20, 5, $noFactura, 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(16, 5, "Fecha: ", 0, 0, 'R');		
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(25, 5, $result_venta['fecha'], 0, 1, 'R');
		$pdf->Cell(78,5,'******************************************************************************', 0, 1, 'C');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(75,5, "Datos del cliente", 0, 1, 'C');
		$pdf->Cell(78,5,'******************************************************************************', 0, 1, 'C');
		$pdf->Cell(55, 5, "Nombre", 0, 0, 'L');
		$pdf->Cell(20, 5, utf8_decode("Teléfono"), 0, 0, 'R');
		// $pdf->Cell(25, 5, utf8_decode("Dirección"), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Ln();
		if ($_GET['cl'] == 1) {
		$pdf->Cell(55, 5, utf8_decode("Público en general"), 0, 0, 'L');
		$pdf->Cell(20, 5, utf8_decode("-------------------"), 0, 0, 'L');
		// $pdf->Cell(25, 5, utf8_decode("-------------------"), 0, 1, 'L');
		}else{
		
		$pdf->Cell(55, 5, utf8_decode($result_cliente['nombre']), 0, 0, 'L');
		$pdf->Cell(20, 5, utf8_decode($result_cliente['telefono']), 0, 0, 'R');
		// $pdf->Cell(25, 5, utf8_decode($result_cliente['direccion']), 0, 1, 'L');
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
		$pdf->Cell(15, 5, "Tipo Venta: ", 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(20, 5, 'Credito', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(16, 5, "Vencimiento: ", 0, 0, 'R');		
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(25, 5, date_format( date_create($result_venta['fecha']), 'd/m/Y') , 0, 1, 'R');
		}

		$pdf->Cell(78, 5,'', 0, 1, 'C');
		$pdf->Cell(78, 5,'********************************************************************************', 0, 1, 'C');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(75, 5, "Detalle de Productos", 0, 1, 'C');
		$pdf->Cell(78, 5,'********************************************************************************', 0, 1, 'C');
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(42, 5, 'Nombre', 0, 0, 'L');
		$pdf->Cell(8, 5, 'Cant', 0, 0, 'L');
		$pdf->Cell(15, 5, 'Precio', 0, 0, 'L');
		$pdf->Cell(15, 5, 'Total', 0, 1, 'L');
		$pdf->SetFont('Arial', '', 7);
		while ($row = mysqli_fetch_assoc($productos)) {
			$pdf->Cell(42, 5, utf8_decode($row['descripcion']), 0, 0, 'L');
			$pdf->Cell(8, 5, $row['cantidad'], 0, 0, 'L');
			$pdf->Cell(15, 5, '$'.number_format($row['precio'], 2, '.', ','), 0, 0, 'L');
			$importe = number_format($row['cantidad'] * $row['precio'], 2, '.', ',');
			$pdf->Cell(15, 5, '$'.$importe, 0, 1, 'L');
		}
		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 8);



		/*VENTA CONTADO*/
		if($tipo==1)
		{
			$pdf->Cell(76, 5, 'Total: $' . number_format($totalventa, 2, '.', ','), 0, 1, 'R');	
			$pdf->Cell(76, 5, 'Pago: $' . number_format($pagocon, 2, '.', ','), 0, 1, 'R');		
			$pdf->Cell(76, 5,  'Cambio: $' .number_format(($pagocon-$totalventa), 2, '.', ','), 0, 1, 'R');	
		}
		
		else if($tipo==2)/*VENTA CREDITO*/
		{
			
			   $pdf->Cell(76, 5, 'Total: $' . number_format($totalventa, 2, '.', ','), 0, 1, 'R');	
				
				/*EVALUAMOS SI HAY MAS ABONOS, PARA MOSTRAR EL SALDO*/ 	
				if(nabonos($result_venta['numcredito'])>1){
					$pdf->Cell(76, 5,  'Saldo: $' .number_format($saldo, 2, '.', ','), 0, 1, 'R');
				}
				$pdf->Cell(76, 5,  'Pago: $' .number_format($pagocon, 2, '.', ','), 0, 1, 'R');		
				$pdf->Cell(76, 5, 'Resta: $' . number_format(($saldo-$pagocon), 2, '.', ','), 0, 1, 'R');
			
			
		}else /*VENTA DEVOLUCION*/
		{
			
			$pdf->Cell(76, 5, 'Total: $' . number_format($totalventa, 2, '.', ','), 0, 1, 'R');	
			$pdf->Cell(76, 5, 'Pago: $' . number_format($pagocon, 2, '.', ','), 0, 1, 'R');	
			$pdf->Cell(76, 5, 'Referencia:' . $referencia, 0, 1, 'R');	
		
			
			
		}
		


		if($tipop!=1) /* EL PAGO SE REALIZO POR TRANSFERENCIA O TARJETA*/		
		{				
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(76, 5, 'Tipo dePago:' . $tipopago, 0, 1, 'R');		
		 		$pdf->Cell(76, 5, 'Referencia:' . $referencia, 0, 1, 'R');

		}	
			
		
		$pdf->Ln();
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(80, 5, utf8_decode("¡Gracias por su preferencia!"), 0, 1, 'C');
		$pdf->Output("compra.pdf", "I");
		}

?>
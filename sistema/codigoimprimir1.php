<?php
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}

	include "../conexion.php";
	include("includes/functions.php");
	

	require_once 'factura/fpdf/fpdf.php';

    $codigo = $_GET['codigo'];

    $pdf = new FPDF('L', 'mm', array(54, 13));
    $pdf->AddPage();
	//$pdf->SetMargins(1, 0, 0);
	$pdf->SetTitle("Codigo de barras");
	$pdf->SetFont('Arial', '', 3);
    // Image example with iresizingconfig
    //$img = file_get_contents('codigosGenerados/'.$codigo.'.png');
    //$pdf->Image('@' . $img, 10, 20, 27, 13, '', '', 'center', false, 0, '', false, false, 0, false, false, false);
   //izquierda
	 //$pdf->image('codigosGenerados/'.$codigo.'.png', 0, 1, 27, 13, 'PNG');
	 //$pdf->AddPage();
	//derecha
	 //$pdf->image('codigosGenerados/'.$codigo.'.png', 30, 1, 27, 13, 'PNG');
	 //$pdf->AddPage();

	 //precio y descripcion
	 $descripcion = descripcion_producto($codigo);
	 $precio = precio_producto($codigo);

	
	
	

/*
	
	$pdf->Cell(25, 1, $descripcion, 1, 0, 'C');
	$pdf->MultiCell(25,1,$descripcion,1,0,'C');
	//$pdf->Cell(25, 1, $descripcion, 0, 0, 'C');

	$pdf->SetXY(0, 2);
	$pdf->Cell(25, 1, '$'.$precio, 0, 0, 'C');
	$pdf->MultiCell(25,1,'$'.$precio,1,0,'C');
	//$pdf->Cell(25, 1, '$'.$precio, 0, 0, 'C');
	//dos
	$pdf->SetXY(2, 4);
	

	/*$pdf->SetXY(30, 0);
	

	$pdf->SetXY(30, 2);
	 */

	/* $pdf->SetXY(34, 4);*/

	//ejemplo con write 
	/*$pdf->SetXY(0,0);
	$pdf->Write(2, $descripcion.'		'.$descripcion, '');
	$pdf->SetXY(0,2);
	$pdf->Write(2, '$'.$precio.'		'.'$'.$precio, '');*/


	//ejemplo con CELL
	
	
	
	$pdf->image('codigosGenerados/'.$codigo.'.png', 0, 1,22, 10, 'PNG');
	$pdf->image('codigosGenerados/'.$codigo.'.png', 32, 1, 22, 10, 'PNG');

	
	//$pdf->SetXY(0,0);
	/*$pdf->Cell(20, 1, $descripcion, 1, 0, 'C');
	$pdf->Cell(20, 1, $descripcion, 1, 1, 'C');
	$pdf->Ln(0);
	//$pdf->SetXY(0, 2);
	$pdf->Cell(20,.7,'$'.$precio, 1,0,'C');
	$pdf->Cell(20, .7, '$'.$precio, 1, 1, 'C');*/
    //Close and output PDF document
    $pdf->Output('codigobarra.pdf', 'I');

?>
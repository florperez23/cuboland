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
//54,13
    $pdf = new FPDF('L', 'mm', array(54, 13));
    $pdf->AddPage();
	//$pdf->SetMargins(1, 0, 0);
	$pdf->SetTitle("Codigo de barras");
	$pdf->SetFont('Arial', '', 4);
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

//agregar el texto a la imagen
$ruta = 'codigosGenerados/Roboto-Regular.ttf';
$nombreImagen = 'codigosGenerados/'.$codigo.'.png';
$imagen = imagecreatefrompng($nombreImagen);
$color = imagecolorallocate($imagen, 0, 0, 0);
$tamanio = 13;
$angulo = 0;
$x = 30;
$y = 100;
$texto1 = '$'.$precio;


 $ruta=dirname(__DIR__, 1)."/sistema/codigosGenerados/Roboto-Regular.ttf";
imagettftext($imagen, $tamanio, $angulo, $x, $y, $color, $ruta, $texto1);
imagepng ($imagen, 'codigosGenerados/'.$codigo.'_1.png'); //la imagen se archiva en la ruta dada
imagedestroy($imagen);
	
	//Buscar en el php.ini
	//extension=php_gd2.dll

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
	

	
	$pdf->image('codigosGenerados/'.$codigo.'_1.png', 0, 1,22, 10, 'PNG');
	$pdf->image('codigosGenerados/'.$codigo.'_1.png', 32, 1, 22, 10, 'PNG');

			
	
		/*$ancho=22;
		$alto=10;
		
		$pdf->SetXY(0,1);
		$pdf->Cell($ancho,$alto,
		$pdf->Image('codigosGenerados/'.$codigo.'.png', 
		$pdf->GetX(), $pdf->GetY(), 22,10), 
		$pdf->Multicell(30,1, $pdf->Image('codigosGenerados/'.$codigo.'.png', 
		$pdf->GetX(), $pdf->GetY(), 22,10), 0,"R", false), 0);
		$pdf->SetX(0);  
		$pdf->SetY(20);    
		$pdf->Cell(22,2,
		'$'.$precio, $pdf->Multicell(30,1, '$'.$precio, 0,"R", false), 0);*/
	
		/*$pdf->SetXY(0,1);
		$pdf->Cell(5,.4,'$'.$precio,1,0,'C');
		//$pdf->Cell(16,3,$pdf->Image('codigosGenerados/'.$codigo.'.png', 
		//$pdf->GetX(), $pdf->GetY(), 16,8),0,0,'C');

		$pdf->SetXY(30,1);
		$pdf->Cell(5,.4,'$'.$precio,0,1,'C');
		//$pdf->Cell(16,3,$pdf->Image('codigosGenerados/'.$codigo.'.png', 
		//$pdf->GetX(), $pdf->GetY(), 16,8),0,1,'C');

		$pdf->SetXY(0,10);
		$pdf->Cell(5,.4,'$'.$precio,0,0,'C');

		$pdf->SetXY(30,10);
		$pdf->Cell(5,.4,'$'.$precio,0,0,'C');*/

	
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
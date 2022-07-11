
<?php include_once "includes/header.php"; 



//GUARDAR ARCHIVOS
if(isset($_POST['proveedor'])){
	
	$proveedor = $_POST['proveedor'];
	$subtotal = $_POST['subtotal'];
	$iva = $_POST['iva'];
	$total = $_POST['total'];
	$fecha = $_POST['fecha'];
	$descripcion = $_POST['descripcion'];
	$usuario_id = $_SESSION['idUser'];

	$total = 0 - $total ;

		$sql = "INSERT INTO gastos(fecha,activo, proveedor, subtotal, iva, total, descripcion, idusuariosube) values ( '$fecha', '1', '$proveedor','$subtotal', '$iva', '$total', '".$descripcion."', '".$usuario_id."')";
		echo $sql;	
		$query_insert = mysqli_query($conexion, $sql);
        if ($query_insert) {

			//HACER el insert el factura para tomarlo en cuenta en los reportes
			$sql = "INSERT INTO factura(nofactura,fecha,usuario,codcliente,totalfactura,idtipoventa,idtipopago,	cancelado,totalventa,referencia,pagocon,numcredito,	saldo,fechacancelacion,usuario_id_mod,subtotal,iva) 
			values ('', now(), '$usuario_id','$proveedor', '$total','4','1',0,'$total','Gasto','','','','','','$subtotal', '$iva')";
			echo $sql;	
			$query_insert = mysqli_query($conexion, $sql);
        	if ($query_insert) {

				historia('Se registro un nuevo gasto ');
				mensajeicono('Se ha registrado con éxito el nuevo gasto!', 'lista_factura.php','','exito');
			}else{
				historia('Error al intentar ingresar un nuevo gasto ');
				mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_factura.php','','error');
			}

        } else {
			historia('Error al intentar ingresar un nuevo gasto ');
			mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_factura.php','','error');
        }
    
	
}


?>




<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Facturas</h1>
		<a href="registro_factura.php" class="btn btn-primary">Nuevo</a>
	</div>

	<!-- Elementos para crear el reporte -->
	<form action="reporteGastos.php" method="post">
	<div class="row">
	
		<div class="col-md-4">
			<label for="producto">Desde</label>
            <input type="date" name="desde" id="desde" value='<?php echo $fecha ?>' class="form-control">
		</div>
		<div class="col-md-4">
			<label for="producto">Hasta</label>
            <input type="date" name="hasta" id="hasta" value='<?php echo $fecha ?>' class="form-control">
		</div>
		<div class="col-md-4">
			<input type="submit" value="Generar Reporte" class="btn btn-primary">
		</div>
	
	</div>
	</form>	







	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered" id="table">
					<thead class="thead-dark">
						<tr>
							<th>ID</th>
							<th>PROVEEDOR</th>
							<th>TOTAL</th>
							<th>DESCRIPCIÓN</th>
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT a.id, a.proveedor, a.subtotal, a.iva, a.total, a.descripcion, pr.proveedor as nomproveedor
						FROM gastos a
						left join proveedor pr on pr.codproveedor = a.proveedor");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['id']; ?></td>
									<td style='width:200px;'><?php echo $data['nomproveedor']; ?></td>
									<td style='width:150px;'><?php echo '$ '.$data['total']; ?></td>
									<td><?php echo $data['descripcion']; ?></td>
									<?php if ($_SESSION['rol'] == 1) { ?>
									<td>
										<form action="eliminar_gastos.php?id=<?php echo $data['id']; ?>" method="post" class="confirmar d-inline">
											<button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
										</form>
									</td>
										<?php } ?>
								</tr>
						<?php }
						} ?>
					</tbody>

				</table>
			</div>

		</div>
	</div>


</div>
<!-- /.container-fluid -->


<?php include_once "includes/footer.php"; ?>

<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Lista de ventas</h1>
		<a href="nueva_venta.php" class="btn btn-primary">Nueva</a>
	</div>

	<!-- Elementos para crear el reporte -->
	<form action="reporteVentas.php" method="post">
	<div class="row">
	
		<div class="col-md-4">
			<label for="producto" style='color:#000;'>Desde</label>
            <input type="date" name="desde" id="desde" value='<?php echo $fecha ?>' class="form-control">
		</div>
		<div class="col-md-4">
			<label for="producto" style='color:#000;'>Hasta</label>
            <input type="date" name="hasta" id="hasta" value='<?php echo $fecha ?>' class="form-control">
		</div>
		<!-- <div class="col-md-4">
			<input type="submit" value="Generar Reporte" class="btn btn-primary">
		</div>-->
	
	</div>
	<div class="row">
		<div class="col" style='width: 500px;'>
			<div class="form-group">
			<label style='color:#000'>Tipo de venta</label>
				<select id="tipoventa" name="tipoventa"  class="form-control">
					<option value="0">SIN ESPECIFICAR</option>
					<option value="1">CONTADO</option>
					<option value="2">CRÉDITO</option>
					<option value="3">DEVOLUCIÓN</option>
				</select>
			</div>
		</div>
		<div class="col" style='width: 500px;'>
			<div class="form-group">
				<label style='color:#000'>Tipo de pago</label>
				<select id="tipopago" name="tipopago"  class="form-control">
					<option value="0">SIN ESPECIFICAR</option>
					<option value="1">EFECTIVO</option>
					<option value="2">TARJETA</option>
					<option value="3">TRANSFERENCIA</option>
					<option value="4">DEPOSITO</option>
					<option value="5">MIXTO</option>
				</select>
			</div>
		</div>
		<div class="col" style='align:right;'>
			<div class="d-sm-flex align-items-center justify-content-between mb-4">

				<input type="submit" value="Generar Reporte" class="btn btn-primary">
			</div>
		</div>
    </div>

	</form>	



	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered" id="table">
					<thead class="thead-dark">
						<tr>
							<th>Id</th>
							<th>Fecha</th>
							<th>Total</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php
						require "../conexion.php";
						$query = mysqli_query($conexion, "SELECT nofactura, fecha,codcliente, totalfactura,idtipoventa,totalventa FROM factura WHERE cancelado = 0 and idtipoventa in (1,2,3) ORDER BY nofactura DESC");
						mysqli_close($conexion);
						$cli = mysqli_num_rows($query);

						if ($cli > 0) {
							while ($dato = mysqli_fetch_array($query)) {
						?>
								<tr>
									<td><?php echo $dato['nofactura']; ?></td>
									<td><?php echo date("d/m/Y H:i:s", strtotime($dato['fecha'])); ?></td>
									<td><?php echo $dato['totalfactura']; ?></td>
									<td>
										<button type="button" class="btn btn-primary view_factura" cl="<?php echo $dato['codcliente'];  ?>" f="<?php echo $dato['nofactura']; ?>" p="<?php echo $dato['totalfactura']; ?>" t="<?php echo $dato['idtipoventa']; ?>">Ver</button>
										<form action="eliminar_venta.php?id=<?php echo $dato['nofactura']; ?>" method="post" class="cancelar d-inline">
											<button  title="Cancelar" class="btn btn-danger" type="submit"><i class="fa fa-ban"></i> </button>
										</form>
									</td>
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
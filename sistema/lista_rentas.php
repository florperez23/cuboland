<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Informacion de los rentas</h1>
		<a href="registro_renta.php" class="btn btn-primary">Nuevo</a>
	</div>


	<!-- Elementos para crear el reporte -->
	<form action="reporteRentas.php" method="post">
	<div class="row">
	<div class="col-md-4" >
			<div class="form-group">
			<label>FILTRO</label>
				<select id="tipo" name="tipo"  class="form-control">
					<option value="0">SIN ESPECIFICAR</option>
					<option value="1">PAGADAS</option>
					<option value="2">NO PAGADAS</option>
				
				</select>
			</div>
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
							<th>Codcubo</th>
							<th>Cubo</th>
							<th>Arrendatario</th>
							<th>Fecha Contrato</th>
							<th>Fecha último pago</th>
                            <th>Fecha próximo pago</th>
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT r.*, c.cubo, a.nombre FROM rentas r
                        inner join cubos c on c.codcubo = r.idcubo
                        inner join  arrendatarios a on a.idarrendatario = r.idarrendatario where r.cancelado = 0");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['idcubo']; ?></td>
									<td><?php echo $data['cubo']; ?></td>
									<td><?php echo $data['nombre']; ?></td>
									<td><?php echo $data['fechacontrato']; ?></td>
									<td><?php echo $data['fechaultimopago']; ?></td>
                                    <td><?php echo $data['fechaproximopago']; ?></td>
									<?php if ($_SESSION['rol'] == 1) { ?>
									<td>
										<a href="editar_renta.php?id=<?php echo $data['id']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
										<form action="eliminar_renta.php?id=<?php echo $data['id']; ?>" method="post" class="confirmar d-inline">
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
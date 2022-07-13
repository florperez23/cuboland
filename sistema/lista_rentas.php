<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Informacion de los rentas</h1>
		<a href="registro_renta.php" class="btn btn-primary">Nuevo</a>
	</div>

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
                        inner join  arrendatarios a on a.idarrendatario = r.idarrendatario");
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
										<form action="eliminar_cubo.php?id=<?php echo $data['codcubo']; ?>" method="post" class="confirmar d-inline">
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
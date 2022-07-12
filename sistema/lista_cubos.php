<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Informacion de los Cubos</h1>
		<a href="registro_cubo.php" class="btn btn-primary">Nuevo</a>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered" id="table">
					<thead class="thead-dark">
						<tr>
							<th>ID</th>
							<th>Cubo</th>
							<th>Precio Renta</th>
							<th>Disponible</th>
							<th>Arrendatario</th>
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT c.*, a.nombre FROM cubos c
						left join arrendatarios a on a.idarrendatario = c.idarrendatario");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['codcubo']; ?></td>
									<td><?php echo $data['cubo']; ?></td>
									<td><?php echo $data['renta']; ?></td>
									<td><?php echo $data['disponible']; ?></td>
									<td><?php echo $data['nombre']; ?></td>
									<?php if ($_SESSION['rol'] == 1) { ?>
									<td>
										<a href="editar_cubo.php?id=<?php echo $data['codcubo']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
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
<?php include_once "includes/header.php"; ?>
<?php
    if(isset($_GET['ideliminar'])){
        require("../conexion.php");
        $id = $_GET['ideliminar'];
        $query_delete = mysqli_query($conexion, "DELETE FROM cat_departamento WHERE iddepartamento = $id");
		historia('Se elimino la categoria '.$id);
		mysqli_close($conexion);
		mensajeicono('Se ha eliminado con éxito la categoría!', 'lista_cat.php','','exito');
    }
?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Proveedores</h1>
		<a href="registro_cat.php" class="btn btn-primary">Nuevo</a>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered" id="table">
					<thead class="thead-dark">
						<tr>
							<th>ID</th>
							<th>CATEGORIA</th>
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM cat_departamento");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['iddepartamento']; ?></td>
									<td><?php echo $data['departamento']; ?></td>
									<?php if ($_SESSION['rol'] == 1) { ?>
									<td>
										<form action="lista_cat.php?ideliminar=<?php echo $data['iddepartamento']; ?>" method="post" class="confirmar d-inline">
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
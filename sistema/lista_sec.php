<?php include_once "includes/header.php"; ?>
<?php
    if(isset($_GET['ideliminar'])){
        require("../conexion.php");
        $id = $_GET['ideliminar'];
        $query_delete = mysqli_query($conexion, "DELETE FROM cat_secciones WHERE idseccion = $id");
        //mysqli_close($conexion);
		historia('Se elimino la seccion '.$id);
		mensajeicono('Se ha eliminado con éxito la sección!', 'lista_sec.php','','exito');

    }
?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Secciones de productos</h1>
		<a href="registro_sec.php" class="btn btn-primary">Nuevo</a>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered" id="table">
					<thead class="thead-dark">
						<tr>
							<th>ID</th>
							<th>SECCIÓN</th>
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM cat_secciones");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['idseccion']; ?></td>
									<td><?php echo $data['seccion']; ?></td>
									<?php if ($_SESSION['rol'] == 1) { ?>
									<td>
										<form action="lista_sec.php" method="get" class="confirmar d-inline">
											<input type='hidden' id='ideliminar' name='ideliminar'	value='<?php echo $data['idseccion']; ?>'>
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
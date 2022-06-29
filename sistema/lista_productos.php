<?php include_once "includes/header.php"; ?>
<script>

function cargar_secciones(){

let idcat = $('#categoria').val();
$.ajax({
  url: "cargar_secciones.php",
  type: "post",
  data: {idcat: idcat},
  success: function(data){
      $('#seccion').html(data+"\n");
  }
});

}
</script>
<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Productos</h1>
		<a href="registro_producto.php" class="btn btn-primary">Nuevo</a>
		<!--<a href="reportes_productos.php" class="btn btn-primary">Reportes</a> -->
	</div>

	<!-- Begin Page Content -->
<div class="container-fluid">
 <!-- Content Row -->
    <div class="row">
        <!-- Elementos para crear el reporte -->
        <form action="reporteProductos.php" method="post">
        <div class="row">
		<div class="col" style='width: 500px;'>
			<div class="form-group">
			<label style='color:#000'>Reporte por Categoría</label>
			<?php
			$query_dptos = mysqli_query($conexion, "SELECT iddepartamento, departamento FROM cat_departamento ORDER BY iddepartamento ASC");
			$resultado_dptos = mysqli_num_rows($query_dptos);
			
			?>

				<select id="categoria" name="categoria" onchange="cargar_secciones();" class="form-control">
				<?php
				if ($resultado_dptos > 0) {
					while ($dptos = mysqli_fetch_array($query_dptos)) {
						if($dptos['iddepartamento'] == 0){
				?>
					<option value="<?php echo $dptos['iddepartamento']; ?>">TODOS</option>
				<?php
						}else{
				?>
					<option value="<?php echo $dptos['iddepartamento']; ?>"><?php echo $dptos['departamento']; ?></option>
				<?php
						}
					}
				}
					?>
				</select>
			</div>
		</div>
		<div class="col" style='width: 500px;'>
			<div class="form-group" name='seccion' id='seccion'>
		
			</div>
		</div>
		<div class="col" style='align:right;'>
			<div class="d-sm-flex align-items-center justify-content-between mb-4">

				<input type="submit" value="Generar Reporte" class="btn btn-primary">
			</div>
		</div>
    </div>

    </form>	
    </div>
</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered" id="table">
					<thead class="thead-dark">
						<tr>
							<th>ID</th>
							<th>CODIGO</th>
							<th>PRODUCTO</th>
							<th>PRECIO</th>
							<th>STOCK</th>
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM producto");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['codproducto']; ?></td>
									<td><?php echo $data['codigo']; ?></td>
									<td><?php echo $data['descripcion']; ?></td>
									<td><?php echo $data['precio']; ?></td>
									<td><?php echo $data['existencia']; ?></td>
										<?php if ($_SESSION['rol'] == 1) { ?>
									<td>
										 <!-- <a href="agregar_producto.php?id=<?php //echo $data['codproducto']; ?>" class="btn btn-primary"><i class='fas fa-audio-description'></i></a>-->

										<a href="editar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

										<form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
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
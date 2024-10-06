<?php include_once "includes/header.php"; 

    $codcubo = $_POST['codcubo'];
  
?>


<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Historial de bajas del cubo </h1>
	</div>

	<!-- Content Row -->
    <div class="row">
        <!-- Elementos para crear el reporte -->
        <form action="reporte_HistorialBajas.php" method="post">
        <div class="row">
	
		<div class="col" style='align:right;'>
			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<input type="hidden" name="cod_cubo" id="cod_cubo" value="<?php echo $codcubo ?>">
				<input type="submit" value="Generar Reporte" class="btn btn-primary">
			</div>
		</div>
		
    </div>
	
    </form>	


	
   
</div>





	<div id="respuesta">
	

	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered" id="table">
					<thead class="thead-dark">
						<tr>
							<!--<th>Codcubo</th>-->
							<th>Código Producto</th>
							<th>Cantidad</th>
							<th>Precio</th>
							<th>Descripción</th>
							<th>Fecha Baja</th>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";
						$sql = "SELECT * from historial_bajas where codcubo = ".$codcubo." ";

						$query = mysqli_query($conexion, $sql);
						//echo $sql;

						if (!empty($query) AND  mysqli_num_rows($query) > 0) { 
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['codproducto']; ?></td>
									<td><?php echo $data['cantidad']; ?></td>
									<td><?php echo $data['precio_venta']; ?></td>
									<td><?php echo $data['descripcion']; ?></td>
									<td><?php echo $data['fecha']; ?></td>
								</tr>
						<?php }
						} ?>
						
					</tbody>

				</table>
			</div>

		</div>
	</div>

	</div>


</div>
<!-- /.container-fluid -->


<?php include_once "includes/footer.php"; ?>
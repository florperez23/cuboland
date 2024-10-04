<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Informacion de los rentas</h1>
		<a href="registro_renta.php" class="btn btn-primary">Nuevo</a>
		<a href="historial_rentas.php" class="btn btn-primary">Historial</a>
	</div>

	<div id="respuesta">
	
	<!-- Elementos para crear el reporte -->
	<form action="reporteRentas.php" method="post">
	<div class="row">
		<div class="col-md-4" >
			<div class="form-group">
			<label>FILTRO</label>
				<select id="tipo" name="tipo" onchange="filtro()" class="form-control">
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
							<!--<th>Codcubo</th>-->
							<th>Cubo</th>
							<th>Arrendatario</th>
							<th>Fecha Contrato</th>
							<th>Fecha último pago</th>
                            <!-- <th>Fecha próximo pago</th> -->
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";
  /* <td><?php echo date("d/m/Y", strtotime($data['fechaproximopago'])); ?></td> */
						$query = mysqli_query($conexion, "SELECT r.*, c.cubo, a.nombre FROM rentas r
                        inner join cubos c on c.codcubo = r.idcubo
                        inner join  arrendatarios a on a.idarrendatario = r.idarrendatario where r.cancelado = 0
						ORDER BY SUBSTR(c.nomenclatura, 1, 1), CAST(SUBSTR(c.nomenclatura, 2, LENGTH(c.nomenclatura)) AS UNSIGNED) ");
						$result = mysqli_num_rows($query);
						if ($result > 0) { 
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<!--<td><?php echo $data['idcubo']; ?></td>-->
									<td><?php echo $data['cubo']; ?></td>
									<td><?php echo $data['nombre']; ?></td>
									
									<td><?php echo date("d/m/Y", strtotime($data['fechacontrato'])); ?></td>
									<td><?php echo date("d/m/Y", strtotime($data['fechaultimopago'])); ?></td>
                                 
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


</div>
<!-- /.container-fluid -->
<script>
  	function filtro(){
		var tipo = $('#tipo').val();

		$.ajax({
			url: "cargar_rentas.php",
			type: "post",
			data: {tipo: tipo},
			success: function(data){

				$('#respuesta').html(data+"\n");
			}
		});
	}
    

  </script>

<?php include_once "includes/footer.php"; ?>
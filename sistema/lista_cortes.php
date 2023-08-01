<?php include_once "includes/header.php"; 
?>


<!-- Modal -->
<div class="modal fade" id="abrircorte" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Abrir corte de caja</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">X</span>
        </button>
      </div>
      <div class="modal-body">
	 
		<label for="montoinicial">Monto Inicial</label>
		<input type="text" placeholder="Ingrese el monto inicial" id="montoinicial" name="montoinicial" class="form-control">
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		<a href="#" class="btn btn-primary" id="btn_guardarcorte">Guardar</a>
		<div class="alert alertAddProduct" style='color:#fff'></div>
      </div>
    </div>
  </div>
</div>

	<!-- Elementos para crear el reporte -->
	<form action="reporteCortes.php" method="post">
	<div class="row">
		<div class="col-md-4">
			<label>Cubo</label>
			<?php
				$query = mysqli_query($conexion, "select * from cubos");
				$res = mysqli_num_rows($query);
			?>

			<select id="cubo" name="cubo" class="form-control">
				<option value='0'>TODOS</option>
				<?php
				if ($res > 0) {
					while ($f = mysqli_fetch_array($query)) {
				?>
					<option value="<?php echo $f['codcubo']; ?>"><?php echo $f['cubo']; ?></option>
				<?php
					}
				}
				
				?>
			</select>
		</div>
	
		<div class="col-md-4">
			<label for="producto" style='color:#000;'>Desde</label>
            <input type="date" name="desde" id="desde" value = "<?php echo $fecha ?>" class="form-control">
		</div>
		<div class="col-md-4">
			<label for="producto" style='color:#000;'>Hasta</label>
            <input type="date" name="hasta" id="hasta" value = "<?php echo $fecha ?>" class="form-control">
		</div>
		<div class="col-md-4">
			<input type="submit" value="Generar corte" class="btn btn-primary">
		</div>
	
	</div>
	
	</form>	



<!-- Modal -->
<div class="modal fade" id="cerrarcorte" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"  id="exampleModalLongTitle">Cerrar corte de caja</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">X</span>
        </button>
      </div>
	  <?php
	  //BUSCAMOS LA FECHA DEL ULTIMO CORTE ABIERTO
	  $fechaCorte = fechaCorte();
	  $montoinicial = montoInicial();
	  $id = idCorteAbierto();
		$sql = "SELECT  SUM(total) Total, SUM(TotalVentas ) TotalVentas
		FROM
		(
			SELECT SUM(f.totalfactura)  as total, count(f.nofactura) as TotalVentas
				FROM factura f
				WHERE f.fecha between '".$fechaCorte."' and now() 
				
		) t";

		//echo $sql;
		$query = mysqli_query($conexion, $sql);
		$result = mysqli_num_rows($query);
	  
		if ($result > 0) {
		  $data = mysqli_fetch_assoc($query);
		}
	?>
		

      <div class="modal-body">
	  	<input type="hidden" id="idcorte" name="idcorte" value="<?php echo $id; ?>">
		<label for="montoinicial" >Monto Inicial</label>
		<input type="text" placeholder="Monto inicial de las ventas" id="montoinicial" name="montoinicial" value="<?php echo $montoinicial; ?>" class="form-control" readonly>
      </div>
	  <div class="modal-body">
		<label for="montofinal" >Monto Final</label>
		<input type="text" placeholder="Monto final de las ventas" id="montofinal" name="montofinal" value="<?php echo $data['Total']; ?>" class="form-control" readonly>
      </div>
	  <div class="modal-body">
		<label for="totalventas" >Total Ventas</label>
		<input type="text" placeholder="total de ventas" id="totalventas" name="totalventas" value="<?php echo $data['TotalVentas']; ?>" class="form-control" readonly>
      </div>
	  <div class="modal-body">
		<label for="totalventas" >Monto General</label>
		<input type="text" placeholder="Monto general de las ventas" id="montogral" name="montogral" value="<?php echo $montoinicial + $data['Total']; ?>" class="form-control" readonly>
      </div>
	  <div class="alert alertAddProduct" ></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		<a href="#" class="btn btn-primary" id="btn_cerrarcorte">Guardar</a>
		

      </div>
    </div>
  </div>
</div>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Cortes de Caja</h1>
		<!-- Button trigger modal -->
		<?php 
		if($fechaCorte == 0){
		?>
		<button type="button" id='abrir' class="btn btn-primary" data-toggle="modal" data-target="#abrircorte">
			Abrir
		</button>
		<?php }else{ ?>
		<button type="button" id='cerrar' class="btn btn-primary" data-toggle="modal" data-target="#cerrarcorte">
			Cerrar
		</button>
		<?php } ?>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered" id="table">
					<thead class="thead-dark">
						<tr>
							<th>ID</th>
							<th>MONTO INICIAL</th>
							<th>MONTO FINAL</th>
							<th>FECHA APERTURA</th>
							<th>FECHA CIERRE</th>
                            <th>TOTAL VENTAS</th>
							<th>MONTO TOTAL</th>
                            <th>ESTADO</th>
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
							<th>PDF</th>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM cortecaja ORDER BY Id DESC");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td style='width:200px;'><?php echo $data['Id']; ?></td>
									<td style='width:150px;'><?php echo '$ '.$data['MontoInicial']; ?></td>
									<td><?php echo $data['MontoFinal']; ?></td>
									
                                    <td><?php echo date("d/m/Y H:i:s", strtotime($data['FechaApertura'])); ?></td>
                                    <td><?php echo date("d/m/Y H:i:s", strtotime($data['FechaCierre'])); ?></td>
                                    <td><?php echo $data['TotalVentas']; ?></td>
                                    <td><?php echo $data['MontoTotal']; ?></td>
                                    <td><?php if($data['Estado']==0){ echo 'Abierta'; } else {echo 'Cerrada'; } ?></td>
									<?php if ($_SESSION['rol'] == 1) { ?>
									<td>
										<form action="eliminar_cortes.php?id=<?php echo $data['Id']; ?>" method="post" class="confirmar d-inline">
											<button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
										</form>
									</td>
									<?php } ?>
									<td>
										<form action='rep_cortecaja.php' method='GET' >
										<input type='hidden' value='<?php echo $data['Id']; ?>' name='idcorte' id='idcorte'>
											<button class="btn btn-info" type="submit"><i class="fas fa-file-pdf"></i> </button>
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
<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Promociones</h1>
		<a href="registro_promocion.php" class="btn btn-primary">Nuevo</a>
		<!--<a href="reportes_productos.php" class="btn btn-primary">Reportes</a> -->
	</div>

	<!-- Elementos para crear el reporte -->
	<form action="reportePromociones.php" method="post">
	<div class="row">
	
		<div class="col-md-4">
			<label for="producto">Desde</label>
            <input type="date" name="desde" id="desde" class="form-control" value="<?php echo date("Y-m-d");?>">
		</div>
		<div class="col-md-4">
			<label for="producto">Hasta</label>
            <input type="date" name="hasta" id="hasta" class="form-control" value="<?php echo date("Y-m-d");?>">
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
							<th>Id</th>
							<th>Producto/Cubo</th>                         
							<th>Codigo</th>
                            <th>Porcentaje/Precio</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Termino</th>
                            <th>Estatus</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php
						require "../conexion.php";

                        $sql="SELECT * from promociones" ;
						$query = mysqli_query($conexion,$sql);
						mysqli_close($conexion);
						$cli = mysqli_num_rows($query);

						if ($cli > 0) {
							while ($dato = mysqli_fetch_array($query)) {
						?>
								<tr>
									<td><?php echo $dato['idpromocion']; ?></td>   
                                    <td><?php echo $dato['idclasificacion']; ?></td>   
                                    <td><?php echo $dato['ididentificador']; ?></td>
                                    <td><?php echo $dato['idtipo']; ?></td>                                 
                                    <td><?php echo $dato['promocion']; ?></td> 
                                    <td><?php echo date_format( date_create($dato['fechatermino']), 'd/m/Y');?></td>
                                    <td><?php echo date_format( date_create($dato['fechavencimiento']), 'd/m/Y');?></td>
                                    <td>
                                        <?php if( $dato['estado'] =='1')
                                    {
                                    echo '<span class="badge bg-success" style="color:white;">Activo</span>';
                                    }else
                                    {
                                        echo '   <span class="badge bg-danger" style="color:white;">Cancelado</span>';
                                    }
                                    ?>
                                  </td>
									<td>
                                   
                                <form action="eliminar_credito.php?id=<?php echo $dato['numcredito']; ?>" method="post" class="cancelar d-inline">
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
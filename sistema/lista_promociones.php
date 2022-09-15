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

                        $sql="SELECT * from promociones 
						left join cubos on 
						promociones.ididentificador=cubos.codcubo" ;
						$query = mysqli_query($conexion,$sql);
						mysqli_close($conexion);
						$cli = mysqli_num_rows($query);

						if ($cli > 0) {
							while ($dato = mysqli_fetch_array($query)) {
						?>
								<tr>
									<td><?php echo $dato['idpromocion']; ?></td>   
                                     
									<td>
                                        <?php if( $dato['idclasificacion'] =='1')
                                    {
                                    echo '<span>Cubo</span>';
                                    }else if( $dato['idclasificacion'] =='3')
                                    {
                                        echo '<span>Renta Cubo</span>';
                                    }
									else
                                    {
                                        echo '<span>Producto</span>';
                                    }
                                    ?>
                                  
                                    
									
									<td>
                                        <?php if( $dato['cubo'] !='')
                                    {
                                    echo '<span>'.$dato['cubo'].'</span>';
									
                                    }
									else
                                    {
                                        echo '<span>'.$dato['ididentificador'].'</span>';
                                    }
                                    ?>
                                  </td>

								  <td>
                                        <?php if( $dato['idtipo'] =='1')
                                    {
                                    echo '<span>'.$dato['promocion'].'%</span>';
                                    }
									else
                                    {
                                        echo '<span>'.$dato['promocion'].'</span>';
                                    }
                                    ?>
                                  </td>
                               
                                    
                                    <td><?php echo date_format( date_create($dato['fechainicio']), 'd/m/Y');?></td>
                                    <td><?php echo date_format( date_create($dato['fechatermino']), 'd/m/Y');?></td>
                                    <td>
                                        <?php if( $dato['fechatermino']<=date("Y-m-d"))
                                    {echo '   <span class="badge bg-danger" style="color:white;">Inactivo</span>';
                                  
                                    }else
                                    {
                                        
										echo '<span class="badge bg-success" style="color:white;">Activo</span>';
                                    }
                                    ?>
                                  </td>
									<td>
                                   
                                <form action="eliminar_promocion.php?id=<?php echo $dato['idpromocion']; ?>" method="post" class="confirmar d-inline">
											<button  title="Eliminar" class="btn btn-danger" type="submit"><i class="fa fa-trash"></i> </button>
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
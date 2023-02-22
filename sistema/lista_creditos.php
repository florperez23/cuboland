<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Lista de creditos</h1>
	</div>

	<!-- Elementos para crear el reporte -->
	<form action="reporteCreditos.php" method="post">
	<div class="row">
	
		<div class="col-md-4">
			<label for="producto">Desde</label>
            <input type="date" name="desde" id="desde" value='<?php echo $fecha ?>'  class="form-control">
		</div>
		<div class="col-md-4">
			<label for="producto">Hasta</label>
            <input type="date" name="hasta" id="hasta" value='<?php echo $fecha ?>'  class="form-control">
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
							<th>Fecha</th>
                            <th>Cliente</th>
							<th>Total</th>
                            <th>Adeudo</th>
                            <th>Fecha Vencimiento</th>
                            <th>Estatus</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php
						require "../conexion.php";

                        $sql="SELECT numcredito, creditos.fecha,totalventa as total,totalventa-(select SUM(totalfactura) from factura where numcredito=creditos.numcredito GROUP BY NUMCREDITO) AS  adeudo,fechavencimiento,estado,nombre 
                        FROM creditos inner join cliente on cliente.idcliente=creditos.idcliente 
                        WHERE creditos.estado!=2" ;
						$query = mysqli_query($conexion,$sql);
						mysqli_close($conexion);
						$cli = mysqli_num_rows($query);

						if ($cli > 0) {
							while ($dato = mysqli_fetch_array($query)) {
						?>
								<tr>
									<td><?php echo $dato['numcredito']; ?></td>                                   
						            <td><?php echo  date_format( date_create($dato['fecha']), 'd/m/Y  H:i:s'); ?></td>
                                    <td><?php echo $dato['nombre']; ?></td>
									<td><?php echo  '$'.number_format($dato['total'], 2, '.', ','); ?></td>
                                    <td><?php echo  '$'.number_format($dato['adeudo'], 2, '.', ',') ?></td>
                                    <td><?php echo date_format( date_create($dato['fechavencimiento']), 'd/m/Y');?></td>
                                    <td>
                                        <?php if( $dato['estado'] =='1')
                                    {
                                    echo '<span class="badge bg-success" style="color:white;">Activo</span>';
                                    }else if  ( $dato['estado'] =='0')
                                    {
                                     echo '   <span class="badge bg-danger" style="color:white;">Liquidado</span>';
                                    }else
                                    {
                                        echo '   <span class="badge bg-danger" style="color:white;">Cancelado</span>';
                                    }
                                    ?>
                                  </td>
									<td>
                                    <button title="Abrir" id="abrirAbonos" name="abrirAbonos" type="button" id="abrir" class="btn btn-primary" data-toggle="modal" data-target="#mostrarCredito">
                                    <i class="fa fa-arrow-right" ></i>
                                </button>
                                <form action="eliminar_credito.php?id=<?php echo $dato['numcredito']; ?>" method="post" class="cancelar d-inline">
											<button  title="Cancelar" class="btn btn-danger" type="submit"><i class="fa fa-ban"></i> </button>
										</form>
        
                                <!-- Modal -->
                                <div class="modal fade" id="mostrarCredito" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Abonos </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">X</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    <div class="row">                                  
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered" id="table">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>Id</th>
                                                            <th>Fecha</th>
                                                            <th>Cliente</th>
                                                            <th>Total</th>
                                                            <th>Pago</th>
                                                            <th>Adeudo</th>
                                                          
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        require "../conexion.php";
                                                        $sql=" SELECT numcredito, creditos.fecha,totalventa as total, (select totalfactura from factura where numcredito=creditos.numcredito GROUP BY numcredito) as pago,
                                                        totalventa-(select SUM(totalfactura) from factura where numcredito=creditos.numcredito GROUP BY numcredito) AS  adeudo, 
                                                         fechavencimiento,estado,nombre   FROM creditos inner join cliente on cliente.idcliente=creditos.idcliente 
                                                        WHERE creditos.estado=1 and  creditos.numcredito = '". $dato['numcredito']."'" ;

                                                        //echo $sql;
                                                        $query1 = mysqli_query($conexion, $sql);
                                                        mysqli_close($conexion);
                                                        $cli1 = mysqli_num_rows($query1);
                                                        $cont=0;
                                                        if ($cli1 > 0) {
                                                            $cont=$cont+1;
                                                            while ($dato1 = mysqli_fetch_array($query1)) {
                                                        ?>
                                                                <tr>
                                                                    <td><?php echo $cont; ?></td>
                                                                
                                                                    <td><?php echo  date_format( date_create($dato1['fecha']), 'd/m/Y  H:i:s'); ?></td>
                                                                    <td><?php echo $dato1['nombre']; ?></td>
                                                                    <td><?php echo '$'.number_format($dato1['total'], 2, '.', ','); ?></td>
                                                                    <td><?php echo '$'.number_format($dato1['pago'], 2, '.', ','); ?></td>
                                                                    <td><?php echo '$'.number_format($dato1['adeudo'], 2, '.', ','); ?></td>
                                                                    <td></td>
                                                                </tr>
                                                                
                                                        <?php }
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                        
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        
                                    </div>
                                    </div>
                                </div>
                                </div>
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
<?php
    include "../conexion.php";
    session_start();
    $tipo = $_POST['tipo'];

    $ultimo = date("Y-m-t", strtotime($fecha));
    $primero = date('Y-m-01');

    $desde = date("Y-m-d",strtotime($primero));//."- 1 day"
    $hasta =  date("Y-m-d",strtotime($ultimo));//."+ 1 day"
  
?>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/popper.js/umd/popper.min.js"> </script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
<script src="vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="js/Chart.bundle.min.js"></script>
<script src="js/front.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/sweetalert2@10.js"></script>
<script type="text/javascript" src="js/producto.js"></script>
<script type="text/javascript" src="js/imprimircodigo.js"></script>
<script type="text/javascript" src="js/all.min.js"></script>
<script type="text/javascript">

  
  $(document).ready(function() {
    $('#table').DataTable({
      language: {
        "decimal": "",
        "emptyTable": "No hay datos",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty": "Mostrando 0 a 0 de 0 registros",
        "infoFiltered": "(Filtro de _MAX_ total registros)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ registros",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "No se encontraron coincidencias",
        "paginate": {
          "first": "Primero",
          "last": "Ultimo",
          "next": "Siguiente",
          "previous": "Anterior"
        },
        "aria": {
          "sortAscending": ": Activar orden de columna ascendente",
          "sortDescending": ": Activar orden de columna desendente"
        }
      }
    });
    var usuarioid = '<?php echo $_SESSION['idUser']; ?>';
    searchForDetalle(usuarioid);
  });
</script>

<!-- Begin Page Content -->
<div class="container-fluid">
 <!-- Content Row -->
   
        <!-- Elementos para crear el reporte -->
        <form action="reporteRentas.php" method="post">
        <div class="row">

            <div class="col-md-4" >
			    <div class="form-group">
			    <label>FILTRO</label>
				<select id="tipo" name="tipo" onchange="filtro()" class="form-control">

                    <?php if($tipo == 0){ ?>
                        <option value="0" selected>SIN ESPECIFICAR</option>
                        <option value="1">PAGADAS</option>
                        <option value="2">NO PAGADAS</option>


                    <?php }else if($tipo == 1){ ?>
                        <option value="0">SIN ESPECIFICAR</option>
                        <option value="1" selected>PAGADAS</option>
                        <option value="2">NO PAGADAS</option>


                    <?php }else if($tipo == 2){ ?>
                        <option value="0">SIN ESPECIFICAR</option>
                        <option value="1">PAGADAS</option>
                        <option value="2" selected>NO PAGADAS</option>
                    
                    <?php }  ?>
				</select>
                </div>
			</div>

            <div class="col-md-4">
                <input type="submit" value="Generar Reporte" class="btn btn-primary">
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
							<th>Codcubo</th>
							<th>Cubo</th>
							<th>Arrendatario</th>
							<th>Fecha Contrato</th>
							<th>Fecha último pago</th>
                            <th>Fecha próximo pago</th>
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
					
                    if($tipo == 0){
                        $sql = 'select r.*, a.nombre, c.cubo from rentas r
                        inner join arrendatarios a on a.idarrendatario = r.idarrendatario
                        inner join cubos c on c.codcubo = r.idcubo
                        where r.cancelado = 0 ';
                    }else if($tipo == 1){
                        $sql = 'SELECT
                        r.*,
                        a.nombre,
                        c.cubo, f.totalfactura
                    FROM
                        rentas r
                        LEFT JOIN arrendatarios a ON a.idarrendatario = r.idarrendatario
                        LEFT JOIN cubos c ON c.codcubo = r.idcubo 
                        LEFT JOIN factura f on f.observaciones = r.idcubo  and date(f.fecha) = r.fechaultimopago
                    WHERE
                        r.cancelado = 0 
                        AND r.fechaultimopago BETWEEN  "'.$desde.'" and "'.$hasta.'"' ;
                    }else if($tipo == 2){
                        $sql = 'select r.*, a.nombre, c.cubo
                        from rentas r
                        inner join arrendatarios a on a.idarrendatario = r.idarrendatario
                        inner join cubos c on c.codcubo = r.idcubo
                     
                        where r.cancelado = 0 and (r.fechaultimopago <= "'.$desde.'" or r.fechaultimopago is null)' ;
                    }
                    

						$query = mysqli_query($conexion, $sql);
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['idcubo']; ?></td>
									<td><?php echo $data['cubo']; ?></td>
									<td><?php echo $data['nombre']; ?></td>
									<td><?php echo date("d/m/Y", strtotime($data['fechacontrato'])); ?></td>
									<td><?php echo date("d/m/Y", strtotime($data['fechaultimopago'])); ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($data['fechaproximopago'])); ?></td>
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

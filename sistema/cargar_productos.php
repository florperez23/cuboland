<?php
    include "../conexion.php";
    session_start();
    $codcubo = $_POST['codcubo'];
  
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
    <div class="row">
        <!-- Elementos para crear el reporte -->
        <form action="reporteProductos.php" method="post">
        <div class="row">
		<div class="col" style='width: 500px;'>
			<div class="form-group">
			<label style='color:#000'>Reporte por Cubos</label>
			<?php
			$query = mysqli_query($conexion, "SELECT * FROM cubos ORDER BY codcubo ASC");
			$res = mysqli_num_rows($query);
			
			?>

				<select id="cubor" name="cubor" onchange="cambiartabla()" class="form-control">
				<option value="0">TODOS</option>
				<?php
				if ($res > 0) {
					while ($d = mysqli_fetch_array($query)) {	
                        if($codcubo == $d['codcubo']){
                ?>
                            <option value="<?php echo $d['codcubo']; ?>" selected><?php echo $d['cubo']; ?></option>
                <?php
                        }else{
				?>
					
					<option value="<?php echo $d['codcubo']; ?>"><?php echo $d['cubo']; ?></option>
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
							<th>CUBO</th>
							<th>CÓDIGO</th>
							<th style="width:250px;">DESCRIPCIÓN</th>
							<th>PRECIO</th>
							
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
					
                            if($codcubo == 0){
                                $sql = "SELECT p.*, c.cubo
                                FROM producto p
                                INNER JOIN cubos c ON c.codcubo = p.codcubo";
                            }else{
                                $sql = "SELECT p.*, c.cubo
                                FROM producto p
                                INNER JOIN cubos c ON c.codcubo = p.codcubo WHERE p.codcubo =  $codcubo";
                            }

						$query = mysqli_query($conexion, $sql);
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['cubo']; ?></td>
									<td><input type='hidden' name='codigob<?php echo $data['codproducto']; ?>' id='codigob<?php echo $data['codproducto']; ?>' value='<?php echo $data['codproducto']; ?>'><?php echo $data['codproducto']; ?></td>
									<td><?php echo $data['descripcion']; ?></td>
									
									<td><?php echo $data['precio']; ?></td>
									
									<td><button type="button" id="generar_barcode" onclick="cb('<?php echo $data['codproducto']; ?>');" class="btn btn-info" ><i class="fas fa-solid fa-barcode"></i></button>
										<?php if ($_SESSION['rol'] == 1) { ?>
									
										 <!-- <a href="agregar_producto.php?id=<?php //echo $data['codproducto']; ?>" class="btn btn-primary"><i class='fas fa-audio-description'></i></a>-->

										<a href="editar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

										<form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
											<button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
										</form>
									
										<?php } ?>
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

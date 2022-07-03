
<?php include_once "includes/header.php"; 



//GUARDAR ARCHIVOS
if(isset($_FILES['archivo']['name'])){
	$directorio = 'archivos/';
	
	$doc = $_FILES["archivo"]["name"];
    $tmp =$_FILES["archivo"]["tmp_name"];
	$numarchivo = narchivo(TRUE); 
	$nomarchivo = $numarchivo.'_'.$doc;
	$subir_archivo = $directorio.basename($nomarchivo);
	$proveedor = $_POST['proveedor'];
	$subtotal = $_POST['subtotal'];
	$iva = $_POST['iva'];
	$total = $_POST['total'];
	$fecha = $_POST['fecha'];
	$descripcion = $_POST['descripcion'];
	$usuario_id = $_SESSION['idUser'];
	if (move_uploaded_file($tmp, $subir_archivo)) {
		//si se guarda en la carpeta hay que guardar en la bd
		$numarchivo = narchivo(FALSE); 
		$sql = "INSERT INTO gastos(archivo,fecha,activo,narchivo, proveedor, subtotal, iva, total, descripcion, idusuariosube) values ('$doc', '$fecha', '1','$numarchivo', '$proveedor','$subtotal', '$iva', '$total', '".$descripcion."', '".$usuario_id."')";
		//echo $sql;	
		$query_insert = mysqli_query($conexion, $sql);
        if ($query_insert) {
			historia('Se registro un nuevo gasto '.$numarchivo.'_'.$doc);
			mensajeicono('Se ha registrado con éxito el nuevo gasto!', 'lista_factura.php','','exito');
			
        } else {
			historia('Error al intentar ingresar un nuevo gasto '.$numarchivo.'_'.$doc);
			mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_factura.php','','error');
        }
    }
		//echo "El archivo es válido y se cargó correctamente.<br><br>";
		//echo"<a href='".$subir_archivo."' target='_blank'><img src='".$subir_archivo."' width='150'></a>";
	 else {
		/*$alert = '<div class="alert alert-danger" role="alert">
		La subida ha fallado
	 </div>';*/
	 historia('Error al intentar subir el archivo del nuevo gasto '.$numarchivo.'_'.$doc);
	 mensajeicono('Hubo un error en la subida del archivo, favor de intentarlo de nuevo.', 'lista_factura.php','','error');
		
	}
		
	//echo $alert;
}


?>




<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Facturas</h1>
		<a href="registro_factura.php" class="btn btn-primary">Nuevo</a>
	</div>

	<!-- Elementos para crear el reporte -->
	<form action="reporteGastos.php" method="post">
	<div class="row">
	
		<div class="col-md-4">
			<label for="producto">Desde</label>
            <input type="date" name="desde" id="desde" class="form-control">
		</div>
		<div class="col-md-4">
			<label for="producto">Hasta</label>
            <input type="date" name="hasta" id="hasta" class="form-control">
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
							<th>ID</th>
							<th>PROVEEDOR</th>
							<th>TOTAL</th>
							<th>DESCRIPCIÓN</th>
							<th>FACTURA (ARCHIVO)</th>
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT a.id, a.narchivo, a.archivo, a.proveedor, a.subtotal, a.iva, a.total, a.descripcion, pr.proveedor as nomproveedor
						FROM gastos a
						left join proveedor pr on pr.codproveedor = a.proveedor");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['id']; ?></td>
									<td style='width:200px;'><?php echo $data['nomproveedor']; ?></td>
									<td style='width:150px;'><?php echo '$ '.$data['total']; ?></td>
									<td><?php echo $data['descripcion']; ?></td>
									<td><a href='descargar.php?nombre=<?php echo $data['narchivo'].'_'.$data['archivo']; ?>' target='_self' title='Haga click aqui para descargar'><?php echo $data['archivo']; ?></a></td>
									<?php if ($_SESSION['rol'] == 1) { ?>
									<td>
										<form action="eliminar_gastos.php?id=<?php echo $data['id']; ?>" method="post" class="confirmar d-inline">
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

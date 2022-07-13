<?php include_once "includes/header.php"; ?>

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
			<label style='color:#000'>Reporte por Cubos</label>
			<?php
			$query = mysqli_query($conexion, "SELECT * FROM cubos ORDER BY codcubo ASC");
			$res = mysqli_num_rows($query);
			
			?>

				<select id="cubor" name="cubor"  class="form-control">
				<option value="0">TODOS</option>
				<?php
				if ($res > 0) {
					while ($d = mysqli_fetch_array($query)) {	
				?>
					
					<option value="<?php echo $d['codcubo']; ?>"><?php echo $d['cubo']; ?></option>
				<?php
						
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
							<th>CODIGO</th>
							<th>DESCRIPCION</th>
							<th>PRECIO</th>
							
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
<!-- /.container-fluid -->
<script>
    function cb(cod) {
        var data = $("#codigob"+cod).val();

        $.post( "guardarImagen.php", { filepath: "codigosGenerados/"+data+".png", text:data }  )
            .done(function( respuesta ) {
				location.href = "codigoimprimir.php?codigo="+data;

                /*Swal.fire({
                    icon: 'success',
                    title: 'Hecho!',
                    text: 'Se ha registrado con éxito el código!',
                    footer: ''
                })*/
            }
        );

		
       // $("#imagen"+data).html('<img src="barcode\\barcode.php?text='+data+'&size=60&codetype=Code39&print=true"/><a href="codigoimprimir.php?codigo='+data+'" class="btn btn-success"><i class=" fas fa-solid fa-print"></i></a>');
      
    }

    

  </script>

<?php include_once "includes/footer.php"; ?>
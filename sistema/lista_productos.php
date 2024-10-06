<?php include_once "includes/header.php"; 

?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Productos</h1>
		<a href="registro_producto.php" class="btn btn-primary">Nuevo</a>
		<!--<a href="reportes_productos.php" class="btn btn-primary">Reportes</a> -->
	</div>

<div id="respuesta">
	
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
			
			$query = mysqli_query($conexion, "SELECT * FROM cubos ORDER BY SUBSTR(nomenclatura, 1, 1), CAST(SUBSTR(nomenclatura, 2, LENGTH(nomenclatura)) AS UNSIGNED)");
			$res = mysqli_num_rows($query);
			
			?>

				<select id="cubor" name="cubor" onchange="cambiartabla()" class="form-control">
				<option value="0">TODOS</option>
				<?php
				if ($res > 0) {
					while ($d = mysqli_fetch_array($query)) {	
				?>
					
					<option value="<?php echo $d['codcubo']; ?>"><?php echo $d['nomenclatura'].'-'.$d['cubo']; ?></option>
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
<!-- /.container-fluid -->
<script>
    function cb(cod) {
        var data = $("#codigob"+cod).val();
		console.log(data);
        $.post( "guardarImagen.php", { filepath: "codigosGenerados/"+data+".png", text:data }  )
            .done(function( respuesta ) {
				console.log(respuesta);
				location.href = "codigoimprimir1.php?codigo="+data;

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


	function cambiartabla(){
		var codcubo = $('#cubor').val();

		$.ajax({
			url: "cargar_productos.php",
			type: "post",
			data: {codcubo: codcubo},
			success: function(data){
				$('#respuesta').html(data+"\n");
			}
		});
	}
    


  </script>

<?php include_once "includes/footer.php"; ?>
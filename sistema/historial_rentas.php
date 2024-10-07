<?php include_once "includes/header.php"; 

$anio = '';
if (isset($_POST['ano'])) { // <= true
	$anio = $_POST['ano'];
}else{
	$anio = date("Y");
}
?>



<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Historial de rentas</h1>
	</div>

	<div id="respuesta">
	
	<!-- Elementos para crear el reporte -->
	<form action="historial_rentas.php" method="post">
	<div class="row">
		<div class="col-md-4" >
			<div class="form-group">
			<label>AÑO</label>
		
				<?php
					echo "<select name='ano' id='ano' class='form-control'>";
					$anioabajo = date("Y")-5;
						for($i=date("Y");$i>=$anioabajo;$i--)
						{
							echo "<option value='".$i."'>".$i."</option>";
						}
					echo "</select>";
					?>
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
							<th>Mes</th>
							<th>No. Cubos Rentdos</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";
						$sql = "SELECT 'ENERO' as mes, count(*) as num, sum(precio) as total FROM historial_rentas WHERE YEAR(fecha_renta) = '2024' and month(fecha_renta) = 1
						UNION
						SELECT 'FEBRERO' as mes, count(*) as num, sum(precio) as total  FROM historial_rentas WHERE YEAR(fecha_renta) = '$anio' and month(fecha_renta) = 2
						UNION
						SELECT 'MARZO' as mes, count(*) as num, sum(precio) as total FROM historial_rentas WHERE YEAR(fecha_renta) = '$anio' and month(fecha_renta) = 3
						UNION
						SELECT 'ABRIL' as mes, count(*) as num, sum(precio) as total FROM historial_rentas WHERE YEAR(fecha_renta) = '$anio' and month(fecha_renta) = 4
						UNION
						SELECT 'MAYO' as mes, count(*) as num, sum(precio) as total  FROM historial_rentas WHERE YEAR(fecha_renta) = '$anio' and month(fecha_renta) = 5
						UNION
						SELECT 'JUNIO' as mes, count(*) as num, sum(precio) as total FROM historial_rentas WHERE YEAR(fecha_renta) = '$anio' and month(fecha_renta) = 6
						UNION
						SELECT 'JULIO' as mes, count(*) as num, sum(precio) as total  FROM historial_rentas WHERE YEAR(fecha_renta) = '$anio' and month(fecha_renta) = 7
						UNION
						SELECT 'AGOSTO' as mes, count(*) as num, sum(precio) as total FROM historial_rentas WHERE YEAR(fecha_renta) = '$anio' and month(fecha_renta) = 8
						UNION
						SELECT 'SEPTIEMBRE' as mes, count(*) as num, sum(precio) as total  FROM historial_rentas WHERE YEAR(fecha_renta) = '$anio' and month(fecha_renta) = 9
						UNION
						SELECT 'OCTUBRE' as mes, count(*) as num, sum(precio) as total  FROM historial_rentas WHERE YEAR(fecha_renta) = '$anio' and month(fecha_renta) = 10
						UNION
						SELECT 'NOVIEMBRE' as mes, count(*) as num, sum(precio) as total  FROM historial_rentas WHERE YEAR(fecha_renta) = '$anio' and month(fecha_renta) = 11
						UNION
						SELECT 'DICIEMBRE' as mes, count(*) as num, sum(precio) as total  FROM historial_rentas WHERE YEAR(fecha_renta) = '$anio' and month(fecha_renta) = 12 ";

						$query = mysqli_query($conexion, $sql);
						//echo $sql;

						
						$myarr = '';
						if (!empty($query) AND  mysqli_num_rows($query) > 0) { 
							
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['mes']; ?></td>
									<td><?php echo $data['num']; ?></td>
									<td><?php echo $data['total']; ?></td>
								</tr>
								
						<?php 

								if ($data['total'] == "") {
									$myarr = $myarr.'0,';
								}else{
									$myarr = $myarr.$data['total'].',';
								}
								
							}
						} 
						$myarr = substr($myarr, 0, -1);
						
						?>
						
					</tbody>

				</table>
				
			</div>
		</div>
		
	</div>

	</div>
	<canvas id="grafica"></canvas>
	

	</div>

</div>
<!-- /.container-fluid -->
<script>


/*var arr = $("#totales").val();*/

const labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']

const graph = document.querySelector("#grafica");

const data = {
    labels: labels,
    datasets: [{
        label:"Historial de Rentas",
        data: [<?php echo $myarr ?>],
        backgroundColor: 'rgba(9, 129, 176, 0.2)'
    }]
};

const config = {
    type: 'bar',
    data: data,
};

new Chart(graph, config);

</script>

<?php include_once "includes/footer.php"; ?>
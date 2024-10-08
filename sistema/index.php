<?php include_once "includes/header.php"; ?>

<div class="container-fluid">
	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-white">Panel de Administración</h1>
	</div>

	<!-- Content Row -->
	<div class="row">

	
		<!-- Earnings (Monthly) Card Example -->
		<a class="col-xl-3 col-md-6 mb-4" href="lista_rentas.php">
			<div class="card border-left-primary shadow h-100 py-2 bg-white">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Rentas</div>
							<!--text-gray-800-->
							<div class="h5 mb-0 font-weight-bold">Rentas</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-user fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</a>
	
		<!-- Earnings (Monthly) Card Example -->
		<a class="col-xl-3 col-md-6 mb-4" href="lista_creditos.php">
			<div class="card border-left-success shadow h-100 py-2 bg-white">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Separos</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">Apartados</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-users fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</a>

		<!-- Earnings (Monthly) Card Example -->
		<a class="col-xl-3 col-md-6 mb-4" href="lista_productos.php">
			<div class="card border-left-info shadow h-100 py-2 bg-white">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Productos</div>
							<div class="row no-gutters align-items-center">
								<div class="col-auto">
									<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">Productos</div>
								</div>
								<div class="col">
									<div class="progress progress-sm mr-2">
										<div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</a>

		<!-- Pending Requests Card Example -->
		<a class="col-xl-3 col-md-6 mb-4" href="ventas.php">
			<div class="card border-left-warning bg-white shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Ventas</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">Ventas</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</a>

		<a class="col-xl-3 col-md-6 mb-4" href="nueva_venta.php">
			<div class="card border-left-warning bg-white shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Caja</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">Caja</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-money-bill-alt fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</a>

		<a class="col-xl-3 col-md-6 mb-4" href="lista_cortes.php">
			<div class="card border-left-warning bg-white shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-titulo text-uppercase mb-1">Corte por cubo</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">Cortes mensuales</div>
						</div>
						<div class="col-auto">
							
							<i class="fas fa-solid fa-cash-register fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</a>

		<a class="col-xl-3 col-md-6 mb-4" href="lista_cubos.php">
			<div class="card border-left-warning bg-white shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Cubos</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">Lista de cubos</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-male fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</a>

		<a class="col-xl-3 col-md-6 mb-4" href="lista_factura.php">
			<div class="card border-left-warning bg-white shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-nvo text-uppercase mb-1">Egresos</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">Salidas de dinero</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-solid fa-file-invoice fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</a>






	 	<div class="col-lg-6">
			<div class="au-card m-b-30">
				<div class="au-card-inner">
					<h3 class="title-2 m-b-40">Separos por vencer</h3>
					<!-- <canvas id="sales-chart"></canvas>-->
					<div id="sales-chart" name="sales-chart" style="width:100%; height:280px; overflow: scroll;"></div>

				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="au-card m-b-30">
				<div class="au-card-inner">
					<h3 class="title-2 m-b-40">Rentas no pagadas</h3>
					<div id="polarChart" name="polarChart" style="width:100%; height:280px; overflow: scroll;"></div>
				</div>
			</div>
		</div> 
	</div>


</div>


<?php include_once "includes/footer.php"; ?>
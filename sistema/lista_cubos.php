<?php include_once "includes/header.php"; ?>
<script>

function calcularRenta(codcubo){
	var fecha = document.getElementById("FechaPago"+codcubo).value;
	var renta = document.getElementById("renta"+codcubo).value;
	var mes = fecha.getMonth();
	var anio =  fecha.getFullYear();
	var dia = fecha.getDate();
	var diasMes  = diasEnUnMes(mes, anio); 
	var disponible = document.getElementById("disponible"+codcubo).value;
	 console.log(fecha);


	if (disponible = 0 ){
		 var precioxDia=( renta / diasMes);
															
		var totalrenta=(diasMes-dia)*precioxDia;

		if(dia==diasMes )
		{
			totalrenta=renta
		}

	}

}


function diasEnUnMes(mes, año) {
	return new Date(año, mes, 0).getDate();
}


</script>
<?php
$DiasMes=0;
$dia=0;
$precioxDia=0;
$totalrenta=0;
$texto=''; 
$DiasMes= date('t'); 
$dia = date('d', strtotime($fecha));//obtenemos el dia actual
$precioxDia=( (float)255 / (float)$DiasMes);
?>
<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Informacion de los Cubos</h1>
		<a href="registro_cubo.php" class="btn btn-primary">Nuevo</a>
	</div>

	<!-- Elementos para crear el reporte -->
	<form action="reporteCubos.php" method="post">
	<div class="row">
	
	<div class="col" style='width: 500px;'>
			<div class="form-group">
			<label style='color:#000'>Estatus del cubo</label>
				<select id="estatus" name="estatus"  class="form-control">
					<option value="5">SIN ESPECIFICAR</option>
					<option value="0">DISPONIBLES</option>
					<option value="1">OCUPADOS</option>
				</select>
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
				<table class="table table-striped table-bordered" id="table" style="aria-sort:disabled">
					<thead class="thead-dark">
						<tr>
							<th style='display:none;'>algo</th>
							<th>NO. CUBO</th>
							<th style="width:100px;">NOMBRE</th>
							
							<th>PRECIO RENTA</th>
							<th style="width:200px;">RENTERO</th>
							<th>DISPONIBLE</th>
							
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						 $query = mysqli_query($conexion, "SELECT c.codcubo, c.nomenclatura, c.cubo, c.renta, c.disponible, r.idarrendatario, a.nombre FROM
						 cubos c left join rentas r on r.idcubo = c.codcubo
						 left join arrendatarios a on a.idarrendatario = r.idarrendatario WHERE  r.cancelado=0 
						 ORDER BY SUBSTR(nomenclatura, 1, 1), CAST(SUBSTR(nomenclatura, 2, LENGTH(nomenclatura)) AS UNSIGNED) ");
						//$sql = "select codcubo, nomenclatura,cubo,renta, disponible,
						//(SELECT arrendatarios.idarrendatario FROM rentas INNER JOIN arrendatarios ON rentas.idarrendatario = arrendatarios.idarrendatario WHERE rentas.cancelado=0 and codcubo=rentas.idcubo) as idarrendatario,
						//(SELECT arrendatarios.nombre FROM rentas INNER JOIN arrendatarios ON rentas.idarrendatario = arrendatarios.idarrendatario WHERE rentas.cancelado=0 and codcubo=rentas.idcubo) as nombre from cubos ORDER BY codcubo ASC";
						
						
						
						
						//echo $sql;
						//$query = mysqli_query($conexion, $sql);

						
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td style='display:none;'><?php echo $data['codcubo']; ?></td>
									<td><?php echo $data['nomenclatura']; ?></td>
									<td><?php echo $data['cubo']; ?></td>
									<td><?php echo $data['renta']; ?></td>
									<td style="width:100px;"><?php echo $data['nombre']; ?></td>
									
									<td><?php 
									if($data['disponible'] == 0){
										echo '<span class="badge bg-success" style="color:white;">Disponible</span>';
									}else{
										echo '<span class="badge bg-danger" style="color:white;">Ocupado</span>';
									}
									
									
									?></td>
									
									<?php if ($_SESSION['rol'] == 1) { ?>
									<td>
										<a href="editar_cubo.php?id=<?php echo $data['codcubo']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
										<form action="eliminar_cubo.php?id=<?php echo $data['codcubo']; ?>" method="post" class="confirmar d-inline">
											<button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
										</form>
										<?php		
										if($data['disponible'] == 0){
												?>
													<a href="#" class="btn btn-primary"  title="Rentar Cubo" class="btn btn-primary"><i class="fa fa-check"  data-toggle="modal" data-target="#exampleModal<?php echo $data['codcubo']; ?>"></i> </a>
											<?php
											}else{
											?>        
												<a href="#" class="btn btn-secondary"  title="Pagar Renta" class="btn btn-primary"><i class="fa fa-credit-card"  data-toggle="modal" data-target="#exampleModal<?php echo $data['codcubo']; ?>"></i> </a>
										<?php
											}
											?> 
									<div class="modal fade" id="exampleModal<?php echo $data['codcubo']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel<?php echo $data['codcubo']; ?>" aria-hidden="true">
									<div class="modal-dialog" role="document">
									<div class="modal-content">
									<form action="renta_cubo.php?codcubo=<?php echo $data['codcubo']; ?>"  method="post" enctype="multipart/form-data">
										<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel<?php echo $data['codcubo']; ?>">Pago de Renta <?php echo $data['cubo']; ?></h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
										</div>
										<div class="modal-body">      
										<div>
									<div class="card_div">
										<div class="card-body"> 
										<label>Arrendatario</label>
										<?php
										
										if ($data['disponible'] == 0) {											
												?>
											<select id="idarrendatario<?php echo $data['codcubo']; ?>" name="idarrendatario<?php echo $data['codcubo']; ?>" class="form-control" required  >
											<?php
											}else{
											?>    
											<select id="idarrendatario<?php echo $data['codcubo']; ?>" name="idarrendatario<?php echo $data['codcubo']; ?>" class="form-control" required  readonly>
										<?php
											}
								
								
										?>
										
										<option value="">Seleccione</option>    
										<?php
										$query1 = mysqli_query($conexion, "SELECT * FROM arrendatarios ");
										$res = mysqli_num_rows($query);
									
										?>
										<?php
										if ($res > 0) {
											while ($f = mysqli_fetch_array($query1)) {												
											if($f['idarrendatario'] == $data['idarrendatario']){
												?>
												<option value="<?php echo $f['idarrendatario']; ?>" selected><?php echo $f['nombre']; ?></option>
											<?php
											}else{
											?>    
											<option value="<?php echo $f['idarrendatario']; ?>"><?php echo $f['nombre']; ?></option>
										<?php
											}
											}
										}
										?>
									</select>
									<input id="renta<?php echo $data['codcubo']; ?>"  name="renta<?php echo $data['codcubo']; ?>" class="form-control" type="hidden"   value="<?php echo $data['renta']; ?>"  >
										<input id="disponible<?php echo $data['codcubo']; ?>"  name="disponible<?php echo $data['codcubo']; ?>" class="form-control" type="hidden"   value="<?php echo $data['disponible']; ?>"  >
											<div class="row">              
												<div class="col-md-6">
													<div class="form-group">
														<label for="tipoven">Tipo de Venta</label>
														<select id="tipoven<?php echo $data['codcubo']; ?>" class="form-control" name="tipoven<?php echo $data['codcubo']; ?>" required="">
															<option value="1">Contado</option>
														<!-- /*	<option value="2">Credito</option>                              -->
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="tipopago">Tipo de Pago</label>
														<select id="tipopago<?php echo $data['codcubo']; ?>" class="form-control" name="tipopago<?php echo $data['codcubo']; ?>" onchange="tipopagocubo(<?php echo $data['codcubo']; ?>);" required="">
															<option value="1">Efectivo</option>
															<option value="2">Tarjeta</option>  
															<option value="3">Transferencia</option>    
															<option value="4">Deposito</option>       
															<option value="5">Pago Mixto</option>                             
														</select>
													</div>
												</div> 
											</div>
												<div class="row" id="ventacontado">
													<div class="col-md-4">
														<div class="form-group">
														<?php
														$fechaUltimoPago=obtenerFechaUltimoPago($data['codcubo']);
															
													    if ($data['disponible'] == 0 or $fechaUltimoPago==NULL OR $fechaUltimoPago=='0000-00-00')
														{
														//echo ' es nuevo';
															$DiasMes= date('t'); 
															$dia = date('d', strtotime($fecha));//obtenemos el dia actual
															$precioxDia=( $data['renta'] / $DiasMes);
															
															$totalrenta=round(number_format(($DiasMes-$dia)*$precioxDia, 2, '.', ',') ,0);
															//echo $totalrenta;

															//$dia=11;
															//or $dia<10
															if($dia==$DiasMes )
															{
																$totalrenta=$data['renta'];
															}
															if(ValidarExiteUnaPromocion($data['codcubo'])=='Existe')
															{
																$totalrenta=PrecioPromocionRenta($data['codcubo']);
																$texto='Renta normal $'. $data['renta'].', precio promoción  $'.$totalrenta; 
															}else
															{
																$texto=''; 
															}

														}else
														{ 			
														//echo 'No es nuevo';												
															$fecha=date("Y-m-d");													
															$mesactual = date("m", strtotime($fecha));
															$mesultimopago= date("m", strtotime($fechaUltimoPago));
															
															$diaultimopago= date("d", strtotime($fechaUltimoPago));
															//$mesesretrazo=(int)$mesactual-(int)$mesultimopago;
															//	$mesesretrazo=$mesesretrazo*1;


															$datetime1=new DateTime($fecha);
															$datetime2=new DateTime($fechaUltimoPago);
															 
															# obtenemos la diferencia entre las dos fechas
															$interval=$datetime2->diff($datetime1);
															 
															# obtenemos la diferencia en meses
															$mesesretrazo=$interval->format("%m");

															//echo $mesactual."<br>";
															//echo $mesultimopago."<br>";
															//echo $dia."<br>";
															//echo $diaultimopago."<br>";
															//echo $mesesretrazo."<br>";
															//$dia=11;
															//and $dia>$diaultimopago
															if( $mesactual!=$mesultimopago and( $mesesretrazo>=1 ))
															{ //echo 'entro1';
																if($dia>10 or ( $mesesretrazo>1 ))
																{       
																//echo 'entro2'; 
																	$totalrenta=((float)$data['renta']+50)*$mesesretrazo;
																	if($mesesretrazo>1){
																		$texto='Tiene '.$mesesretrazo.' de retrazo!!<br>La renta es de $'. $data['renta'].' + $50.00 por cada mes de retrazo.'; 
																	}else
																	{//echo 'entro3';
																		$texto='La renta es de $'. $data['renta'].' + $50.00 por retrazo.'; 
																	}
																}
																else{	
																//echo 'entro4';															
																	$totalrenta=$data['renta'];
																	$texto=''; 
																}
															
															}
															else{	
																///echo 'No tiene retrazo';															
																	$totalrenta=$data['renta'];
																	$texto=''; 
																}

												
															if($mesactual==$mesultimopago and ($fechaUltimoPago!=NULL OR $fechaUltimoPago!='0000-00-00'))
															{
																$totalrenta=0;
																$texto='La renta se encuentrá al dia.'; 

															}
															
														
													    }
														
														 ?>



														    
															<label for="totalmodal" class="font-weight-bold">Renta</label>
															<input id="totalFIJO<?php echo $data['codcubo']; ?>"  name="totalFIJO<?php echo $data['codcubo']; ?>" class="form-control" type="hidden" placeholder="totalFIJO"  value="$<?php echo $totalrenta ?>"  readonly >
															<input id="totalmodal<?php echo $data['codcubo']; ?>"  name="totalmodal<?php echo $data['codcubo']; ?>" class="form-control" type="text" placeholder="Total"  value="$<?php echo $totalrenta ?>"  readonly >
														
														</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label for="pagar_con"  class="font-weight-bold">Pagar</label>
																<input required id="pagar_con<?php echo $data['codcubo']; ?>" name="pagar_con<?php echo $data['codcubo']; ?>" class="form-control"  type="text" placeholder="0.00"  onkeyup="pagarcon(<?php echo $data['codcubo']; ?>);" onchange="MASK(this,this.value,'$##,###,##0.00',1);">
															</div>
														</div>
														<div class="col-md-4" id="divCambio<?php echo $data['codcubo']; ?>" >
															<div class="form-group">
																<label for="cambio" class="font-weight-bold">Cambio</label>  
																<input id="cambio<?php echo $data['codcubo']; ?>" class="form-control" type="text" placeholder="Cambio" value="0.00" readonly onchange="MASK(this,this.value,'$##,###,##0.00',1);">
															</div>
														</div>
														<div class="col-md-4" id="divAdelantar<?php echo $data['codcubo']; ?>" >
															
																<label for="Adelantar<?php echo $data['codcubo']; ?>" class="font-weight-bold">Adelantar Meses</label>  
																<input onkeyup="adelantarMeses('<?php echo $data['codcubo']; ?>')" name="adelantar<?php echo $data['codcubo']; ?>" id="adelantar<?php echo $data['codcubo']; ?>" class="form-control" type="text"  value="0" >
															
														</div>
														<div class="col-md-6" id="divFechaPago<?php echo $data['codcubo']; ?>" >
															
																<label for="FechaPago<?php echo $data['codcubo']; ?>" class="font-weight-bold">Fecha Pago</label>  
																<input  onchange = "calcularRenta(<?php echo $data['codcubo']; ?>)" name="FechaPago<?php echo $data['codcubo']; ?>" id="FechaPago<?php echo $data['codcubo']; ?>" class="form-control" type="date" value="<?php echo $fecha; ?>" >
															
														</div>
													<?php
															//if($dia>10 && $data['disponible']==1)	
															//{				
																       echo '<center  style="width:100%"><label class="font-weight-bold" style="color:#f44336">'.$texto.'</label></center>';
														//}?>
														
														
														
													
												</div>

												<!-- <div class="row" id="ventacredito"  style="display:none;">
													<div class="col-md-4">
														<div class="form-group">
															<label for="totalmodalC" class="font-weight-bold">Renta</label>
															<input id="totalmodalC<?php //echo $data['codcubo']; ?>"  class="form-control" type="text" placeholder="Total"  value=""  readonly >
														</div>
														</div>
														<div class="col-md-4" id="divSaldo">
															<div class="form-group">
																<label for="saldo" class="font-weight-bold">Saldo</label>
																<input id="saldo<?php //echo $data['codcubo']; ?>" class="form-control"  type="text" placeholder="0.00"  value="" readonly  onchange="MASK(this,this.value,'$##,###,##0.00',1);">
															</div>
														</div>  
														<div class="col-md-4">
															<div class="form-group">
																<label for="pagar_conC" class="font-weight-bold">Pago</label>
																<input id="pagar_conC<?php //echo $data['codcubo']; ?>" name="pagar_conC<?php //echo $data['codcubo']; ?>" class="form-control"  type="text" placeholder="0.00"  value="" onchange="MASK(this,this.value,'$##,###,##0.00',1);">
															</div>
														</div>                            
														<div class="col-md-4" id="divFechaVencimiento">
															<div class="form-group">
																<label for="fechaven" class="font-weight-bold">Fecha Venciminto </label>
																<input id="fechaven<?php //echo $data['codcubo']; ?>" name="fechaven<?php //echo $data['codcubo']; ?>" class="form-control"  type="datetime"   value="<?php //echo date("d-m-Y",strtotime(date("d-m-Y")."+ 1 month"));;?>" >
															</div>
														</div> 
														<div class="col-md-4" id="divcredito">
															<div class="form-group">
																<label for="numcredito" class="font-weight-bold">NumCredito</label>  
																<input id="numcredito<?php //echo $data['codcubo']; ?>" name="numcredito<?php //echo $data['codcubo']; ?>" class="form-control" type="text" placeholder="Cambio" value="0" readonly>
															</div>
														</div>                    
														
												</div> -->

 												
												<div class="form-group" id='referencia<?php echo $data['codcubo']; ?>' style="display:none;">
													<label for="numreferencia" class="font-weight-bold">Referencia</label>  
													<input id="numreferencia<?php echo $data['codcubo']; ?>" name="numreferencia<?php echo $data['codcubo']; ?>"  class="form-control" type="text" placeholder="Referencia" value="">
												</div>
												<div class="form-group" id='pagomixtocubos<?php echo $data['codcubo']; ?>' style="display:none;">  
												<div class="row" > 
												<div class="col-md-2">
														<div class="form-group" id="divpagomixto" style="display:none;">
															<label  id="etiquetaPago" class="font-weight-bold">Total</label>
															<input   id='totalpagomixtocubos<?php echo $data['codcubo']; ?>' name='totalpagomixtocubos<?php echo $data['codcubo']; ?>' class="form-control" type="text" placeholder="Total"  value=""  disabled="" >
														</div>
														</div>
												
													
												<table>
													<tr>
														<td> <input type="checkbox" name="pagomcubos<?php echo $data['codcubo']; ?>[]" id="pagomcubos<?php echo $data['codcubo']; ?>[]" value="1"><label>Efectivo</label> </td>
														<td><input onkeyup="functionefectivo(<?php echo $data['codcubo']; ?>)" id="pefectivoc<?php echo $data['codcubo']; ?>"  style="display:none;" name="pefectivoc<?php echo $data['codcubo']; ?>" type="number" class="form-control" type="text" placeholder="0.00"  value=""> </td>
													</tr>
													<tr>
														<td> <input type="checkbox" name="pagomcubos<?php echo $data['codcubo']; ?>[]" id="pagomcubos<?php echo $data['codcubo']; ?>[]" value="2"><label>Tarjeta</label> </td>
														<td><input onkeyup="functiontarjeta(<?php echo $data['codcubo']; ?>)" id="ptarjetac<?php echo $data['codcubo']; ?>"  style="display:none;" name="ptarjetac<?php echo $data['codcubo']; ?>" type="number" class="form-control" type="text" placeholder="0.00"  value=""> 
														<label name="etiquetatarjeta<?php echo $data['codcubo']; ?>"  style="display:none;" id="etiquetatarjetac<?php echo $data['codcubo']; ?>" class="font-weight-bold"></label> 
														<br><label name="etiquetat<?php echo $data['codcubo']; ?>"  style="display:none; font-size: smaller;    color: red;    font-family: none;" id="etiquetat" class="font-weight-bold">5% de comisión por pago con tarjeta</label> 
													</td>
													</tr>
													<tr>
														<td> <input type="checkbox" name="pagomcubos<?php echo $data['codcubo']; ?>[]" id="pagomcubos<?php echo $data['codcubo']; ?>[]" value="3"><label>Transferencia</label> </td>
														<td><input onkeyup="functiontransferencia(<?php echo $data['codcubo']; ?>)"  id="ptransferenciac<?php echo $data['codcubo']; ?>" style="display:none;"   name="ptransferenciac<?php echo $data['codcubo']; ?>" type="number" class="form-control" type="text" placeholder="0.00"  value=""> </td>
													</tr>
													<tr>
														<td> <input type="checkbox" name="pagomcubos<?php echo $data['codcubo']; ?>[]"  id="pagomcubos<?php echo $data['codcubo']; ?>[]" value="4"><label>Deposito</label> </td>
														<td><input onkeyup="functiondeposito(<?php echo $data['codcubo']; ?>)" id="pdepositoc<?php echo $data['codcubo']; ?>"   style="display:none;" name="pdepositoc<?php echo $data['codcubo']; ?>"  type="number" class="form-control" type="text" placeholder="0.00"  value=""> </td>
													</tr>
												</table>
											
											</div>   	

											</div>   					
											
						
									</div> 
					 
								</div>
				
									</div>
										<div class="alert alertCambio"></div>
										<div class="modal-footer">     
										<button type="button" style="text-align: center;" class="btn btn-danger" data-dismiss="modal" id="btnCerrar" name="btnCerrar">Close</button>										
										<?php if($totalrenta!=0)
										
										echo '<button class="btn btn-primary" id="regitrarRenta" name="regitrarRenta" style="text-align: center;" type="submit">TerminarRenta </button>';
										?>
										</div>
									</div>
									</form>
									</div>									
									</div>								
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

<!-- echo $mesesretrazo; if($mesesretrazo!=0)  -->
<!-- /.container-fluid -->


<!-- <script type="text/javascript">
    // usando javascript
    window.addEventListener("load", function() {
    input = document.getElementById("regitrarRenta");
 
    input.addEventListener("click", clicked2, false);
});

function clicked2() {
document.getElementById("regitrarRenta").disabled = true;
}
</script> -->


<?php include_once "includes/footer.php"; ?>

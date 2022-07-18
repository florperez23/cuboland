<?php include_once "includes/header.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Informacion de los Cubos</h1>
		<a href="registro_cubo.php" class="btn btn-primary">Nuevo</a>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered" id="table">
					<thead class="thead-dark">
						<tr>
							<th>ID</th>
							<th>Cubo</th>
							<th>Precio Renta</th>
							<th>Disponible</th>
							
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th>ACCIONES</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM cubos ");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td><?php echo $data['codcubo']; ?></td>
									<td><?php echo $data['cubo']; ?></td>
									<td><?php echo $data['renta']; ?></td>
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
									
										<a href="#" class="btn btn-primary"  class="btn btn-primary"><i class="fa fa-credit-card"  data-toggle="modal" data-target="#exampleModal<?php echo $data['codcubo']; ?>"></i> </a>
									<div class="modal fade" id="exampleModal<?php echo $data['codcubo']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel<?php echo $data['codcubo']; ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
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
                 
                  <div class="row">              
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipoven">Tipo de Venta</label>
                            <select id="tipoven" class="form-control" name="tipoven" required="">
                                <option value="1">Contado</option>
                                <option value="2">Credito</option>                             
                            </select>
                        </div>
                    </div>
                      <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipopago">Tipo de Pago</label>
                            <select id="tipopago" class="form-control" name="tipopago" required="">
                                <option value="1">Efectivo</option>
                                <option value="2">Tarjeta</option>  
                                <option value="3">Transferencia</option>    
                                <option value="4">Deposito</option>                            
                            </select>
                        </div>
                      </div> 
                   </div>
                      <div class="row" id="ventacontado">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="totalmodal" class="font-weight-bold">Renta</label>
                                <input id="totalmodal"  class="form-control" type="text" placeholder="Total"  value="<?php echo $data['renta']; ?>"  disabled="" >
								<input id="idrrendatario"  class="form-control" type="text"   value="<?php echo $data['idarrendatario']; ?>"  disabled="" >
                            </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pagar_con"  class="font-weight-bold">Pagar</label>
                                    <input id="pagar_con" name="pagar_con" class="form-control"  type="text" placeholder="0.00"   onchange="MASK(this,this.value,'$##,###,##0.00',1);">
                                </div>
                            </div>
                            <div class="col-md-4" id="divCambio" >
                                <div class="form-group">
                                    <label for="cambio" class="font-weight-bold">Cambio</label>  
                                    <input id="cambio" class="form-control" type="text" placeholder="Cambio" value="0.00" disabled="" onchange="MASK(this,this.value,'$##,###,##0.00',1);">
                                </div>
                            </div>

                           
                      </div>

                      <div class="row" id="ventacredito"  style="display:none;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="totalmodalC" class="font-weight-bold">Renta</label>
                                <input id="totalmodalC"  class="form-control" type="text" placeholder="Total"  value=""  disabled="" >
                            </div>
                            </div>
                            <div class="col-md-4" id="divSaldo">
                                <div class="form-group">
                                    <label for="saldo" class="font-weight-bold">Saldo</label>
                                    <input id="saldo" class="form-control"  type="text" placeholder="0.00"  value="" disabled onchange="MASK(this,this.value,'$##,###,##0.00',1);">
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pagar_conC" class="font-weight-bold">Pago</label>
                                    <input id="pagar_conC" name="pagar_conC" class="form-control"  type="text" placeholder="0.00"  value="" onchange="MASK(this,this.value,'$##,###,##0.00',1);">
                                </div>
                            </div>                            
                            <div class="col-md-4" id="divFechaVencimiento">
                                <div class="form-group">
                                    <label for="fechav" class="font-weight-bold">Fecha Venciminto </label>
                                    <input id="fechav" class="form-control"  type="datetime"   value="<?php echo date("d-m-Y",strtotime(date("d-m-Y")."+ 1 month"));;?>" >
                                </div>
                            </div> 
                            <div class="col-md-4" id="divcredito">
                                <div class="form-group">
                                    <label for="numcredito" class="font-weight-bold">NumCredito</label>  
                                    <input id="numcredito" class="form-control" type="text" placeholder="Cambio" value="0" disabled="">
                                </div>
                            </div>                    
                            
                      </div>

                    
                    <div class="form-group" id='referencia' style="display:none;">
                        <label for="numreferencia" class="font-weight-bold">Referencia</label>  
                        <input id="numreferencia" class="form-control" type="text" placeholder="Referencia" value="">
                    </div>
                           

                                   
                </div>
          </div>
      </div>
        </div>
        <div class="alert alertCambio"></div>
        <div class="modal-footer">     
        <button type="button" style="text-align: center;" class="btn btn-danger" data-dismiss="modal" id="btnCerrar" name="btnCerrar">Close</button>
         <a href="#" class="btn btn-primary" id="btn_facturar_renta"><i class="fas fa-save"></i> Terminar Renta</a>
        </div>
      </div>
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
<!-- /.container-fluid -->



<?php include_once "includes/footer.php"; ?>
<?php include_once "includes/header.php"; ?>

<div class="container-fluid">
        <h4 class="text-center">Salidas del inventario</h4>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> VENDEDOR</label>
                        <p style="font-size: 16px; text-transform: uppercase; color: red;"><?php echo $_SESSION['nombre']; ?></p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label>Acciones</label>
                    <div id="acciones_venta" class="form-group">
                        <a href="#" class="btn btn-danger" id="btn_anular_venta_salida">Anular</a>
                        <!-- <a href="#" class="btn btn-primary" id="btn_facturar_venta"><i class="fas fa-save"></i> Generar Venta</a> -->
                        <a href="#" class="btn btn-primary" id="procesarVentaSalida" class="btn btn-primary" ><i class="fas fa-save"></i> Terminar salida</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th width="100px">Código</th>
                            <th>Des.</th>
                            <th>Stock</th>
                            <th width="100px">Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                        <tr>
                            <td><input type="hidden" name="txt_cod_producto" id="txt_cod_producto">
                                <input type="text" name="cod_pro" id="cod_pro">
                            </td>
                            <td id="txt_descripcion">-</td>
                            <td id="txt_existencia">-</td>
                            <td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
                            <td><a href="#" id="add_product_salida" class="btn btn-dark" style="display: none;">Agregar</a></td>
                        </tr>
                        <tr>
                            <th>Id</th>
                            <th colspan="2">Descripción</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="detalle_venta_salida">
                        <!-- Contenido ajax -->

                    </tbody>

                    
                </table>

            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<!-- Modal -->

<div id='modalpago'>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Forma de Pago</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">      
        <div>
          <div class="card_div">
              <div class="card-body">
                  <form id="formulario" onsubmit="registrarCliVenta(event);" autocomplete="off">
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
                                <label for="totalmodal" class="font-weight-bold">Total</label>
                                <input id="totalmodal"  class="form-control" type="text" placeholder="Total"  value=""  disabled="" >
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
                                <label for="totalmodalC" class="font-weight-bold">Total</label>
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
                           

                    </form>                  
                </div>
          </div>
      </div>
        </div>
        <div class="alert alertCambio"></div>
        <div class="modal-footer">     
        <button type="button" style="text-align: center;" class="btn btn-danger" data-dismiss="modal" id="btnCerrar" name="btnCerrar">Close</button>
         <a href="#" class="btn btn-primary" id="btn_facturar_venta"><i class="fas fa-save"></i> Terminar Venta</a>
        </div>
      </div>
    </div>
  </div>
  <!-- mensajeicono("Venta Realizada correctamente","","","info"); -->


  <div class="modal fade" id="modalBusquedacliente" tabindex="-1" role="dialog" aria-labelledby="modalBusquedaclienteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="width: 120%;">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">BuscarCliente</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">      
        <div>
          <div class="card_div">
              <div class="card-body">
                  <form id="formulario" >
                  <div class="row">
		<div class="col-lg-12">

			<div class="table-responsive">
				<table class="table table-striped table-bordered" id="table">
					<thead class="thead-dark">
						<tr>
							<!-- <th>ID</th> -->
							<th>DNI</th>
							<th>NOMBRE</th>
							<th>TELEFONO</th>
							<th>DIRECCIÓN</th>
							<?php if ($_SESSION['rol'] == 1) { ?>
							<th></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						include "../conexion.php";

						$query = mysqli_query($conexion, "SELECT * FROM cliente where idcliente");
						$result = mysqli_num_rows($query);
						if ($result > 0) {
							while ($data = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<!-- <td><?php echo $data['idcliente']; ?></td> -->
									<td><?php echo $data['dni']; ?></td>
									<td><?php echo $data['nombre']; ?></td>
									<td><?php echo $data['telefono']; ?></td>
									<td><?php echo $data['direccion']; ?></td>
								
									<td> <center>
										<a class="btn btn-success" onclick="seleccionarCliente('<?php echo $data['dni']; ?>');"><i class='fa fa-reply' style="color:white"></i></a>
										<!-- <form action="eliminar_cliente.php?id=<?php echo $data['idcliente']; ?>" method="post" class="confirmar d-inline">
											<button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
										</form> -->
                                    </center>
									</td>
									
								</tr>
						<?php }
						} ?>
					</tbody>

				</table>
			</div>

		</div>
	</div>
                    </form>                  
                </div>
          </div>
      </div>
        </div>
        <div class="alert alertCambio"></div>
        <div class="modal-footer">    
        <button type="button" style="text-align: center;" class="btn btn-danger" data-dismiss="modal" id="btnCerrar" name="btnCerrar">Close</button>
      
        </div>
      </div>
    </div>
  </div>
  



<?php include_once "includes/footer.php"; ?>
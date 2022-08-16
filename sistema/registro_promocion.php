<?php include_once "includes/header.php";
  include "../conexion.php";
  if (!empty($_POST)) {
    $alert = "";

    if (empty($_POST['clasificacion']) || empty($_POST['tipo']) || empty($_POST['promocion']) || empty($_POST['desde']) || empty($_POST['hasta'])) {
      mensajeicono('Todos los campos son obligatorios.', 'registro_promocion.php','','info');

    } else {
      $clasificacion = $_POST['clasificacion']; 

     
      $tipo = $_POST['tipo'];
      $promocion = $_POST['promocion'];     
      $fechainicio = $_POST['desde'];
      $fechatermino = $_POST['hasta'];
      $fechainicio = date("Y/m/d", strtotime($fechainicio));
      $fechatermino = date("Y/m/d", strtotime($fechatermino));
      $usuario = $_SESSION['idUser'];
      
      if($clasificacion==1 or $clasificacion==3)
      {
        $identificador = $_POST['codigocub'];
      }else
      {
        $identificador = $_POST['codigopro'];
      }
  

      if($identificador =='0' || empty($identificador)){
        mensajeicono('Todos los campos son obligatorios.', 'registro_promocion.php','','info');    
      
      }else{
       // echo ValidarExiteUnaPromocion($identificador);
      if(ValidarExiteUnaPromocion($identificador)=='Existe')
      {
        mensajeicono('Ya existe una promocion para este producto/cubo', 'registro_promocion.php','','info');    
      
      }else{
      $sql= "INSERT INTO promociones(idclasificacion, ididentificador,idtipo,promocion,fechainicio,fechatermino,usuario_id, fecha) values ('$clasificacion','$identificador', '$tipo', '$promocion', '$fechainicio','$fechatermino','$usuario', now())";
     // echo $sql;
      $query_insert = mysqli_query($conexion, $sql);
      if ($query_insert) {        
        historia('Se registro la prmocion ');
        mensajeicono('Se ha registrado con éxito la promocion!', 'lista_promociones.php','','exito');

      } else {
        historia('Error al intentar registrar la promocion ');
        mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_promociones.php','','error');
      }
    }
    }
    }
  }
  ?>

</script>
 <!-- Begin Page Content -->
 <div class="container-fluid">

   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
     <h1 class="h3 mb-0 text-gray-800">Registrar Promocion</h1>
     <a href="lista_promociones.php" class="btn btn-primary">Regresar</a>
   </div>

   <!-- Content Row -->
   <div class="row">
     <div class="col-lg-6 m-auto">
       <div class="card_div">
         <div class="card-header bg-primary">
           Nueva Promocion
         </div>
         <div class="card-body">
           <form action="" method="post" autocomplete="off">
             <?php echo isset($alert) ? $alert : ''; ?>

              
             <div class="form-group">
             <label for="codigo">Especifique como se aplicará la promoción</label><br>
                
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="clasificacion" id="rdproducto"  value="2">
                 <label class="form-check-label" for="inlineRadio2">Producto</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="clasificacion"  id="rdcubo"   value="1">
                    <label class="form-check-label" for="inlineRadio1">Cubo</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="clasificacion"  id="rdrenta"   value="3">
                    <label class="form-check-label" for="inlineRadio1">Renta</label>
                </div>
            </div>



             <div class="form-group" >
               <label for="codigo" id="labelcodigo" name="labelcodigo">Codigo</label>
               <div  id="cubo" name="cubo"> </div>
               <div  id="prod" name="prod">
               <table  width=100% ></tr>
               <td width=95%>   <input type="text" placeholder="Codigo" name="codigopro" id="codigopro" class="form-control"  ></td>
               <td width=5% ><center> <a href="#" class="btn btn-secondary"  name="btnBuscarProducto" id="btnBuscarProducto"data-toggle="modal" data-target="#modalBusquedaProducto"><i class="fa fa-search" aria-hidden="true"></i></center></td>          
               </tr></table>
               </div>          
             </div>

             <div class="form-group">
             <label for="codigo">Especifique que tipo de promocion se aplicará</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipo"  id="rdtipo" value="1">
                    <label class="form-check-label" for="inlineRadio1">Porcentaje (%)</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipo"  value="2">
                 <label class="form-check-label" for="inlineRadio2">Precio fijo</label>
                </div>
            </div>
            <div class="form-group">
               <label for="promocion"  id="labelpromocion" name="labelpromocion">Promocion</label>
               <input type="text" placeholder="%" name="promocion" id="promocion" class="form-control">
             </div>
             <div class="form-group">
               <label for="codigo">Fecha Inicio</label>
               <input type="date" name="desde" id="desde" class="form-control" value="<?php echo date("Y-m-d");?>">
             </div>
            
             <div class="form-group">
               <label for="codigo">Fecha Termino</label>
               <input type="date" name="hasta" id="hasta" class="form-control" value="<?php echo date("Y-m-d");?>">
             </div>

      
             
             <input type="submit" value="Guardar promocion" class="btn btn-primary">
           </form>
         </div>
       </div>
     </div>
   </div>
 </div>



 <div class="modal fade" id="modalBusquedaProducto" tabindex="-1" role="dialog" aria-labelledby="modalBusquedaProductoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="width: 120%;">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Buscar Producto</h5>
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
							<th>CODIGO</th>
							<th>PRODUCTO</th>
              <th>CUBO</th>					
							<th></th>						
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
									<td><?php echo $data['codproducto']; ?></td>
									<td><?php echo $data['descripcion']; ?></td>
									<td><?php echo $data['codcubo']; ?></td>
																
									<td> <center>
										<a class="btn btn-success" onclick="seleccionarProducto('<?php echo $data['codproducto']; ?>');"><i class='fa fa-reply' style="color:white"></i></a>
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
  

 <!-- /.container-fluid -->
 <?php include_once "includes/footer.php"; ?>

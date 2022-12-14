 <?php include_once "includes/header.php";
  include "../conexion.php";
  if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['codigo']) or empty($_POST['descripcion'])) {
      mensajeicono('El campo código o descripción no pueden estar vacios.', 'registro_producto.php','','info');

    } else {
      $idcubo = $_POST['cubop'];
      $nomenclatura = $_POST['nom'];
      $signum = $_POST['numsig'];
      $codigo = $_POST['codigo'];
      $descripcion = $_POST['descripcion'];
      $precio = $_POST['precio'];
      $mayoreo = $_POST['mayoreo'];
      $cant_may = $_POST['cant'];
      $existencia = $_POST['existencia'];
      $usuario_id = $_SESSION['idUser'];

      echo $signum;

      //validamos si lleva la nomenclatura de la C
      $empieza = substr($codigo, 0, 1);
      echo $empieza; 
      if($empieza <> 'C'){
        $signum = '';
      }

      $sql = "INSERT INTO producto(codproducto, nomenclatura,numsiguiente,descripcion,precio,mayoreo, registro, fecha, codcubo, cantidad_mayoreo, existencia) 
      values ('$codigo','$nomenclatura', '$signum', '$descripcion', '$precio','$mayoreo', '$usuario_id', now(), '$idcubo', '$cant_may', '$existencia')";
      echo $sql;
      $query_insert = mysqli_query($conexion, $sql );
      if ($query_insert) {
        historia('Se registro el nuevo producto '.$codigo);
        mensajeicono('Se ha registrado con éxito el producto!', 'lista_productos.php','','exito');

      } else {
        historia('Error al intentar registrar el nuevo producto '.$codigo);
        mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_productos.php','','error');
      }
    }
  }
  ?>

 <!-- Begin Page Content -->
 <div class="container-fluid">

   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
     <h1 class="h3 mb-0 text-gray-800">Registrar Productos</h1>
     <a href="lista_productos.php" class="btn btn-primary">Regresar</a>
   </div>

   <!-- Content Row -->
   <div class="row">
     <div class="col-lg-6 m-auto">
       <div class="card_div">
         <div class="card-header bg-primary">
           Nuevo Producto
         </div>
         <div class="card-body">
           <form action="" method="post" autocomplete="off">
             <?php echo isset($alert) ? $alert : ''; ?>

             <div class="form-group">
               <label>Cubo</label>
               <?php
                $query = mysqli_query($conexion, "SELECT * FROM cubos WHERE disponible = 1");
                $res = mysqli_num_rows($query);
               
                ?>

               <select id="cubop" name="cubop" class="form-control">
               <option value="0">Seleccione una opción</option>
                 <?php
                  if ($res > 0) {
                    while ($f = mysqli_fetch_array($query)) {
                      
                  ?>
                     <option value="<?php echo $f['codcubo']; ?>"><?php echo $f['cubo']; ?></option>
                 <?php
                    }
                  }
                  ?>
               </select>
             </div>

             <div class="form-group">
               <label for="codigo">Nomenclatura</label>
               <input type="text"  name="nom" id="nom" class="form-control" readonly>
             </div>
             <div class="form-group">
               <label for="codigo">Siguiente número</label>
               <input type="text"  name="numsig" id="numsig" class="form-control" >
             </div>

             <div class="form-group">
               <label for="codigo">Código del producto</label>
               <input type="text"  name="codigo" id="codigo" class="form-control">
             </div>
             <div class="form-group">
               <label for="producto">Descripción del producto</label>
               <input type="text" maxlength="23" placeholder="Ingrese la descripcion del producto" name="descripcion" id="descripcion" class="form-control">
             </div>

             <div class="form-group">
               <label for="preciocosto">Precio Producto</label>
               <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio">
             </div>

             <div class="form-group">
               <label for="cant">Cantidad Mayoreo</label>
               <input type="text" placeholder="Ingrese precio" class="form-control" name="cant" id="cant">
             </div>

             <div class="form-group">
               <label for="mayoreo">Precio Mayoreo</label>
               <input type="text" placeholder="Ingrese precio" class="form-control" name="mayoreo" id="mayoreo">
             </div>

             <div class="form-group">
               <label for="existencia">Existencia</label>
               <input type="text" placeholder="Ingrese existencia" class="form-control" name="existencia" id="existencia">
             </div>
             
             
             <input type="submit" name='guardarProductobtn' id='guardarProductobtn' value="Guardar Producto" class="btn btn-primary">
           </form>
         </div>
       </div>
     </div>
   </div>
 </div>
 <!-- /.container-fluid -->
 <?php include_once "includes/footer.php"; ?>
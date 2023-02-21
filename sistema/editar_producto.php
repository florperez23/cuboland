<?php
include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
  $alert = "";

    $codcubo = $_POST['cubop'];
    $codproducto = $_POST['codigo'];

    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $mayoreo = $_POST['mayoreo'];
    $cant_may = $_POST['cant'];
    $existencia = $_POST['existencia'];
    $moduser = $_SESSION['idUser'];

    $query_update = mysqli_query($conexion, "UPDATE producto SET  descripcion = '".$descripcion."', precio = '".$precio."', mayoreo = '$mayoreo', modifico='$moduser', cantidad_mayoreo='$cant_may', existencia='$existencia' WHERE codproducto = '$codproducto'");
    if ($query_update) {
      historia('Se actualizo el producto '.$codproducto);
      mensajeicono('Se ha registrado con éxito la modificacion del producto!', 'lista_productos.php','','exito');

    } else {
      historia('Error al actualizar el producto '.$codproducto);
      mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_productos.php','','error');

    }
  }
//}

// Validar producto

if (empty($_REQUEST['id'])) {
  header("Location: lista_productos.php");
} else {
  $id_producto = $_REQUEST['id'];

  $sql = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = '$id_producto'");
  $result_sql = mysqli_num_rows($sql);
  if ($result_sql == 0) {
    header("Location: lista_cliente.php");
  } else {
    while ($data = mysqli_fetch_array($sql)) {
      $codproducto = $data['codproducto'];
      $nom = $data['nomenclatura'];
      $signum = $data['numsiguiente'];
      $descripcion = $data['descripcion'];
      $precio = $data['precio'];
      $mayoreo = $data['mayoreo'];
      $codcubo = $data['codcubo'];
      $cant_may = $data['cantidad_mayoreo'];
      $existencia = $data['existencia'];
    }
  } 
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

  <div class="row">
    <div class="col-lg-6 m-auto">

      <div class="card_div">
        <div class="card-header bg-primary text-white">
          Modificar producto
        </div>
        <div class="card-body">
          <form action="" method="post">
            <?php echo isset($alert) ? $alert : ''; ?>
            
            <div class="form-group">
               <label>Cubo</label>
               <?php
                $query = mysqli_query($conexion, "SELECT * FROM cubos WHERE codcubo = '$codcubo'");
                $res = mysqli_num_rows($query);
               
                ?>

               <select id="cubop" name="cubop" class="form-control" disable>
                 <?php
                  if ($res > 0) {
                    while ($f = mysqli_fetch_array($query)) {
                      if($f['codcubo'] == $codcubo){
                        ?>
                        <option value="<?php echo $f['codcubo']; ?>" selected><?php echo $f['cubo']; ?></option>
                      <?php
                      }else{
                      ?>        
                     <option value="<?php echo $f['codcubo']; ?>"><?php echo $f['cubo']; ?></option>
                 <?php
                      }
                    }
                  }
                  ?>
               </select>
             </div>

             <div class="form-group">
               <label for="codigo">Código del producto</label>
               <input type="text"  name="codigo" id="codigo" class="form-control" value="<?php echo $codproducto; ?>" readonly>
             </div>
             <div class="form-group">
               <label for="producto">Descripción del producto</label>
               <input type="text"  maxlength="23" placeholder="Ingrese la descripcion del producto" name="descripcion" id="descripcion" value="<?php echo $descripcion; ?>" class="form-control">
             </div>

             <div class="form-group">
               <label for="preciocosto">Precio Producto</label>
               <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio" value="<?php echo $precio; ?>">
             </div>

             <div class="form-group">
               <label for="cant">Cantidad Mayoreo</label>
               <input type="text" placeholder="Ingrese precio" class="form-control" name="cant" id="cant" value="<?php echo $cant_may ?>">
             </div>

             <div class="form-group">
               <label for="mayoreo">Precio Mayoreo</label>
               <input type="text" placeholder="Ingrese precio" class="form-control" name="mayoreo" id="mayoreo" value="<?php echo $mayoreo ?>">
             </div>

             <div class="form-group">
               <label for="existencia">Existencia</label>
               <input type="text" placeholder="Ingrese existencia" class="form-control" name="existencia" id="existencia" value="<?php echo $existencia ?>">
             </div>
             
            <input type="submit" value="Actualizar Producto" class="btn btn-primary">
          </form>
        </div>
      </div>
    </div>
  </div>


</div>
<!-- /.container-fluid -->
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>
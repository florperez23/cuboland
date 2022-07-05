<?php
include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
  $alert = "";
 /* if (empty($_POST['codigo']) || empty($_POST['producto']) || empty($_POST['precio'])) {
    $alert = '<div class="alert alert-primary" role="alert">
              Todo los campos son requeridos
            </div>';
  } else {+*/
    $codproducto = $_GET['id'];
    $proveedor = $_POST['proveedor'];
    $codigo = $_POST['codigo'];
    $producto = $_POST['producto'];   
    $preciocosto = $_POST['preciocosto'];
    $precio = $_POST['precioventa'];
    $preciomayoreo = $_POST['preciomayoreo'];
    $cantidad = $_POST['cantidad'];
    $medida = $_POST['medida'];
    $categoria = $_POST['categoria'];
    if(isset($_POST['sec'])){
      $sec = $_POST['sec'];
    }else{
      $sec = "";
    }
    $moduser = $_SESSION['idUser'];
    $query_update = mysqli_query($conexion, "UPDATE producto SET codigo = '$codigo', descripcion = '$producto', proveedor= '$proveedor', precio = '$precio', existencia = '$cantidad', preciocosto = '$preciocosto', preciomayoreo = '$preciomayoreo', unidadmedida = '$medida', categoria = '$categoria', seccion = '$sec', modifico='$moduser' WHERE codproducto = $codproducto");
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
  if (!is_numeric($id_producto)) {
    header("Location: lista_productos.php");
  }

  $sql = "SELECT p.codproducto, p.codigo, p.descripcion, p.precio, pr.codproveedor, pr.proveedor, p.existencia, p.preciocosto, p.preciomayoreo, med.nombrecorto, dpto.departamento, med.idunidadmedida, dpto.iddepartamento, cs.idseccion, cs.seccion FROM producto p 
  left JOIN proveedor pr ON p.proveedor = pr.codproveedor 
  left join cat_departamento dpto on dpto.iddepartamento = p.categoria
  left join cat_unidadmedida med on med.idunidadmedida = p.unidadmedida
  left join cat_secciones cs on cs.idseccion = p.seccion
  WHERE p.codproducto = $id_producto";
  //echo $sql;
  $query_producto = mysqli_query($conexion, $sql);
  $result_producto = mysqli_num_rows($query_producto);

  if ($result_producto > 0) {
    $data_producto = mysqli_fetch_assoc($query_producto);
  } /*else {
    header("Location: lista_productos.php");
  }*/
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
              <label for="codigo">Código de Barras</label>
              <input type="text" placeholder="Ingrese código de barras" name="codigo" id="codigo" class="form-control" value="<?php echo $data_producto['codigo']; ?>">
            </div>
            <div class="form-group">
              <label for="producto">Producto</label>
              <input type="text" class="form-control" placeholder="Ingrese nombre del producto" name="producto" id="producto" value="<?php echo $data_producto['descripcion']; ?>">
            </div>
            <div class="form-group">
              <label for="nombre">Proveedor</label>
              <?php $query_proveedor = mysqli_query($conexion, "SELECT * FROM proveedor ORDER BY proveedor ASC");
              $resultado_proveedor = mysqli_num_rows($query_proveedor);
              
              ?>
              <select id="proveedor" name="proveedor" class="form-control">
                <?php
                if ($resultado_proveedor > 0) {
                  while ($proveedor = mysqli_fetch_array($query_proveedor)) {
                    if($proveedor['codproveedor']==$data_producto['codproveedor']){
                ?>
                  <option value="<?php echo $data_producto['codproveedor']; ?>" selected><?php echo $data_producto['proveedor']; ?></option>

                 <?php
                      }else{
                  ?>
                    <option value="<?php echo $proveedor['codproveedor']; ?>"><?php echo $proveedor['proveedor']; ?></option>
                <?php
                    }
                  }
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="precio">Precio  Costo</label>
              <input type="number" placeholder="Ingrese precio" class="form-control" name="preciocosto" id="preciocosto" value="<?php echo $data_producto['preciocosto']; ?>">
            </div>
            <div class="form-group">
               <label for="precio">Precio Venta</label>
               <input type="number" placeholder="Ingrese precio" class="form-control" name="precioventa" id="precioventa" value="<?php echo $data_producto['precio']; ?>">
             </div>
             <div class="form-group">
               <label for="preciomayoreo">Precio Mayoreo</label>
               <input type="number" placeholder="Ingrese precio" class="form-control" name="preciomayoreo" id="preciomayoreo" value="<?php echo $data_producto['preciomayoreo']; ?>">
             </div>
             <div class="form-group">
               <label for="cantidad">Cantidad</label>
               <input type="number" placeholder="Ingrese cantidad" class="form-control" name="cantidad" id="cantidad" value="<?php echo $data_producto['existencia']; ?>">
             </div>
             <div class="form-group">
               <label>Unidad de Medida</label>
               <?php
                $query_medida = mysqli_query($conexion, "SELECT idunidadmedida, nombrecorto FROM cat_unidadmedida");
                $resultado_medida = mysqli_num_rows($query_medida);
                ?>

               <select id="medida" name="medida" class="form-control">
                 <?php
                  if ($resultado_medida > 0) {
                    while ($medida = mysqli_fetch_array($query_medida)) {
                      if($medida['idunidadmedida']==$data_producto['idunidadmedida']){
                      // code...
                  ?>
                    <option value="<?php echo $data_producto['idunidadmedida']; ?>" selected><?php echo $data_producto['nombrecorto']; ?></option>
                  <?php
                      }else{
                  ?>
                     <option value="<?php echo $medida['idunidadmedida']; ?>"><?php echo $medida['nombrecorto']; ?></option>
                 <?php
                    }
                  }
                }
                  ?>
               </select>
             </div>

             <div class="form-group">
               <label>Categoria</label>
               <?php
                $query_dptos = mysqli_query($conexion, "SELECT iddepartamento, departamento FROM cat_departamento ORDER BY departamento ASC");
                $resultado_dptos = mysqli_num_rows($query_dptos);
                
                ?>

               <select id="categoria" name="categoria" class="form-control">
                 <?php
                  if ($resultado_dptos > 0) {
                    while ($dptos = mysqli_fetch_array($query_dptos)) {
                      echo 'iddepto '.$dptos['iddepartamento'];
                      echo 'query '.$data_producto['departamento'];
                      if($dptos['iddepartamento']==$data_producto['iddepartamento']){
                  ?>
                    <option value="<?php echo $data_producto['iddepartamento']; ?>" selected><?php echo $data_producto['departamento']; ?></option>
                    <?php
                      }else{
                  ?>
                     <option value="<?php echo $dptos['iddepartamento']; ?>"><?php echo $dptos['departamento']; ?></option>
                 <?php
                      }
                    }
                  }
                  ?>
                </select>
             </div>

             <div class="form-group">
            
            <?php
            $sql = "SELECT * FROM cat_secciones WHERE iddepartamento = ".$data_producto['iddepartamento']."";
            echo $sql;
            $query_medida = mysqli_query($conexion, $sql);
            $resultado_medida = mysqli_num_rows($query_medida);
            mysqli_close($conexion);
            ?>

            <?php
              if ($resultado_medida > 0) {
            ?>
              <label>Sección del producto</label>  
              <select id="sec" name="sec" class="form-control">

                <?php
                while ($secciones = mysqli_fetch_array($query_medida)) {
                  if($secciones['idseccion']==$data_producto['idseccion']){
                ?>
                  <option value="<?php echo $data_producto['idseccion']; ?>" selected><?php echo $data_producto['seccion']; ?></option>
                <?php
                  }else{
                ?>
                  <option value="<?php echo $secciones['idseccion']; ?>"><?php echo $secciones['seccion']; ?></option>
                <?php
                  }
                }
                ?>
                </select>
            <?php
              }
            ?>
            
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
<?php include_once "includes/header.php";
  include "../conexion.php";
  if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precioventa']) || $_POST['precioventa'] <  0 || empty($_POST['cantidad'] || $_POST['cantidad'] <  0)) {
      mensajeicono('Todos los campos son obligatorios.', 'registro_producto.php','','info');

    } else {
      $codigo = $_POST['codigo'];
      $proveedor = $_POST['proveedor'];
      $producto = $_POST['producto'];     
      $cantidad = $_POST['cantidad'];
      $usuario_id = $_SESSION['idUser'];
      $preciocosto = $_POST['preciocosto'];
      $precio = $_POST['precioventa'];
      $preciomayoreo = $_POST['preciomayoreo'];
      $unidadmedida = $_POST['medida'];
      $categoria = $_POST['categoria'];
      if(isset($_POST['sec'])){
        $sec = $_POST['sec'];
      }else{
        $sec = "";
      }
      


      $query_insert = mysqli_query($conexion, "INSERT INTO producto(codigo, proveedor,descripcion,precio,existencia,usuario_id, preciocosto, preciomayoreo, unidadmedida, categoria, seccion, fecha) values ('$codigo','$proveedor', '$producto', '$precio', '$cantidad','$usuario_id', '$preciocosto', '$preciomayoreo', '$unidadmedida', '$categoria', '$sec', now())");
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
<script>

function cargar_secciones(){

let idcat = $('#categoria').val();
$.ajax({
  url: "cargar_secciones.php",
  type: "post",
  data: {idcat: idcat},
  success: function(data){
      $('#seccion').html(data+"\n");
  }
});

}
</script>
 <!-- Begin Page Content -->
 <div class="container-fluid">

   <!-- Page Heading -->
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
     <h1 class="h3 mb-0 text-gray-800">Registrar Promocion</h1>
     <a href="lista_productos.php" class="btn btn-primary">Regresar</a>
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
                    <input class="form-check-input" type="radio" name="clasificacion"   value="1">
                    <label class="form-check-label" for="inlineRadio1">Cubo</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="clasificacion"  value="2">
                 <label class="form-check-label" for="inlineRadio2">Producto</label>
                </div>
            </div>



             <div class="form-group">
               <label for="codigo" id="labelcodigo" name="labelcodigo">Codigo</label>
               <input type="text" placeholder="Codigo" name="codigo" id="codigo" class="form-control">
             </div>

             <div class="form-group">
             <label for="codigo">Especifique que tipo de promocion se aplicará</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipo"  value="1">
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
               <input type="date" name="desde" id="hasta" class="form-control" value="<?php echo date("Y-m-d");?>">
             </div>

         

            

             <div class="form-group" name='seccion' id='seccion'></div>
             
             <input type="submit" value="Guardar Producto" class="btn btn-primary">
           </form>
         </div>
       </div>
     </div>
   </div>
 </div>
 <!-- /.container-fluid -->
 <?php include_once "includes/footer.php"; ?>
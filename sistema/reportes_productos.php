<?php include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precioventa']) || $_POST['precioventa'] <  0 || empty($_POST['cantidad'] || $_POST['cantidad'] <  0)) {
    $alert = '<div class="alert alert-danger" role="alert">
              Todo los campos son obligatorios
            </div>';
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
    


    $query_insert = mysqli_query($conexion, "INSERT INTO producto(codigo, proveedor,descripcion,precio,existencia,usuario_id, preciocosto, preciomayoreo, unidadmedida, categoria, seccion) values ('$codigo','$proveedor', '$producto', '$precio', '$cantidad','$usuario_id', '$preciocosto', '$preciomayoreo', '$unidadmedida', '$categoria', '$sec')");
    if ($query_insert) {
      $alert = '<div class="alert alert-success" role="alert">
              Producto Registrado
            </div>';
    } else {
      $alert = '<div class="alert alert-danger" role="alert">
              Error al registrar el producto
            </div>';
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


<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
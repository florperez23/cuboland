<?php include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
  $alert = "";

    $idarrendatario = $_POST['id'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $fecha = $_POST['fecha'];
  
   
    $sql = "UPDATE arrendatarios SET nombre = '$nombre', telefono = '$telefono', fechaingreso = '$fecha' WHERE idarrendatario = $idarrendatario";
   // echo $sql;
    $sql_update = mysqli_query($conexion, $sql);
    if ($sql_update) {
     
      historia('Se ha actualizado el arrendatario '.$idarrendatario);
      mensajeicono('Se ha actualizado el arrendatario con éxito!', 'lista_arrendatarios.php','','exito');  

    } else {
      historia('Error al actualizar el arrendatario '.$idarrendatario);
      mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_arrendatarios.php','','error');
    }  
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
  header("Location: lista_arrendatarios.php");
}
$id = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM arrendatarios WHERE idarrendatario = $id");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
  header("Location: lista_cliente.php");
} else {
  while ($data = mysqli_fetch_array($sql)) {
    $id = $data['idarrendatario'];
    $nombre = $data['nombre'];
    $fecha = $data['fechaingreso'];
    $telefono = $data['telefono'];

  }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

  <div class="row">
    <div class="col-lg-6 m-auto">
      <div class="card_div">
        <div class="card-header bg-primary">
          Modificar datos del arrendador
        </div>
        <div class="card-body">
          <form class="" action="" method="post">
            <?php echo isset($alert) ? $alert : ''; ?>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-group">
              <label for="nombre">Nombre</label>
              <input type="text" placeholder="Ingrese Nombre" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre; ?>">
            </div>
            <div class="form-group">
                <label for="nombre">Fecha de ingreso</label>
                <input type="date"  name="fecha" id="fecha" value="<?php echo $fecha ?>" class="form-control">
            </div>
            <div class="form-group">
              <label for="telefono">Teléfono</label>
              <input type="number" placeholder="Ingrese Teléfono" name="telefono" class="form-control" id="telefono" value="<?php echo $telefono; ?>">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar arrendatario</button>
          </form>
        </div>
      </div>
    </div>
  </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
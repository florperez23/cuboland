<?php
include "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['cubo']) ) {
    mensajeicono('Todos los campos son obligatorios.', 'editar_cubo.php','','info');

  } else {
    $idcubo = $_GET['id'];
    $cubo = $_POST['cubo'];
    $renta = $_POST['renta'];

    $sql_update = mysqli_query($conexion, "UPDATE cubos SET cubo = '$cubo', renta = '$renta'  WHERE codcubo = $idcubo");

    if ($sql_update) {
      historia('Se actualizo el proveedor '.$idproveedor);
      mensajeicono('Se ha actualizado con éxito los datos del cubo!', 'lista_cubos.php','','exito');

    } else {
      historia('Error al actualizar el proveedor '.$idproveedor);
      mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_cubos.php','','error');

    }
  }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
  header("Location: lista_cubos.php");
  mysqli_close($conexion);
}
$idcubo = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM cubos WHERE codcubo = $idcubo");
mysqli_close($conexion);
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
  header("Location: lista_proveedor.php");
} else {
  while ($data = mysqli_fetch_array($sql)) {
    $idcubo = $data['codcubo'];
    $cubo = $data['cubo'];
    $renta = $data['renta'];

  }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <div class="row">
    <div class="col-lg-6 m-auto">

      <div class="card_div">
        <div class="card-header bg-primary">
          Modificar Información del cubo
        </div>
        <div class="card-body">
          <?php echo isset($alert) ? $alert : ''; ?>
          <form class="" action="" method="post">
            <input type="hidden" name="id" value="<?php echo $idcubo; ?>">
            <div class="form-group">
              <label for="proveedor">Nombre del cubo</label>
              <input type="text" placeholder="Ingrese nombre del cubo" name="cubo" class="form-control" id="cubo" value="<?php echo $cubo; ?>">
            </div>
            <div class="form-group">
              <label for="nombre">Renta</label>
              <input type="text" placeholder="Ingrese el monto de la renta" name="renta" class="form-control" id="renta" value="<?php echo $renta; ?>">
            </div>

            <input type="submit" value="Editar Cubo" class="btn btn-primary">
          </form>
        </div>
      </div>
    </div>
  </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
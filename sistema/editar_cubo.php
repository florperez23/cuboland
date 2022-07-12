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
    $idarr = $_POST['rentero'];

    if($idarr == 0){
      $sql_update = mysqli_query($conexion, "UPDATE cubos SET cubo = '$cubo', renta = '$renta', idarrendatario = 0, disponible = 0  WHERE codcubo = $idcubo");

    }else{
      $sql_update = mysqli_query($conexion, "UPDATE cubos SET cubo = '$cubo', renta = '$renta', idarrendatario = '$idarr', disponible = 1  WHERE codcubo = $idcubo");

    }

    if ($sql_update) {
      historia('Se actualizo el cubo '.$idcubo);
      mensajeicono('Se ha actualizado con éxito los datos del cubo!', 'lista_cubos.php','','exito');

    } else {
      historia('Error al actualizar el cubo '.$idcubo);
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

$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
  header("Location: lista_proveedor.php");
} else {
  while ($data = mysqli_fetch_array($sql)) {
    $idcubo = $data['codcubo'];
    $cubo = $data['cubo'];
    $renta = $data['renta'];
    $disponible = $data['disponible'];
    $idarr = $data['idarrendatario'];

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

            <div class="form-group">
               <label>Arrendatario asociado</label>
               <?php
                $query = mysqli_query($conexion, "select * from arrendatarios");
                $res = mysqli_num_rows($query);
                ?>

               <select id="rentero" name="rentero" class="form-control">
               <option value="0">Sin especificar</option>
                 <?php
                  if ($res > 0) {
                    while ($f = mysqli_fetch_array($query)) {
                      if($f['idarrendatario']==$idarr){
                      // code...
                  ?>
                    <option value="<?php echo $f['idarrendatario']; ?>" selected><?php echo $f['nombre']; ?></option>
                  <?php
                      }else{
                  ?>
                     <option value="<?php echo $f['idarrendatario']; ?>"><?php echo $f['nombre']; ?></option>
                 <?php
                    }
                  }
                }
                  ?>
               </select>
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
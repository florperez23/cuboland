<?php
include "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['cubo']) ) {
    mensajeicono('Todos los campos son obligatorios.', 'editar_cubo.php','','info');

  } else {
    $idrenta = $_GET['id'];
    $cubo = $_POST['cubo'];
    $idarr = $_POST['rentero'];
    $fecha = $_POST['fecha'];
    $cuboant = idcuboanterior($idrenta);
    $sql = "UPDATE rentas SET idcubo = '$cubo', idarrendatario = '$idarr', fechacontrato = '$fecha'  WHERE id = $idrenta";
    echo $sql;
    $sql_update = mysqli_query($conexion, $sql);

    if ($sql_update) {

        if($cubo == $cuboant){
            historia('Se actualizo la renta '.$idrenta);
            mensajeicono('Se ha actualizado con éxito los datos del cubo!', 'lista_rentas.php','','exito');
        }else{
            $sql_update = mysqli_query($conexion, "UPDATE cubos SET disponible = 0  WHERE codcubo = $cuboant");
            if ($sql_update) {
                $sql_update = mysqli_query($conexion, "UPDATE cubos SET disponible = 1  WHERE codcubo = $cubo");
                if ($sql_update) {
                    historia('Se actualizon los datos de la renta '.$idrenta);
                    mensajeicono('Se ha actualizado con éxito los datos del cubo!', 'lista_rentas.php','','exito');
                }else{
                    historia('Error al actualizar el cubo nuevo al que se cambio la renta'.$idrenta);
                    mensajeicono('Error al actualizar los datos del cubo nuevo!', 'lista_rentas.php','','error');
                }

            }else{
                historia('Error al actualizar el cubo nuevo al que se cambio la renta'.$idrenta);
                mensajeicono('Error al actualizar los datos del cubo nuevo!', 'lista_rentas.php','','error');
            }
        }

    } else {
        historia('Error al actualizar el cubo nuevo al que se cambio la renta'.$idrenta);
        mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_rentas.php','','error');
    }
  }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
  header("Location: lista_rentas.php");
 // mysqli_close($conexion);
}
$id = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM rentas WHERE id = $id");

$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
  header("Location: lista_rentas.php");
} else {
  while ($data = mysqli_fetch_array($sql)) {
    $idcubo = $data['idcubo'];
    $idarr = $data['idarrendatario'];
    $fecha = $data['fechacontrato'];
  }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <div class="row">
    <div class="col-lg-6 m-auto">

      <div class="card_div">
        <div class="card-header bg-primary">
          Modificar Información de la renta
        </div>
        <div class="card-body">
          <?php echo isset($alert) ? $alert : ''; ?>
          <form class="" action="" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            
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

                    <div class="form-group">
                    <label>Cubo asociado</label>
                    <?php
                        $sql = "select * from cubos where disponible = 0
                        union select * from cubos where codcubo = '".$idcubo."'";
                        //echo $sql;
                        $query = mysqli_query($conexion, $sql );
                        $res = mysqli_num_rows($query);
                    ?>

                    <select id="cubo" name="cubo" class="form-control">
                        <option value='0'>Seleccione una opción</option>
                        <?php
                         if ($res > 0) {
                            while ($f = mysqli_fetch_array($query)) {
                                if($f['codcubo']==$idcubo){
                                // code...
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
                        <label for="fecha">Fecha Contrato</label>
                        <input type="date" placeholder="Ingrese nombre" name="fecha" id="fecha" value= '<?php echo $fecha ?>' class="form-control">
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
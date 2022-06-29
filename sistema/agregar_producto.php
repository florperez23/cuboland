<?php
include_once "includes/header.php";
include "../conexion.php";
// Validar producto

if (empty($_REQUEST['id'])) {
   // header("Location: lista_productos.php");
} else {
    $id_producto = $_REQUEST['id'];
    if (!is_numeric($id_producto)) {
        header("Location: lista_productos.php");
    }
    $query_producto = mysqli_query($conexion, "SELECT codproducto, descripcion, proveedor, precio, existencia FROM producto WHERE codproducto = $id_producto");
    $result_producto = mysqli_num_rows($query_producto);

    if ($result_producto > 0) {
        $data_producto = mysqli_fetch_assoc($query_producto);
    } else {
        header("Location: lista_productos.php");
    }
}
// Agregar Productos a entrada
if (!empty($_POST)) {
    $alert = "";
    if (!empty($_POST['cantidad']) || !empty($_POST['precio']) || !empty($_POST['producto_id'])) {
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $producto_id = $_GET['id'];
        $usuario_id = $_SESSION['idUser'];
        $query_insert = mysqli_query($conexion, "INSERT INTO entradas(codproducto,cantidad,precio,usuario_id) VALUES ($producto_id, $cantidad, $precio, $usuario_id)");
        if ($query_insert) {
            // ejecutar procedimiento almacenado
            $query_upd = mysqli_query($conexion, "CALL actualizar_precio_producto($cantidad,$precio,$producto_id)");
            $result_pro = mysqli_num_rows($query_upd);
            if ($result_pro > 0) {
                $alert = '<div class="alert alert-success" role="alert">
                        Producto actualizado con exito
                    </div>';
            }
        } else {
            echo "error";
        }
        mysqli_close($conexion);
    } else {
        echo "error";
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

   <!-- Modal -->
    <div class="modal fade" id="ajusteinventario" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" style='color: #fff;' id="exampleModalLongTitle">Abrir corte de caja</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">X</span>
            </button>
        </div>
        <div class="modal-body">
        
            <label for="montoinicial" style="color: #fff;">Monto Inicial</label>
            <input type="text" placeholder="Ingrese el monto inicial" id="montoinicial" name="montoinicial" class="form-control">
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <a href="#" class="btn btn-primary" id="btn_guardarcorte">Guardar</a>
            <div class="alert alertAddProduct" style='color:#fff'></div>
        </div>
        </div>
    </div>
    </div>


    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ajuste de Inventario</h1>
        <button type="button" id='abrir' class="btn btn-primary" data-toggle="modal" data-target="#ajusteinventario">
			Ajustar
		</button>
        <!-- Button trigger modal -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>CÓDIGO PRODUCTO</th>
                            <th>DESCRIPCIÓN</th>
                            <th>FECHA</th>
                            <th>ENTRADAS</th>
                            <th>SALIDAS</th>
                            <?php if ($_SESSION['rol'] == 1) { ?>
                            <th>ACCIONES</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../conexion.php";

                        $query = mysqli_query($conexion, "SELECT * FROM ajuste_inventario ORDER BY id DESC");
                        $result = mysqli_num_rows($query);
                        if ($result > 0) {
                            while ($data = mysqli_fetch_assoc($query)) { ?>
                                <tr>
                                    <td><?php echo $data['codproducto']; ?></td>
                                    <td><?php echo '$ '.$data['descripcion']; ?></td>
                                    <td><?php echo $data['fecha']; ?></td>
                                    <td><?php echo $data['entradas']; ?></td>
                                    <td><?php echo $data['salidas']; ?></td>
                                    <?php if ($_SESSION['rol'] == 1) { ?>
                                    <td>
                                        <form action="eliminar_cortes.php?id=<?php echo $data['Id']; ?>" method="post" class="confirmar d-inline">
                                            <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                        </form>
                                    </td>
                                        <?php } ?>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>

                </table>
            </div>

        </div>
    </div>



</div>
<!-- End of Main Content -->
<?php include_once "includes/footer.php"; ?>
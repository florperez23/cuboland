<?php
include_once "includes/header.php";
include "../conexion.php";
// Validar producto

?>
<!-- Begin Page Content -->
<div class="container-fluid">

   <!-- Modal -->
    <div class="modal fade" id="ajusteinventario" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" style='color: #fff;' id="exampleModalLongTitle">Entradas/Salidas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">X</span>
            </button>
        </div>
        <div class="modal-body">
            <label for="cod_pro" style="color: #fff;">Buscar producto</label>
            <input type="text" placeholder="Ingrese el codigo del producto a buscar" name="cod_pro" id="cod_pro" class="form-control">           
        </div>
        <div class="modal-body">
            <label for="name" style="color: #fff;">Nombre</label>
            <input type="text" placeholder="Nombre del producto" name="name" id="name" class="form-control" readonly>           
        </div>
        <div class="modal-body">
            <label for="cantidad" style="color: #fff;">Cantidad</label>
            <input type="text" placeholder="Cantidad en stock del producto" name="cantidad" id="cantidad" class="form-control" readonly>           
        </div>
        <div class="modal-body">
            <label for="agregar" style="color: #fff;">Agregar + ó Restar - *</label>
            <input id="agregar" class="form-control" type="number" name="agregar" placeholder="Agregar Existencia" required="">           
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <a href="#" class="btn btn-primary" id="btn_guardarajuste">Guardar</a>
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
    <div id='respuesta' name='respuesta'></div>
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
                                    <td><?php echo $data['descripcion']; ?></td>
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
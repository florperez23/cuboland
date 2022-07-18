<?php include_once "includes/header.php"; ?>

<div class="container-fluid">
        <h4 class="text-center">Salidas del inventario</h4>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> VENDEDOR</label>
                        <p style="font-size: 16px; text-transform: uppercase; color: red;"><?php echo $_SESSION['nombre']; ?></p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label>Acciones</label>
                    <div id="acciones_venta" class="form-group">
                        <a href="#" class="btn btn-danger" id="btn_anular_venta_salida">Anular</a>
                        <!-- <a href="#" class="btn btn-primary" id="btn_facturar_venta"><i class="fas fa-save"></i> Generar Venta</a> -->
                        <a href='salidas_pdf.php' class="btn btn-primary" id="procesarVentaSalida" class="btn btn-primary" ><i class="fas fa-save"></i> Terminar salida</a>
                    </div> 
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th width="100px">Código</th>
                            <th>Des.</th>
                            <th>Stock</th>
                            <th width="100px">Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                        <tr>
                            <td><input type="hidden" name="txt_cod_producto" id="txt_cod_producto">
                                <input type="text" name="cod_pro" id="cod_pro">
                            </td>
                            <td id="txt_descripcion">-</td>
                            <td id="txt_existencia">-</td>
                            <td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
                            <td><a href="#" id="add_product_salida" class="btn btn-dark" style="display: none;">Agregar</a></td>
                        </tr>
                        <tr>
                            <th>Id</th>
                            <th colspan="2">Descripción</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="detalle_venta_salida">
                    <?php 
                    $sql="SELECT
                    dts.*,
                    sum( dts.cantidad ) AS total,
                    p.descripcion
                FROM
                    detalle_temp_salidas dts
                inner join producto p on p.codproducto = dts.codproducto
                INNER JOIN rentas r on r.idcubo = p.codcubo
                 GROUP BY dts.codproducto";
                    //echo $sql;
                    $query_detalle_temp = mysqli_query($conexion, $sql);
                    $result = mysqli_num_rows($query_detalle_temp);
                    
                    
                    $arrayData = array();
                    if ($result > 0) {
                        while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
                       ?>
                       <tr>
                        <td><?php echo $data['codproducto'] ?></td>
                        <td colspan="2"> <?php echo$data['descripcion'] ?></td>
                        <td class="textcenter"> <?php echo$data['total'] ?></td>
                        <td>
                            <a href="#" class="link_delete" onclick="event.preventDefault(); eliminar_salida('<?php echo $data['correlativo'] ?>');"><i class="fas fa-trash-alt"></i> Eliminar</a>
                        </td>
                    </tr>
                    <?php }
                    } ?>
                    </tbody>

                    
                </table>

            </div>
        </div>
    </div>

</div>

<?php include_once "includes/footer.php"; ?>
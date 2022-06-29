<?php
include_once "includes/header.php";
include "../conexion.php";
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card-header bg-primary text-white">
                Abrir corte de caja
            </div>

            <!--GENERAR CODIGO DE BARRAS-->
            <form action='lista_cortes.php' method='POST'>
            <div class="card">

                <div class="form-group">
                    <label for="nombre">Monto Inicial</label>
                    <input type="text" placeholder="Ingrese el monto inicial" id="montoinicial" name="montoinicial" class="form-control">
                </div>
                
              
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>   
            </form>         
        </div>
    </div>
</div>


<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
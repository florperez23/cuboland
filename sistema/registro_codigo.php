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
                Registro de código de barras
            </div>

            <!--GENERAR CODIGO DE BARRAS-->
            <form action='registro_codigo.php' method='GET'>
            <div class="card_div">
                <div id="respuesta"></div>
                <form action="" autocomplete="off" method="post" class="card-body p-2">
                <div class="form-group">
                    <label for="nombre">Nombre del producto</label>
                    <input type="text" placeholder="Ingrese nombre" id="nombre" name="nombre" class="form-control">
                </div>
                <div class="form-group">
                    <a style='color:#f03406;' href="#" name='rand' id = 'rand'>Generar aleatorio</a>
                </div>
                <div class="form-group">
                    <label for="nombre">Código del producto</label>
                    <input type="text" id="data" name="data" placeholder="Ingresa un valor" class="form-control"> 
                </div>
                
                <div id="imagen"></div>
                <div class="form-group">
                <button type="button" id="generar_barcode" onclick="cb();" class="btn btn-primary">Generar código de barras</button>
                
                <button type="submit" id="imprimir"   class="btn btn-primary">Imprimir</button>
                </div>
            </div>   
            </form>         
        </div>
    </div>
</div>

<?php
    
  if(isset($_GET['data'])){
        
        $codigo = $_GET['data'];
        echo "<center><iframe src='codigoimprimir.php?codigo=".$codigo."' style='width:50%; height:50%; border: 0px solid black;'></iframe></center>";
    }

?>


<script>
    function cb() {
        var data = $("#data").val();
        var nombre = $("#nombre").val();
       // $("#data").val('');
        //$("#nombre").val('');
        $.post( "guardarImagen.php", { filepath: "codigosGenerados/"+data+".png", text:data, nombre:nombre }  )
            .done(function( data ) {
                Swal.fire({
                    icon: 'success',
                    title: 'Hecho!',
                    text: 'Se ha registrado con éxito el código!',
                    footer: ''
                })
                //$("#respuesta").html(data);
                //$("#imagen").html('<img src="codigosGenerados/'+text+'.png"/>');
            }
        );

        $("#imagen").html('<img src="barcode\\barcode.php?text='+data+'&size=90&codetype=Code39&print=true"/>');


     /*   $.ajax({
            url: 'guardarImagen.php',
            type: 'post',			
            data: {text:data, nombre:nombre},
            success: function(data){
                $("#imagen").html('<img src="barcode\\barcode.php?text='+data+'&size=90&codetype=Code39&print=true"/>');

            }
        });*/
      
    }

    

  </script>


<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
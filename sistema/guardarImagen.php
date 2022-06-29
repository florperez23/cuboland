<?php
include "../conexion.php";
include "barcode/barcode.php";
session_start();

$filepath = $_POST['filepath'];
$text = $_POST['text'];
$nombre = $_POST['nombre'];

//VERIFICAMOS QUE NO EXISTA YA EL CODIGO DE BARRAS EN TABLA CODIGOS DE BARRA
$sql = mysqli_query($conexion, "SELECT * FROM codigobarras WHERE codigo = $text");
$result = mysqli_num_rows($sql);
if ($result > 0) {

    echo '<div class="alert alert-primary" role="alert">
        ERROR: El código que intenta registrar, ya existe, favor de intentar con otro.
    </div>';

}else{

     //AHORA VERIFICAMOS TABLA PRODUCTO
    //VERIFICAMOS QUE NO EXISTA YA EL CODIGO DE BARRAS
    $sql1 = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = $text");
    $result1 = mysqli_num_rows($sql1);
    if ($result1 > 0) {
        echo '<div class="alert alert-primary" role="alert">
            ERROR: El código que intenta registrar, ya existe, favor de intentar con otro.
        </div>';
    }else{
        barcode( $filepath, $text,'70','horizontal','code128',true,1);
        $usuario_id = $_SESSION['idUser'];
        //guardar en la bd
        $sql =  "INSERT INTO codigobarras(codigo,producto, idusuario, fecha) values ('$text', '$nombre', '$usuario_id', now())";
        //echo $sql;
        $query_insert = mysqli_query($conexion, $sql);
        if ($query_insert) {
            historia('Se registro el nuevo código '.$text);

            echo '<div class="alert alert-primary" role="alert">
                                Código Registrado.
                            </div>';
        } else {
            historia('Error al intentar guardar el registro del el nuevo código '.$text);
            echo '<div class="alert alert-danger" role="alert">
                                Error al guardar el codigo generado, favor de intentarlo nuevamente.
                        </div>';
        }
    }
   
}

mysqli_close($conexion);
?>

<?php
include("../conexion.php");
include("includes/functions.php");
session_start();
//print_r($_POST);
if (!empty($_POST)) {
  // Extraer datos del producto
  if ($_POST['action'] == 'infoProducto') {
      $data = "";
    $producto_id = $_POST['producto'];
  

    if(ExiteUnaPromocion($producto_id)!=0)   
    {
      $datos=ExiteUnaPromocion($producto_id);

      //$newPrecio."|".$idclasificacion."|".$tipo."|".$promocion."|".$descripcion."|".$existencia."|".$precio."|".$codproducto;
                  $datospromo = explode("|", $datos);
                  $newPrecio=$datospromo[0]; 
                  $idclasificacion=$datospromo[1]; 
                  $tipo=$datospromo[2]; 
                  $promocion=$datospromo[3]; 
                  $descripcion=$datospromo[4]; 
                  $existencia=$datospromo[5]; 
                  $precio=$datospromo[6]; 
                  $codproducto=$datospromo[7]; 

      $data = [
        "newprecio" =>  $newPrecio,  
        "precio" =>  $precio,  
        "tipo" =>  $tipo,       
        "descuento" => $promocion,
        "descripcion" => $descripcion,
        "existencia" => $existencia,
        "codproducto" => $codproducto,
    ];
   
      echo json_encode($data,JSON_UNESCAPED_UNICODE);
    

    }else{
      $sql="SELECT codproducto, descripcion, precio, existencia, precio, as newprecio, FROM producto WHERE codproducto = '".$producto_id."'";
    

    $query = mysqli_query($conexion, );

    $result = mysqli_num_rows($query);
    if ($result > 0) {
      $data = mysqli_fetch_assoc($query);
      echo json_encode($data,JSON_UNESCAPED_UNICODE);
      exit;
    }else {
      $data = 0;
    }
  }

    
  }
  
// Eliminar Producto
  if ($_POST['action'] == 'delProduct') {
    if (empty($_POST['producto_id']) || !is_numeric($_POST['producto_id'])) {
      echo "error";
    }else {

    $idproducto = $_REQUEST['producto_id'];
    $query_delete = mysqli_query($conexion, "UPDATE producto SET estado = 0 WHERE codproducto = $idproducto");
    mysqli_close($conexion);

  }
 echo "error";
 exit;
}
// Buscar Cliente
if ($_POST['action'] == 'searchCliente') {
  if (!empty($_POST['cliente'])) {
    $dni = $_POST['cliente'];

    $query = mysqli_query($conexion, "SELECT * FROM cliente WHERE dni LIKE '$dni'");
    mysqli_close($conexion);
    $result = mysqli_num_rows($query);
    $data = '';
    if ($result > 0) {
      $data = mysqli_fetch_assoc($query);
    }else {
      $data = 0;
    }
    echo json_encode($data,JSON_UNESCAPED_UNICODE);
  }
  exit;
}
// registrar cliente = ventas
if ($_POST['action'] == 'addCliente') {

  $dni = $_POST['dni_cliente']; 
  $nombre = $_POST['nom_cliente'];
  $telefono = $_POST['tel_cliente'];
  $direccion = $_POST['dir_cliente'];
  $usuario_id = $_SESSION['idUser'];

  //$dni=nextDni();
  $query_insert = mysqli_query($conexion, "INSERT INTO cliente(dni, nombre, telefono, direccion, usuario_id, fecha) VALUES ('$dni','$nombre','$telefono','$direccion','$usuario_id', now())");
 
  if ($query_insert) {
    $codCliente = mysqli_insert_id($conexion);
    $msg = $codCliente;
   
  }else {
    $msg = 'error';
  }
  mysqli_close($conexion);
  echo $msg;
  exit;
}
// agregar producto a detalle temporal
if ($_POST['action'] == 'addProductoDetalle') {
  if (empty($_POST['producto']) || empty($_POST['cantidad'])){
    echo 'error';
  }else {    

    $codproducto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];
    $token = md5($_SESSION['idUser']);
    $query_iva = mysqli_query($conexion, "SELECT igv FROM configuracion");
    $result_iva = mysqli_num_rows($query_iva); 
    $sql="CALL add_detalle_temp ('$codproducto',$cantidad,'$token')";
   //echo $sql;
    $query_detalle_temp = mysqli_query($conexion, $sql);
    $result = mysqli_num_rows($query_detalle_temp);
    $detalleTabla = '';
    $sub_total = 0;
    $iva = 0;
    $total = 0;
    $arrayData = array();
    if ($result > 0) {
        
    if ($result_iva > 0) {
      $info_iva = mysqli_fetch_assoc($query_iva);
      $iva = $info_iva['igv'];
    }
    while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
      $precioTotal = round($data['cantidad'] * $data['precio_promocion'], 2);
      $sub_total = round($sub_total + $precioTotal, 2);
      $total = round($total + $precioTotal, 2);

      $detalleTabla .= '<tr>
      <td>'.$data['codproducto'].'</td>
      <td colspan="2">'.$data['descripcion'].'</td>
      <td class="textcenter">'.$data['cantidad'].'</td>
      <td class="textright">'.$data['precio'].'</td>';
      if($data['idtipopromocion']==1)
      {
      $detalleTabla .= '<td class="textright">'.$data['promocion'].'%</td>';
      }else{
        $detalleTabla .= '<td class="textright">'.$data['promocion'].'</td>';
      
      }
      $detalleTabla .='<td class="textright">'.number_format($precioTotal, 2, '.', ',').'</td>
      <td>
          <a href="#" class="link_delete" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="fas fa-trash-alt"></i> Eliminar</a>
      </td>
  </tr>';
    }
    $total = round($sub_total, 2);
    $detalleTotales ='<tr>
        <td colspan="5" class="textright">Total S/.</td>
        <td class="textright">'.number_format($total, 2, '.', ',').'</td>
    </tr>';
  
    $arrayData['detalle'] = $detalleTabla;
    $arrayData['totales'] = $detalleTotales;
    $arrayData['totalmodal'] = number_format($total, 2, '.', ',');
    echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
  }else {
    echo 'error';
  }
  mysqli_close($conexion);

  }
  exit;
}
// extrae datos del detalle tem
if ($_POST['action'] == 'searchForDetalle') {

  if (empty($_POST['user'])){
    echo 'error';
  }else {
    $token = md5($_SESSION['idUser']);
$sql="SELECT tmp.correlativo, tmp.token_user,
sum(tmp.cantidad) as cantidad, tmp.precio_venta, p.codproducto, p.descripcion,p.precio,
tmp.precio_promocion,tmp.promocion,tmp.idtipopromocion
FROM detalle_temp tmp INNER JOIN producto p ON tmp.codproducto = p.codproducto
where token_user = '$token' 		GROUP BY tmp.codproducto";
//echo $sql;
    $query = mysqli_query($conexion, $sql);      
      $result = mysqli_num_rows($query);

    $query_iva = mysqli_query($conexion, "SELECT igv FROM configuracion");
    $result_iva = mysqli_num_rows($query_iva);
    $detalleTabla = '';
    $sub_total = 0;
    $iva = 0;
    $total = 0;
    $data = "";
    $arrayDatadata = array();
    if ($result > 0) {
    if ($result_iva > 0) {
      $info_iva = mysqli_fetch_assoc($query_iva);
      $iva = $info_iva['igv'];
    }
    while ($data = mysqli_fetch_assoc($query)) {
      $precioTotal = round($data['cantidad'] * $data['precio_promocion'], 2);
      $sub_total = round($sub_total + $precioTotal, 2);
      $total = round($total + $precioTotal, 2);
     
        $detalleTabla .= '<tr>
            <td>'.$data['codproducto'].'</td>
            <td colspan="2">'.$data['descripcion'].'</td>
            <td class="textcenter">'.$data['cantidad'].'</td>
            <td class="textright">'.$data['precio'].'</td>';
            if($data['idtipopromocion']==1)
            {
            $detalleTabla .= '<td class="textright">'.$data['promocion'].'%</td>';
            }else{
              $detalleTabla .= '<td class="textright">'.$data['promocion'].'</td>';
            
            }
            $detalleTabla .='<td class="textright">'.number_format($precioTotal, 2, '.', ',').'</td>
            <td>
                <a href="#" class="link_delete" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="fas fa-trash-alt"></i> Eliminar</a>
            </td>
        </tr>';
    }
    $total = round($sub_total, 2);
    $detalleTotales = '<tr>
        <td colspan="5" class="textright">Total S/.</td>
        <td class="textright">'.number_format($total, 2, '.', ',').'</td>
    </tr>';

    $arrayData['detalle'] = $detalleTabla;
    $arrayData['totales'] = $detalleTotales;
    $arrayData['totalmodal'] = number_format($total, 2, '.', ',');
    echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
    exit;
  }else {
    $data = 0;
    exit;
  }
  mysqli_close($conexion);

  }
  exit;
}


// extrae datos del detalle temp
if ($_POST['action'] == 'searchClienteCredito') {
  $detalleTabla="";
  if (empty($_POST['cliente'])){
    echo 'error';
    // code...
  }else
  {
    $idcliente = $_POST['cliente'];  
     $sql="SELECT numcredito, creditos.fecha,totalventa as total,totalventa-(select SUM(totalfactura) from factura where numcredito=creditos.numcredito GROUP BY NUMCREDITO) AS  adeudo,fechavencimiento,estado,nombre 
     FROM creditos inner join cliente on cliente.idcliente=creditos.idcliente 
     WHERE creditos.estado=1 and  cliente.idcliente = '$idcliente'" ;
    // echo $sql;
    $query = mysqli_query($conexion, $sql);
    $result = mysqli_num_rows($query);
     if ($result > 0) {
  // $detalleTabla .= '<span>'.$id_detalle.'</span>';
   $detalleTabla .= ' <div class="col-lg-12"> <div class="form-group">
   <h4 class="text-center">Creditos Activos</h4> 
   </div>
   <div class="card">
   <div class="card-body">
   <div class="table-responsive">
				<table class="table table-striped table-bordered" id="table">
					<thead class="thead-dark">
						<tr>
							  <th>Id</th>
							  <th>Fecha</th>               
							  <th>Total</th>
                <th>Adeudo</th>
                <th>Fecha Vencimiento</th>
                <th>Estatus</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>';	  
   while ($data = mysqli_fetch_assoc($query)) {    
      $detalleTabla .= '<tr>
          <td class="textcenter">'.$data['numcredito'].'</td>
          <td class="textcenter">'.date_format( date_create($data['fecha']), 'd/m/Y').'</td>
          <td class="textcenter">'.$data['total'].'</td>
          <td class="textcenter">'.$data['adeudo'].'</td>
          <td class="textright">'.date_format( date_create($data['fechavencimiento']), 'd/m/Y').'</td>';
           if( $data['estado'] =='1')
          {
            $detalleTabla .= '<td><span class="badge bg-success" style="color:white;">Activo</span></td>';
          }else{
            $detalleTabla .='<td><span class="badge bg-danger" style="color:white;">Liquidada</span></td>';
          }
          $detalleTabla .='<td>
          <a href="#" class="btn btn-primary" id="abonar" class="btn btn-primary" data-toggle="modal" onclick="abrirModalAbono('.$data['numcredito'].','.$data['total'].','.$data['adeudo'].');"><i class="fa fa-credit-card"></i> Abonar</a>
          </td>
      </tr>';
 
 
  }
  $detalleTabla .= '</tbody>
  </table>
  </div>                       
   </div>
   </div> 
    </div>';
 
}

 
}

$arrayData['detalle'] = $detalleTabla;


echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
    

  
  exit;
}

// extrae datos del detalle temp
if ($_POST['action'] == 'delProductoDetalle') {
  if (empty($_POST['id_detalle'])){
    echo 'error';
    // code...
  }else {
    $id_detalle = $_POST['id_detalle'];
    $token = md5($_SESSION['idUser']);


    $query_iva = mysqli_query($conexion, "SELECT igv FROM configuracion");
    $result_iva = mysqli_num_rows($query_iva);

    $query_detalle_tmp = mysqli_query($conexion, "CALL del_detalle_temp($id_detalle,'$token')");
    $result = mysqli_num_rows($query_detalle_tmp);

    $detalleTabla = '';
    $sub_total = 0;
    $iva = 0;
    $total = 0;
      $data = "";
    $arrayDatadata = array();
    if ($result > 0) {
    if ($result_iva > 0) {
      $info_iva = mysqli_fetch_assoc($query_iva);
      $iva = $info_iva['igv'];
    }
    while ($data = mysqli_fetch_assoc($query_detalle_tmp)) {
      $precioTotal = round($data['cantidad'] * $data['precio'], 2);
      $sub_total = round($sub_total + $precioTotal, 2);
      $total = round($total + $precioTotal, 2);

        $detalleTabla .= '<tr>
            <td>'.$data['codproducto'].'</td>
            <td colspan="2">'.$data['descripcion'].'</td>
            <td class="textcenter">'.$data['cantidad'].'</td>
            <td class="textright">'.$data['precio'].'</td>';
            if($data['idtipopromocion']==1)
            {
            $detalleTabla .= '<td class="textright">'.$data['promocion'].'%</td>';
            }else{
              $detalleTabla .= '<td class="textright">'.$data['promocion'].'</td>';
            
            }
            $detalleTabla .='<td class="textright">'.number_format($precioTotal, 2, '.', ',').'</td>
            <td>
                <a href="#" class="link_delete" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="fas fa-trash-alt"></i> Eliminar</a>
            </td>
        </tr>';
    
    }
    $impuesto = round($sub_total / $iva, 2);
    $tl_sniva = round($sub_total - $impuesto, 2);
    $total = round($tl_sniva + $impuesto, 2);

    $detalleTotales = '<tr>
        <td colspan="5" class="textright">Total S/.</td>
        <td class="textright">'.number_format($total, 2, '.', ',').'</td>
    </tr>';

    $arrayData['detalle'] = $detalleTabla;
    $arrayData['totales'] = $detalleTotales;
    $arrayData['totalmodal'] = number_format($total, 2, '.', ',');

    echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
  }else {
    $data = 0;
  }
  mysqli_close($conexion);

  }
  exit;
}
// Anular Ventas
if ($_POST['action'] == 'anularVenta') {
    $data = "";
  $token = md5($_SESSION['idUser']);
  $query_del = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE token_user = '$token'");
  mysqli_close($conexion);
  if ($query_del) {
    echo 'ok';
  }else {
    $data = 0;
  }
  exit;
}
//procesarVenta
if ($_POST['action'] == 'procesarVenta') {
  if (empty($_POST['codcliente'])) {
    $codcliente = 1;
  }else{

    $codcliente = $_POST['codcliente'];
    $token = md5($_SESSION['idUser']);
    $usuario = $_SESSION['idUser'];
    $tipoventa = $_POST['tipoventa'];
    $fechaven = $_POST['fechaven'];
    $numcredito=$_POST['numcredito'];
    $tipopago = $_POST['tipopago'];
    
    

    if(isset($_POST['pago'])){
      $pagocon = $_POST['pago'];
    }else{
      $pagocon = "";
    }

    // if(isset($_POST['total'])){
    //   $total= $_POST['total'];
    // }else{
    //   $total= "";
    // }
    
    
    if(isset($_POST['referencia'])){
      $referencia = $_POST['referencia'];
    }else{
      $referencia = "";
    }
    
    $query = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE token_user = '$token' ");
    $result = mysqli_num_rows($query);
    /*EVALUAMOS  SI EXISTE UN NUMERO DE CREDITO QUIERE DECIR QUE ES UN ABONO*/
    if($numcredito> 0)
    {
      $result=1;
    }
  }
  /*QUITAMOS EL SIGNO DE PESOS*/
  $pagocon = str_replace("$", "", $pagocon);
  $pagocon = str_replace(",", "", $pagocon);
    $newDate = date("Y/m/d", strtotime($fechaven));

  if ($result > 0) {
  $sql="CALL procesar_venta($usuario,$codcliente,'$token',$tipoventa,'$pagocon','$newDate',$tipopago,'$referencia','$numcredito')";
    //echo $sql;    
  $query_procesar = mysqli_query($conexion, $sql,);
    $result_detalle = mysqli_num_rows($query_procesar);
    if ($result_detalle > 0) {    
      $data = mysqli_fetch_assoc($query_procesar);
      //historia('Se registro una venta por $'.$data['totalventa'].' y con numero de factura '.$data['nofactura'].'');
      echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }else {
      echo "error2";
    }
  }else {
    echo "error1";
  }
  mysqli_close($conexion);
  exit;
}

  //procesarGuia
  if ($_POST['action'] == 'procesarGuia') {
    if (empty($_POST['codcliente'])) {
      $codcliente = 1;
    } else {
      $codcliente = $_POST['codcliente'];

      $token = md5($_SESSION['idUser']);
      $usuario = $_SESSION['idUser'];
      $query = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE token_user = '$token' ");
      $result = mysqli_num_rows($query);
    }

    if ($result > 0) {
      $query_procesar = mysqli_query($conexion, "CALL procesar_guia($usuario,$codcliente,'$token')");
      $result_detalle = mysqli_num_rows($query_procesar);
      if ($result_detalle > 0) {
        $data = mysqli_fetch_assoc($query_procesar);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
      } else {
        echo "error";
      }
    } else {
      echo "error";
    }
    mysqli_close($conexion);
    exit;
  }
  //procesarBoleta
  if ($_POST['action'] == 'procesarBoleta') {
    if (empty($_POST['codcliente'])) {
      $codcliente = 1;
    } else {
      $codcliente = $_POST['codcliente'];

      $token = md5($_SESSION['idUser']);
      $usuario = $_SESSION['idUser'];
      $query = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE token_user = '$token' ");
      $result = mysqli_num_rows($query);
    }

    if ($result > 0) {
      $query_procesar = mysqli_query($conexion, "($usuario,$codcliente,'$token')");
      $result_detalle = mysqli_num_rows($query_procesar);
      if ($result_detalle > 0) {
        $data = mysqli_fetch_assoc($query_procesar);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
      } else {
        echo "error";
      }
    } else {
      echo "error";
    }
    mysqli_close($conexion);
    exit;
  }

// Info factura
  if ($_POST['action'] == 'infoFactura') {
  if (!empty($_POST['nofactura'])) {
    $nofactura = $_POST['nofactura'];
    $query = mysqli_query($conexion, "SELECT * FROM factura WHERE nofactura = '$nofactura' AND estado = 1");
    mysqli_close($conexion);
    $result = mysqli_num_rows($query);
    if ($result > 0) {
      $data = mysqli_fetch_assoc($query);
      historia('Se consulto la informacion de la factura '.$nofactura);
      echo json_encode($data,JSON_UNESCAPED_UNICODE);
      exit;
    }
  }
  echo "error";
  exit;
  }


  // anular factura
  if ($_POST['action'] == 'anularFactura') {
    if (!empty($_POST['noFactura'])) {
        $data = "";
      $noFactura = $_POST['noFactura'];
      $query_anular = mysqli_query($conexion, "CALL anular_factura($noFactura)");
      mysqli_close($conexion);
      $result = mysqli_num_rows($query_anular);
      if ($result > 0) {
        $data = mysqli_fetch_assoc($query_anular);
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        exit;
      }
    }
    $data = 0;
    exit;
    }
    // Cambiar contraseña
    if ($_POST['action'] == 'changePasword') {
      if (!empty($_POST['passActual']) && !empty($_POST['passNuevo'])) {
        $password = md5($_POST['passActual']);
        $newPass = md5($_POST['passNuevo']);
        $idUser = $_SESSION['idUser'];
        $code = '';
        $msg = '';
        $arrayData = array();
        $query_user = mysqli_query($conexion, "SELECT * FROM usuario WHERE clave = '$password' AND idusuario = $idUser");
        $result = mysqli_num_rows($query_user);
        if ($result > 0) {
          $query_update = mysqli_query($conexion, "UPDATE usuario SET clave = '$newPass' where idusuario = $idUser");
          mysqli_close($conexion);
          if ($query_update) {
            $code = '00';
            $msg = "su contraseña se ha actualizado con exito";
            historia('Se actualizo la contraseña del usuario '.$idUser);
            header("Refresh:1; URL=salir.php");
          }else {
            $code = '2';
            historia('Error al actualizar la contraseña del usuario '.$idUser);
            $msg = "No es posible actualizar su contraseña";
          }
        }else {
          $code = '1';
          $msg = "La contraseña actual es incorrecta";
        }
        $arrayData = array('cod' => $code, 'msg' => $msg);
        echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
      }else {
        echo "error";
      }
      exit;
      }



}


// Guardar inicio de corte de caja
if ($_POST['action'] == 'guardarCorte') {
  $montoinicial = $_POST['montoinicial'];
  $usuario_id = $_SESSION['idUser'];
   
  $query_del = mysqli_query($conexion, "INSERT INTO cortecaja(MontoInicial,FechaApertura,Estado, Usuario) values ('$montoinicial', now(), '0', '$usuario_id')");
  mysqli_close($conexion);
  if ($query_del) {
    historia('Se creo un nuevo corte de caja');
    echo 'ok';
  }else {
    historia('Error al crear el nuevo corte de caja');
    $data = 0;
  }

  exit;
}


// cerrar corte de caja
if ($_POST['action'] == 'cerrarCorte') {
  $id = $_POST['idcorte'];
  $montoinicial = $_POST['montoinicial'];
  $montofinal = $_POST['montofinal'];
  $totalventas = $_POST['totalventas'];
  $montototal = $_POST['montogral'];
  //VERIFICAMOS PRIMERO QUE haya ventas

  if($montofinal == '' or $montofinal == 0){
    echo '1';
    historia('Se intento cerrar caja sin ventas');
  }else{
    $sql = "UPDATE cortecaja SET MontoFinal = ".$montofinal.", FechaCierre = now(), TotalVentas = ".$totalventas.", MontoTotal = ".$montototal.", Estado = 1 WHERE Id = ".$id."";
    //echo $sql;
    
    $query_del = mysqli_query($conexion, $sql);
    mysqli_close($conexion);
    if ($query_del) {
      historia('Se hizo un cierre de corte de caja');
      echo 'ok';
    }else {
      historia('Error al intentar hacer el cierre de corte de caja');
      $data = 0;
    }
  }
  exit;
}






if ($_POST['action'] == 'guardarAjuste') {  
  $cod_pro = $_POST['cod_pro'];
  $name = $_POST['name'];
  $cantidad = $_POST['cantidad'];
  $agregar = $_POST['agregar'];
  $usuario_id = $_SESSION['idUser'];
  //BUSCAR SI YA EXISTE EN AJUSTE DEL DIA DE HOY
  $id = existeEnAjusteelProducto($cod_pro);

  if($id == 0){
    //SI NO EXISTE REGISTRO DE HOY, ES NUEVO
     //SI ES MENOR A CERO SE VA A QUITAR DEL STOCK
    if($agregar < 0){
      //echo 'entro a insert restando salidas';
      $existencia  = $cantidad - abs($agregar);
      $sql="INSERT INTO ajuste_inventario(codproducto, descripcion, fecha, salidas, usuario) VALUES ('$cod_pro','$name', now(), '$agregar', '$usuario_id')";
      //echo $sql;
        $query = mysqli_query($conexion, $sql);
        if ($query) {
          echo actualizarExistenciasenProducto($cod_pro, $existencia);
          echo 'ok';
          historia('Se hizo un ajuste de inventario del producto '.$cod_pro);
        }else {
          historia('Error al actualizar el inventario en la primer entrada del dia del producto '.$cod_pro);
          $data = 0;

        }
        
        
    }else{
      //echo 'entro a insert sumando entradas';
      $existencia  = $cantidad + ($agregar);
      $sql="INSERT INTO ajuste_inventario(codproducto, descripcion, fecha, entradas, usuario) VALUES ('$cod_pro','$name',now(), '$agregar', '$usuario_id')";
      //echo $sql;
  
      $query = mysqli_query($conexion, $sql);
      if ($query) {
        echo actualizarExistenciasenProducto($cod_pro, $existencia);
        echo 'ok';
        historia('Se hizo un ajuste de inventario del producto '.$cod_pro);
      }else {
        historia('Error al actualizar el inventario en la primer entrada del dia del producto '.$cod_pro);
        $data = 0;
      }
    }

  }else{
    //SI TENEMOS REGISTRO DE HOY, SOLO ACTUALIZAMOS LOS NUMEROS
    //SI ES MENOR A CERO SE VA A QUITAR DEL STOCK
    $entradasAnt = entradasQueTenia($id);
    $salidasAnt = salidasQueTenia($id);
   
    if($agregar < 0){
      //echo 'entro a actualizar restando salidas';
      $entradas = $entradasAnt;
      $salidas  = $salidasAnt + ($agregar);
      $existencia  = $cantidad - abs($agregar);
      $sql="UPDATE ajuste_inventario SET entradas = '$entradas', salidas = '$salidas' WHERE id = '$id'";
      //echo $sql;
      $query = mysqli_query($conexion, $sql);
      if ($query) {
        echo actualizarExistenciasenProducto($cod_pro, $existencia);
        historia('Se actualizo el ajuste de inventario del producto '.$cod_pro);
        echo 'ok';
      }else {
        historia('Error al actualizar el inventario del producto '.$cod_pro);
        $data = 0;
      }
    }else{
      //echo 'entro a actualizar sumando entradas';
      $entradas = $entradasAnt + ($agregar);
      $salidas  = $salidasAnt;
      $existencia  = $cantidad + ($agregar);
      $sql="UPDATE ajuste_inventario SET entradas ='$entradas', salidas = '$salidas' WHERE id = '$id'";
      //echo $sql;
      $query = mysqli_query($conexion, $sql);
      if ($query) {
        echo actualizarExistenciasenProducto($cod_pro, $existencia);
        historia('Se actualizo el ajuste de inventario del producto '.$cod_pro);
        echo 'ok';
      }else {
        historia('Error al actualizar el inventario del producto '.$cod_pro);
        $data = 0;
      }
    }

  } 
   mysqli_close($conexion);

  exit;
}

 
// VALIDAR QUE NO SE REBASE EL STOCK
if ($_POST['action'] == 'productoDetalleValida') {
  $data = "";
  if (empty($_POST['producto']) ){
        echo 'error';
        }else { 
          $codproducto = $_POST['producto'];
       $token = md5($_SESSION['idUser']);
      $sql="select sum(cantidad) as cantidad   from detalle_temp where codproducto='".$codproducto."'";
    echo $sql;
     $query = mysqli_query($conexion, $sql);
     mysqli_close($conexion);
     $result = mysqli_num_rows($query);
     if ($result > 0) {
       $data = mysqli_fetch_assoc($query);     
      echo $data["cantidad"];
       exit;
     }else{
       $data = "";
     }
}
exit;
}


// Next IdCliente
if ($_POST['action'] == 'nextIdcliente') {
  
	$sql = "select Max(idcliente)+1 as idcliente from cliente order by idcliente desc";
  //echo $sql;
    $query = mysqli_query($conexion, $sql);
    mysqli_close($conexion);
    $result = mysqli_num_rows($query);
    if ($result > 0) {
      $data = mysqli_fetch_assoc($query);     
     echo $data["idcliente"];
      exit;
    }else{
      $data = "1";
    }
 
  exit;
}




// Siguiente numero del producto
if ($_POST['action'] == 'signumero') {
  $codcubo = $_POST['idcubo'];
	$sql = "select if(ISNULL(max(numsiguiente)), 0, max(numsiguiente)) + 1 as signum from producto where codcubo = '$codcubo' ";
 // echo $sql;
    $query = mysqli_query($conexion, $sql);
    mysqli_close($conexion);
    $result = mysqli_num_rows($query);
    if ($result > 0) {
      $data = mysqli_fetch_assoc($query);     
     echo $data["signum"];
      exit;
    }else{
      $data = "0";
    }
 
  exit;
}
 ?>
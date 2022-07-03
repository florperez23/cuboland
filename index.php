<?php
$alert = '';
session_start();
if (!empty($_SESSION['active'])) {
  header('location: sistema/');
} else {
  if (!empty($_POST)) {
    if (empty($_POST['usuario']) || empty($_POST['clave'])) {
      $alert = '<div class="alert alert-danger" role="alert">
  Ingrese su usuario y su clave
</div>';
    } else {
      require_once "conexion.php";
      $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
      $clave = md5(mysqli_real_escape_string($conexion, $_POST['clave']));
      $query = mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.correo,u.usuario,r.idrol,r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE u.usuario = '$user' AND u.clave = '$clave'");
      mysqli_close($conexion);
      $resultado = mysqli_num_rows($query);
      if ($resultado > 0) {
        $dato = mysqli_fetch_array($query);
        $_SESSION['active'] = true;
        $_SESSION['idUser'] = $dato['idusuario'];
        $_SESSION['nombre'] = $dato['nombre'];
        $_SESSION['email'] = $dato['correo'];
        $_SESSION['user'] = $dato['usuario'];
        $_SESSION['rol'] = $dato['idrol'];
        $_SESSION['rol_name'] = $dato['rol'];
        $user = $dato['idusuario'];
        global $user;
        header('location: sistema/');
      } else {
        $alert = '<div class="alert alert-danger" role="alert">
              Usuario o Contraseña Incorrecta
            </div>';
        session_destroy();
      } 

   /*   require_once "conexion.php";
      $username = $_POST['usuario'];
      $nip =md5( $_POST['clave']);
  
     echo 'user'.$username;
     echo 'NIp'.$nip;
  
      $sql="SELECT * FROM usuario   WHERE usuario='".$username."' and clave='".$nip."'";
     echo $sql;
     $r = $conexion -> query($sql); 
     if($f = $r -> fetch_array())	
      {
          if ($f['clave']== $nip){
              session_start();
              $_user = $f['usuario'];	
              global  $_user;
             		
              header('location: sistema/');
            
              $_SESSION['active'] = true;
              $_SESSION['idUser'] = $dato['idusuario'];
              $_SESSION['nombre'] = $dato['nombre'];
              $_SESSION['email'] = $dato['correo'];
              $_SESSION['user'] = $dato['usuario'];
              $_SESSION['rol'] = $dato['idrol'];
              $_SESSION['rol_name'] = $dato['rol'];
              
          }
          else {
            $alert = '<div class="alert alert-danger" role="alert">
            Usuario o Contraseña Incorrecta
          </div>';
      session_destroy();
          }
      }
      else {
      echo 'usuario incorrecto';
  */
  
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>CUBOLAND</title>

  <!-- Custom fonts for this template-->
  <link rel="stylesheet" href="sistema/vendor/bootstrap/css/bootstrap.min.css">
  <!-- Custom styles for this template-->
  <link href="sistema/css/style.violet.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image" style="background: white;">
                <img src="sistema/img/imagen1.png" class="img-thumbnail" style="position: absolute;  top: 0%;  left: 0%; height:100%; width: 100%;">
              </div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-white mb-4">Iniciar Sesión</h1>
                  </div>
                  <form class="user" method="POST">
                    <?php echo isset($alert) ? $alert : ""; ?>
                    <div class="form-group">
                      <label for="">Usuario</label>
                      <input type="text" class="form-control" placeholder="Usuario" name="usuario"></div>
                    <div class="form-group">
                      <label for="">Contraseña</label>
                      <input type="password" class="form-control" placeholder="Contraseña" name="clave">
                    </div>
                    <input type="submit" value="Iniciar" class="btn btn-primary">
                    <hr>
                  </form>
                  <hr>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- JavaScript files-->
    <script src="sistema/vendor/jquery/jquery.min.js"></script>
    <script src="sistema/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="sistema/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="sistema/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="sistema/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="sistema/js/front.js"></script>


</body>

</html>

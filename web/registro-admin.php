<!DOCTYPE html>
<html lang="es">
	<head>
    <meta charset="utf-8">
		<link rel="stylesheet" href="recursos/css/main.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="vi/ewport" content="width=device-width, initial-scale=1">
    <title>Registro Proveedores</title>
  </head>

	<body background="imagenes/fondo-web.png" class="img">
    <section>

      <form class="formulario-registro" action="#" method="post" enctype="multipart/form-data"> 
        <p>Nombre de usuario   </p><input name="nombre_proveedor"    type="text"     class="botones-registro"  placeholder="Ingrese nombre"      minlength="3" required>
        <p>Correo electronico   </p><input name="correo_proveedor"    type="email"    class="botones-registro"  placeholder="Ingrese correo electronico"  minlength="7" required>
        <p>Contraseña           </p><input name="clave_proveedor"     type="password" class="botones-registro"  placeholder="Ingrese contraseña"  minlength="8" required>

        <!-- <br><br>Categoria de comida que vendera
          <select class="botones-registro" name="Categoria" required>
            <option disabled>Elija alguna opcion</option>                                
            <option>Chatarra        </option>                              
            <option>Vegetariana     </option>
            <option>Asiatica        </option>
            <option>Tradicional     </option>
          </select>             -->
        <br>
        <input type="submit" name="datos_proveedor" value="Registrar" class="iniciosesion">
        <p><a href="iniciosesion-cliente.php">¿Ya tengo cuenta?</a></p>
      </form>

    </section>

    <?php
      include '../bd/conexion.php';
      $conexion=mysqli_connect('localhost','root','','app_msg') or die ('problemas en la conexion');
      if(isset($_POST['datos_proveedor'])){
        $id_rol = '2';
        $nombre_proveedor = $_POST['nombre_proveedor'];
        $correo_proveedor = $_POST['correo_proveedor'];
        $clave_proveedor = $_POST['clave_proveedor'];
        $clave_proveedor = password_hash($clave_proveedor, PASSWORD_DEFAULT);
        $insertar = "INSERT INTO Usuarios (Nombre_Usuario,Correo_Usuario,Clave_Usuario,id_rol)
        values ('$nombre_proveedor','$correo_proveedor','$clave_proveedor','$id_rol')";
        $ejecutar = mysqli_query($conexion,$insertar);

        if ($ejecutar){
          echo "<script>
                  alert ('registrado correctamente');
                    
                </script> ";
        }else{
            echo "no se pudo";
        }
      }
    ?>
  </body>
</html>
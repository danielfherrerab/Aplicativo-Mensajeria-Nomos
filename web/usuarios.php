<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jquery-3.0.0.min.js"></script>
    <title>Usuarios</title>
  </head>
  <body>
    <?php
      include_once '../bd/conexion.php';
      session_start();
      if(!isset($_SESSION['Id_rol'])){
        header('location: ../index.php');
      }
      else{
        if($_SESSION['Id_rol'] !=1){
          header('location: ../index.php');
        }
      }
      $Rol = $_SESSION['Id_rol'];
      $id_usuario = $_SESSION['Id_usuario'];
      $nombre_usuario = $_SESSION['Nombre_usuario'];

			date_default_timezone_set("America/Bogota");
			setlocale(LC_ALL,"es_ES");
      $fecha_inicio   = date('Y-m-d 00:00:00');
			$fecha_hoy      = date("Y-m-d H:i:s");
      $fecha_despues 	= date("Y-m-d 23:59:59",strtotime($fecha_hoy."+ 1 day,"));
    ?>

<div class="contenedor_mayor">
    <div class="nav_superior">
        <div class="hamburger">
            <div class="one"></div>
            <div class="two"></div>
            <div class="three"></div>
        </div>
        <div class="top_menu">
            <div class="logo"><?php echo $nombre_usuario ?></div>
            <ul>
                <li>
                    <a href="../index.php?close_login=<?php echo $id_usuario; ?>"><i class="fas"><img src="../assets/images/exit.png" width="27px" style="margin-top: 3px;"></i></a>
                </li>
            </ul>
        </div>
    </div>

    <div class="nav_lateral">
        <ul>
            <li>
                <a href="../index.php">
                    <span class="icon"><img src="../assets/images/home.png" alt="" width="20px"></span>
                    <span class="title">Inicio</span>
                </a>
            </li>
            <li>
                <a href="all_encargos.php">
                    <span class="icon"><img src="../assets/images/calendar.png" alt="" width="20px"></span>
                    <span class="title">Modi. Encargos</span>
                </a>
            </li>
            <li>
                <a href="usuarios.php" class="active">
                    <span class="icon"><img src="../assets/images/users.png" alt="" width="20px"></span>
                    <span class="title">Usuarios</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="users_container">
        <aside class="lista_usuarios">
          <div class="encabezado_lista">
            <h2>Usuarios</h2>
          </div>
          <div class="contenido_lista">
          <table class="tabla_encargos" border="0" cellspacing="10px">
              <thead>
                <tr>
                  <th>Nombre de usuario</th>
                  <th>Correo de usuario</th>
                  <th>Tipo de usuario</th>
                  <th>Accion</th>
                  <th>Mensajero asignado</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $encargos = mysqli_query($conexion,"SELECT * from usuarios inner join roles on roles.id_rol = usuarios.id_rol order by rol asc");
                  if(mysqli_num_rows($encargos) > 0){
                    while($listar = mysqli_fetch_array($encargos)){
                      $id_user = $listar[0];
                      $nombre = $listar[1];
                      $correo = $listar[2];
                      $rol = $listar[6];
                ?>
                <tr>
                  <input type="hidden" id="usuario<?php echo $id_user; ?>" value="<?php echo $id_user; ?>">
                  <td><?php echo $nombre; ?> <input type="hidden" id="nombre<?php echo $id_user; ?>" value="<?php echo $nombre; ?>"></td>
                  <td><?php echo $correo; ?> <input type="hidden" id="correo<?php echo $id_user; ?>" value="<?php echo $correo; ?>"> </td>
                  <td><?php echo $rol; ?><input type="hidden" id="rol<?php echo $id_user; ?>" value="<?php echo $rol; ?>"> </td>
                  <td><input type="button" id="<?php echo $id_user; ?>" onclick="modificar(this.id)" class="inc_regis" value="Modificar"></td>
                  <td><?php 
                    $msg_enc = mysqli_query($conexion,"SELECT * from msg_enc where encargado = $id_user");
                    $mensajero = "";
                    $i = 1;
                    while($asignado = mysqli_fetch_array($msg_enc)){
                        if($i > 1){
                          $mensajero .= " , ".$asignado[1];
                        }
                        else{
                          $mensajero .= $asignado[1];
                        }
                        $i++;
                    }
                    echo $mensajero;
                  ?></td>
                </tr>
                <?php
                    
                  }
                  }else{
                     ?>
                     <tr aria-colspan="3">
                      <td colspan="3">No se enontro nada</td>
                     </tr>
                     <?php
                  }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <td>
                    <input type="button" value="Asignar mensajero" onclick="mostrarAsignacion()">
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </aside>
        <aside class="agregar_usuario">
          <div class="encabezado_lista">
            <h2>Agregar usuario</h2>
          </div>
          <div class="contenido_lista">
            <form action="" method="post">
              <fieldset>
                <legend>Escriba el nombre del usuario</legend>
                <input type="text" name="nuevo_nombre" required>
              </fieldset>
              <fieldset>
                <legend>Escriba el correo del usuario</legend>
                <input type="text" name="nuevo_correo" required>
              </fieldset>
              <fieldset>
                <legend>Digite la contraseña</legend>
                  <input type="password" name="nueva_contraseña" required>
              </fieldset>
              <fieldset>
                <legend>Repita la contraseña</legend>
                  <input type="password" name="precontraseña" required>
              </fieldset>
              <fieldset>
                <legend>Escoja un rol para el usuario</legend>
                <select name="nuevo_rol" required>
                  <option value="" selected>Elija una opcion</option>
                  <option value="2">Encargado</option>
                  <option value="3">Mensajero</option>
                </select>
              </fieldset>
              <div class="btn_registrarEnc">
                <input type="submit" value="AÑADIR USUARIO" name="agregar_usuario">
              </div>
            </form>
          </div>
        </aside>
        <aside class="modificar_usuarios">
          <div class="encabezado_lista">
            <h2>Modificar usuario</h2>
            <button class="close" onclick="quitarmodificar()">X</button>
          </div>
          <div class="contenido_lista">
            <form action="" method="post">
              <fieldset>
              <input type="hidden" name="modi_id" value="">
                <legend>Nombre del usuario</legend>
                <input type="text" name="modi_nombre" required value="">
              </fieldset>
              <fieldset>
                <legend>Correo del usuario</legend>
                <textarea name="modi_correo" required value=""></textarea>
              </fieldset>
              <fieldset>
                <legend>Contraseña</legend>
                  <input type="password" name="modi_contraseña" required value="" placeholder="Ingrese nueva contraseña">
              </fieldset>
              <fieldset>
                <legend>Escoja un rol para el usuario</legend>
                
                <select name="modi_rol" required>
                  <option value="" disabled>Elija una opcion</option>
                  <option value="encargado" id="rol_Encargado">Encargado</option>
                  <option value="mensajero" id="rol_Mensajero" >Mensajero</option>
                  <option value="administrador" id="rol_Administrador" >Administrador</option>
                </select>
              </fieldset>
              <div class="btn_registrarEnc">
                <input type="submit" value="MODIFICAR USUARIO" name="modificar_usuario">
              </div>
            </form>
          </div>
        </aside>
        <aside class="asignar_mensajero">
          <div class="encabezado_lista">
            <h2>Asignar mensajero</h2>
          </div>
          <div class="contenido_lista">
            <form action="" method="post">
              <fieldset>
                <legend>Seleccione a quien le asignara</legend>
                <select name="encargado" required>
                  <option value="" disabled selected>Seleccione uno</option>
                  <?php 
                  $consulta = mysqli_query($conexion,"SELECT * from usuarios where id_rol != 3 ");
                  while($fila = mysqli_fetch_array($consulta)){
                    echo "<option value='$fila[0]'>$fila[1]</option>";
                  }
                  ?>
                  </select>
              </fieldset>
              <fieldset>
                <legend>Seleccione a un mensajero</legend>
                <select name="mensajero_asignado" required>
                  <option value="" disabled selected>Seleccione uno</option>
                  <?php 
                  $consulta = mysqli_query($conexion,"SELECT * from usuarios where id_rol = 3 ");
                  while($fila = mysqli_fetch_array($consulta)){
                    echo "<option value='$fila[0]'>$fila[1]</option>";
                  }
                  ?>
                  </select>
              </fieldset>
              <div class="btn_registrarEnc">
                <input type="submit" value="Asignar" name="asignar_mensajero">
              </div>
            </form>
          </div>
        </aside>
        </div>

<div class="footer" style="align-content: center;text-align: center">
    <img src="../assets/images/logo-blanco.png" style="max-height: 100px;margin-bottom: 20px;">
    <p> Política de Datos Personales Editorial Nomos </p>
    <p> Diagonal 18Bis N° 41-17 </p>
    <p> Bogotá - Colombia </p>
    <p> PBX: 6012086500 </p>
    <p> Línea gratuita nacional 01 8000 180 777 </p>
    <p> Correo: info@nomos.co </p>
    <p> © Copyright 2022  &nbsp;&nbsp; | &nbsp;&nbsp;  Nomos Impresores  &nbsp;&nbsp;  |   Todos los derechos reservados   |   Realizado por Rational Software & IT</p>
</div>
</div>

    <?php
      if((isset($_POST['agregar_usuario'])) and (isset($_POST['precontraseña']) != "")){
        $new_name = $_POST['nuevo_nombre'];
        $new_email = $_POST['nuevo_correo'];
        $new_pass = $_POST['nueva_contraseña'];
        $rep_pass = $_POST['precontraseña'];
        $new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $new_rol = $_POST['nuevo_rol'];

       
          $guardar_usuario = mysqli_query($conexion,"INSERT INTO usuarios (nombre_usuario,correo_usuario,clave_usuario,id_rol) values ('$new_name','$new_email','$new_pass','$new_rol')");
          if($guardar_usuario){
            echo "<script> alert('Se ha registrado correctamente'); javascript:window.location='usuarios.php;</script>";
          }
          else{
            echo "<script> alert('No se pudo registrar'); window.location.href ='usuarios.php';</script>";
          }
        }else{
          echo "<script> alert('Deben concidir las dos contraseñas'); javascript:window.location='usuarios.php;</script>";
        }
      if((isset($_POST['modificar_usuario'])) and (isset($_POST['modi_precontraseña']) != "")){
        $new_name = $_POST['modi_nombre'];
        $new_email = $_POST['modi_correo'];
        $new_pass = $_POST['modi_contraseña'];
        $new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $new_rol = $_POST['modi_rol'];
       
          $guardar_usuario = mysqli_query($conexion,"UPDATE usuarios set nombre_usuario = '$new_name',correo_usuario = '$new_email',clave_usuario = '$new_pass',id_rol = '$new_rol')");
          if($guardar_usuario){
            echo "<script> alert('Se ha registrado correctamente'); javascript:window.location='usuarios.php;</script>";
          }
          else{
            echo "<script> alert('No se pudo registrar'); window.location.href ='usuarios.php';</script>";
          }
        }else{
          echo "<script> alert('Deben concidir las dos contraseñas'); javascript:window.location='usuarios.php;</script>";
      }


      /*ASIGNAR MENSAJERO A USUARIOS */
      if((isset($_POST['asignar_mensajero'])) and (isset($_POST['mensajero_asignado']) != "")){
        $id_encargado = $_POST['encargado'];
        $id_mensajero = $_POST['mensajero_asignado'];

        $requerir = mysqli_query($conexion,"SELECT * from usuarios where id_usuario like '%$id_mensajero%'");
        while($columna = mysqli_fetch_array($requerir)){
          $nombre_mensajero = $columna[1];
        }

          $guardar_asignacion = mysqli_query($conexion,"INSERT INTO msg_enc values($id_mensajero,'$nombre_mensajero',$id_encargado)");
          if($guardar_asignacion){
            echo "<script> alert('Se ha registrado correctamente'); javascript:window.location='usuarios.php;</script>";
          }
          else{
            echo "<script> alert('No se pudo registrar'); window.location.href ='usuarios.php';</script>";
          }
        }else{
          echo "<script> alert('Deben llenar los dos campos'); javascript:window.location='usuarios.php;</script>";
      }
    ?>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/app.js"></script>

<script>
    function cmb_estado(){
        var cod = document.getElementById("estados").value;
        // alert(cod);
        /* Para obtener el texto */
        var combo = document.getElementById("estados");
        var selected = combo.options[combo.selectedIndex].text;
        // alert(selected);

        window.location.href ='principal_adt.php?encarg='+cod+'&est='+selected+'';
    }
    function modificar(id){
      $(".users_container").addClass("users_modificar");
      $(".users_container").removeClass("mostrar_asignacion");

            var nombre = document.getElementById("nombre"+id).value;
            
            var correo = document.getElementById("correo"+id).value;
            var rol = document.getElementById("rol"+id).value;
            
            $('input[name=modi_id').val(id);
            $('input[name=modi_nombre').val(nombre);
            $('textarea[name=modi_correo').val(correo);

            $("option[value='"+rol+"']").attr("selected", true);

    }
    function quitarmodificar(){
      $(".users_container").removeClass("users_modificar");
    }


    function mostrarAsignacion(){
      $(".users_container").removeClass("users_modificar");
      $(".users_container").toggleClass("mostrar_asignacion");
    }
    

    $(document).ready(function(){
        $(".contenedor_mayor").toggleClass("collapse");

        $(".hamburger").click(function(){
            $(".contenedor_mayor").toggleClass("collapse");
        });
      });
  </script>
  </body>
</html>
<?php
  include_once '../bd/conexion.php';
  session_start();
  $Rol = $_SESSION['Id_rol'];
  if(!$_SESSION){
		header('location: ../index.php');
  }
  else {
    if ($_SESSION['Id_rol'] == 3) {
      header('location: ../index.php');
    }
  }

  $id_usuario = $_SESSION['Id_usuario'];
  $nombre_usuario = $_SESSION['Nombre_usuario'];

  date_default_timezone_set("America/Bogota");
  setlocale(LC_ALL,"es_ES");
  $fecha_inicio   = date('Y-m-d 00:00:00');
  $fecha_hoy      = date("Y-m-d H:i:s");
  $fecha_despues 	= date("Y-m-d 23:59:59",strtotime($fecha_hoy."+ 1 day,"));
  $i = 0;

  if(isset($_GET['registrado'])) {
    echo "<div class='registrado'>Se ha registrado correctamente </div>";
  }

  if(isset($_GET['cambiado'])) {
    echo "<div class='registrado'>Se ha cambiado de estado </div>";
  }
  

  $meses = ['Default','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
?>
<!doctype html>
<html lang="es">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <link rel="stylesheet" href="../assets/css/style.css">
      <link rel="stylesheet" href="../assets/css/carrusel.css">
      <link rel="stylesheet" type="text/css" href="extensiones/TablaAdvance/css/dataTables.jqueryui.css"/>
      <title>Encargos</title>
      <script src="../assets/js/jquery.min.js"></script>
      <script src="../assets/js/app.js"></script>

      <script src="../assets/js/jquery-3.0.0.min.js"></script>
      <script src="extensiones/TablaAdvance/js/jquery.dataTables.js"></script>

  </head>
  <body>
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
                    <a href="all_encargos.php" class="active">
                        <span class="icon"><img src="../assets/images/calendar.png" alt="" width="20px"></span>
                        <span class="title">Modi. Encargos</span>
                    </a>
                </li>
                <?php if($Rol == 1) { ?>
                <li>
                    <a href="usuarios.php">
                        <span class="icon"><img src="../assets/images/users.png" alt="" width="20px"></span>
                        <span class="title">Usuarios</span>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>

        <div class="container_encargos">
        <aside class="por_realizar">
          <div class="encabezado_lista">
            <h2>Encargos por realizar</h2>
          </div>
          <div class="contenido_lista">
            <table class="tabla_encargos" border="0" cellspacing="10px">
              <thead>
                <tr>
                  <th>Nº enc.</th>
                  <th>Est.</th>
                  <th>Desc.</th>
                  <th>Mens.</th>
                  <th>Fecha req.</th>
                  <th>Fech dest.</th>
                  <th>Modi.</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $encargos = mysqli_query($conexion,"SELECT encargos.id_encargo,encargos.id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,foto_encargo,estado FROM encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_mensajero where estado = 'pendiente' or 'reprogramado' order by estado desc,id_encargo asc");
                  if($_SESSION['Id_rol'] == 2){
                    $encargos = mysqli_query($conexion,"SELECT encargos.id_encargo,encargos.id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,foto_encargo,estado FROM encargos INNER JOIN usuarios join msg_enc on usuarios.id_usuario = encargos.id_mensajero where encargado like '%$id_usuario%' and estado = 'pendiente' or 'reprogramado' order by estado desc,id_encargo asc");
                  }
                  if(mysqli_num_rows($encargos) > 0){
                    while($listar = mysqli_fetch_array($encargos)){
                      $id_encargo = $listar[0];
                      $estado = $listar[8];
                      $descripcion = $listar[3];
                      $mensajero = $listar[2];
                      $fec_requerida = $listar[5];
                      $mes_reali 	= date("n",strtotime($fec_requerida));
                      $dia_reali 	= date("d",strtotime($fec_requerida));
                      $year_reali 	= date("Y",strtotime($fec_requerida));
                ?>
                <tr class='<?php echo $estado; ?>'>
                 <td><?php echo $id_encargo; ?></td>
                  <td>
                  <select name="estado" id="<?php echo $id_encargo; ?>" data-id="<?php echo $id_encargo; ?>" onchange="cmb_estado(this)">
                        <?php
                          if($estado == "completado"){
                            echo "<option value=".$id_encargo." selected>$estado</option>";
                          }
                          if($estado == "pendiente"){
                            echo "<option value=".$id_encargo." selected>$estado</option>";
                            echo "<option value=".$id_encargo.">completado</option>";
                            echo "<option value=".$id_encargo.">reprogramado</option>";
                          }
                          if($estado == "reprogramado"){
                            echo "<option value=".$id_encargo." selected>$estado</option>";
                            echo "<option value=".$id_encargo.">pendiente</option>";
                            echo "<option value=".$id_encargo.">completado</option>";
                          }
                        ?>
                      </select>
                      <input type="hidden" id="<?php echo $id_encargo; ?>" value="<?php echo $id_encargo; ?>" class="id_encargo">
                  </td>
                  <td class="justify"><?php echo $descripcion; ?><input type="hidden" id="conf_descripcion<?php echo $id_encargo; ?>" value="<?php echo $descripcion; ?>"></td>
                  <td><?php echo $mensajero; ?>   <input type="hidden" id="conf_encargado<?php echo $id_encargo; ?>" value="<?php echo $mensajero; ?>"></td>
                  <td nowrap="2"><?php echo date("d",strtotime($fec_requerida))." / ".$meses[date("n",strtotime($fec_requerida))]." / ".date("Y",strtotime($fec_requerida)); ?></td>
                  <td nowrap="2"><?php echo $dia_reali."  ".$meses[$mes_reali]."  ".$year_reali; ?></td>
                  <td class="justify"><button onclick="javascript:window.location='all_encargos.php?modificar=<?php echo $id_encargo;?>'">Modificar</button></td>
                </tr>
                <?php
                $i++;
                    }
                  }else{
                     ?>
                     <tr>
                      <td colspan="10">No se encontro nada</td>
                     </tr>
                     <?php
                  }
                ?> 
              </tbody>
            </table>
          </div>
        </aside>

            <aside class="realizados">
                <div class="encabezado_lista">
                    <h2>Encargos realizados</h2>
                </div>
                <div class="contenido_lista">
                    <table class="tabla_encargos" border="0" cellspacing="10px" id="tabla_realizados">
                        <thead>
                        <tr>
                            <th>Nº enc.</th>
                            <th>Est.</th>
                            <th>Desc.</th>
                            <th>Mens.</th>
                            <th>Fecha req.</th>
                            <th>Fech dest.</th>
                            <th>Obser</th>
                            <th>Foto</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                          $encargos = mysqli_query($conexion,"SELECT encargos.id_encargo,encargos.id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,foto_encargo,estado,fecha_completado,foto_encargo2,foto_encargo3 FROM encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_mensajero where estado = 'completado' order by estado desc,fecha_requerida desc");
                          if($_SESSION['Id_rol'] == 2){
                            $encargos = mysqli_query($conexion,"SELECT encargos.id_encargo,encargos.id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,foto_encargo,estado,fecha_completado,foto_encargo2,foto_encargo3 FROM encargos INNER JOIN usuarios join msg_enc on usuarios.id_usuario = encargos.id_mensajero where encargado like '%$id_usuario%' and estado = 'completado' order by estado desc,fecha_requerida desc");
                          }
                          if(mysqli_num_rows($encargos) > 0){
                            while($listar = mysqli_fetch_array($encargos)){
                              $id_encargo = $listar[0];
                              $estado = $listar[8];
                              $descripcion = $listar[3];
                              $mensajero = $listar[2];
                              $fec_requerida = $listar[5];
                              $observacion = $listar[6];
                              $foto_encargo = $listar[7];
                              $foto_encargo2 = $listar[10];
                              $foto_encargo3 = $listar[11];
                              $fec_completado = $listar[9];
                              $mes_reali 	= date("m",strtotime($fec_completado));
                              $dia_reali 	= date("d",strtotime($fec_completado));
                              $year_reali 	= date("Y",strtotime($fec_completado));
                              ?>
                                <tr class='<?php echo $estado; ?>'>
                                    <td><?php echo $id_encargo; ?></td>
                                    <td><?php echo $estado; ?></td>
                                    <td class="justify"><?php echo $descripcion; ?></td>
                                    <td><?php echo $mensajero; ?></td>
                                    <td nowrap="2"><?php echo date("d",strtotime($fec_requerida))." / ".$meses[date("n",strtotime($fec_requerida))]." / ".date("Y",strtotime($fec_requerida)); ?></td>
                                    <td nowrap="2"><?php echo $dia_reali."  ".$meses[$mes_reali]."  ".$year_reali; ?></td>
                                    <td class="justify"><?php echo $observacion; ?></td>
                                    <td class="justify"><button onclick="mostrarfoto(this)" data-id="<?php echo $i; ?>">Ver foto</button>
                                    <div id="foto_<?php echo $i; ?>" data-id="foto_<?php echo $i; ?>" class="carrusel_allencargos">

                                        <input type="radio" name="fotos" autofocus value="fotouno" id="fotouno<?php echo $i; ?>" />
                                        <input type="radio" name="fotos" value="fotodos" id="fotodos<?php echo $i; ?>" />
                                        <input type="radio" name="fotos" value="fototres" id="fototres<?php echo $i; ?>" />

                                        <label for="fotouno<?php echo $i; ?>" class="divisor_fotos"><?php echo "<img src='../assets/recursos/$foto_encargo' class='temporal'>"; ?></label><label for="fotodos<?php echo $i; ?>" class="divisor_fotos"><?php echo "<img src='../assets/recursos/$foto_encargo2' class='temporal'>"; ?></label><label for="fototres<?php echo $i; ?>" class="divisor_fotos"><?php echo "<img src='../assets/recursos/$foto_encargo3' class='temporal'>"; ?></label>

                                    <button class="close" data-id="<?php echo $i; ?>" onclick="quitarfoto(this)">X</button>
                               
                                </div>
                                  </td>
                                </tr>
                              <?php
                              $i++;
                            }
                          }else{
                            ?>
                              <tr>
                                  <td colspan="10">No se encontro nada</td>
                              </tr>
                            <?php
                          }
                        ?>
                        </tbody>
                    </table>
                </div>
            </aside>

        <aside class="encargos_totales">
          <div class="encabezado_lista">
            <h2>Todos los encargos</h2>
          </div>
          <div class="contenido_lista">
            <table class="tabla_encargos display" border="0" cellspacing="10px" id="tabla_total">
              <thead>
                <tr>
                  <th>Nº enc.</th>
                  <th>Est.</th>
                  <th>Descr.</th>
                  <th>Mens.</th>
                  <th>Fec dest.</th>
                  <th>Fec comp.</th>
                  <th>Modi.</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $encargos = mysqli_query($conexion,"SELECT encargos.id_encargo,encargos.id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,estado,fecha_completado FROM encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_mensajero order by estado desc,fecha_requerida desc");
                  if($_SESSION['Id_rol'] == 2){
                    $encargos = mysqli_query($conexion,"SELECT encargos.id_encargo,encargos.id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,estado,fecha_completado FROM encargos INNER JOIN usuarios join msg_enc on usuarios.id_usuario = encargos.id_mensajero where encargado like '%$id_usuario%' order by estado desc,fecha_requerida desc");
                  }
                  if(mysqli_num_rows($encargos) > 0){
                    while($listar = mysqli_fetch_array($encargos)){
                      $id_encargo = $listar[0];
                      $estado = $listar[7];
                      $descripcion = $listar[3];
                      $mensajero = $listar[2];
                      $fec_requerida = $listar[5];
                      $fec_completado = $listar[8];
                      $mes_reali 	= date("m",strtotime($fec_completado));
                      $dia_reali 	= date("d",strtotime($fec_completado));
                      $year_reali 	= date("Y",strtotime($fec_completado));
                ?>
                <tr class='<?php echo $estado; ?>'>
                 <td><?php echo $id_encargo; ?></td>
                  <td>
                  <select name="estado" id="<?php echo $id_encargo; ?>" data-id="<?php echo $id_encargo; ?>" onchange="cmb_estado(this)">
                      <?php 
                        if($estado == "completado"){
                          echo "<option value=".$id_encargo." selected>$estado</option>";
                        }
                        if($estado == "pendiente"){
                          echo "<option value=".$id_encargo." selected>$estado</option>";
                          echo "<option value=".$id_encargo.">completado</option>";
                          echo "<option value=".$id_encargo.">reprogramado</option>";
                        }
                        if($estado == "reprogramado"){
                          echo "<option value=".$id_encargo." selected>$estado</option>";
                          echo "<option value=".$id_encargo.">pendiente</option>";
                          echo "<option value=".$id_encargo.">completado</option>";
                        }
                      ?>
                    </select>  
                    <input type="hidden" id="<?php echo $id_encargo; ?>" value="<?php echo $id_encargo; ?>" class="id_encargo">  
                  </td>
                  <td class="justify"><?php echo $descripcion; ?><input type="hidden" id="conf_descripcion<?php echo $id_encargo; ?>" value="<?php echo $descripcion; ?>"></td>
                  <td><?php echo $mensajero; ?>   <input type="hidden" id="conf_encargado<?php echo $id_encargo; ?>" value="<?php echo $mensajero; ?>"></td>
                  <td nowrap="2"><?php echo date("d",strtotime($fec_requerida))." / ".$meses[date("n",strtotime($fec_requerida))]." / ".date("Y",strtotime($fec_requerida)); ?></td>
                  <td nowrap="2"><?php echo $dia_reali."  ".$meses[$mes_reali]."  ".$year_reali; ?></td>
                  <td class="justify"><button onclick="javascript:window.location='all_encargos.php?modificar=<?php echo $id_encargo;?>'">Modificar</button></td>
                </tr>
                <?php
                    }
                  }else{
                     ?>
                     <tr>
                      <td colspan="10">No se encontro nada</td>
                     </tr>
                     <?php
                  }
                ?> 
              </tbody>
            </table>
          </div>
        </aside>

            <aside class="modificar_encargos">
              <?php
                if(isset($_GET['modificar'])){
                  $modi_id_enc = $_GET['modificar'];
                  $modificar_encargo = mysqli_query($conexion,"SELECT * FROM encargos where id_encargo = $modi_id_enc");
                  while($linea_modi = mysqli_fetch_array($modificar_encargo)){
                    $modi_mensajero = $linea_modi[2];
                    $modi_descripcion = $linea_modi[3];
                    $modi_FecRequerida = $linea_modi[5];
                    $modi_estado = $linea_modi[8];
                  }
                  ?>
                    <div class="encabezado_lista">
                        <h2>Modificar encargo <?php echo $modi_id_enc; ?></h2>
                    </div>
                    <div class="contenido_lista">
                        <form action="" method="post">
                            <h4>Para modificar algun encargo primero seleccione uno</h4>
                            <fieldset>
                                <legend>Escriba la descripcion</legend>
                                <textarea  name="descripcion_encargo" required minlength="5" placeholder=""><?php echo $modi_descripcion; ?></textarea>
                            </fieldset>
                            <fieldset>
                                <legend>Escoja la fecha en la que realizara</legend>
                                <input type="date" name="fecha_encargo" required value="<?php echo $modi_FecRequerida; ?>">
                            </fieldset>
                            <fieldset>
                                <legend>Seleccione un mensajero</legend>
                                <select name="mensajero" required>
                                    <option value="" disabled selected>Seleccione una opcion</option>
                                  <?php
                                    $mensajeros = mysqli_query($conexion,"SELECT id_usuario,nombre_usuario FROM usuarios where id_rol = 3");
                                    while($num_msj = mysqli_fetch_array($mensajeros)){
                                      $id_mensajero = $num_msj[0];
                                      $mensajero = $num_msj[1];
                                      if($modi_mensajero == $id_mensajero){
                                        echo "<option value=".$id_mensajero." selected>$mensajero</option>";
                                      }else{
                                        echo '<option value="'.$id_mensajero.'">'.$mensajero.'</option>';
                                      }
                                    }
                                  ?>
                                </select>
                            </fieldset>
                            <div class="btn_registrarEnc">
                                <input type="submit" value="GUARDAR" name="registrar_encargo">
                            </div>
                        </form>
                    </div>
                  <?php
                }
              ?>
            </aside>

             <!-- CUADRO DE CONFIRMACION DE ENTREGA -->
        <aside id="lista_enc" class="list_enc">
          <div class="lista_encargos">
            <div class="encabezado_lista">
              <h2>MODIFICAR</h2>
              <button onclick="oct_estado()" class="close">X</button>
            </div>

            <aside class="contenido_lista">
              <form action="#" method="post" enctype="multipart/form-data">
                <fieldset>
                    <input type="hidden" name="accept_id" id="accept_id">
                  <legend>Descripcion del encargo</legend>
                  <textarea  name="accept_descripcion" id="accept_descripcion" required minlength="5" readonly></textarea>
                </fieldset>
                <fieldset>
                  <legend>Estado actual</legend>
                  <input type="text" name="accept_estado" id="accept_estado" value="" readonly>
                </fieldset>
                <fieldset>
                  <legend>Cargar foto de evidencia</legend>
                  <p>Puede subir una o hasta 3 fotos en caso de que el estado sea completado</p>
                  <input type="file" name="foto_evide" id="foto_opc1" accept="image/*" >
                  <input type="button" name="foto_evide2" id="foto_opc2" accept="image/*" class="foto_opcional" value="+">
                  <input type="button" name="foto_evide3" id="foto_opc3" accept="image/*" class="foto_opcional" value="+">
                  <input type="button" onclick="ver()" value="mostrar fotos">
                </fieldset>
                <div class="carrusel_evidencia">

                  <input type="radio" name="fancy" autofocus value="clubs" id="clubs" />
                  <input type="radio" name="fancy" value="hearts" id="hearts" />
                  <input type="radio" name="fancy" value="spades" id="spades" />

                  <label for="clubs" class="separador_fotos"><img id="imagenPrevisualizacion1"  class="temporal"></label><label for="hearts" class="separador_fotos"><img id="imagenPrevisualizacion2"  class="temporal"></label><label for="spades" class="separador_fotos"><img id="imagenPrevisualizacion3" class="temporal"></label>

                  <div class="keys" onclick="quitarEvidencias()">Salir</div>
                </div>
                <fieldset>
                  <legend>Observacion</legend>
                  <textarea  name="accept_obser" id="accept_obser" minlength="5" placeholder="Observacion opcional"></textarea>
                </fieldset>
                <div class="btn_registrarEnc">
                  <input type="submit" value="COMPLETAR" name="completar_encargo">
                </div>
              </form>
            </aside>
          </div>
        </aside>


                <!-- CUADRO DE REPROGRAMACION  -->
                <aside id="1_reprogramar" class="list_enc">
          <div class="lista_encargos">
            <div class="encabezado_lista">
              <h2>REPROGRAMAR</h2>
              <button onclick="oct_estado(this.id)" id="reprogramar" class="close">X</button>
            </div>

            <aside class="contenido_lista">
              <form action="#" method="post" enctype="multipart/form-data">
                <fieldset>
                    <input type="hidden" name="repro_id" id="repro_id">
                  <legend>Descripcion del encargo</legend>
                  <textarea  name="repro_descripcion" id="repro_descripcion" required minlength="5" readonly></textarea>
                </fieldset>
                <fieldset>
                  <legend>Estado a cambiar</legend>
                  <input type="text" name="repro_estado" id="repro_estado" value="" readonly>
                </fieldset>
                <fieldset>
                  <legend>Reprogramar fecha</legend>
                  <input type="date" name="repro_reprogramar" id="repro_reprogramar" value="<?php echo $solo_fechaHoy; ?>" readonly>
                </fieldset>
                <fieldset>
                  <legend>Observacion</legend>
                  <textarea  name="repro_obser" id="repro_obser" minlength="5" placeholder="Observacion opcional"></textarea>
                </fieldset>
                <div class="btn_registrarEnc">
                  <input type="submit" value="COMPLETAR" name="reprogramar_encargo">
                </div>
              </form>
            </aside>
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
      if((isset($_POST['registrar_encargo'])) and (isset($_POST['descripcion_encargo']) != "")){
        $obt_descripcion = $_POST['descripcion_encargo'];
        $obt_fecha_encargo = $_POST['fecha_encargo'];
        $obt_mensajero = $_POST['mensajero'];

        $originalDate = $obt_fecha_encargo;
        $obt_fecha_encargo = date("Y-m-d H:i:s", strtotime($originalDate));
      
        $guardar_encargo = mysqli_query($conexion,"INSERT INTO encargos (id_usuario,id_mensajero,descripcion,fecha_registrada,fecha_requerida,Estado) values ('$id_usuario','$obt_mensajero','$obt_descripcion','$fecha_hoy','$obt_fecha_encargo','pendiente')");
        if($guardar_encargo){
          echo "<script> window.location.href ='all_encargos.php?registrado=1';</script>";
        }
        else{
          echo "<script> alert('No se pudo registrar'); window.location.href ='principal_adt.php';</script>";
        }
      }

    ?>


<?php
      /* PARA COMPLETAR ENCARGO */
      if((isset($_POST['completar_encargo'])) and (isset($_POST['accept_descripcion']) != "")){
          $accept_id = $_POST['accept_id'];
        $accept_descripcion = $_POST['accept_descripcion'];
        $accept_estado = $_POST['accept_estado'];
        $accept_obser = $_POST['accept_obser'];

        $consulta = "UPDATE encargos set Estado = '$accept_estado',observacion = '$accept_obser',Fecha_completado = '$fecha_hoy'";

        $carpeta = "../assets/recursos/";
				opendir($carpeta);

			
        if($_FILES['foto_evide']['name']){
          $nuevoNameFoto       = rand(1,10000).$_FILES['foto_evide']['name'];
          $filename    = $_FILES['foto_evide']['name'];
          $sourceFoto  = $_FILES['foto_evide']['tmp_name'];

          $imagenUno = $carpeta.'/'.$nuevoNameFoto;

          move_uploaded_file($sourceFoto, $imagenUno);
          
          $consulta .= ",foto_encargo = '$nuevoNameFoto'";
        }
        if($_FILES['foto_evide2']['name']){
          $segundoNameFoto       = rand(1,10000).$_FILES['foto_evide2']['name'];
          $filename    = $_FILES['foto_evide2']['name'];
          $sourceFoto  = $_FILES['foto_evide2']['tmp_name'];

          $imagenDos = $carpeta.'/'.$segundoNameFoto;

          move_uploaded_file($sourceFoto, $imagenDos);
          
          $consulta .= ",foto_encargo2 = '$segundoNameFoto'";
        }
        if($_FILES['foto_evide3']['name']){
          $tercerNameFoto       = rand(1,10000).$_FILES['foto_evide3']['name'];
          $filename    = $_FILES['foto_evide3']['name'];
          $sourceFoto  = $_FILES['foto_evide3']['tmp_name'];

          $imagenTres = $carpeta.'/'.$tercerNameFoto;

          move_uploaded_file($sourceFoto, $imagenTres);
          
          $consulta .= ",foto_encargo3 = '$tercerNameFoto'";
        }

        $consulta .= "WHERE id_encargo = $accept_id";
        $guardar_encargo = mysqli_query($conexion,$consulta);
        if($guardar_encargo){
          echo "<script> window.location.href ='all_encargos.php?cambiado=1';</script>";
        }
        else{
          echo "<script> alert('No se pudo cambiar'); ;</script>";
        }
      }



            /* PARA REPROGRAMAR ENCARGO */
            if((isset($_POST['reprogramar_encargo'])) and (isset($_POST['repro_descripcion']) != "")){
              $repro_id = $_POST['repro_id'];
            $repro_descripcion = $_POST['repro_descripcion'];
            $repro_estado = $_POST['repro_estado'];
            $repro_reprogramar = $_POST['repro_reprogramar'];
            $repro_obser = $_POST['repro_obser'];
      
            $consulta = "UPDATE encargos set Estado = '$repro_estado',observacion = '$repro_obser',fecha_requerida = '$repro_reprogramar' ";
      
            $consulta .= "WHERE id_encargo = $repro_id";
            $guardar_encargo = mysqli_query($conexion,$consulta);
            if($guardar_encargo){
              echo "<script> alert('Se ha reprogramado'); 
              window.location.href ='principal_enc.php';</script>";
            }
            else{
              echo "<script> alert('No se pudo reprogramar'); window.location.href ='principal_enc.php';</script>";
            }
          }
    ?>

    <script>
        function cmb_estado(verencargo){
            var id = $(verencargo).data("id");
            var cod = document.getElementById(id).value;

            var combo = document.getElementById(id);
            var selected = combo.options[combo.selectedIndex].text;

            if(selected == "completado"){
              $("#1_modificar").addClass("show_confirmacion");
              $("#1_reprogramar").removeClass("show_confirmacion");
              var descripcion = document.getElementById("conf_descripcion"+id).value;
            }
            else{
              $("#1_modificar").removeClass("show_confirmacion");
              $("#1_reprogramar").addClass("show_confirmacion");
              $('input[name=repro_id').val(id);
              $('input[name=repro_descripcion').val(descripcion);
              $('input[name=repro_estado').val(selected);
            }
            $('input[name=accept_id').val(id);
            $('textarea[name=accept_descripcion').val(descripcion);
            $('input[name=accept_estado').val(selected);

        }
        function oct_estado(){
            $(".list_enc").toggleClass("show_confirmacion");
        }
        $(document).ready(function(){
            $(".contenedor_mayor").toggleClass("collapse");
            $(".hamburger").click(function(){
                $(".contenedor_mayor").toggleClass("collapse");
            });
            $(".inc_regis").click(function(){
                $(".main_container").toggleClass("two_container");
                $(".registrar_encargos").toggleClass("show_registrar");
            });
            $(".show_todos").click(function(){
                $(".list_enc").toggleClass("show_encargos");
                $(".lista_realizados").removeClass("show_encargos");
            });
            $(".show_realizados").click(function(){
                $(".list_enc").removeClass("show_encargos");
                $(".lista_realizados").toggleClass("show_encargos");
            });

            $('#tabla_total').dataTable( {
                "language": {"sProcessing":     "Procesando...","sLengthMenu":     "Mostrar _MENU_ registros","sZeroRecords":    "No se encontraron resultados","sEmptyTable":     "Ningún dato disponible en esta tabla","sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros","sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros","sInfoFiltered":   "(filtrado de un total de _MAX_ registros)","sInfoPostFix":    "","sSearch":         "Buscar registro: ","sUrl":            "","sInfoThousands":  ",","sLoadingRecords": "Cargando...","oPaginate": {"sFirst":    "Primero","sLast":     "Último","sNext":     "Siguiente","sPrevious": "Anterior" },"oAria": { "sSortAscending":  ": Activar para ordenar la columna de manera ascendente", "sSortDescending": ": Activar para ordenar la columna de manera descendente" }}
            } );
            $('#tabla_realizados').dataTable( {
                "language": {"sProcessing":     "Procesando...","sLengthMenu":     "Mostrar _MENU_ registros","sZeroRecords":    "No se encontraron resultados","sEmptyTable":     "Ningún dato disponible en esta tabla","sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros","sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros","sInfoFiltered":   "(filtrado de un total de _MAX_ registros)","sInfoPostFix":    "","sSearch":         "Buscar registro: ","sUrl":            "","sInfoThousands":  ",","sLoadingRecords": "Cargando...","oPaginate": {"sFirst":    "Primero","sLast":     "Último","sNext":     "Siguiente","sPrevious": "Anterior" },"oAria": { "sSortAscending":  ": Activar para ordenar la columna de manera ascendente", "sSortDescending": ": Activar para ordenar la columna de manera descendente" }}
            } );

            $("input#foto_opc2").click(function(){
          $(this).val('');
          $(this).get(0).type = 'file';
        });
        $("input#foto_opc3").click(function(){
          $(this).val('');
          $(this).get(0).type = 'file';
        });
        });
        function mostrarfoto(verfoto){
            var id = $(verfoto).data("id");
            // alert(id);
            var foto = "#foto_"; var final = foto+id;
            $(final).toggleClass("ver_fotos");
        }
        function quitarfoto(verfoto){
            var cerrar_id = $(verfoto).data("id");
            var cerrar_foto = "#foto_"; var final = cerrar_foto+cerrar_id;
            $(final).toggleClass("ver_fotos");
        }



        function ver(){
          $(".carrusel_evidencia").addClass("form");
        }
        function quitarEvidencias(){
          $(".carrusel_evidencia").toggleClass("form");
        }
        for(var i = 1; i <= 3; i++){
        const $seleccionArchivos = document.querySelector("#foto_opc"+i),
            $imagenPrevisualizacion = document.querySelector("#imagenPrevisualizacion"+i);

            $seleccionArchivos.addEventListener("change", () => {
              const archivos = $seleccionArchivos.files;
              if (!archivos || !archivos.length) {
                $imagenPrevisualizacion.src = "";
                return;
              }
              const primerArchivo = archivos[0];
              const objectURL = URL.createObjectURL(primerArchivo);
              $imagenPrevisualizacion.src = objectURL;
            });
          }
    </script>
  </body>
</html>
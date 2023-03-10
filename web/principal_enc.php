<?php
  include_once '../bd/conexion.php';

  session_start();
  if(!$_SESSION){
    header('location: ../index.php');
  }
  if($_SESSION['Id_rol'] !=2){
    header('location: ../index.php');
  }
  $Rol = $_SESSION['Id_rol'];
  $id_usuario = $_SESSION['Id_usuario'];
  $num_usuario = $_SESSION['Id_usuario'];
  $nombre_usuario = $_SESSION['Nombre_usuario'];

  date_default_timezone_set("America/Bogota");
  setlocale(LC_ALL,"es_ES");
  $fecha_inicio   = date('Y-m-d 00:00:00');
  $solo_fechaHoy   = date('Y-m-d');
  $final_dia   = date('Y-m-d 23:59:59');
  $fecha_hoy      = date("Y-m-d H:i:s");
  $solofecha_hoy      = date("Y-m-d");
  $fecha_despues 	= date("Y-m-d 23:59:59",strtotime($fecha_hoy."+ 1 day,"));

  if(isset($_GET['encarg'])) {
    $num_enc = $_GET['encarg'];
    $cam__estado = $_GET['est'];
    $rectificar = mysqli_query($conexion,"UPDATE encargos set estado = '$cam__estado',fecha_completado = '$fecha_hoy' where id_encargo = $num_enc");

    if($rectificar){
      echo "<script> alert('Se cambio de estado'); window.location.href ='principal_enc.php'; </script>";
    }
  }

  $SqlEventos   = ("SELECT * from msg_enc where encargado = $num_usuario");
  $resulEventos = mysqli_query($conexion, $SqlEventos);

  require_once('extensiones/app/Database.php');
  require_once('extensiones/app/Tarea.php');

  $tareas = (new Tarea())->getTareas();
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/fullcalendar.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/carrusel.css">
    <title>Encargado</title>
    <script src="../assets/js/jquery.min.js"></script>

    <script src="../assets/js/jquery-3.0.0.min.js"></script>
  </head>

  <body>
		<!-- <div class="loading" id="loading"><div class="cargador"></div></div> -->
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
            <a href="../index.php" class="active">
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
        </ul>
      </div>

      <div class="main_container">
        <aside class="lista_calendario">
          <div id="calendar"></div>
        </aside>
        <aside class="lista_prioridades">
          <div class="encabezado_lista">
              <h2>Lista de prioridad del dia</h2>
          </div>
          
          <p align="justify">Puede modificar la prioridad de los encargos del dia en curso arrastrando los recuadros hacia un nivel inferior o superior</p>
          <ul class="tareas" id="tareas">
            <?php $e = 1;$sum = 0;$rest = 255; 
              $bscar_mensajero = mysqli_query($conexion, "SELECT * from msg_enc where encargado = $num_usuario");
              while($msg = mysqli_fetch_array($bscar_mensajero)){
                $encargos = mysqli_query($conexion,"SELECT encargos.id_encargo,id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,estado,visto from encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_mensajero where encargos.id_mensajero = $msg[Id_mensajero]  and fecha_requerida = '$solofecha_hoy' order by prioridad");
                while($listar = mysqli_fetch_array($encargos)){
            ?>

            <li class="tarea" data-id="<?php echo $listar['id_encargo']; ?>" style="background-color: rgba(<?php echo $sum.",".$rest; ?>,0,0.15)">
              <?php echo "<b>#".$e."<br>Descripcion:  </b>".$listar['descripcion']."<br><b>Mensajero:</b> ".$listar['mensajero']; ?>
            </li>
            <?php
            $e++;$sum = $sum + 25;$rest = $rest - 25; 
              }
            }
            ?>
          </ul>
        </aside>
        <aside class="lista_encargos">
          <div class="encabezado_lista">
            <h2>Encargos del dia</h2>
          </div>
          <div class="contenido_lista">
              <table class="tabla_encargos" border="0" cellspacing="10px">
              <thead>
                <tr>
                  <th>Estado</th>
                  <th>Descripcion</th>
                  <th>Mensajero</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $bscar_mensajero = mysqli_query($conexion, "SELECT * from msg_enc where encargado = $num_usuario");
                  if(mysqli_num_rows($bscar_mensajero) >= 1){
                  while($msg = mysqli_fetch_array($bscar_mensajero)){
                    $encargos = mysqli_query($conexion,"SELECT encargos.id_encargo,id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,estado,visto from encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_mensajero where encargos.id_mensajero = $msg[Id_mensajero]  and fecha_requerida = '$solofecha_hoy' order by prioridad");
                      while($listar = mysqli_fetch_array($encargos)){
                        $id_encargo = $listar[0];
                        $estado = $listar[7];
                        $descripcion = $listar[3]; 
                        $mensajero = $listar[2];
                        $fec_requerida = $listar[5];
                ?>
                <tr class='<?php echo $estado; ?>'>
                  <td>
                    <select name="estado" id="<?php echo $id_encargo; ?>" data-id="<?php echo $id_encargo; ?>" onchange="cmb_estado(this)">
                      <?php 
                        if($estado == "completado"){
                          echo "<option value=".$estado." selected>$estado</option>";
                        }else{
                          $estados = array('pendiente','completado','reprogramado');
                          for ($i=0; $i <= 2; $i++) {
                            if($estados[$i] == $estado){
                              echo "<option value=".$id_encargo." selected>$estados[$i]</option>";
                            }else{
                              echo "<option value=".$id_encargo.">$estados[$i]</option>";
                            }
                          }
                        }
                      ?>
                    </select>    
                    <input type="hidden" id="<?php echo $id_encargo; ?>" value="<?php echo $id_encargo; ?>" class="id_encargo">
                  </td>
                  <td><?php echo $descripcion; ?> <input type="hidden" id="conf_descripcion<?php echo $id_encargo; ?>" value="<?php echo $descripcion; ?>"></td>
                  <td><?php echo $mensajero; ?>   <input type="hidden" id="conf_encargado<?php echo $id_encargo; ?>" value="<?php echo $mensajero; ?>"></td>
                </tr>
                <?php
                  }                    
                }
              }else{
                ?>
                <tr>
                  <td colspan="3" rowspan="3">No hay encargos para el dia de hoy</td>
                </tr>
                <?php                   
                }
                ?>
              </tbody>
            </table>
              <a href="#lista_completa" id="mostrar_completados"><button class="show_todos">Todos los encargos</button></a>
              <a href="#lista_realizados" id="mostrar_realizados"><button class="show_realizados">Encargos realizados</button></a>
              <button class="inc_regis" style="background-color: rgba(61, 98, 169, 0.45)">A??adir uno nuevo</button>
          </div>
            <br><br><br>
          <?php include 'extensiones/encargos.php'; ?>
        </aside>

            <aside class="registrar_encargos">
                <div class="encabezado_lista">
                    <h2>Registrar encargo</h2>
                </div>

                <div class="contenido_lista">
                    <form action="" method="post">
                        <fieldset>
                            <legend>Escriba la descripcion *</legend>
                            <textarea  name="descripcion_encargo" required minlength="5" placeholder="Ingrese aqui la informacion que realizara el mensajero"></textarea>
                        </fieldset>
                        <fieldset>
                            <legend>Escoja la fecha en la que realizara *</legend>
                            <input type="date" name="fecha_encargo" required min="<?php echo $solo_fechaHoy; ?>" value="<?php echo $solofecha_hoy; ?>">
                        </fieldset>
                        <fieldset>
                            <legend>Seleccione un mensajero *</legend>
                            <select name="mensajero" required>
                                <option value="" disabled selected>Seleccione una opcion</option>
                              <?php
                                $mensajeros = mysqli_query($conexion,"SELECT usuarios.id_usuario,nombre_usuario FROM usuarios inner join msg_enc on usuarios.id_usuario = msg_enc.id_mensajero where msg_enc.encargado like  '%$id_usuario%'");
                                while($num_msj = mysqli_fetch_array($mensajeros)){
                                  $id_mensajero = $num_msj[0];
                                  $mensajero = $num_msj[1];
                                  echo '<option value="'.$id_mensajero.'">'.$mensajero.'</option>';
                                }
                              ?>
                            </select>
                        </fieldset>
                        <div class="btn_registrarEnc">
                            <input type="submit" value="REGISTRAR" name="registrar_encargo">
                        </div>
                    </form>
                </div>
            </aside>
        </div>
          <?php
            include('extensiones/modalUpdateEvento.php');
          ?>
          <!-- CUADRO DE CONFIRMACION DE ENTREGA -->
        <aside id="1_modificar" class="list_enc">
          <div class="lista_encargos">
            <div class="encabezado_lista">
              <h2>MODIFICAR</h2>
              <button onclick="oct_estado(this.id)" id="modificar" class="close">X</button>
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
                  <input type="file" name="foto_evide" id="foto_opc1" accept=".jpg, .png" >
                  <input type="button" name="foto_evide2" id="foto_opc2" accept=".jpg, .png" class="foto_opcional" value="+">
                  <input type="button" name="foto_evide3" id="foto_opc3" accept=".jpg, .png" class="foto_opcional" value="+">
                  <button onclick="ver()"><a href="#">mostrar_foto</a></button>
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


        <div class="footer" style="align-content: center;text-align: center">
            <img src="../assets/images/logo-blanco.png" style="max-height: 100px;margin-bottom: 20px;">
            <p> Pol??tica de Datos Personales Editorialf Nomos </p>
            <p> Diagonal 18Bis N?? 41-17 </p>
            <p> Bogot?? - Colombia </p>
            <p> PBX: 6012086500 </p>
            <p> L??nea gratuita nacional 01 8000 180 777 </p>
            <p> Correo: info@nomos.co </p>
            <p> ?? Copyright 2022  &nbsp;&nbsp; | &nbsp;&nbsp;  Nomos Impresores  &nbsp;&nbsp;  |   Todos los derechos reservados   |   Realizado por Rational Software & IT</p>
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
          echo "<script> window.location.href ='principal_enc.php';</script>";
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
          echo "<script> alert('Se ha cambiado de estado'); window.location.href ='principal_enc.php';</script>";
        }
        else{
          echo "<script> alert('No se pudo cambiar'); window.location.href ='principal_enc.php';</script>";
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


    <script src="../assets/js/jquery.min.js"></script>


    <script type="text/javascript" src="../assets/js/moment.min.js"></script>
    <script type="text/javascript" src="../assets/js/fullcalendar.min.js"></script>
    <script src="../assets/js/es.js"></script>
    <script src="../assets/js/app.js"></script>
    <script src="../assets/js/loader.js"></script>
    

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/draggable/1.0.0-beta.8/sortable.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.0/axios.min.js"></script>

    <script>
      function myFunction(){
  const el = document.querySelector('#loading');
  
  el.classList.add('loading2');
}
        $(document).ready(function(){
            $("#calendar").fullCalendar({
                header: {
                    left: "prev,next today",
                    center: "title",
                    right: "month,agendaWeek,agendaDay"
                },
                locale: 'es',
                defaultView: "month",
                navLinks: true,
                editable: false,
                eventLimit: true,
                selectable: false,
                selectHelper: false,

//Nuevo Evento
                select: function(start, end){
                    $("#exampleModal").modal();
                    $("input[name=fecha_inicio]").val(start.format('DD-MM-YYYY'));

                    var valorFechaFin = end.format("DD-MM-YYYY");
                    var F_final = moment(valorFechaFin, "DD-MM-YYYY").subtract(1, 'days').format('DD-MM-YYYY'); //Le resto 1 dia
                    $('input[name=fecha_fin').val(F_final);

                },

                events: [
                  <?php
                  while($msg = mysqli_fetch_array($resulEventos)){
                  $encargos_enc = mysqli_query($conexion,"SELECT encargos.id_encargo,id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,estado,visto from encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_mensajero where encargos.id_mensajero = $msg[Id_mensajero]  and fecha_requerida = '$solofecha_hoy' order by prioridad");
                    while($dataEvento = mysqli_fetch_array($encargos_enc)){
                      $estado = $dataEvento['estado'];
                  $final = date("Y-m-d",strtotime($dataEvento['fecha_requerida']));
                  if ($estado == "pendiente"){ $color = "#FFEC6DC1"; }elseif($estado == "completado"){ $color = "#A7FF6DC1"; }else{ $color = "#FF6D6DC3"; } ?>
                    {
                        _id: '<?php echo $dataEvento['id_encargo']; ?>',
                        title: '<?php echo $dataEvento['descripcion']; ?>',
                        start: '<?php echo $final; ?>',
                        end:   '<?php echo $dataEvento['fecha_requerida']; ?>',
                        color: '<?php echo $color; ?>',
                        mensajero: '<?php echo $dataEvento['mensajero']; ?>',
                    },
                  <?php 
                  }
             } 
             ?>
                ],

//Eliminar Evento
                eventRender: function(event, element) {
                    element
                        .find(".fc-content")
                        .prepend("<span  class='closeon'><img src='../assets/images/delete.png' alt='' width='20px'></span>");
                    //Eliminar evento
                    element.find(".closeon").on("click", function() {

                        var pregunta = confirm("Deseas Borrar este Evento?");
                        if (pregunta) {

                            $("#calendar").fullCalendar("removeEvents", event._id);

                            $.ajax({
                                type: "POST",
                                url: 'deleteEvento.php',

                                data: {id:event._id},

                            });
                        }
                    });
                },

//Modificar Evento del Calendario
                eventClick:function(event){
                    var idEvento = event._id;
                    $('input[name=idEvento').val(idEvento);
                    $('textarea[name=evento').val(event.title);
                    $('input[name=mensajero').val(event.mensajero);
                    $('input[name=fecha_inicio').val(event.start.format('DD / MM / YYYY'));


                    $(".modal").toggleClass("show_modal");
                },
            });

            setTimeout(function () {
                $(".alert").slideUp(300);
            }, 3000);

            window.onload = () => {
                const sortable = new Sortable.default(document.querySelectorAll('.tareas'), {
                    draggable: 'li'
                });

                sortable.on('sortable:stop', () => {
                    setTimeout(() => {
                        const tareas = document.getElementsByClassName('tarea');
                        const sortedData = new Array();

                        [...tareas].forEach((tarea, index) => {
                            sortedData.push({
                                id: tarea.getAttribute('data-id'),
                                orden: (index + 1)
                            });
                        });

                        let formData = new FormData();
                        formData.append('data', JSON.stringify(sortedData));

                        axios.post('extensiones/app/api/ordenarTareas.php',formData)
                            .then(res => console.log(res))
                            .catch(err => console.log(err));

                    }, 100);
                });
            }

            $(".contenedor_mayor").toggleClass("collapse");
            $(".hamburger").click(function(){
                $(".contenedor_mayor").toggleClass("collapse");
            });
            $(".inc_regis").click(function(){
                $(".main_container").toggleClass("two_container");
                $(".registrar_encargos").toggleClass("show_registrar");
            });
            $(".show_todos").click(function(){
                $(".lista_completa").toggleClass("show_tablas");
                $(".lista_realizados").removeClass("show_tablas");
            });
            $(".show_realizados").click(function(){
                $(".lista_completa").removeClass("show_tablas");
                $(".lista_realizados").toggleClass("show_tablas");
            });
            $("#quitar_encargo").click(function(){
                $(".modal").removeClass("show_modal");
            });

            document.getElementById("modalUpdateEvento").addEventListener('click', function(e) {
                /*2. Si el div con id clickbox contiene a e. target*/
                if (document.getElementById('contentInterior').contains(e.target)) {
                } else {
                    $(".modal").removeClass("show_modal");
                }
            })
        });
        function cmb_estado(verencargo){
            var id = $(verencargo).data("id");
            var cod = document.getElementById(id).value;

            var combo = document.getElementById(id);
            var selected = combo.options[combo.selectedIndex].text;
            var descripcion = document.getElementById("conf_descripcion"+id).value;

            if(selected == "completado"){
              $("#1_modificar").addClass("show_confirmacion");
              $("#1_reprogramar").removeClass("show_confirmacion");
              
            }
            else{
              $("#1_modificar").removeClass("show_confirmacion");
              $("#1_reprogramar").addClass("show_confirmacion");
              $('input[name=repro_id').val(id);
              $('textarea[name=repro_descripcion').val(descripcion);
              $('input[name=repro_estado').val(selected);
            }
            $('input[name=accept_id').val(id);
            $('textarea[name=accept_descripcion').val(descripcion);
            $('input[name=accept_estado').val(selected);

        }
        function oct_estado(estado){
            $("#1_"+estado).toggleClass("show_confirmacion");
        }
        $("input#foto_opc2").click(function(){
          $(this).val('');
          $(this).get(0).type = 'file';
        });
        $("input#foto_opc3").click(function(){
          $(this).val('');
          $(this).get(0).type = 'file';
        });
        
        function ver(){
          $(".carrusel_evidencia").addClass("form");
        }
        $(".keys").click(function(){
          $(".carrusel_evidencia").removeClass("form");
        });
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
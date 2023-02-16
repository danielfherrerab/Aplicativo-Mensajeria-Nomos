<?php
  include_once '../bd/conexion.php';
  session_start();
  if(!isset($_SESSION['Id_rol'])){
    header('location: ../index.php');
  }
  else{
    if($_SESSION['Id_rol'] !=3){
      header('location: ../index.php');
    }
  }
  $i = 0;
  $Rol = $_SESSION['Id_rol'];
  $id_usuario = $_SESSION['Id_usuario'];
  $nombre_usuario = $_SESSION['Nombre_usuario'];

  date_default_timezone_set("America/Bogota");
  setlocale(LC_ALL,"es_ES");
  $fecha_inicio   = date('Y-m-d 00:00:00');
  $final_dia   = date('Y-m-d 23:59:59');
  $fecha_hoy      = date("Y-m-d H:i:s");
  $fecha_despues 	= date("Y-m-d 23:59:59",strtotime($fecha_hoy."+ 1 day,"));

  if(isset($_GET['encarg'])) {
    $num_enc = $_GET['encarg'];
    $cam__estado = $_GET['est'];

    $rectificar = mysqli_query($conexion,"UPDATE encargos set estado = '$cam__estado',fecha_completado = '$fecha_hoy' where id_encargo = $num_enc");

    if($rectificar){
      echo "<script> alert('Se cambio de estado'); window.location.href ='principal_msg.php'; </script>";
    }
  }

  if(isset($_GET['cambiado'])) {
    echo "<div class='registrado'>Se ha cambiado de estado </div>";
  }


  $SqlEventos   = ("SELECT * FROM encargos where id_mensajero = $id_usuario");
  $resulEventos = mysqli_query($conexion, $SqlEventos);
?>
<!doctype html>
<html lang="es">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <link rel="stylesheet" href="../assets/css/style.css">
      <link rel="stylesheet" href="../assets/css/carrusel.css">
      <link rel="stylesheet" type="text/css" href="../assets/css/fullcalendar.css">
      <title>Mensajero</title>
      <script src="../assets/js/jquery.min.js"></script>
  </head>
  <body>
		<div class="loading" id="loading"><div class="cargador"></div></div>
    <div class="contenedor_mayor">
        <div class="nav_superior">
            <div class="hamburger">
                <div class="one"></div>
                <div class="two"></div>
                <div class="three"></div>
            </div>
            <div class="top_menu">
                <div class="logo"><?php echo $nombre_usuario; ?></div>
                <ul>
                    <li>
                        <a href="#" id="open_notificacion"><i class="fas"><img src="../assets/images/alert.png" width="30px"></i></a>
                        <div class="notificacion"><span id="recibidos"></span></div>
                    </li>
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
                    <a href="encargos_msg.php">
                        <span class="icon"><img src="../assets/images/calendar.png" alt="" width="20px"></span>
                        <span class="title">Encargos</span>
                    </a>
                </li>
                <li>
                    <a href="cambiar_clave.php">
                        <span class="icon"><img src="../assets/images/reset.png" alt="" width="20px"></span>
                        <span class="title">Cambiar clave</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main_container">
            <aside class="lista_calendario">
                <div id="calendar"></div>
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
                  <th>Encargado por</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $encargos = mysqli_query($conexion,"SELECT encargos.id_encargo,encargos.id_usuario,usuarios.nombre_usuario as responsable,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,estado FROM encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_usuario where encargos.id_mensajero = $id_usuario order by estado desc,id_encargo asc;");
                  if(mysqli_num_rows($encargos) > 0){
                    while($listar = mysqli_fetch_array($encargos)){
                      $id_encargo = $listar[0];
                      $estado = $listar[7];
                      $descripcion = $listar[3];
                      $mensajero = $listar[2];
                      $fec_requerida = $listar[5];
                ?>
                <tr class="<?php echo $estado; ?>">
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
                  }else{
                     ?>
                     <tr aria-colspan="3">
                      <td colspan="3">No se encontro nada</td>
                     </tr>
                     <?php
                  }
                ?>
              </tbody>
            </table>
          </div>
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
                  <input type="file" name="foto_evide" id="foto_opc1" accept=".jpg, .png" >
                  <input type="button" name="foto_evide2" id="foto_opc2" accept=".jpg, .png" class="foto_opcional" value="+">
                  <input type="button" name="foto_evide3" id="foto_opc3" accept=".jpg, .png" class="foto_opcional" value="+">
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
      <?php
        include('extensiones/modalUpdateEvento.php');
      ?>
      </div>
        <div class="footer" style="align-content: center;text-align: center">
            <img src="../assets/images/logo-blanco.png" alt="" style="max-height: 100px;margin-bottom: 20px;">
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
    ?>

    <?php
      include ('notificacion/ModeloMensaje.php');
      $message = new ModeloMensaje;
      $datos = $message->listar();
    ?>

    <aside class="tabla_notificacion" id="tabla_notificacion">
        <div class="space_btn_oct"><div class="icono_ocultar"></div></div>
    <table class="tabla_encargos" border="0" cellspacing="10px">
        <thead>
        <tr>
            <th>Estado</th>
            <th>Descripcion</th>
            <th>Encargado por</th>
            <th>Fecha entrega</th>
        </tr>
        </thead>
        <tbody id="bodytable">
        </tbody>
    </table>
</aside>
  </div> <!-- /container -->


    <script src ="../assets/js/jquery-3.0.0.min.js"> </script>

    <script type="text/javascript" src="../assets/js/moment.min.js"></script>
    <script type="text/javascript" src="../assets/js/fullcalendar.min.js"></script>
    <script src="../assets/js/es.js"></script>
    <script src="../assets/js/app.js"></script>
  <script src="../assets/js/loader.js"></script>
    <script>
      function myFunction(){
  const el = document.querySelector('#loading');
  
  el.classList.add('loading2');
}
        $("#open_notificacion").click(function(){
            var myVar = setInterval(ciclo, 5000);

            if( $( ".tabla_notificacion" ).hasClass( "mostrar_notificacion" ) ) {
                var myVar = setInterval(ciclo, 5000);
                $(".tabla_notificacion").toggleClass("mostrar_notificacion");
            }
            else{
                var myVar = setInterval(ciclo, 4000);
                function ciclo() {
                    $.get("notificacion/api.php", function (data) {
                        const prueba = JSON.parse(data);
                        if (prueba.success == true) {
                            console.log("actualizado exitosamente")
                            prueba.registro.map((columna)=>{
                                $( "#bodytable" ).append( "<tr><td>"+columna.Estado+"</td><td>"+columna.descripcion+"</td><td>"+columna.responsable+"</td><td>"+columna.fecha_requerida+"</td></tr>" );
                                //$("#bodytable").hide().appendTo("<tr><td>"+columna.Id_encargo+"</td><td>"+columna.descripcion+"</td><td>"+columna.Estado+"</td></tr>").show('normal');
                            })
                        } else {
                            console.log("no hay datos " + prueba.success)
                        }
                    });
                }
            $(".tabla_notificacion").toggleClass("mostrar_notificacion");
            }
        });
        $(".space_btn_oct").click(function(){
            $(".tabla_notificacion").toggleClass("mostrar_notificacion");
        });

        function cmb_estado(verencargo){
            var id = $(verencargo).data("id");
            var cod = document.getElementById(id).value;

            var combo = document.getElementById(id);
            var selected = combo.options[combo.selectedIndex].text;


            $(".list_enc").toggleClass("show_confirmacion");
            var descripcion = document.getElementById("conf_descripcion"+id).value;

            $('input[name=accept_id').val(id);
            $('textarea[name=accept_descripcion').val(descripcion);
            $('input[name=accept_estado').val(selected);

        }
        function oct_estado(){
            $(".list_enc").toggleClass("show_confirmacion");
        }

            $(".contenedor_mayor").toggleClass("collapse");
            $(".hamburger").click(function(){
                $(".contenedor_mayor").toggleClass("collapse");
            });
            $(".show_todos").click(function(){
                $(".list_enc").toggleClass("show_tablas");
                $(".lista_realizados").removeClass("show_tablas");
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

        $(document).ready(function() {
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
                  while($dataEvento = mysqli_fetch_array($resulEventos)){
                  $estado = $dataEvento['Estado'];
                  $final = date("Y-m-d",strtotime($dataEvento['Fecha_requerida']));
                  if ($estado == "pendiente"){ $color = "#FFEC6DC1"; }elseif($estado == "completado"){ $color = "#A7FF6DC1"; }else{ $color = "#FF6D6DC3"; } ?>
                    {
                        _id: '<?php echo $dataEvento['Id_encargo']; ?>',
                        title: '<?php echo $dataEvento['descripcion']; ?>',
                        start: '<?php echo $final; ?>',
                        end:   '<?php echo $dataEvento['Fecha_requerida']; ?>',
                        color: '<?php echo $color; ?>',
                    },
                  <?php } ?>
                ],

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


                eventClick:function(event){
                    var idEvento = event._id;
                    $('input[name=idEvento').val(idEvento);
                    $('textarea[name=evento').val(event.title);
                    $('input[name=fecha_inicio').val(event.start.format('DD / MM / YYYY'));

                    $(".modal").addClass("show_modal");
                },
            });


            setTimeout(function () {
                $(".alert").slideUp(300);
            }, 3000);

        });




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

          $("input#foto_opc2").click(function(){
          $(this).val('');
          $(this).get(0).type = 'file';
          $("input#foto_opc2").removeClass("foto_opcional");
        });
        $("input#foto_opc3").click(function(){
          $(this).val('');
          $(this).get(0).type = 'file';
          $("input#foto_opc3").removeClass("foto_opcional");
        });
        function oct_estado(){
            $(".list_enc").toggleClass("show_confirmacion");
        }
    </script>
  </body>
</html>
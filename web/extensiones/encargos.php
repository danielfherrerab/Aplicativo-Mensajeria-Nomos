<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administrador</title>
  </head>
  <body>
    <?php
      include_once '../bd/conexion.php';
      $Rol = $_SESSION['Id_rol'];
      $id_usuario = $_SESSION['Id_usuario'];
      $nombre_usuario = $_SESSION['Nombre_usuario'];

      $mes_inicio = date("Y-m-01");
			$date = new DateTime('now');
			$date->modify('last day of this month');
			$mes_final =  $date->format('Y-m-d');
      $meses = ['Default','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    ?>

	<aside class="lista_completa">
        <p id="lista_completa"><br><br><br></p>
		<div class="lista_encargos">
        <div class="encabezado_lista">
          <h2>Todos los encargos</h2>
          <h5>Encargos del mes</h5>
        </div>

            <aside class="contenido_lista">
                <table class="tabla_encargos" border="0" cellspacing="10px" id="tabla_prioridad">
            <thead>
              <tr>
                <th>Estado</th>
                <th>Descripcion</th>
                <th>Mensajero</th>
                <th>Fecha requerida</th>
                <th>Fecha de completado</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $encargos = mysqli_query($conexion,"SELECT encargos.id_encargo,id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,estado,fecha_completado FROM encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_mensajero  where (fecha_requerida between '$mes_inicio' and '$mes_final') order by estado desc,fecha_completado desc");
                if($_SESSION['Id_rol'] == 2){
                  $encargos = mysqli_query($conexion,"SELECT encargos.id_encargo,id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,estado,fecha_completado FROM encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_mensajero where encargos.id_usuario = $id_usuario and fecha_requerida between '$mes_inicio' and '$mes_final' order by estado,fecha_requerida desc");
                }
                if(mysqli_num_rows($encargos) > 0){
                  while($listar = mysqli_fetch_array($encargos)){
                    $id_encargo = $listar[0];
                    $estado = $listar[7];
                    $descripcion = $listar[3];
                    $mensajero = $listar[2];
                    $fec_requerida = $listar[5];
                    $fec_completado = $listar[8];
                    $mes_hecho 	= date("m",strtotime($fec_completado));
                    $dia_hecho 	= date("d",strtotime($fec_completado));
                    $year_hecho 	= date("Y",strtotime($fec_completado));
              ?>
              <tr class='<?php echo $estado; ?>'>
                <td>
                  <select name="estado" id="estados" onchange="cmb_estado()">
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
                </td>
                <td><?php echo $descripcion; ?></td>
                <td><?php echo $mensajero; ?></td>
                <td><?php echo date("d",strtotime($fec_requerida))." / ".$meses[date("n",strtotime($fec_requerida))]." / ".date("Y",strtotime($fec_requerida)); ?></td>
                <td><?php if($fec_completado <= "2018-00-00"){ echo "<b><i>sin completar</i></b>"; } else { echo $dia_hecho." / ".$meses[$mes_hecho]." / ".$year_hecho; } ?></td>
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
				</aside>
			</div>
		</aside>

    <!-- ENCARGOS REALIZADOS -->

    <aside class="lista_realizados">
        <p id="lista_realizados"><br><br><br></p>
			<div class="lista_encargos">
        <div class="encabezado_lista">
          <h2>Encargos realizados</h2>
        </div>

				<aside class="contenido_lista">
                    <table class="tabla_encargos" border="0" cellspacing="10px">
            <thead>
              <tr>
                <th>Estado</th>
                <th>Descripcion</th>
                <th>Mensajero</th>
                <th>Fecha requerida</th>
                <th>Fecha de completado</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $encargos = mysqli_query($conexion,"SELECT encargos.id_encargo,id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,estado,fecha_completado FROM encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_mensajero where estado = 'completado' and fecha_requerida between '$mes_inicio' and '$mes_final' order by fecha_completado desc");
                if($_SESSION['Id_rol'] == 2){
                  $encargos = mysqli_query($conexion,"SELECT encargos.id_encargo,id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,estado,fecha_completado FROM encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_mensajero where encargos.id_usuario = $id_usuario and estado = 'completado' and fecha_requerida between '$mes_inicio' and '$mes_final' order by fecha_completado desc");
                }
                if(mysqli_num_rows($encargos) > 0){
                  while($listar = mysqli_fetch_array($encargos)){
                    $id_encargo = $listar[0];
                    $estado = $listar[7];
                    $descripcion = $listar[3];
                    $mensajero = $listar[2];
                    $fec_requerida = $listar[5];
                    $fec_completado = $listar[8];
                    $mes_hecho 	= date("n",strtotime($fec_completado));
                    $dia_hecho 	= date("d",strtotime($fec_completado));
                    $year_hecho 	= date("Y",strtotime($fec_completado));
              ?>
              <tr class='<?php echo $estado; ?>'>
                <td>
                  <select name="estado" id="estados" onchange="cmb_estado()">
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
                </td>
                <td><?php echo $descripcion; ?></td>
                <td><?php echo $mensajero; ?></td>
                <td><?php echo date("d",strtotime($fec_requerida))." / ".$meses[date("n",strtotime($fec_requerida))]." / ".date("Y",strtotime($fec_requerida)); ?></td>
                  <td><?php echo $dia_hecho." / ".$meses[$mes_hecho]." / ".$year_hecho; ?></td>
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
				</aside>
			</div>
		</aside>

  </body>
</html>
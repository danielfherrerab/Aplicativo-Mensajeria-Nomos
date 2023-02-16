<?php

  include ('ModeloMensaje.php');

  $message = new ModeloMensaje;
  $datos = $message->consulta_mensaje_noleido();

  if ($datos->num_rows>=1){

    $datanew = [];

    foreach ($datos as  $value) {
      // code...
      $datacol['responsable']  = $value['responsable'];
      $datacol['descripcion']  = $value['descripcion'];
      $datacol['Estado']  = $value['estado'];
      $datacol['fecha_requerida']  = $value['fecha_requerida'];

      $message->update_mensaje($value['id_encargo']);

      array_push($datanew,$datacol);
    }

    $response['registro'] = $datanew;
    $response['success'] = true;
  }
  else {
    $response['registro'] = null;
    $response['success'] = false;
  }

  // respuesta eb json
  echo json_encode($response);

?>

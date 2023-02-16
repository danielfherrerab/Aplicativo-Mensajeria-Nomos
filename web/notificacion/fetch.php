<?php

include("../../bd/conexion.php");
session_start();
$id_usuario = $_SESSION['Id_usuario'];
$nombre_usuario = $_SESSION['Nombre_usuario'];

if(isset($_POST["view"]))
{
 $output = '';
 $query_1 = $conexion->query("SELECT * FROM encargos where id_mensajero = $id_usuario and visto = 0");
 $count = $query_1->num_rows;
 $data = array(
  'notification'   => $output,
  'unseen_notification' => $count
 );
 echo json_encode($data);
}
?>
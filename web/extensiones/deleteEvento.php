<?php
require_once('../../bd/conexion.php');
$id    		= $_REQUEST['id']; 

$sqlDeleteEvento = ("DELETE FROM encargos WHERE  id_encargo=$id");
$resultProd = mysqli_query($conexion, $sqlDeleteEvento);

?>
  
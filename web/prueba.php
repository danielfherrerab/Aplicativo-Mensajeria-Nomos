<?php
include '../bd/conexion.php';
 $output = '';
 $query_1 = $conexion->query("SELECT * FROM encargos where visto = 0");
 $count = $query_1->num_rows;
 $data = array(
  'notification'   => $output,
  'unseen_notification' => $count
 );
 echo json_encode($data);


 $arr=$Dni->get('67828282', true);

foreach ($arr as $row)
{
    echo $row["dni"]."<br />";
    echo $row["nombres"]."<br />";
    echo $row["apellido_paterno"]."<br />";
    echo $row["apellido_materno"]."<br />";
}
 ?>
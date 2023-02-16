<?php
  require_once ('../Database.php');
  require_once ('../Tarea.php');

  $data = $_POST['data'];
  $tareasOrdenadas = json_decode($data);

  var_dump($tareasOrdenadas);

  (new Tarea())->ordenarTareas($tareasOrdenadas);

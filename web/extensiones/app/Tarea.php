<?php
class Tarea extends Database{
	public function __construct() {
		parent::__construct();
	}

	public function __destruct() {
		$this->closeConnection();
	}

	public function getTareas() {
		$Rol = $_SESSION['Id_rol'];
		$id_usuario = $_SESSION['Id_usuario'];
		$fecha_hoy      = date("Y-m-d");
		$res = $this->query("SELECT encargos.id_encargo,id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,estado,prioridad FROM encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_mensajero  where fecha_requerida = '$fecha_hoy' order by prioridad asc");
		if($Rol == 2){
			$res = $this->query("SELECT encargos.id_encargo,id_mensajero,usuarios.nombre_usuario as mensajero,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,estado,prioridad FROM encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_mensajero where encargos.id_usuario = $id_usuario and fecha_requerida = '$fecha_hoy' order by prioridad asc");
		}
		
    return $this->fetchData($res);
	}

  public function ordenarTareas($tareasOrdenadas) {
    foreach ($tareasOrdenadas as $tarea) {
      $this -> query("UPDATE encargos SET prioridad = $tarea->orden WHERE id_encargo = $tarea->id");
    }
  }
}
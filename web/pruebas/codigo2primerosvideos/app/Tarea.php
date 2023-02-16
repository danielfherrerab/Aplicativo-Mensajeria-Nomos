<?php

class Tarea extends Database{
	public function __construct() {
		parent::__construct();
	}

	public function __destruct() {
		$this->closeConnection();
	}

	public function getTareas() {
		$res = $this->query("SELECT * FROM encargos order by prioridad asc");
    return $this->fetchData($res);
	}

  public function ordenarTareas($tareasOrdenadas) {
    foreach ($tareasOrdenadas as $tarea) {
      $this -> query("UPDATE encargos SET prioridad = $tarea->orden WHERE id_encargo = $tarea->id");
    }
  }
}
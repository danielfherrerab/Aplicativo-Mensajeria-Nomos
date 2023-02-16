<?php
	$DB_HOST = $_ENV['DB_HOST'];
	$DB_USER = $_ENV['DB_USER'];
	$DB_PASSWORD = $_ENV['DB_PASSWORD'];
	$DB_NAME = $_ENV['DB_NAME'];
	$DB_PORT = $_ENV['DB_PORT'];

	$conexion=mysqli_connect($DB_HOST,$DB_USER,$DB_PASSWORD,$DB_NAME,$DB_PORT);
	class Conexion extends mysqli {

		public function __construct() {
			parent::__construct($BD_HOST,$BD_USER,$BD_PASSWORD,$BD_NAME,$BD_PORT);
			$this->query("SET NAMES 'utf8';");
			$this->connect_errno ? die ('Error con la conexion') : $exito = 'Conectado';
			//echo $exito;
			unset($exito);

		}

	}
?>
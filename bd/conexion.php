<?php
	$BD_HOST = $_ENV['BD_HOST'];
	$BD_NAME = $_ENV['BD_NAME'];
	$BD_PASSWORD = $_ENV['BD_PASSWORD'];
	$BD_PORT = $_ENV['BD_PORT'];
	$BD_USER = $_ENV['BD_USER'];

	class Conexion extends mysqli {

		public function __construct() {
			parent::__construct($BD_HOST,$BD_USER,$BD_PASSWORD,$BD_NAME);
			$this->query("SET NAMES 'utf8';");
			$this->connect_errno ? die ('Error con la conexion') : $exito = 'Conectado';
			//echo $exito;
			unset($exito);

		}

	}
  $conexion=mysqli_connect($BD_HOST,$BD_USER,$BD_PASSWORD,$BD_NAME) or die ('problemas en la conexion');
?>
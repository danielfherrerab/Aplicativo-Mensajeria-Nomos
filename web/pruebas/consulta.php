<!DOCTYPE html>
<html lang="es">
	<head>

	</head>

	<body>
		<?php
			/////// CONEXIÓN A LA BASE DE DATOS /////////
			include_once '../../bd/conexion.php';
			session_start();
			date_default_timezone_set("America/Bogota");
			setlocale(LC_ALL,"es_ES");
			$fecha_hoy 		= date("Y-m-d H:i:s");
			$mes_hoy 			= date("m");
			$dia 					= date('Y-m-01');
			$fecha 				= date("Y-m-d H:i:s");
			//////////////// VALORES INICIALES ///////////////////////
			error_reporting(0);
			$tabla = "";
			$buscarArticulos = mysqli_query($conexion,"SELECT * from encargos");
			while($lista = mysqli_fetch_array($buscarArticulos)){}
			$query = "SELECT * from encargos";

			///////// LO QUE OCURRE AL TECLEAR SOBRE EL INPUT DE BUSQUEDA ////////////
			if(isset($_POST['alumnos'])) {
				$q 			= $conexion->real_escape_string($_POST['alumnos']);
				$query 	= "SELECT * from encargos";
			}

			$buscarAlumnos=$conexion -> query($query);

			$tabla .= '
			<table border="0" cellspacing="10" class="datos_planchas">
				<tr>
					<th>Numero artic</th>
					<th>Descripcion	</th>
					<th>Referencia	</th>';


			if ($buscarAlumnos->num_rows > 0) {
				while($filaAlumnos= $buscarAlumnos->fetch_array()) {
                  $articulo = $filaAlumnos[0];
                  $descripcion = $filaAlumnos[1];
                  $referencia = $filaAlumnos[2];

                  // $columna_consumo = "Consumo_$articulo";
                  // $columna_inventario= "Inventario_$articulo";
                  // $consulta_final = "SELECT $columna_consumo,$columna_inventario,($columna_inventario - $columna_consumo) as total FROM tabla_final where Id_Mes = $mes_hoy";
                  $tabla .= '
				</tr>
				<tr>
						<td class="cont_msg"><a href="principal.php?articu=' . $articulo . '">' . $articulo . '</a><div class="msj_emgt">Elegir articulo</div></td>
						<td class="desc-tabla">' . $descripcion . '	</td>
						<td class="desc-tabla">' . $referencia . '	</td>';

                  $tabla .= '</table>';
                }
                }
			else {
				$tabla='
				<table border="1" cellspacing="0">
					<tr>
						<td colspan="2">No se encontraron coincidencias con sus criterios de búsqueda.</td>
					</tr>';
			}
			echo $tabla;
		?>
	</body>
</html>
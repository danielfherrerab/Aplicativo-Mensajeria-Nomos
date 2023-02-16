<?php
	include_once 'bd/conexion.php';
	date_default_timezone_set("America/Bogota");
	setlocale(LC_ALL,"es_ES");
	// $dia = date('Y-m-01 H:i:s');
	// $fecha = date("Y-m-d H:i:s");
	session_start();
	if (isset($_GET['close_login'])){
		session_unset();
		unset($_SESSION["Id_usuario"]);
		session_destroy();//header('Location:../login.php');
		echo "<script> window.location.href ='index.php';</script>";
	}
	if (isset($_SESSION['Id_rol'])){
		switch($_SESSION['Id_rol']) {
			case 1:
				header('Location: web/principal_adt.php');		break;
			case 2:
				header('Location: web/principal_enc.php');			break;
			case 3:
				header('Location: web/principal_msg.php');			break;
		}
	}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="assets/css/style.css">
		<title>Iniciar sesion</title>
  	</head>

  	<body>
		<div class="loading" id="loading"><div class="cargador"></div></div>
    <div class="logo_superior"></div>
		<div class="inicio_sesion">
			<h1>Iniciar Sesion</h1>
			<fieldset>
                <legend>Ingreso de datos</legend>
				<form action="" method="POST">
					<h3>Digite su correo electronico</h3>
					<input type="email" placeholder="Ingrese su correo electronico" name="correo_usuario" required class="btn_texto"><br><br>
					<h3>Digite su contraseña</h3>
					<input type="password" placeholder="Ingrese su contraseña" name="clave_usuario" required><br><br><br>
					<input type="submit" value="INGRESAR">
				</form>
			</fieldset>

    <?php
			if (isset($_POST['correo_usuario']) && isset($_POST['clave_usuario'])) {
				$username = mysqli_real_escape_string($conexion, $_POST["correo_usuario"]);
				$password = mysqli_real_escape_string($conexion, $_POST["clave_usuario"]);  
				$query = "SELECT * FROM usuarios WHERE correo_usuario = '$username'";  
				$result = mysqli_query($conexion, $query); 

				if(mysqli_num_rows($result) >= 1)  
				{  
					while($row = mysqli_fetch_array($result))  {  
						if(password_verify($password, $row["Clave_usuario"]))  {  
							if (isset($_POST['correo_usuario']) && isset($_POST['clave_usuario'])) {
								$username = $_POST['correo_usuario'];
								$password = $_POST['clave_usuario'];
								
								$query 		= mysqli_query($conexion,"SELECT * FROM usuarios WHERE correo_usuario = '$username'");
								
								
								if ($row == true) {
									$Rol					= $row[4];
									$_SESSION['Id_rol'] = $Rol;

									switch($Rol) {
										case 1:
											echo "<script> window.location.href = 'web/principal_adt.php'; </script>";		break;
										case 2:
											echo "<script> window.location.href = 'web/principal_enc.php'; </script>";		break;
										case 3:
											echo "<script> window.location.href = 'web/principal_msg.php'; </script>";		break;
									}

									$id_usuario						= $row[0];
									$_SESSION['Id_usuario'] 		= $id_usuario;
									$correo_usuario 				= $row[2];
									$_SESSION['Correo_usuario'] 	= $correo_usuario;
									$nombre_usuario 				= $row[1];
									$_SESSION['Nombre_usuario'] 	= $nombre_usuario;
									echo $correo_usuario;

								}
								else{
									echo "<div class='msg_erroneo'>El usuario puede que no exista o el correo electronico o la contraseña es invalida!</div>";
								}
							}
						}  
						else  {  
							echo "<div class='msg_erroneo'>El usuario puede que no exista o el correo electronico o la contraseña es invalida!</div>";
						}  
					}  
				}  
				else  
				{  
					echo "<div class='msg_erroneo'>El usuario puede que no exista o el correo electronico o la contraseña es invalida!</div>";
				} 
			}
		?>
        </div>
				<script src="assets/js/jquery-3.0.0.min.js"></script>
				<script src="assets/js/loader.js"></script>
  </body>
</html>
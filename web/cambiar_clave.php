<?php
	include_once 'conexion/conexion.php';
	session_start();
	if(!$_SESSION){
	header('location: index.php');
	}
	if(!isset($_SESSION['correo'])){
		header('location: index.php');
	}
	
	$correo_usuario = $_SESSION['correo'];
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- css -->
		<link rel="stylesheet" href="assets/css/main.css">
		<link rel="shortcut icon" href="assets/imagenes/LOGO.png" />
		<title>Cambiar contraseña - NOMOS</title>
	</head>

	<body>
		<div class="logo_superior"></div>
		<div class="inicio_sesion">
			<h1>CAMBIO DE CONTRASEÑA</h1>

			<?php if(isset($_POST['cambiar_clave'])){ ?>
			<fieldset>
				<form action="#" method="post">
				<h4>Ingrese la informacion de su cuenta</h4>
					<h3> Digite su correo electronico </h3>
					<input type="email" 		placeholder="Ingrese su correo electronico" name="Correo_Usuario" required>
					<h3> Digite su anterior contraseña</h3>
					<input type="password" 	placeholder="Ingrese su contraseña" 				name="Clave_Usuario" 	required><br>
					<input type="submit" 		value="INGRESAR" 		name="recuperar_clave">
				</form>
			</fieldset>
			<?php } ?>
		
			<?php 
				if(isset($_POST['recuperar_clave'])) {
					if(isset($_POST['Correo_Usuario']) && isset($_POST['Clave_Usuario'])) {
						$username = mysqli_real_escape_string($conexion, $_POST["Correo_Usuario"]);  
						$password = mysqli_real_escape_string($conexion, $_POST["Clave_Usuario"]);  
						$query  = "SELECT * FROM usuarios WHERE correo = '$username'";  
						$result = mysqli_query($conexion, $query); 

						if(mysqli_num_rows($result) >= 1) {  
							while($row = mysqli_fetch_array($result)) {  
								if(password_verify($password, $row["clave"])) {  
			?>

			<fieldset>
				<form action="#" method="post">
					<h3>Escriba su nueva contraseña</h3>
					<input type="password" placeholder="Ingrese su nueva contraseña" name="nueva_clave" required>
					<h3>Confirme su nueva contraseña</h3>
					<input type="password" placeholder="Repita su contraseña" 			 name="confirma_clave" required><br>
					<input type="submit" 	 value="CAMBIAR CLAVE" name="confirmacion">
				</form>
			</fieldset>

			<?php
								}
								else {
									echo "<div class='mensaje'>El usuario puede que no exista o el correo electronico o la contraseña es invalida!</div>";
								}
							}
						}  
						else {  
							echo "<div class='mensaje'>El usuario puede que no exista o el correo electronico o la contraseña es invalida!</div>";
						}  
					}  
				}

				if(isset($_POST['confirmacion'])) {
					$nueva_clave 		= $_POST['nueva_clave'];
					$confirma_clave = $_POST['confirma_clave'];

					if($nueva_clave != $confirma_clave) {
						echo "Las contraseñas no coinciden";
					}
					else {
						$nueva_clave 	 = password_hash($nueva_clave, PASSWORD_DEFAULT);  
						$cambiar_clave = mysqli_query($conexion,"UPDATE usuarios set clave = '$nueva_clave' where correo = '$correo_usuario'");
						
						if($cambiar_clave){
							echo "<script> alert('Se ha cambiado la contraseña correctamente, vuelva a iniciar sesion'); window.location.href ='index.php';</script>";
							session_unset();
							unset($_SESSION["Correo_Usuario"]);
							session_destroy();//header('Location:../login.php');
						}
						else{
							echo "<script> alert('No se podido cambiar la contraseña, vuelva a intentarlo'); window.location.href ='cambiar_clave.php';</script>";
						}
					}
				}
			?>
			<a href="index.php" id="show-modal"><button>VOLVER</button></a>
		</div>
		<script src="assets/js/main.js"></script>
  </body>
</html>

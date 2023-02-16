<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>

<?php
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
  else{
      header('Location: ../index.php');
  }
?>

</body>
</html>
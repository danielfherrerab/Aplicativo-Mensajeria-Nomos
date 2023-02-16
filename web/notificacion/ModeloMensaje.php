<?php

class ModeloMensaje  {

  public function conectar_bd()
  {
    $servername = $_ENV['DB_HOST'];
    $username = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASSWORD'];
    $dbname = $_ENV['DB_NAME'];
    /* crear la conexión */
    $conn = new mysqli($servername, $username, $password, $dbname, $_ENV['DB_PORT']);
    /* comprobar la conexión */
    if ($conn->connect_error) {
        die("Falló la conexión:: " . $conn->connect_error);
    }
    else
    {
      return $conn;
    }
  }

  public function listar(){
    $conn =  $this->conectar_bd();

    $consulta = "SELECT encargos.id_encargo,encargos.id_usuario,usuarios.nombre_usuario as responsable,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,estado FROM encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_usuario where encargos.id_mensajero = 2 and visto=0 ORDER by Id_encargo asc";
    $res = $conn->query($consulta);
    //$res = mysqli_query($enlace, $consulta);
    return $res;
  }

  public function consulta_mensaje_noleido(){
    $conn =  $this->conectar_bd();

    $consulta = "SELECT encargos.id_encargo,encargos.id_usuario,usuarios.nombre_usuario as responsable,encargos.descripcion,fecha_registrada,fecha_requerida,observacion,estado FROM encargos INNER JOIN usuarios on usuarios.id_usuario = encargos.id_usuario where encargos.id_mensajero = 2 and visto=0 ORDER by Id_encargo asc";
    $res = $conn->query($consulta);
    return $res;
  }

  public function update_mensaje($Id_encargo){
    $conn =  $this->conectar_bd();
    $consulta = "UPDATE encargos SET visto=1 WHERE Id_encargo = $Id_encargo ";
    $res = $conn->query($consulta);
    //return $res;
  }
}

?>

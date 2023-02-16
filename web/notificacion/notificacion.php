<html>
  <head>
    <title></title>
    <meta content="">
    <script src="../../assets/js/jquery-3.0.0.min.js"></script>
  </head>
  <body>

      <?php
        include ('ModeloMensaje.php');
        $message = new ModeloMensaje;
        $datos = $message->listar();
      ?>

      <table class="tabla_encargos" border="0" cellspacing="10px">
          <thead>
          <tr>
              <th>Estado</th>
              <th>Descripcion</th>
              <th>Encargado por</th>
          </tr>
          </thead>
        <tbody id="bodytable">
        </tbody>
      </table>

    </div> <!-- /container -->

    <script>
    var myVar = setInterval(ciclo, 5000);

    function ciclo (){

     $.get( "api.php", function( data ) {

        const prueba = JSON.parse(data);

        if (prueba.success==true) {

          console.log("actualizado exitosamente")

          prueba.registro.map((columna)=>{

            $( "#bodytable" ).append( "<tr><td>"+columna.Estado+"</td><td>"+columna.descripcion+"</td><td>"+columna.responsable+"</td></tr>" );
            //$("#bodytable").hide().appendTo("<tr><td>"+columna.Id_encargo+"</td><td>"+columna.descripcion+"</td><td>"+columna.Estado+"</td></tr>").show('normal');

          })

        }
        else{
          console.log("no hay datos "+prueba.success)
        }

      });

    }
    </script>
  </body>
</html>

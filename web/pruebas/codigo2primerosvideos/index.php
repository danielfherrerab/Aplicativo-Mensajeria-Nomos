<?php
	
	require_once('app/Database.php');
	require_once('app/Tarea.php');
	
	$tareas = (new Tarea())->getTareas();

?>

<!DOCTYPE html>
<html>
<head>
	<title>Ordenar</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div class="container">
		<div class="form">
			<div class="form-control">
				<input type="text" placeholder="Escribe la tarea y presiona Enter" id="txtNuevaTarea">
			</div>
		</div>

		<ul class="tareas" id="tareas">
			<?php  foreach ($tareas as $tarea):
              ?>
				<li class="tarea" data-id="<?php echo $tarea['Id_encargo']; ?>">
					<?php echo $tarea['descripcion']; ?>
				</li>
			<?php
                endforeach;
            ?>
		</ul>
	</div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/draggable/1.0.0-beta.8/sortable.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.0/axios.min.js"></script>


    <script>
        window.onload = () => {
            const sortable = new Sortable.default(document.querySelectorAll('ul'), {
                draggable: 'li'
            });

            sortable.on('sortable:stop', () => {
                setTimeout(() => {
                    const tareas = document.getElementsByClassName('tarea');
                    const sortedData = new Array();

                    [...tareas].forEach((tarea, index) => {
                        sortedData.push({
                            id: tarea.getAttribute('data-id'),
                            orden: (index + 1)
                        });
                    });

                    let formData = new FormData();
                    formData.append('data', JSON.stringify(sortedData));

                    axios.post('app/api/ordenarTareas.php',formData)
                        .then(res => console.log(res))
                        .catch(err => console.log(err));

                }, 100);
            });

        }
    </script>
</body>
</html>
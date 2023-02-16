$(obtener_registros());

function obtener_registros(registros)
{
	$.ajax({
		url : 'consulta_registros.php',
		type : 'POST',
		dataType : 'html',
		data : { registros: registros },
		})

	.done(function(resultado){
		$("#tabla_registros").html(resultado);
	})
}

$(document).on('keyup', '#busqueda_registros', function()
{
	var valorBusqueda=$(this).val();
	if (valorBusqueda!="")
	{
		obtener_registros(valorBusqueda);
	}
	else
		{
			obtener_registros();
		}
});
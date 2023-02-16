$(obtener_ordenes());

function obtener_ordenes(ordenes)
{
	$.ajax({
		url : 'consulta_consumos.php',
		type : 'POST',
		dataType : 'html',
		data : { ordenes: ordenes },
		})

	.done(function(resultado){
		$("#tabla_ordenes").html(resultado);
	})
}

$(document).on('keyup', '#busqueda_OP', function()
{
	var valorBusqueda=$(this).val();
	if (valorBusqueda!="")
	{
		obtener_ordenes(valorBusqueda);
	}
	else
		{
			obtener_ordenes();
		}
});
$(obtener_paginacion());

function obtener_paginacion(paginacion)
{
	$.ajax({
		url : 'consulta_paginacion.php',
		type : 'POST',
		dataType : 'html',
		data : { paginacion: paginacion },
		})

	.done(function(resultado){
		$("#tabla_paginacion").html(resultado);
	})
}

$(document).on('keyup', '#busqueda_paginacion', function()
{
	var valorBusqueda=$(this).val();
	if (valorBusqueda!="")
	{
		obtener_paginacion(valorBusqueda);
	}
	else
		{
			obtener_paginacion();
		}
});
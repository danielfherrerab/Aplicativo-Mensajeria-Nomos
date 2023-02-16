$(obtener_inventario());

function obtener_inventario(inventario)
{
	$.ajax({
		url : 'consulta_inventario.php',
		type : 'POST',
		dataType : 'html',
		data : { inventario: inventario },
		})

	.done(function(resultado){
		$("#tabla_inventario").html(resultado);
	})
}

$(document).on('keyup', '#busqueda_inventario', function()
{
	var valorBusqueda=$(this).val();
	if (valorBusqueda!="")
	{
		obtener_inventario(valorBusqueda);
	}
	else
		{
			obtener_inventario();
		}
});
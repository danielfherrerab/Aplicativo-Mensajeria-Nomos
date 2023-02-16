
			//Mensajes Recibidos
			$(document).ready(function(){
			
			//MOSTRAR NOTIFICACIONES
			
			function cargarNotificaciones(view = '')
			 {
				var $mensaje =  $('.mensaje');
				$.ajax({
				 url:"notificacion/fetch.php",
				 method:"POST",
				 data:{view:view},
				 dataType:"json",
				 success:function(data)
				 {
					$('#notify').html(data.notification);
			
					if(data.unseen_notification > 0)
					{
					 $('#recibidos').html(data.unseen_notification);
					}
			
					else{
						 $('#count').html('');
						$('#title').html('app_msg');
					}

				 }
			
				});
			
			 }
			
			 setInterval(function(){ 
				cargarNotificaciones();; 
			 }, 5000);
			 
			});
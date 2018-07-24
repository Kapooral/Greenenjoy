$(function() {

	$('[name=greenenjoy_postbundle_comment]').on('submit', function(e){
		var form = $(this);
		var type = form.attr('method');
		var data = {};

		form.find('[name]').each(function(index, value) {
			var input = $(this);
			var name = input.attr('name');
			var value = input.val();
			data[name] = value;
		});

		$.ajax({
			type: type,
			url: location.href,
			data: data,
			dataType: 'JSON',
			success: function(data){
				swal({
				    icon: 'success',
				    text: data.message,
				    button: false,
				    timer: 2000
			    });

			    setTimeout(function(){ location.reload(); }, 1500);
			},
			error: function(response){
				swal({
					icon: 'error',
					text: 'Votre commentaire n\'a pas été posté.',
					timer: 2000
				});
			}
		});

		e.preventDefault();
	});

});

function reportComment(id, target, token){
	
	swal({
	    icon: 'warning',
	    text: 'Voulez-vous signaler ce commentaire ?',
	    buttons: ['Annuler', 'Signaler']
	}).then((result) => {
		if (result){

			var data = {'comment_id': id, 'authenticate': token};

			$.ajax({
				type: 'POST',
				url: target,
				data: data,
				dataType: 'JSON',
				success: function(data){
					if(data.success){
						swal({
						    icon: 'success',
						    text: data.message,
						    button: false,
						    timer: 2000
					    });
					}
					else
					{
						swal({
						    icon: 'error',
						    text: data.message,
						    button: false,
						    timer: 2000
					    });
					}
				},
				error: function(data){
					swal({
						icon: 'error',
						text: data,
						button: false,
						timer: 1500
					});
				}
			});
		}
	});
}


function authorizeComment(id, target, token){

	swal({
	    icon: 'warning',
	    text: 'Voulez-vous autoriser ce commentaire ?',
	    buttons: ['Annuler', 'Autoriser']
	}).then((result) => {
		if (result){

			var data = {'comment_id': id, 'authenticate': token};

			$.ajax({
				type: 'POST',
				url: target,
				data: data,
				dataType: 'JSON',
				success: function(data){
					if(data.success){
						swal({
						    icon: 'success',
						    text: data.message,
						    button: false,
						    timer: 2000
					    });

					    setTimeout(function(){ location.reload(); }, 1500);
					}
					else
					{
						swal({
						    icon: 'error',
						    text: data.message,
						    button: false,
						    timer: 2000
					    });
					}
				},
				error: function(data){
					swal({
						type: 'error',
						text: data,
						button: false,
						timer: 2000
					});
				}
			});
		}
	});	
}


function deleteComment(id, target, token){

	swal({
	    icon: 'warning',
	    text: 'Voulez-vous supprimer ce commentaire ?',
	    buttons: ['Annuler', 'Supprimer']
	}).then((result) => {
		if (result){

			var data = {'comment_id': id, 'authenticate': token};

			$.ajax({
				type: 'POST',
				url: target,
				data: data,
				dataType: 'JSON',
				success: function(data){
					if(data.success){
						swal({
						    icon: 'success',
						    text: data.message,
						    button: false,
						    timer: 2000
					    });

					    setTimeout(function(){ location.reload(); }, 1500);
					}
					else
					{
						swal({
						    icon: 'error',
						    text: data.message,
						    button: false,
						    timer: 2000
					    });
					}
				},
				error: function(data){
					swal({
						type: 'error',
						text: data,
						button: false,
						timer: 2000
					});
				}
			});
		}
	});
}


function deletePost(id, token){

	swal({
	    type: 'warning',
	    text: 'Voulez-vous supprimer cet article ?',
	    showCancelButton: true,
	    cancelButtonText: 'Non',
	    cancelButtonColor: '#a94442',
	    confirmButtonColor: '#3c763d',
	    confirmButtonText: 'Oui'
	}).then((result) => {
		if (result.value){
			var data = {'comment_id': id, 'authenticate': token};

			$.ajax({
				type: 'POST',
				url: target,
				data: data,
				dataType: 'JSON',
				success: function(data){
					if(data.success){
						swal({
						    icon: 'success',
						    text: data.message,
						    button: false,
						    timer: 2000
					    });

					    setTimeout(function(){ location.reload(); }, 1500);
					}
					else
					{
						swal({
						    icon: 'error',
						    text: data.message,
						    button: false,
						    timer: 2000
					    });
					}
				},
				error: function(data){
					swal({
						type: 'error',
						text: data,
						button: false,
						timer: 2000
					});
				}
			});
		}
	});
}
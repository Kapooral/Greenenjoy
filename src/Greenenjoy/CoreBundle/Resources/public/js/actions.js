$(function() {

	$('[name=subscribe_form]').on('submit', function(e){
		var form = $(this);
		var type = form.attr('method');
		var target = form.attr('action');
		var data = {};

		form.find('[name]').each(function(index, value) {
			var input = $(this);
			var name = input.attr('name');
			var value = input.val();
			data[name] = value;
		});

		$.ajax({
			type: type,
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
					    icon: 'info',
					    text: data.message,
					    button: false,
					    timer: 2000
				    });
				}
			},
			error: function(response){
				swal({
					icon: 'error',
					text: 'Une erreur est survenue.',
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
						text: 'Une erreur est survenue.',
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
						text: 'Une erreur est survenue.',
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
						text: 'Une erreur est survenue.',
						button: false,
						timer: 2000
					});
				}
			});
		}
	});
}

function likePost(id, target, token) {

	swal({
	    icon: 'warning',
	    text: 'Aimez-vous cet article ?',
	    buttons: ['Annuler', 'Oui']
	}).then((result) => {
		if (result){

			var data = {'post_id': id, 'authenticate': token};

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
						text: 'Une erreur est survenue.',
						button: false,
						timer: 2000
					});
				}
			});
		}
	});
}

function deletePost(id, target, token){

	swal({
	    icon: 'warning',
	    text: 'Voulez-vous supprimer cet article ?',
	    buttons: ['Annuler', 'Supprimer']
	}).then((result) => {
		if (result){

			var data = {'post_id': id, 'authenticate': token};

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
						text: 'Une erreur est survenue.',
						button: false,
						timer: 2000
					});
				}
			});
		}
	});
}
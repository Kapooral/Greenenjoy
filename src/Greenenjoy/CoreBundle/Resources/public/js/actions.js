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

function reportComment(id){

	swal({
	    icon: 'warning',
	    text: 'Voulez-vous signaler ce commentaire ?',
	    buttons: ['Annuler', 'Signaler']
	}).then((result) => {
		if (result){
			var form = $('[name=report]');
			var type = form.attr('method');
			var target = form.attr('action');
			var data = {'comment_id': id, 'authenticate': form.find('[name]').val()};

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


function authorizeComment(id, token){

	swal({
	    type: 'warning',
	    text: 'Voulez-vous autoriser ce commentaire ?',
	    showCancelButton: true,
	    cancelButtonText: 'Non',
	    cancelButtonColor: '#a94442',
	    confirmButtonColor: '#3c763d',
	    confirmButtonText: 'Oui'
	}).then((result) => {
		if (result.value){
			$.ajax({
				type: 'GET',
				url: 'index.php',
				data: 'action=authorize&id=' + id + '&token=' + token,
				dataType: 'JSON',
				success: function(data){
					if(data.success){
						swal({
						    type: 'success',
						    text: data.text,
						    showConfirmButton: false,
						    timer: 1500
					    });

					    setTimeout(function(){ location.reload(); }, 1500);
					}
				},
				error: function(data){
					swal({
						type: 'error',
						text: data,
						showConfirmButton: false,
						timer: 1500
					});
				}
			});
		}
	});	
}


function deleteComment(id, token){

	swal({
	    type: 'warning',
	    text: 'Voulez-vous supprimer ce commentaire ?',
	    showCancelButton: true,
	    cancelButtonText: 'Non',
	    cancelButtonColor: '#a94442',
	    confirmButtonColor: '#3c763d',
	    confirmButtonText: 'Oui'
	}).then((result) => {
		if (result.value){
			$.ajax({
				type: 'GET',
				url: 'index.php',
				data: 'action=deleteComment&id=' + id + '&token=' + token,
				dataType: 'JSON',
				success: function(data){
					if(data.success){
						swal({
						    type: 'success',
						    text: data.text,
						    showConfirmButton: false,
						    timer: 1500
					    });

					    setTimeout(function(){ location.reload(); }, 1500);
					}
				},
				error: function(data){
					swal({
						type: 'error',
						text: data,
						showConfirmButton: false,
						timer: 1500
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
			$.ajax({
				type: 'GET',
				url: 'index.php',
				data: 'action=deletePost&id=' + id + '&token=' + token,
				dataType: 'JSON',
				success: function(data){
					if(data.success){
						swal({
						    type: 'success',
						    text: data.text,
						    showConfirmButton: false,
						    timer: 1500
					    });

					    setTimeout(function(){ location.reload(); }, 1500);
					}
				},
				error: function(data){
					swal({
						type: 'error',
						text: data,
						showConfirmButton: false,
						timer: 1500
					});
				}
			});
		}
	});
}
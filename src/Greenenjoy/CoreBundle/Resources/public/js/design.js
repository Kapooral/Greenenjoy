$(function() {

	$('div.card-deck > div').slice(0, 3).show();
	$('#more_content').click(function(e) {
		e.preventDefault();

		$('div.card-deck > div:hidden').slice(0, 3).show(500);
		$('#less_content').show(500);

		if ($('div.card-deck > div:hidden').length == 0) {
			$('#more_content').hide(500);
		}
		
	});

	$('#less_content').click(function(e) {
		e.preventDefault();
		$('#more_content').show(500);

		if ($('div.card-deck > div:visible').length == 4) {
			$('div.card-deck > div:visible').slice(-1).hide(500);
			$('#less_content').hide(500);
		}
		else if ($('div.card-deck > div:visible').length == 5) {
			$('div.card-deck > div:visible').slice(-2).hide(500);
			$('#less_content').hide(500);
		}
		else {
			$('div.card-deck > div:visible').slice(-3).hide(500);

			if ($('div.card-deck > div:visible').length == 6) {
				$('#less_content').hide(500);
			}
		}
	});
});

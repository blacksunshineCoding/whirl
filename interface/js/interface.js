$(document).ready(function() {
	sh_highlightDocument();
	
	$('a.smooth').click(function(){
	    $('html, body').animate({
	        scrollTop: $( $.attr(this, 'href') ).offset().top
	    }, 500);
	    return false;
	});
	
	$('select.interface-mode').change(function() {
		var mode = $(this).val();
		switch (mode) {
			case 'fast':
				break;
				
			default:
			case 'steps':
				break;
				
			case 'expert':
				$('.chapter-expert').fadeIn(250);
				$('pre.expert').fadeIn(250);
				smoothScroll('.chapter-expert');
				break;
		}
		
		console.log(mode);
	});
	
	$('#button-start').click(function() {
		$('.chapter-cache').fadeIn(500);
		smoothScroll('.chapter-cache');
		$('#button-start').fadeOut(250);
	});
	
	$('#button-cache').click(function() {
		$('.chapter-cache .load-wrapper').fadeIn(50);
		$.ajax({
			url: 'ajax.php?do=clear',
			type: 'GET',
			success: function(data){
				$('.chapter-settings').fadeIn(500, function() {
					$('.step-term').fadeIn(500);
					smoothScroll('.step-term');
					$('#button-cache').fadeOut(250);
				});
			}
		}).done(function() {
			$('.chapter-cache .load-wrapper').fadeOut(250);
		});
	});
	
	$('#button-term').click(function() {
		$('.step-term .load-wrapper').fadeIn(50);
		var term = $('input.term').val();
		
		if (typeof term !== 'undefined' && term != '') {
			$('.step-term .load-wrapper').fadeOut(250);
			$('.hiddenTerm').val(term);
			$('.step-term .alert-danger').fadeOut(300);
			$('.step-term .alert-success').fadeIn(300, function() {
				$('.step-quantity').fadeIn(300);
				smoothScroll('.step-quantity');
				$('#button-term').fadeOut(250);
			});
		} else {
			$('.step-term .alert-danger').fadeIn(300);
		}
	});
	
	$('#button-quantity').click(function() {
		$('.step-quantity .load-wrapper').fadeIn(50);
		var quantity = $('input.quantity').val();
		
		if (typeof quantity !== 'undefined' && $.isNumeric(quantity) && isNaturalNumber(quantity) && quantity >= 2) {
			$('.step-quantity .load-wrapper').fadeOut(250);
			$('.hiddenQuantity').val(quantity);
			$('.step-quantity .alert-danger').fadeOut(300);
			$('.step-quantity .alert-success').fadeIn(300, function() {
				$('.chapter-execution').fadeIn(300, function() {
					$('.step-fetch').fadeIn(300);
					smoothScroll('.step-fetch');
					$('#button-quantity').fadeOut(250);
				});
			});
		} else {
			$('.step-quantity .alert-danger').fadeIn(300);
		}
	});
	
	$('#button-fetch').click(function() {
		$('.step-fetch .load-wrapper').fadeIn(50);
		var term = $('.hiddenTerm').val();
		var quantity = $('.hiddenQuantity').val();
		
		$.ajax({
			url: 'ajax.php?do=fetch&term=' + encodeURIComponent(term) + '&quantity=' + quantity,
			type: 'GET',
			success: function(data){
				$('.step-fetch .load-wrapper').fadeOut(250);
				$('.json-container').html(data);
				$('.step-fetch .alert-success').fadeIn(300, function() {
					$('.step-save').fadeIn(300);
					smoothScroll('.step-save');
					$('#button-fetch').fadeOut(250);
				});
			}
		}).done(function() {
		});
	});
	
	$('#button-save').click(function() {
		$('.step-save .load-wrapper').fadeIn(50);
		var term = $('.hiddenTerm').val();
		var quantity = $('.hiddenQuantity').val();
		
		$.ajax({
			url: 'ajax.php?do=save&term=' + encodeURIComponent(term) + '&quantity=' + quantity,
			type: 'GET',
			success: function(data){
				$('.step-save .load-wrapper').fadeOut(250);
				$('.json-container').html(data);
				$('.step-save .alert-success').fadeIn(300, function() {
					$('.step-resize').fadeIn(300);
					smoothScroll('.step-resize');
					$('#button-save').fadeOut(250);
				});
			}
		}).done(function() {
		});
	});
	
	$('#button-resize').click(function() {
		$('.step-resize .load-wrapper').fadeIn(50);
		var term = $('.hiddenTerm').val();
		var quantity = $('.hiddenQuantity').val();
		
		$.ajax({
			url: 'ajax.php?do=resize&term=' + encodeURIComponent(term) + '&quantity=' + quantity,
			type: 'GET',
			success: function(data){
				$('.step-resize .load-wrapper').fadeOut(250);
				$('.step-resize .alert-success').fadeIn(300, function() {
					$('.step-multiply').fadeIn(300);
					smoothScroll('.step-multiply');
					$('#button-resize').fadeOut(250);
				});
			}
		}).done(function() {
		});
	});
	
	$('#button-multiply').click(function() {
		$('.step-multiply .load-wrapper').fadeIn(50);
		var term = $('.hiddenTerm').val();
		var quantity = $('.hiddenQuantity').val();
		
		$.ajax({
			url: 'ajax.php?do=multiply&term=' + encodeURIComponent(term) + '&quantity=' + quantity,
			type: 'GET',
			success: function(data){
				$('.step-multiply .load-wrapper').fadeOut(250);
				$('.step-multiply .alert-success').fadeIn(300, function() {
					$('.step-final').fadeIn(300);
					smoothScroll('.step-final');
					$('#button-multiply').fadeOut(250);
				});
			}
		}).done(function() {
		});
	});
	
	$('#button-final').click(function() {
		$('.step-download .load-wrapper').fadeIn(50);
		var term = $('.hiddenTerm').val();
		var quantity = $('.hiddenQuantity').val();
		
		$.ajax({
			url: 'ajax.php?do=final&term=' + encodeURIComponent(term) + '&quantity=' + quantity,
			type: 'GET',
			success: function(data) {
				$('.step-download .load-wrapper').fadeOut(250);
				$('.step-download .image').attr('download', $('.hiddenTerm').val());
				$('.step-download .image').attr('href', '../cache/blend/' + data);
				$('.step-download .image img').attr('src', '../cache/blend/' + data);
				$('.step-final .alert-success').fadeIn(300, function() {
					$('.step-download').fadeIn(300);
					$('.continue').fadeOut(300);
					$('.step-thanks').fadeIn(300);
					smoothScroll('.step-download');
					$('#button-final').fadeOut(250);
				});
			}
		}).done(function() {
		});
	});
});

function isNaturalNumber(n) {
	n = n.toString();
	var n1 = Math.abs(n),
		n2 = parseInt(n, 10);
	return !isNaN(n1) && n2 === n1 && n1.toString() === n;
}

function smoothScroll(target) {
    $('html, body').animate({
        scrollTop: $(target).offset().top
    }, 500);
}
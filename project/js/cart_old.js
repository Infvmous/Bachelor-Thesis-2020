$(document).ready(function() {
	initBinds();

	function initBinds() {
		if ($('.remove_cart').length > 0) {
			$('.remove_cart').bind('click', removeFromCart);
		}
		if ($('.update_cart').length > 0) {
			$('.update_cart').bind('click', updateCart);
		}
		if ($('.fld_qty').length > 0) {
			$('.fld_qty').bind('keypress', function(e) {
				var code = e.keyCode ? e.keyCode : e.which;
				if (code == 13) {
					updateCart();
				}
			});
		}
	}

	function removeFromCart() {
		var item = $(this).attr('rel');
		$.ajax({
			type: 'POST',
			url: '/modules/cart_remove.php',
			dataType: 'html',
			data: { id: item },
			success: function() {
				refreshBigCart();
				refreshSmallCart();
			},
			error: function() {
				alert('Ошибка ajax');
			}
		});
	}

	function refreshSmallCart() {
		$.ajax({
			url: '/modules/cart_small_refresh.php',
			dataType: 'json',
			success: function(data) {
				$.each(data, function(k, v) {
					$('#cart_small .' + k + ' span').text(v);
				});
			},
			error: function(data) {
				alert('Ошибка ajax');
			}
		});
	}

	function refreshBigCart() {
		$.ajax({
			url: '/modules/cart_view.php',
			dataType: 'html',
			success: function(data) {
				$('#cart_big').html(data);
				initBinds();
			},
			error: function(data) {
				alert('Ошибка ajax');
			}
		});
	}

	if ($('.add_to_cart').length > 0) {
		$('.add_to_cart').click(function() {
			var trigger = $(this);
			var param = trigger.attr('rel');
			var item = param.split('_');

			$.ajax({
				type: 'POST',
				url: '/modules/cart.php',
				dataType: 'json',
				data: { id: item[0], job: item[1] },
				success: function(data) {
					var new_id = item[0] + '_' + data.job;
					if (data.job != item[1]) {
						if (data.job == 0) {
							trigger.attr('rel', new_id);
							trigger.text('Удалить из корзины');
							trigger.addClass('red');
						} else {
							trigger.attr('rel', new_id);
							trigger.text('Добавить в корзину');
							trigger.removeClass('red');
						}
						refreshSmallCart();
					}
				},
				error: function(data) {
					alert('Ошибка ajax');
				}
			});
			return false;
		});
	}

	function updateCart() {
		$('#frm_cart :input').each(function() {
			var sid = $(this)
				.attr('id')
				.split('-');
			var val = $(this).val();
			$.ajax({
				type: 'POST',
				url: '/modules/cart_qty.php',
				data: { id: sid[1], qty: val },
				success: function() {
					refreshSmallCart();
					refreshBigCart();
				},
				error: function() {
					alert('Ошибка ajax');
				}
			});
		});
	}

	// Перенаправление на PayPal
	if ($('.paypal').length > 0) {
		$('.paypal').click(function() {
			var token = $(this).attr('id');
			var image = '<div style="text-align:center">';
			image = image + '<img src="/images/loadinfo.net.gif"';
			image = image + ' alt="Proceeding to PayPal" />';
			image =
				image +
				'<br />Пожалуйста подождите пока мы перенаправляем Вас в PayPal...';
			image = image + '</div><div id="frm_pp"></div>';

			$('#big_cart').fadeOut(200, function() {
				$(this)
					.html(image)
					.fadeIn(200, function() {
						send2PP(token);
					});
			});
		});
	}

	function send2PP(token) {
		$.ajax({
			type: 'POST',
			url: '/modules/paypal.php',
			data: { token: token },
			dataType: 'html',
			success: function(data) {
				$('#frm_pp').html(data);
				// Автоматический редирект в PayPal
				$('#frm_paypal').submit();
			},
			error: function() {
				alert('Ошибка ajax');
			}
		});
	}
});

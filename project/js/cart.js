var cartObject = {
	/*
	updateCartEnter: function (thisIdentity) {
		'use strict';
		$(document).on('keypress', thisIdentity, function (e) {
			var code = e.keyCode ? e.keyCode : e.which;
			if (code == 13) {
				e.preventDefault();
				e.stopPropagation();
				cartObject.updateCart();
			}
		});
	},
	*/
	updateCartSelect: function (thisIdentity) {
		'use strict';
		$(document).on('change', thisIdentity, function (e) {
			let sid = $(this).attr('id').split('-');
			let val = $(this).val();
			cartObject.updateCart(sid, val, thisIdentity);
		});
	},
	updateCart: function (sid, val, thisIdentity) {
		'use strict';
		$.each($('.select_qty'), function () {
			$.post(
				'/modules/cart_qty.php',
				{ id: sid[1], qty: val },
				function (data) {
					cartObject.refreshSmallCart();
					cartObject.refreshBigCart();
				},
				'html'
			);
		});
	},
	refreshBigCart: function () {
		'use strict';
		$.post(
			'/cart/action/view',
			function (data) {
				$('#cart_big').html(data);
			},
			'html'
		);
	},
	refreshSmallCart: function () {
		'use strict';
		$.post(
			'/modules/cart_small_refresh.php',
			function (data) {
				$.each(data, function (k, v) {
					$('#cart_small .' + k + ' span').text(v);
				});
			},
			'json'
		);
	},
	removeFromCart: function (thisIdentity) {
		'use strict';
		$(document).on('click', thisIdentity, function (e) {
			e.preventDefault();
			let item = $(this).attr('rel');
			$.post(
				'/modules/cart_remove.php',
				{ id: item },
				function (data) {
					cartObject.refreshBigCart();
					cartObject.refreshSmallCart();
				},
				'html'
			);
		});
	},
	add2Cart: function (thisIdentity) {
		'use strict';
		$(document).on('click', thisIdentity, function (e) {
			e.preventDefault();
			let trigger = $(this);
			let param = trigger.attr('rel');
			let item = param.split('_');
			$.post(
				'/modules/cart.php',
				{ id: item[0], job: item[1] },
				function (data) {
					var new_id = item[0] + '_' + data.job;
					if (data.job != item[1]) {
						if (data.job === 0) {
							trigger.attr('rel', new_id);
							trigger.text('Удалить из корзины');
							trigger.addClass('red');
						} else {
							trigger.attr('rel', new_id);
							trigger.text('Добавить в корзину');
							trigger.removeClass('red');
						}
						cartObject.refreshSmallCart();
					}
				},
				'json'
			);
		});
	},
	updateCartClick: function (thisIdentity) {
		'use strict';
		$(document).on('click', thisIdentity, function (e) {
			e.preventDefault();
			cartObject.updateCart();
		});
	},
	loadingPayPal: function (thisIdentity) {
		'use strict';
		$(document).on('click', thisIdentity, function (e) {
			e.preventDefault();
			e.stopPropagation();
			let thisShippingOption = $('input[name="shipping"]:checked');
			if (thisShippingOption.length > 0) {
				var token = $(this).attr('id');
				var image = '<div style="text-align:center">';
				image = image + '<img src="/images/loadinfo.net.gif"';
				image = image + ' alt="Proceeding to PayPal" />';
				image = image + '<br />Перенаправляем Вас в PayPal...';
				image = image + '</div><div id="frm_pp"></div>';
				$('#cart_big').fadeOut(200, function () {
					$(this)
						.html(image)
						.fadeIn(200, function () {
							cartObject.send2PayPal(token);
						});
				});
			} else {
				systemObject.topValidation('Выберите вариант доставки');
			}
		});
	},
	send2PayPal: function (token) {
		'use strict';
		$.post(
			'/modules/paypal.php',
			{ token: token },
			function (data) {
				if (data && !data.error) {
					$('#frm_pp').html(data.form);
					// Автоматический редирект в PayPal
					$('#frm_paypal').submit();
				} else {
					systemObject.topValidation(data.message);
					var thisTimeout = setTimeout(function () {
						window.location.reload();
					}, 5000);
				}
			},
			'json'
		);
	},
	emailInactive: function (thisIdentity) {
		'use strict';
		$(document).on('click', thisIdentity, function (e) {
			e.preventDefault();
			let thisId = $(this).attr('data-id');
			console.log(thisId);
			$.getJSON('/modules/resend.php?id=' + thisId, function (data) {
				if (!data.error) {
					location.href = '/resent.html';
				} else {
					location.href = '/resent-failed.html';
				}
			});
		});
	},
	shipping: function (thisIdentity) {
		'use strict';
		$(document).on('change', thisIdentity, function (e) {
			let thisOption = $(this).val();
			$.getJSON(
				'/modules/summary_update.php?shipping=' + thisOption,
				function (data) {
					if (data && !data.error) {
						$('#cartSubTotal').html(data.totals.cartSubTotal);
						$('#cartVat').html(data.totals.cartVat);
						$('#cartTotal').html(data.totals.cartTotal);
					}
				}
			);
		});
	},
};
var systemObject = {
	showHideRadio: function (thisIdentity) {
		'use strict';
		$(document).on('click', thisIdentity, function (e) {
			let thisTarget = $(this).attr('name');
			let thisValue = $(this).val();
			if (thisValue == 1) {
				$('.' + thisTarget).hide();
			} else {
				$('.' + thisTarget).show();
			}
		});
	},
	topValidationTemp: function (thisMessage) {
		'use strict';
		let thisTemp = '<div id="topMessage">';
		thisTemp += thisMessage;
		thisTemp += '</div>';
		return thisTemp;
	},
	topValidation: function (thisMessage) {
		'use strict';
		if (thisMessage !== '' && typeof thisMessage !== 'undefined') {
			if ($('#topMessage').length > 0) {
				$('#topMessage').remove();
			}
			$('body').prepend(
				$(systemObject.topValidationTemp(thisMessage)).fadeIn(200)
			);
			let thisTimeout = setTimeout(function () {
				$('#topMessage').fadeOut(200, function () {
					$(this).remove();
				});
			}, 5000);
		}
	},
	isEmpty: function (thisValue) {
		'use strict';
		return !(thisValue !== '' && typeof thisValue !== 'undefined');
	},
	replaceValues: function (thisArray) {
		'use strict';
		$.each(thisArray, function (thisKey, thisValue) {
			$(thisKey).html(thisValue);
		});
	},
};
$(document).ready(function () {
	'use strict';
	systemObject.showHideRadio('.showHideRadio');
	cartObject.removeFromCart('.remove_cart');
	//cartObject.updateCartClick('.update_cart');
	//cartObject.updateCartEnter('.fld_qty');
	cartObject.updateCartSelect('.select_qty');
	cartObject.add2Cart('.add_to_cart');
	cartObject.loadingPayPal('.paypal');
	cartObject.emailInactive('#emailInactive');
	cartObject.shipping('.shippingRadio');
});

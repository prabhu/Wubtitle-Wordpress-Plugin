(function (Stripe, document) {
	const { adminAjax, nonce, stripeKey } = WP_GLOBALS;
	let stripe = null;

	const confirmPayment = (clientSecret, paymentMethod) => {
		stripe
			.confirmCardPayment(clientSecret, {
				payment_method: paymentMethod,
				setup_future_usage: 'off_session',
			})
			.then((response) => {
				if (response.paymentIntent.status === 'succeeded') {
					window.unonload = window.opener.redirectToCallback(
						'notices-code=payment'
					);
					window.close();
				}
				document.getElementById('error-message').innerHTML =
					response.data;
			});
	};

	const handleConfirm = () => {
		fetch(adminAjax, {
			method: 'POST',
			credentials: 'include',
			headers: new Headers({
				'Content-Type': 'application/x-www-form-urlencoded',
			}),
			body: `action=change_plan&_ajax_nonce=${nonce}`,
		})
			.then((resp) => resp.json())
			.then((response) => {
				if (response.success) {
					if (
						response.data &&
						response.data.status === 'requires_action'
					) {
						confirmPayment(
							response.data.clientSecret,
							response.paymentMethod
						);
					} else {
						window.unonload = window.opener.redirectToCallback(
							'notices-code=payment'
						);
						window.close();
					}
				} else {
					document.getElementById('error-message').innerHTML =
						response.data;
				}
			});
	};

	const init = () => {
		const confirmButton = document.querySelector('#confirm_changes');
		const closeButton = document.querySelector('#forget');
		stripe = Stripe(stripeKey);

		if (confirmButton) {
			confirmButton.addEventListener('click', () => {
				handleConfirm();
			});
		}
		if (closeButton) {
			closeButton.addEventListener('click', () => {
				window.opener.cancelPayment();
			});
		}
	};

	return {
		init,
	};
})(Stripe, document).init();

const paymentModule = (function (Stripe, document) {
	let stripe = null;

	const { adminAjax, nonce, wubtitleEnv } = WP_GLOBALS;

	const openStripeForm = (sessionId) => {
		if (sessionId) {
			stripe.redirectToCheckout({ sessionId });
		}
	};

	const handleSubmit = () => {
		fetch(adminAjax, {
			method: 'POST',
			credentials: 'include',
			headers: new Headers({
				'Content-Type': 'application/x-www-form-urlencoded',
			}),
			body: `action=update_payment_method&_ajax_nonce=${nonce}`,
		})
			.then((resp) => resp.json())
			.then((response) => {
				if (response.success) {
					openStripeForm(response.data, stripe);
				} else {
					document.getElementById('error-message').innerHTML =
						response.data;
				}
			});
	};

	const init = () => {
		const stripeKey =
			wubtitleEnv === 'development'
				? 'pk_test_lFmjf2Dz7VURTslihG0xys7m00NjW2BOPI'
				: 'pk_live_PvwHkJ49ry3lfXwkXIx2YKBE00S15aBYz7';
		stripe = Stripe(stripeKey);
		handleSubmit();
	};

	return {
		init,
	};
})(Stripe, document);

paymentModule.init();

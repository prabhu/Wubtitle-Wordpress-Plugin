const paymentModule = (function(Stripe, document) {
	let stripe = null;

	const { adminAjax, nonce } = WP_GLOBALS;

	const openStripeForm = sessionId => {
		if (sessionId) {
			stripe.redirectToCheckout({ sessionId });
		}
	};

	const handleSubmit = e => {
		e.preventDefault();
		const select = document.querySelector("#select").value;

		fetch(adminAjax, {
			method: "POST",
			credentials: "include",
			headers: new Headers({
				"Content-Type": "application/x-www-form-urlencoded"
			}),
			body: `action=submit_plan&_ajax_nonce=${nonce}&pricing_plan=${select}`
		})
			.then(resp => resp.json())
			.then(data => {
				if (data.success) {
					openStripeForm(data.data, stripe);
				}
			});
	};

	const init = () => {
		stripe = Stripe("pk_test_nfUYjFiwdkzYpPOfCZkVZiMK00lOAFcAK7");
		const form = document.querySelector("#form");

		form.addEventListener("submit", handleSubmit);
	};

	return { init };
})(Stripe, document);

paymentModule.init();

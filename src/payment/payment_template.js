const paymentModule = (function(Stripe, document) {
	let stripe = null;

	const { adminAjax, nonce } = WP_GLOBALS;

	const openStripeForm = sessionId => {
		if (sessionId) {
			stripe.redirectToCheckout({ sessionId });
		}
	};

	const handleChoise = plan => {
		fetch(adminAjax, {
			method: "POST",
			credentials: "include",
			headers: new Headers({
				"Content-Type": "application/x-www-form-urlencoded"
			}),
			body: `action=submit_plan&_ajax_nonce=${nonce}&pricing_plan=${plan}`
		})
			.then(resp => resp.json())
			.then(data => {
				if (data.success) {
					openStripeForm(data.data, stripe);
				} else {
					/* eslint-disable */
					alert("Non è possibile raggiungere il servizio");
					/* eslint-enable */
				}
			});
	};

	const init = () => {
		stripe = Stripe("pk_test_nfUYjFiwdkzYpPOfCZkVZiMK00lOAFcAK7");
		const buttons = document.querySelectorAll(".button-choose-plan");
		buttons.forEach(button => {
			button.addEventListener("click", () => {
				const plan = button.getAttribute("plan");
				handleChoise(plan);
			});
		});
	};

	return { init };
})(Stripe, document);

paymentModule.init();

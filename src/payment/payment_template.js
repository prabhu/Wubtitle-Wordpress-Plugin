/*  global WP_GLOBALS  */
const paymentModule = (function(Stripe, document) {
	let stripe = null;

	const { adminAjax, nonce, licenseKey } = WP_GLOBALS;

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
			body: `action=submit_plan&_ajax_nonce=${nonce}&pricing_plan=${select}&license_key=${licenseKey}`
		})
			.then(resp => resp.json())
			.then(response => {
				if (response.success) {
					openStripeForm(response.data, stripe);
				} else {
					document.getElementById("error-message").innerHTML =
						response.data;
				}
			});
	};

	const init = () => {
		stripe = Stripe("pk_test_nfUYjFiwdkzYpPOfCZkVZiMK00lOAFcAK7");
		const form = document.querySelector("#form");

		form.addEventListener("submit", handleSubmit);
	};

	return {
		init
	};
})(Stripe, document);

paymentModule.init();

const paymentModule = (function(Stripe, document) {
	let stripe = null;

	const openStripeForm = sessionId => {
		if (sessionId) {
			stripe.redirectToCheckout({ sessionId });
		}
	};

	const handleSubmit = e => {
		e.preventDefault();
		const select = document.querySelector("#select").value;
		// TODO: cambiare con variabili globali
		const nonce =
			"<?php echo esc_js( wp_create_nonce( 'itr_ajax_nonce' ) ); ?>";

		// TODO: cambiare con variabile globale
		fetch("<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>", {
			method: "POST",
			credentials: "include",
			headers: new Headers({
				"Content-Type": "application/x-www-form-urlencoded"
			}),
			body: `action=submit_plan&_ajax_nonce=${nonce}&pricing_plan=${select}`
		})
			.then(resp => resp.json())
			.then(data => {
				if (data) {
					openStripeForm(data, stripe);
				}
			});
	};

	const init = () => {
		stripe = Stripe("pk_test_nfUYjFiwdkzYpPOfCZkVZiMK00lOAFcAK7");
		const form = document.querySelector("#form");

		form.addEventListener("submit", handleSubmit);
	};

	return init;
})(Stripe, document);

document.addEventListener("DOMContentLoaded", function() {
	paymentModule.init();
});

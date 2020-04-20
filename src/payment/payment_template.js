document.addEventListener("DOMContentLoaded", function() {
	const stripe = Stripe("pk_test_nfUYjFiwdkzYpPOfCZkVZiMK00lOAFcAK7");
	const form = document.querySelector("#form");

	form.addEventListener("submit", e => handleSubmit(e, stripe));
});

const handleSubmit = (e, stripe) => {
	e.preventDefault();
	const select = document.querySelector("#select").value;
	const nonce =
		"<?php echo esc_html( wp_create_nonce( 'itr_ajax_nonce' ) ); ?>";

	fetch("<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>", {
		method: "POST",
		credentials: "include",
		headers: new Headers({
			"Content-Type": "application/x-www-form-urlencoded"
		}),
		body: `action=submit_plan&_ajax_nonce=${nonce}&pricing_plan=${select}`
	})
		.then(resp => resp.json())
		.then(function(data) {
			if (data) {
				openStripeForm(data, stripe);
			}
		});
};

const openStripeForm = (sessionId, stripe) => {
	if (sessionId) {
		stripe.redirectToCheckout({ sessionId });
	}
};

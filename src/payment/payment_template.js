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

	const request = new XMLHttpRequest();
	request.open(
		"POST",
		"<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>",
		true
	);
	request.onload = function() {
		if (this.status >= 200 && this.status < 400) {
			const response = JSON.parse(this.response);
			if (response.success) {
				stripe.redirectToCheckout({
					sessionId: response.data
				});
			}
		}
	};
	request.setRequestHeader(
		"Content-type",
		"application/x-www-form-urlencoded"
	);
	request.send(
		"action=submit_plan&_ajax_nonce=" + nonce + "&pricing_plan=" + select
	);
};

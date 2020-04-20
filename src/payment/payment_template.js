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

	const data = {
		action: "submit_plan",
		_ajax_nonce: nonce,
		pricing_plan: select
	};

	fetch("<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>", {
		method: "POST",
		headers: {
			"Content-Type": "application/json"
		},
		body: JSON.stringify(data)
	})
		.then(response => response.json())
		.then(res => {
			if (res) {
				stripe.redirectToCheckout({
					sessionId: res
				});
				// .then(function(result) {
				// 	const messageError = result.error.message;
				//  TODO: Gestire errore. Rimosso per lintJS.

				// });
			}
		});
	//  TODO: Gestire errore. Rimosso per lintJS.
	// .catch(error => {
	// 	// console.error("Error:", error);
	// });
};

document.addEventListener("DOMContentLoaded", function() {
	const BuyLicenseWindow = null;
	document
		.querySelector("#buy-license-button")
		.addEventListener("click", () => {
			showBuyLicenseWindow(BuyLicenseWindow);
		});
});

const showBuyLicenseWindow = BuyLicenseWindow => {
	if (BuyLicenseWindow === null || BuyLicenseWindow.closed) {
		const windowFeatures = `
            left=500,
            top=200,
            width=500,
            height=500,
            scrollbars=yes,
        `;
		BuyLicenseWindow = window.open("", "Buy license", windowFeatures);

		fetch(
			`${ajax_stripe.ajax_url}?action=payment_template&_ajax_nonce=${ajax_stripe.nonce}`
		)
			.then(function(response) {
				return response.text();
			})
			.then(function(text) {
				BuyLicenseWindow.document.body.innerHTML = text;
			});
	} else {
		BuyLicenseWindow.focus();
	}
};

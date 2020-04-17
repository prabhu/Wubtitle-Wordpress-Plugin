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

		// Send the form via ajax
		wp.ajax
			.send(ajax_stripe.ajax_url, {
				data: {
					nonce: ajax_stripe.nonce,
					action: "payment_template"
				}
			})
			.done(function(response) {
				BuyLicenseWindow.document.write(response);
			});
	} else {
		BuyLicenseWindow.focus();
	}
};

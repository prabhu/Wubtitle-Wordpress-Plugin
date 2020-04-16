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

		const request = new XMLHttpRequest();
		request.open(
			"GET",
			`${ajax_stripe.ajax_url}?_ajax_nonce=${ajax_stripe.nonce}&action=payment_template`,
			true
		);

		request.onload = function() {
			if (this.status >= 200 && this.status < 400) {
				BuyLicenseWindow.document.write(this.response);
			} else {
				BuyLicenseWindow.document.write("error");
			}
		};

		request.onerror = function() {
			BuyLicenseWindow.document.write("error");
		};

		request.send();
	} else {
		BuyLicenseWindow.focus();
	}
};

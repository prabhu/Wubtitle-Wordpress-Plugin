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

		const data = {
			action: "payment_template",
			_ajax_nonce: ajax_stripe.nonce
		};

		fetch(ajax_stripe.ajax_url, {
			method: "GET",
			headers: {
				"Content-Type": "application/json"
			},
			body: JSON.stringify(data)
		})
			.then(response => response.json())
			.then(res => {
				if (res) {
					BuyLicenseWindow.document.body.innerHTML = res;
				}
			});
		//  TODO: Gestire errore. Rimosso per lintJS.
		// .catch(error => {
		// 	// console.error("Error:", error);
		// });
	} else {
		BuyLicenseWindow.focus();
	}
};

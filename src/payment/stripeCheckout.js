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

		//TODO: Cambiare in Vanilla JS

		// jQuery.get(
		// 	my_ajax_object.ajax_url,
		// 	{
		// 		_ajax_nonce: my_ajax_object.nonce,
		// 		action: "payment_template"
		// 	},
		// 	function(response) {
		// 		BuyLicenseWindow.document.write(response); //insert server response
		// 	}
		// );
	} else {
		BuyLicenseWindow.focus();
	}
};

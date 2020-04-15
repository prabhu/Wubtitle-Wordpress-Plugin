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
		BuyLicenseWindow = window.open(
			"",
			"Buy license",
			windowFeatures
        );
        // const formHTML = `
        //     <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc placerat libero et sagittis ultrices. Etiam at turpis id orci porta tincidunt. Ut dictum placerat dolor ac iaculis. Donec in sagittis elit. Nunc vitae dolor leo. Duis sed nulla vitae sapien pharetra blandit. In iaculis, leo in vestibulum elementum, nisi nunc malesuada nulla, et dictum sapien sapien et purus. Phasellus eu consequat quam. In sed fringilla eros, a congue nulla. Sed sagittis tellus rhoncus, lacinia lacus sed, eleifend nisi. </p>
        //     <button id="buy-license-button" class="button button-primary" >Scegli Piano</button>
        // `;
        var data = {
			'action': 'payment_template'
		};
        // const formHTML = jQuery.get(ajaxurl, data, function(response) {
		// 	alert('Got this from the server: ' + response);
        // });
        jQuery.get(my_ajax_object.ajax_url, {         //POST request
            _ajax_nonce: my_ajax_object.nonce,     //nonce
            action: "payment_template",            //action
        }, function(response) {                    //callback
            BuyLicenseWindow.document.write(response);           //insert server response
        });
        // BuyLicenseWindow.document.write(formHTML);
	} else {
		BuyLicenseWindow.focus();
	}
};

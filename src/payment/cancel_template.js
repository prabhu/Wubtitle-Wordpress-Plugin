const cancelSubscriptionModule = (function(document, WP_GLOBALS) {
	const { adminAjax, nonce } = WP_GLOBALS;

	const handleSubmit = e => {
		e.preventDefault();
		const select = document.querySelector("#select").value;

		fetch(adminAjax, {
			method: "POST",
			credentials: "include",
			headers: new Headers({
				"Content-Type": "application/x-www-form-urlencoded"
			}),
			body: `action=cancel_subscription&_ajax_nonce=${nonce}&choise=${select}`
		})
			.then(resp => resp.json())
			.then(data => {
				document.body.innerHTML = data.data;
			});
	};

	const init = () => {
		const form = document.querySelector("#form");

		form.addEventListener("submit", handleSubmit);
	};

	return {
		init
	};
})(document, WP_GLOBALS);

cancelSubscriptionModule.init();

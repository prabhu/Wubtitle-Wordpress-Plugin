const unsubscribeModule = (function(document) {
	const { adminAjax, nonce } = WP_GLOBALS;

	const handleUnsubscription = () => {
		fetch(adminAjax, {
			method: "POST",
			credentials: "include",
			headers: new Headers({
				"Content-Type": "application/x-www-form-urlencoded"
			}),
			body: `action=submit_plan&_ajax_nonce=${nonce}&choise=free`
		})
			.then(resp => resp.json())
			.then(data => {
				document.querySelector("#message").innerHTML = data.data;
				setTimeout(() => {
					window.close();
				}, 3000);
			});
	};

	const init = () => {
		const unsubscribeButton = document.querySelector("#unsubscribeButton");
		const closeButton = document.querySelector("#close");

		unsubscribeButton.addEventListener("click", () => {
			handleUnsubscription();
		});

		closeButton.addEventListener("click", () => {
			window.close();
		});
	};

	return { init };
})(document);

unsubscribeModule.init();

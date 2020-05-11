/* eslint-disable no-console */
document.addEventListener("DOMContentLoaded", function() {
	const button = document.querySelector("#youtube-button");
	button.addEventListener("click", () => {
		document.querySelector("#message").innerHTML = "Getting transcript...";
		const input = document.querySelector("#youtube-url");
		const urlInput = input.value;
		const videoId = urlInput.replace("https://youtu.be/", "");
		getSubtitle(videoId);
	});

	const getSubtitle = id => {
		let text = "";
		wp.ajax
			.send("get_info_yt", {
				type: "POST",
				data: { id, nonce: "dfve" }
			})
			.then(response => {
				if (response === "error") {
					document.querySelector("#message").innerHTML = "Error";
				} else if (!response) {
					document.querySelector("#message").innerHTML =
						"Transcrizione non disponibile";
				} else {
					fetch(response)
						.then(res => res.json())
						.then(data => {
							data.events.forEach(a => {
								if (a.segs !== undefined) {
									a.segs.forEach(b => {
										text += b.utf8;
									});
								}
							});
							document.querySelector(
								"#content_ifr"
							).contentWindow.document.body.innerHTML = text;
							document.querySelector("#message").innerHTML = "";
						});
				}
			});
	};
});

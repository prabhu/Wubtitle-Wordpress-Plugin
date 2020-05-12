document.addEventListener("DOMContentLoaded", () => {
	const button = document.querySelector("#youtube-button");
	button.addEventListener("click", () => {
		document.querySelector("#message").innerHTML = "Getting transcript...";
		const input = document.querySelector("#youtube-url");
		const urlInput = input.value;
		const videoId = urlInput.replace("https://youtu.be/", "");
		getTranscripts(videoId);
	});

	const getTranscripts = id => {
		let text = "";
		wp.ajax
			.send("get_info_yt", {
				type: "POST",
				data: { id, nonce: "nonce" }
			})
			.then(response => {
				if (response === "error") {
					document.querySelector("#message").innerHTML = "Error";
				} else if (!response) {
					document.querySelector("#message").innerHTML =
						"Transcript not available";
				} else {
					fetch(response)
						.then(res => res.json())
						.then(data => {
							data.events.forEach(event => {
								if (event.segs !== undefined) {
									event.segs.forEach(seg => {
										text += seg.utf8;
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

/* eslint-disable no-console */
document.addEventListener("DOMContentLoaded", function() {
	const buttonIframe = document.querySelector("#youtube-button");
	buttonIframe.addEventListener("click", () => {
		const input = document.querySelector("#youtube-url");
		const urlInput = input.value;
		const videoId = urlInput.replace("https://youtu.be/", "");
		const iframeTemplate = `
			<iframe
				id="iframe-video"
				width="560"
				height="315"
				src="https://www.youtube.com/embed/${videoId}"
				frameborder="0"
				allow="accelerometer;
				autoplay;
				encrypted-media;
				gyroscope;
				picture-in-picture"
				allowfullscreen
			>
			</iframe>
		`;
		document.querySelector("#video-embed").innerHTML = iframeTemplate;
		getSubtitle(videoId);
	});

	const getSubtitle = id => {
		let text = "";
		wp.ajax
			.send("get_info_yt", {
				type: "POST",
				data: { id }
			})
			.then(response => {
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
						document.querySelector("#text").innerHTML = text;
					});
			});
	};
});

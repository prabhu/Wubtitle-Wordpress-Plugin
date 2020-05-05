/* eslint-disable no-console */
document.addEventListener("DOMContentLoaded", function() {
	const buttonIframe = document.querySelector("#youtube-iframe-button");
	buttonIframe.addEventListener("click", () => {
		const input = document.querySelector("#youtube-url");
		const url = input.value;
		const videoId = url.replace("https://youtu.be/", "");
		const urlInfo = `https://www.youtube.com/get_video_info?video_id=${videoId}`;
		console.log(urlInfo);
		fetch(urlInfo)
			.then(response => response.json())
			.then(data => console.log(data));
		// const iframeTemplate = `
		// 	<iframe
		// 		id="iframe-video"
		// 		width="560"
		// 		height="315"
		// 		src="https://www.youtube.com/embed/${videoId}"
		// 		frameborder="0"
		// 		allow="accelerometer;
		// 		autoplay;
		// 		encrypted-media;
		// 		gyroscope;
		// 		picture-in-picture"
		// 		allowfullscreen
		// 	>
		// 	</iframe>
		// `;
		// document.querySelector("#video-embed").innerHTML = iframeTemplate;
	});

	const buttonSubtitle = document.querySelector("#youtube-subtitles-button");

	buttonSubtitle.addEventListener("click", () => {});
});

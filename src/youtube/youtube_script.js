/* eslint-disable no-console */
document.addEventListener("DOMContentLoaded", function() {
	const buttonIframe = document.querySelector("#youtube-iframe-button");
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
		document.querySelector("iframe").addEventListener("fetch", () => {
			console.log("ciao");
		});

		//TODO: get requests.
		// const urlText =
		// 	"https://www.youtube.com/api/timedtext?v=wISSAdLFwas&asr_langs=de%2Cen%2Ces%2Cfr%2Cit%2Cja%2Cko%2Cnl%2Cpt%2Cru&caps=asr&xorp=true&hl=it&ip=0.0.0.0&ipbits=0&expire=1588776998&sparams=ip%2Cipbits%2Cexpire%2Cv%2Casr_langs%2Ccaps%2Cxorp&signature=571F700EE75AABE8057EFCE157ACCC1CF157DA71.97F2DA6946220B8F02B9C27C9E7F70E959E9BE6F&key=yt8&kind=asr&lang=it&fmt=json3&xorb=2&xobt=3&xovt=3";
		// fetch(urlText)
		// 	.then(response => response.json())
		// 	.then(data => console.log(data));
	});

	// const buttonSub = document.querySelector("#youtube-subtitles-button");
	// buttonSub.addEventListener("click", logRequests);
	// async function logRequests() {
	// 	const harLog = await chrome.devtools.network.getHAR();
	// 	console.log(`HAR version: ${harLog.version}`);
	// 	for (const entry of harLog.entries) {
	// 		console.log(entry.request.url);
	// 	}
	// }

	const buttonSub = document.querySelector("#youtube-subtitles-button");
	buttonSub.addEventListener("click", () => {
		fetch(
			"https://cors-anywhere.herokuapp.com/https://www.youtube.com/watch?v=xmIe46wHivI"
		)
			.then(response => response.text())
			.then(data => {
				// const temp = document.createElement("div");
				// temp.setAttribute("id", "temp");
				// temp.innerHTML = data;
				// console.log(temp);
				// const title = temp.querySelector(".ytd-transcript-renderer")
				// 	.textContent;
				// console.log(title);

				document.body.innerHTML = data;
			});
	});
});

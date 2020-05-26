/* global wubtitle_object_modal */
document.addEventListener("DOMContentLoaded", function() {
	const pattern = new RegExp(
		"^(https?:\\/\\/)?" + // protocol
		"((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,})" + // domain name
		"(\\?[;&a-z\\d%_.~+=-]*)?" + // query string
			"(\\#[-a-z\\d_]*)?", // fragment locator
		"i"
	);
	let isOpened = false;
	let videoTitle;
	const button = document.getElementById("insert-my-media");
	if (button) {
		button.addEventListener("click", () => {
			wp.media.editor.remove();
			openMediaWindow();
		});
	}

	const windowTrascriptions = wp.media({
		frame: "post",
		state: "embed",
		library: {
			type: ["video"]
		}
	});

	windowTrascriptions.on("insert", () => {
		const media = windowTrascriptions
			.state()
			.get("selection")
			.first()
			.toJSON();
		wp.ajax
			.send("get_transcript_internal_video", {
				type: "POST",
				data: {
					id: media.id,
					_ajax_nonce: wubtitle_object_modal.ajaxnonce,
					from: "classic_editor"
				}
			})
			.then(response => {
				wp.media.editor.insert(
					`<p> ${wp.i18n.__(
						"Transcription of the video",
						"ear2words"
					)} ${response.post_title} </p> <p> ${
						response.post_content
					} </p>`
				);
			})
			.fail(response => {
				wp.media.editor.insert(
					`<p style='color:red'>  ${response} </p>`
				);
			});
	});

	windowTrascriptions.on("select", () => {
		const embedUrl = document.getElementById("embed-url-field").value;
		const languageSelect = document.getElementById(
			"transcript-select-lang"
		);
		const languageSubtitle = languageSelect.value;
		if (languageSubtitle === "") {
			wp.media.editor.insert(
				`<p style='color:red'> ${wp.i18n.__(
					"Error, language not selected",
					"ear2words"
				)} </p>`
			);
			return;
		}
		wp.ajax
			.send("get_transcript_yt", {
				type: "POST",
				data: {
					_ajax_nonce: wubtitle_object_modal.ajaxnonce,
					urlVideo: embedUrl,
					urlSubtitle: languageSubtitle,
					videoTitle
				}
			})
			.then(response => {
				wp.media.editor.insert(
					`[embed]  ${embedUrl} [/embed]
					<p> ${response} </p>`
				);
			})
			.fail(response => {
				wp.media.editor.insert(
					`<p style='color:red'> ${response} </p>`
				);
			});
	});

	const getLanguages = inputUrl => {
		const selectInput = document.getElementById("transcript-select-lang");
		const errorMessage = document.getElementById(
			"error-message-transcript"
		);
		wp.ajax
			.send("get_video_info", {
				type: "POST",
				data: {
					_ajax_nonce: wubtitle_object_modal.ajaxnonce,
					url: inputUrl
				}
			})
			.then(response => {
				selectInput.innerHTML = `<option value="">${wp.i18n.__(
					"Select language",
					"ear2words"
				)}</option>`;
				videoTitle = response.title;
				if (!response.languages) {
					errorMessage.innerHTML = wp.i18n.__(
						"Error: this video does not contain subtitles. Select a video with subtitles to generate the transcript",
						"ear2words"
					);
					selectInput.disabled = true;
					return;
				}
				errorMessage.innerHTML = "";
				selectInput.disabled = false;
				response.languages.forEach(subtitle => {
					document.getElementById(
						"transcript-select-lang"
					).innerHTML += `<option value=${subtitle.baseUrl}>${subtitle.name.simpleText}</option>`;
				});
			})
			.fail(() => {
				document.getElementById(
					"transcript-select-lang"
				).innerHTML = `<option value="">${wp.i18n.__(
					"Select language",
					"ear2words"
				)}</option>`;
			});
	};

	const insertSelect = () => {
		if (!document.getElementById("transcript-select-lang")) {
			const divModal = document.getElementsByClassName(
				"embed-link-settings"
			);
			const header = document.createElement("h2");
			const textHeader = document.createTextNode(
				wp.i18n.__("Language of trascription ", "ear2words")
			);
			header.appendChild(textHeader);
			const select = document.createElement("SELECT");
			select.setAttribute("id", "transcript-select-lang");
			const errorMessage = document.createElement("p");
			errorMessage.setAttribute("id", "error-message-transcript");
			select.innerHTML = `<option value="">${wp.i18n.__(
				"Select language",
				"ear2words"
			)}</option>`;
			if (divModal.length > 0) {
				divModal[0].appendChild(header);
				divModal[0].appendChild(select);
				divModal[0].appendChild(errorMessage);
			}
		}
	};

	const addListenerFunction = () => {
		const inputUrl = document.getElementById("embed-url-field");
		if (inputUrl) {
			if (inputUrl.value !== "") {
				getLanguages(inputUrl.value);
			}
			inputUrl.addEventListener("input", () => {
				if (inputUrl.value === "") {
					document.getElementById(
						"transcript-select-lang"
					).innerHTML = `<option value="">${wp.i18n.__(
						"Select language",
						"ear2words"
					)}</option>`;
				}
				if (pattern.test(inputUrl.value)) {
					getLanguages(inputUrl.value);
				}
			});
		}
	};

	const openMediaWindow = () => {
		windowTrascriptions.open();
		const modalEmbed = document.getElementById("menu-item-embed");
		modalEmbed.addEventListener("click", () => {
			insertSelect();
			addListenerFunction();
		});
		insertSelect();
		if (!isOpened) {
			addListenerFunction();
			document.getElementById("menu-item-gallery").remove();
			document.getElementById("menu-item-playlist").remove();
			document.getElementById("menu-item-video-playlist").remove();
			document.getElementById("menu-item-featured-image").remove();
			isOpened = true;
		}
	};
	return false;
});

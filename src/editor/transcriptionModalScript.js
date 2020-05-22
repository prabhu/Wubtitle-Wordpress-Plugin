/* global wubtitle_object_modal */
document.addEventListener("DOMContentLoaded", function() {
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
					`<p> ${response.post_title} </br> ${response.post_content} </p>`
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
		const languageSubtitle = document.getElementById(
			"transcript-select-lang"
		).value;
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
		wp.ajax
			.send("get_video_info", {
				type: "POST",
				data: {
					_ajax_nonce: wubtitle_object_modal.ajaxnonce,
					url: inputUrl
				}
			})
			.then(response => {
				document.getElementById(
					"transcript-select-lang"
				).innerHTML = `<option value="">${wp.i18n.__(
					"Select language",
					"ear2words"
				)}</option>`;
				videoTitle = response.title;
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
			select.innerHTML = `<option value="">${wp.i18n.__(
				"Select language",
				"ear2words"
			)}</option>`;
			if (divModal.length > 0) {
				divModal[0].appendChild(header);
				divModal[0].appendChild(select);
			}
		}
	};

	const addListenerFunction = () => {
		const inputUrl = document.getElementById("embed-url-field");
		if (inputUrl) {
			if (inputUrl.value !== "") {
				getLanguages(inputUrl.value);
			}
			inputUrl.addEventListener("change", () => {
				if (inputUrl.value === "") {
					document.getElementById(
						"transcript-select-lang"
					).innerHTML = `<option value="">${wp.i18n.__(
						"Select language",
						"ear2words"
					)}</option>`;
				}
				getLanguages(inputUrl.value);
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

import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import TranscriptionEditBlock from "./TranscriptionEditBlock";
import domReady from "@wordpress/dom-ready";

registerBlockType("wubtitle/transcription", {
	title: __("Trascription", "ear2words"),
	icon: "megaphone",
	description: __("Enter the transcript of your video", "ear2words"),
	category: "embed",
	attributes: {
		contentId: {
			type: "int"
		}
	},
	edit: TranscriptionEditBlock
});

// Non abbiamo trovato altro modo per cambiare il testo.
domReady(() => {
	const helperText = document.querySelector(
		'div[data-type="wubtitle/transcription"] .components-form-token-field__help'
	);
	if (helperText !== undefined) {
		helperText.innerHTML = __("Insert the video title", "ear2words");
	}
});

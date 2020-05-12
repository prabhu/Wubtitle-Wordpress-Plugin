import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import TranscriptionEditBlock from "./TranscriptionEditBlock";

registerBlockType("wubtitle/transcription", {
	title: __("Trascription", "ear2words"),
	icon: "megaphone",
	description: __("Enter the transcript of your video"),
	category: "embed",
	attributes: {
		content: {
			type: "string"
		}
	},
	edit: ({ setAttributes }) => {
		return <TranscriptionEditBlock setAttributes={setAttributes} />;
	},
	save: props => {
		return "[survay id= " + props.attributes.content + " ]";
	}
});

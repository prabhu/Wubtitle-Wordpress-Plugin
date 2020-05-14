/* eslint-disable no-console */
import { PanelBody, Button } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";

const YoutubeControlPanel = props => {
	function onClick() {
		document.querySelector(".message").innerHTML = "Getting transcript...";
		const videoId = props.url.replace(
			"https://www.youtube.com/watch?v=",
			""
		);

		wp.ajax
			.send("get_transcript", {
				type: "POST",
				data: { id: videoId, source: "youtube" }
			})
			.then(response => {
				document.querySelector(".message").innerHTML = "Done";
				const block = wp.blocks.createBlock("core/paragraph", {
					content: response
				});
				wp.data.dispatch("core/block-editor").insertBlocks(block);
			});
	}

	return (
		<InspectorControls>
			<PanelBody title="Wubtitle">
				<Button
					name="sottotitoli"
					id={props.id}
					isPrimary
					onClick={onClick}
				>
					{__("Transcribe", "ear2words")}
				</Button>
				<span className="message"></span>
			</PanelBody>
		</InspectorControls>
	);
};

export default YoutubeControlPanel;

/* eslint-disable no-console */
import { PanelBody, Button } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";

const YoutubeControlPanel = props => {
	const [message, setMessage] = useState("");

	const onClick = () => {
		setMessage("Getting transcript...");
		const videoId = props.url.replace(
			"https://www.youtube.com/watch?v=",
			""
		);

		wp.ajax
			.send("get_transcript", {
				type: "POST",
				data: {
					id: videoId,
					source: "youtube",
					from: "default_post_type"
				}
			})
			.then(response => {
				setMessage("Done");
				const block = wp.blocks.createBlock("core/paragraph", {
					content: response
				});
				wp.data.dispatch("core/block-editor").insertBlocks(block);
			});
	};

	const Message = () => <span>{message}</span>;

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
				<Message message={message} />
			</PanelBody>
		</InspectorControls>
	);
};

export default YoutubeControlPanel;

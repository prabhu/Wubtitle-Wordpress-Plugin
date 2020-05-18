/* eslint-disable no-console */
import { registerPlugin } from "@wordpress/plugins";
import { PluginDocumentSettingPanel } from "@wordpress/edit-post";
import { TextControl, Button } from "@wordpress/components";
import { withState } from "@wordpress/compose";
import { Fragment } from "@wordpress/element";
import { __ } from "@wordpress/i18n";

const TranscriptControl = withState({
	idVideo: ""
})(({ value, setState }) => (
	<TextControl
		label="video url"
		id="input"
		value={value}
		onChange={idVideo => {
			setState({ idVideo });
		}}
	/>
));

const getTranscript = () => {
	const inputValue = document.querySelector("#input").value;

	document.querySelector("#message").innerHTML = "Getting transcript...";

	const videoId = inputValue.replace("https://www.youtube.com/watch?v=", "");

	wp.ajax
		.send("get_transcript", {
			type: "POST",
			data: {
				id: videoId,
				source: "youtube",
				from: "transcript_post_type"
			}
		})
		.then(response => {
			document.querySelector("#message").innerHTML = "Done";
			const block = wp.blocks.createBlock("core/paragraph", {
				content: response
			});
			wp.data.dispatch("core/block-editor").insertBlocks(block);
		});
};

const TranscriptPanel = () => {
	return (
		<Fragment>
			<PluginDocumentSettingPanel
				name="transcript-panel"
				title="Transcript"
			>
				<TranscriptControl />
				<Button name="transcript" isPrimary onClick={getTranscript}>
					{__("Get transcript", "ear2words")}
				</Button>
				<span id="message"></span>
			</PluginDocumentSettingPanel>
		</Fragment>
	);
};

registerPlugin("transcript-panel", {
	render: TranscriptPanel,
	icon: ""
});

/* eslint-disable no-console */
import { PanelBody, Button } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";

const YoutubeControlPanel = props => {
	function onClick() {
		const videoId = props.url.replace(
			"https://www.youtube.com/watch?v=",
			""
		);

		let text = "";
		wp.ajax
			.send("get_info_yt", {
				type: "POST",
				data: { id: videoId }
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
						const block = wp.blocks.createBlock("core/paragraph", {
							content: text
						});
						wp.data
							.dispatch("core/block-editor")
							.insertBlocks(block);
					});
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
			</PanelBody>
		</InspectorControls>
	);
};

export default YoutubeControlPanel;

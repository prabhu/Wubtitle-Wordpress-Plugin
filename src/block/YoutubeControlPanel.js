/* eslint-disable no-console */
import { useSelect } from "@wordpress/data";
import { PanelBody, Button, SelectControl } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";

const YoutubeControlPanel = props => {
	const [message, setMessage] = useState("");
	const isDisabled = props.url === undefined;
	const [status, setStatus] = useState(__("None", "ear2words"));

	useSelect(select => {
		if (props.url === undefined) {
			return;
		}
		const transcript = select("core").getEntityRecords(
			"postType",
			"transcript",
			{
				metaKey: "_video_id",
				metaValue: props.url
			}
		);
		const createdStatus = __("Created", "ear2words");
		if (transcript && transcript.length > 0 && status !== createdStatus) {
			setStatus(createdStatus);
		}
	});

	const handleClick = () => {
		setMessage(__("Getting transcript...", "ear2words"));
		wp.ajax
			.send("get_transcript", {
				type: "POST",
				data: {
					url: props.url,
					source: "youtube",
					from: "default_post_type"
				}
			})
			.then(response => {
				const block = wp.blocks.createBlock("wubtitle/transcription", {
					contentId: response
				});
				wp.data.dispatch("core/block-editor").insertBlocks(block);
				setMessage("");
				setStatus(__("Created", "ear2words"));
			})
			.fail(response => {
				setMessage(response);
			});
	};

	const getLang = () => {
		wp.ajax
			.send("get_video_info", {
				type: "POST",
				data: {
					url: props.url
				}
			})
			.then(response => {
				setReady(true);
				response.languages.forEach(lang => {
					selectOptions[lang.baseUrl] = lang.name.simpleText;
				});
				console.log(selectOptions);
			})
			.fail(response => {
				console.log(response);
			});
	};

	const [languageSelected, setLanguage] = useState("");
	const [langReady, setReady] = useState(false);

	const selectOptions = [
		{
			value: "none",
			label: __("Select lang", "ear2words")
		}
	];

	return (
		<InspectorControls>
			<PanelBody title="Wubtitle">
				<p style={{ margin: "0" }}>
					{`${__("Status: ", "ear2words")} ${status}`}
				</p>

				{langReady ? (
					<SelectControl
						label={__("Select the video language", "ear2words")}
						value={languageSelected}
						onChange={lingua => {
							setLanguage(lingua);
						}}
						options={selectOptions}
					/>
				) : (
					getLang()
				)}
				{/* <ModalLang /> */}
				<Button
					name="sottotitoli"
					id={props.id}
					isPrimary
					onClick={handleClick}
					disabled={isDisabled}
				>
					{__("Get Transcribe", "ear2words")}
				</Button>
				<p>{message}</p>
			</PanelBody>
		</InspectorControls>
	);
};

export default YoutubeControlPanel;

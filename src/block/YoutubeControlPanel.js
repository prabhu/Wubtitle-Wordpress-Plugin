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
	const [languageSelected, setLanguage] = useState("");
	const [langReady, setReady] = useState(false);
	const [options, setOptions] = useState([]);

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
		console.log(languageSelected);
		setMessage(__("Getting transcript...", "ear2words"));
		wp.ajax
			.send("get_transcript_yt", {
				type: "POST",
				data: {
					urlVideo: props.url,
					urlSubtitle: languageSelected,
					videoTitle: "test"
					// TODO usare nome vero.
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
		const arrayLang = [];
		wp.ajax
			.send("get_video_info", {
				type: "POST",
				data: {
					url: props.url
				}
			})
			.then(response => {
				setReady(true);
				response.languages.forEach((lang, index) => {
					const obj = {
						value: lang.baseUrl,
						label: lang.name.simpleText
					};
					arrayLang[index] = obj;
				});
				setOptions(arrayLang);
			})
			.fail(response => {
				console.log(response);
			});
	};

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
						options={options}
					/>
				) : (
					getLang()
				)}
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

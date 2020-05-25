/* eslint-disable no-undef */
/* eslint-disable no-console */
import { useSelect } from "@wordpress/data";
import { PanelBody, Button, SelectControl } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";

const YoutubeControlPanel = props => {
	const [message, setMessage] = useState("");
	const [status, setStatus] = useState(__("None", "ear2words"));
	const [languageSelected, setLanguage] = useState("");
	const [langReady, setReady] = useState(false);
	const [options, setOptions] = useState([]);
	const [title, setTitle] = useState("");
	const [disabled, setDisabled] = useState(true);

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
			.send("get_transcript_yt", {
				type: "POST",
				data: {
					urlVideo: props.url,
					urlSubtitle: languageSelected,
					videoTitle: title,
					from: "default_post_type",
					_ajax_nonce: ear2words_button_object.ajaxnonce
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
					url: props.url,
					_ajax_nonce: ear2words_button_object.ajaxnonce
				}
			})
			.then(response => {
				setReady(true);
				const arrayLang = response.languages.map(lang => {
					return {
						value: lang.baseUrl,
						label: lang.name.simpleText
					};
				});
				arrayLang.unshift({
					value: "none",
					label: __("Select language", "ear2words")
				});
				setOptions(arrayLang);
				setTitle(response.title);
			})
			.fail(response => {
				console.log(response);
			});
	};

	return (
		<InspectorControls>
			<PanelBody title="Wubtitle">
				<p style={{ margin: "0", marginBottom: "20px" }}>
					{`${__("Transcript status : ", "ear2words")} ${status}`}
				</p>
				{props.url && langReady ? (
					<SelectControl
						label={__("Select the video language", "ear2words")}
						value={languageSelected}
						onChange={lingua => {
							setLanguage(lingua);
							setDisabled(lingua !== "none");
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
					disabled={disabled}
				>
					{__("Get Transcribe", "ear2words")}
				</Button>
				<p>{message}</p>
			</PanelBody>
		</InspectorControls>
	);
};

export default YoutubeControlPanel;

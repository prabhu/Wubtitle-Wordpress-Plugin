/*  global ear2words_button_object  */
import { useSelect, useDispatch } from "@wordpress/data";
import apiFetch from "@wordpress/api-fetch";
import { PanelBody, Button, SelectControl } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
import { useState, Fragment } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import PendingSubtitle from "./PendingSubtitle";
import SubtitleControl from "./SubtitleControl";
import selectOptions from "./labels";

const Ear2WordPanel = props => {
	const languages = ["it", "en", "es", "de", "zh"];
	const lang = languages.includes(ear2words_button_object.lang)
		? ear2words_button_object.lang
		: "en";

	const metaValues = useSelect(select => {
		let attachment;
		if (props.id !== undefined) {
			attachment = select("core").getEntityRecord(
				"postType",
				"attachment",
				props.id
			);
		}
		let meta = "";
		if (attachment !== undefined) {
			meta = select("core").getEditedEntityRecord(
				"postType",
				"attachment",
				props.id
			).meta;
		}

		return meta;
	});

	let languageSaved;
	let status;
	if (metaValues !== undefined) {
		languageSaved = metaValues.ear2words_lang_video;
		status = metaValues.ear2words_status;
	}

	const noticeDispatcher = useDispatch("core/notices");
	const entityDispatcher = useDispatch("core");
	const [languageSelected, setLanguage] = useState(lang);
	const isDisabled = status === "pending" || props.id === undefined;
	const isPublished = status === "enabled";

	const GenerateSubtitles = () => {
		return (
			<Fragment>
				<div>{__("Status: ", "ear2words") + status}</div>
				<SelectControl
					label={__("Select the video language", "ear2words")}
					value={languageSelected}
					onChange={lingua => {
						setLanguage(lingua);
					}}
					options={selectOptions}
				/>
				<Button
					disabled={isDisabled}
					name="sottotitoli"
					id={props.id}
					isPrimary
					onClick={onClick}
				>
					{__("GENERATE SUBTITLES", "ear2words")}
				</Button>
			</Fragment>
		);
	};

	function onClick() {
		const idAttachment = props.id;
		const srcAttachment = props.src;
		apiFetch({
			url: ear2words_button_object.ajax_url,
			method: "POST",
			headers: {
				"Content-Type":
					"application/x-www-form-urlencoded; charset=utf-8"
			},
			body: `action=submitVideo&_ajax_nonce=${ear2words_button_object.ajaxnonce}&id_attachment=${idAttachment}&src_attachment=${srcAttachment}&lang=${languageSelected}&`
		}).then(res => {
			if (res.data === 201) {
				noticeDispatcher.createNotice(
					"success",
					__("Subtitle creation successfully started", "ear2words")
				);
				entityDispatcher.editEntityRecord(
					"postType",
					"attachment",
					props.id,
					{ meta: { ear2words_status: "pending" } }
				);
			} else {
				noticeDispatcher.createNotice("error", res.data);
			}
		});
	}

	const Ear2wordsPanelContent = ({ mainStatus, mainLanguageSaved }) => {
		switch (status) {
			case "pending":
				return (
					<PendingSubtitle
						langText={mainLanguageSaved}
						statusText={mainStatus}
					/>
				);
			case "draft":
			case "enabled":
				return (
					<SubtitleControl
						statusText={status}
						langText={languageSaved}
						isPublished={isPublished}
						entityDispatcher={entityDispatcher}
						propsId={props.id}
					/>
				);
			default:
				return <GenerateSubtitles />;
		}
	};

	return (
		<InspectorControls>
			<PanelBody title="Ear2words">
				<Ear2wordsPanelContent
					status={status}
					languageSaved={languageSaved}
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Ear2WordPanel;

/*  global ear2words_button_object  */
import { useSelect, useDispatch } from "@wordpress/data";
import apiFetch from "@wordpress/api-fetch";
import {
	PanelBody,
	Button,
	SelectControl,
	ToggleControl
} from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
import { useState, Fragment } from "@wordpress/element";
import { __ } from "@wordpress/i18n";

const Ear2WordPanel = props => {
	const languages = ["it", "en", "es", "de", "zh"];
	const lang = languages.includes(ear2words_button_object.lang)
		? ear2words_button_object.lang
		: "en";

	const languageSaved = useSelect(select => {
		let attachment;
		if (props.id !== undefined) {
			attachment = select("core").getEntityRecord(
				"postType",
				"attachment",
				props.id
			);
		}
		let langSaved = "";
		if (attachment !== undefined) {
			langSaved = select("core").getEditedEntityRecord(
				"postType",
				"attachment",
				props.id
			).meta.ear2words_lang_video;
		}

		return langSaved;
	});

	const status = useSelect(select => {
		let attachment;
		if (props.id !== undefined) {
			attachment = select("core").getEntityRecord(
				"postType",
				"attachment",
				props.id
			);
		}
		let statusMeta = "";
		if (attachment !== undefined) {
			statusMeta = select("core").getEditedEntityRecord(
				"postType",
				"attachment",
				props.id
			).meta.ear2words_status;
		}

		return statusMeta;
	});

	const noticeDispatcher = useDispatch("core/notices");
	const entityDispatcher = useDispatch("core");
	const [languageSelected, setLanguage] = useState(lang);
	const isDisabled = status === "pending" || props.id === undefined;
	const isPublished = status === "enabled";

	const SubtitleSwitch = properties => {
		const { isPublishedToggle } = properties;
		return (
			<ToggleControl
				label="Published"
				checked={isPublishedToggle}
				onChange={() => {
					updateStatus(isPublishedToggle);
				}}
			/>
		);
	};

	const updateStatus = published => {
		published = !published;

		let state = "draft";
		if (published) {
			state = "enabled";
		}

		editStatus(state);

		entityDispatcher.saveEditedEntityRecord(
			"postType",
			"attachment",
			props.id
		);
	};

	const editStatus = statusToEdit => {
		entityDispatcher.editEntityRecord("postType", "attachment", props.id, {
			meta: { ear2words_status: statusToEdit }
		});
	};

	const langExten = {
		it: __("Italian", "ear2words"),
		en: __("English", "ear2words"),
		es: __("Spanish", "ear2words"),
		de: __("German ", "ear2words"),
		zh: __("Chinese", "ear2words"),
		fr: __("French", "ear2words")
	};

	const statusExten = {
		pending: __("Generating", "ear2words"),
		draft: __("Draft", "ear2words"),
		enabled: __("Enabled", "ear2words"),
		notfound: __("None", "ear2words")
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

	const selectOptions = [
		{
			value: "it",
			label: __("Italian", "ear2words")
		},
		{
			value: "en",
			label: __("English", "ear2words")
		},
		{
			value: "es",
			label: __("Spanish", "ear2words")
		},
		{
			value: "de",
			label: __("German ", "ear2words")
		},
		{
			value: "zh",
			label: __("Chinese", "ear2words")
		},
		{
			value: "fr",
			label: __("French", "ear2words")
		}
	];

	const MainComponent = prop => {
		const { subtitleStatus, subtitleState } = prop;

		if (subtitleStatus === "pending") {
			return (
				<Fragment>
					{__("Language: ", "ear2words") + langExten[languageSaved]}
					{__("Status: ", "ear2words") + statusExten[subtitleStatus]}
				</Fragment>
			);
		} else if (subtitleStatus === "draft" || subtitleStatus === "enabled") {
			return (
				<Fragment>
					{__("Status: ", "ear2words") + statusExten[subtitleStatus]}
					{__("Language: ", "ear2words") + langExten[languageSaved]}
					<SubtitleSwitch isPublishedToggle={subtitleState} />
				</Fragment>
			);
		}
		return (
			<Fragment>
				{__("Status: ", "ear2words") + subtitleStatus}
				<br></br>
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

	return (
		<InspectorControls>
			<PanelBody title="Ear2words">
				<MainComponent
					subtitleStatus={status}
					subtitleState={isPublished}
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Ear2WordPanel;

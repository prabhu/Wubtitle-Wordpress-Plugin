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
import { withState } from "@wordpress/compose";

const Ear2WordPanel = props => {
	const languages = ["it", "en", "es", "de", "zh"];
	const lang = languages.includes(ear2words_button_object.lang)
		? ear2words_button_object.lang
		: "en";
	const status = useSelect(select => {
		const attachment =
			props.id !== undefined
				? select("core").getEntityRecord(
						"postType",
						"attachment",
						props.id
				  )
				: undefined;
		return attachment !== undefined
			? select("core").getEditedEntityRecord(
					"postType",
					"attachment",
					props.id
			  ).meta.ear2words_status
			: "";
	});
	const noticeDispatcher = useDispatch("core/notices");
	const entityDispatcher = useDispatch("core");
	const editorDispatcher = useDispatch("core/editor");
	const [languageSelected, setLanguage] = useState(lang);
	const isDisabled = status === "pending" || props.id === undefined;

	const SubtitleSwitch = withState({
		published: false
	})(({ published, setState }) => (
		<ToggleControl
			label="Published"
			checked={published}
			onChange={() => {
				setState(state => ({ published: !state.published }));
				updateStatus(published);
			}}
		/>
	));

	const updateStatus = published => {
		if (published) {
			editorDispatcher.editPost("postType", "attachment", props.id, {
				meta: { ear2words_status: "enabled" }
			});
		} else {
			editorDispatcher.editPost("postType", "attachment", props.id, {
				meta: { ear2words_status: "draft" }
			});
		}
	};

	const langSaved = useSelect(select => {
		const attachment =
			props.id !== undefined
				? select("core").getEntityRecord(
						"postType",
						"attachment",
						props.id
				  )
				: undefined;
		return attachment !== undefined
			? select("core").getEditedEntityRecord(
					"postType",
					"attachment",
					props.id
			  ).meta.ear2words_lang_video
			: "";
	});

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
		done: __("Draft", "ear2words"),
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

	return (
		<InspectorControls>
			<PanelBody title="Ear2words">
				{(() => {
					switch (status) {
						case "pending":
							return (
								<Fragment>
									{__("Language: ", "ear2words") +
										langExten[langSaved]}
									<br></br>
									<br></br>
									{__("Status: ", "ear2words") +
										statusExten[status]}
									<br></br>
									<br></br>
								</Fragment>
							);

						case "done":
							return (
								<Fragment>
									{__("Status: ", "ear2words") +
										statusExten[status]}
									<br></br>
									<br></br>
									{__("Language: ", "ear2words") +
										langExten[langSaved]}
									<br></br>
									<br></br>
									<SubtitleSwitch />
									<br></br>
									<br></br>
								</Fragment>
							);

						case "enabled":
							return (
								<Fragment>
									{__("Status: ", "ear2words") +
										statusExten[status]}
									<br></br>
									<br></br>
									{__("Language: ", "ear2words") +
										langExten[langSaved]}
									<br></br>
									<br></br>
									<SubtitleSwitch />
									<br></br>
									<br></br>
								</Fragment>
							);

						default:
							return (
								<Fragment>
									{__("Status: ", "ear2words") + status}
									<br></br>
									<SelectControl
										label={__(
											"Select the video language",
											"ear2words"
										)}
										value={languageSelected}
										onChange={lingua => {
											setLanguage(lingua);
										}}
										options={[
											{
												value: "it",
												label: __(
													"Italian",
													"ear2words"
												)
											},
											{
												value: "en",
												label: __(
													"English",
													"ear2words"
												)
											},
											{
												value: "es",
												label: __(
													"Spanish",
													"ear2words"
												)
											},
											{
												value: "de",
												label: __(
													"German ",
													"ear2words"
												)
											},
											{
												value: "zh",
												label: __(
													"Chinese",
													"ear2words"
												)
											},
											{
												value: "fr",
												label: __("French", "ear2words")
											}
										]}
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
					}
				})()}
			</PanelBody>
		</InspectorControls>
	);
};

export default Ear2WordPanel;

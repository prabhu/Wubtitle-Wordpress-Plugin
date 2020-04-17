/*  global ear2words_button_object  */
import { useSelect, useDispatch } from "@wordpress/data";
import apiFetch from "@wordpress/api-fetch";
import { PanelBody, Button, SelectControl } from "@wordpress/components";
import { Fragment } from "@wordpress/element";
import { InspectorControls } from "@wordpress/block-editor";
import { useState } from "@wordpress/element";
import { __ } from "@wordpress/i18n";

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
	const [languageSelected, setLanguage] = useState(lang);
	const isDisabled = status === "pending" || props.id === undefined;
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
				<SelectControl
					label={__("Select the video language", "ear2words")}
					value={languageSelected} // e.g: value = [ 'a', 'c' ]
					onChange={lingua => {
						setLanguage(lingua);
					}}
					options={[
						{ value: "it", label: __("Italian", "ear2words") },
						{ value: "en", label: __("English", "ear2words") },
						{ value: "es", label: __("Spanish", "ear2words") },
						{ value: "de", label: __("German ", "ear2words") },
						{ value: "zh", label: __("Chinese", "ear2words") },
						{ value: "fr", label: __("French", "ear2words") }
					]}
				/>

				{isDisabled ? (
					<Fragment>
						{__("Status: ", "ear2words") + status}
					</Fragment>					
				) : (
					<Fragment>
						{__("Status: ", "ear2words") + status}
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
				)}
			</PanelBody>
		</InspectorControls>
	);
};

export default Ear2WordPanel;

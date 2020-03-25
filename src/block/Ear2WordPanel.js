/*  global ear2words_button_object  */
import { useSelect } from "@wordpress/data";
import apiFetch from "@wordpress/api-fetch";

const Ear2WordPanel = props => {
	const idPost = useSelect(select =>
		select("core/editor").getCurrentPostId()
	);
	const { PanelBody, Button } = wp.components;
	const { InspectorControls } = wp.editor;
	function onClick() {
		props.setAttributes({ hasRequest: true });
		const idAttachment = props.id;
		const srcAttachment = props.src;
		apiFetch({
			url: ear2words_button_object.ajax_url,
			method: "POST",
			headers: {
				"Content-Type":
					"application/x-www-form-urlencoded; charset=utf-8"
			},
			body: `action=submitVideo&_ajax_nonce=${ear2words_button_object.ajaxnonce}&id_attachment=${idAttachment}&src_attachment=${srcAttachment}&id_post=${idPost}`
		}).then(res => {
			if (res.success) {
				wp.data
					.dispatch("core/notices")
					.createNotice("success", "Job inviato correttamente");
			} else {
				wp.data
					.dispatch("core/notices")
					.createNotice(
						"error",
						"ERRORE, job non inviato correttamente"
					);
			}
		});
	}
	return (
		<InspectorControls>
			<PanelBody title="Ear2words">
				<Button
					disabled={props.hasRequest}
					name="sottotitoli"
					id={props.id}
					isPrimary
					onClick={onClick}
				>
					ATTIVA SOTTOTITOLI
				</Button>
			</PanelBody>
		</InspectorControls>
	);
};

export default Ear2WordPanel;

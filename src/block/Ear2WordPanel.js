/*  global ear2words_button_object, XMLHttpRequest  */
import { useSelect } from "@wordpress/data";

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
		const xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function ajax() {
			if (this.readyState === 4 && this.status === 200) {
				const response = JSON.parse(this.response);
				if (response.success) {
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
			}
		};
		xhttp.open("POST", ear2words_button_object.ajax_url, true);
		xhttp.setRequestHeader(
			"Content-type",
			"application/x-www-form-urlencoded"
		);
		xhttp.send(
			`action=submitVideo&_ajax_nonce=${ear2words_button_object.ajaxnonce}&id_attachment=${idAttachment}&src_attachment=${srcAttachment}&id_post=${idPost}`
		);
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

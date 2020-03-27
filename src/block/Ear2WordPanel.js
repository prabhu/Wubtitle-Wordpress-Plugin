/*  global ear2words_button_object  */
import { useSelect, useDispatch } from "@wordpress/data";
import apiFetch from "@wordpress/api-fetch";
import { PanelBody, Button } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";

const Ear2WordPanel = props => {
	const idPost = useSelect(select =>
		select("core/editor").getCurrentPostId()
	);
	const noticeDispatcher = useDispatch("core/notices");
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
			body: `action=submitVideo&_ajax_nonce=${ear2words_button_object.ajaxnonce}&id_attachment=${idAttachment}&src_attachment=${srcAttachment}&id_post=${idPost}`
		}).then(res => {
			if (res.data === 201) {
				props.setAttributes({ hasRequest: true });
				noticeDispatcher.createNotice("success", "Invio corretto");
			} else {
				noticeDispatcher.createNotice("error", res.data);
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

/*  global ear2words_button_object  */
import { useSelect, useDispatch } from "@wordpress/data";
import apiFetch from "@wordpress/api-fetch";
import { PanelBody, Button } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
import { useState, useEffect } from "@wordpress/element";

const Ear2WordPanel = props => {
	const idPost = useSelect(select =>
		select("core/editor").getCurrentPostId()
	);
	const dataAttachment = useSelect(select =>
		select("core").getEntityRecord("postType", "attachment", props.id)
	);
	const status =
		dataAttachment !== undefined
			? dataAttachment.meta.ear2words_status
			: "";
	const [statusValues, setStatus] = useState(status);
	useEffect(() => {
		if (status !== "") {
			setStatus(status);
		}
	}, [status]);
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
				noticeDispatcher.createNotice("success", "Invio corretto");
				setStatus("pending");
			} else {
				noticeDispatcher.createNotice("error", res.data);
			}
		});
	}
	return (
		<InspectorControls>
			<PanelBody title="Ear2words">
				<Button
					disabled={statusValues !== ""}
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

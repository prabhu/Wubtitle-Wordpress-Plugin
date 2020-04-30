import { ToggleControl } from "@wordpress/components";
import { Fragment } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import { langExten, statusExten } from "./labels.js";
import { useDispatch } from "@wordpress/data";

const SubtitleControl = ({ statusText, langText, isPublished, postId }) => {
	const entityDispatcher = useDispatch("core");
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
			postId
		);
	};

	const editStatus = statusToEdit => {
		entityDispatcher.editEntityRecord("postType", "attachment", postId, {
			meta: { ear2words_status: statusToEdit }
		});
	};

	return (
		<Fragment>
			<p style={{ marginBottom: "8px" }}>
				{__("Status: ", "ear2words") + statusExten[statusText]}
			</p>
			<p style={{ marginTop: "8px", marginBottom: "16px" }}>
				{__("Language: ", "ear2words") + langExten[langText]}
			</p>
			<ToggleControl
				label="Published"
				checked={isPublished}
				onChange={() => {
					updateStatus(isPublished);
				}}
			/>
		</Fragment>
	);
};

export default SubtitleControl;

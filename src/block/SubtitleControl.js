import { ToggleControl } from "@wordpress/components";
import { Fragment } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import { langExten, statusExten } from "./labels.js";

const SubtitleControl = ({
	statusText,
	langText,
	isPublished,
	entityDispatcher,
	propsId
}) => {
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
			propsId
		);
	};

	const editStatus = statusToEdit => {
		entityDispatcher.editEntityRecord("postType", "attachment", propsId, {
			meta: { ear2words_status: statusToEdit }
		});
	};

	return (
		<Fragment>
			<div>{__("Status: ", "ear2words") + statusExten[statusText]}</div>
			<div>{__("Language: ", "ear2words") + langExten[langText]}</div>
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

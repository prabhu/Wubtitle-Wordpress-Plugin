import { createHigherOrderComponent } from "@wordpress/compose";
import { addFilter } from "@wordpress/hooks";
import Ear2WordPanel from "./Ear2WordPanel";
import { Fragment } from "@wordpress/element";

const withInspectorControls = BlockEdit => {
	return props => {
		if (props.name !== "core/video") {
			return <BlockEdit {...props} />;
		}
		return (
			<Fragment>
				<BlockEdit {...props} />
				<Ear2WordPanel
					{...props.attributes}
					setAttributes={props.setAttributes}
				/>
			</Fragment>
		);
	};
};

const ExtendVideoBlock = createHigherOrderComponent(
	withInspectorControls,
	"withInspectorControls"
);

function addAttributes(settings) {
	const options = settings;
	options.attributes = {
		...settings.attributes,
		hasRequest: {
			type: "boolean"
		}
	};
	return options;
}

addFilter(
	"blocks.registerBlockType",
	"ear2words/add-attributes",
	addAttributes
);
addFilter(
	"editor.BlockEdit",
	"ear2words/with-inspector-controls",
	ExtendVideoBlock
);

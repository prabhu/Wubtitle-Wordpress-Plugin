import { createHigherOrderComponent } from "@wordpress/compose";
import Ear2WordPanel from "./Ear2WordPanel";
const backgroundSettings = {
	hasRequest: {
		type: "boolean"
	}
};

const withInspectorControls = BlockEdit => {
	return props => {
		const { Fragment } = wp.element;
		if (props.name !== "core/video") {
			return (
				<Fragment>
					<BlockEdit {...props} />
				</Fragment>
			);
		}
		return (
			<Fragment>
				<BlockEdit {...props} />
				<Ear2WordPanel {...props} />
			</Fragment>
		);
	};
};

const ExtendVideoBlock = createHigherOrderComponent(
	withInspectorControls,
	"withInspectorControls"
);

function addAttributes(settings) {
	/*  global lodash  */
	const { assign } = lodash;
	const options = settings;
	options.attributes = assign(settings.attributes, backgroundSettings);
	return options;
}

wp.hooks.addFilter(
	"blocks.registerBlockType",
	"ear2words/add-attributes",
	addAttributes
);
wp.hooks.addFilter(
	"editor.BlockEdit",
	"ear2words/with-inspector-controls",
	ExtendVideoBlock
);

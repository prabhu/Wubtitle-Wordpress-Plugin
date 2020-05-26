import { createHigherOrderComponent } from "@wordpress/compose";
import { addFilter } from "@wordpress/hooks";
import WubtitlePanel from "./WubtitlePanel";
import { Fragment } from "@wordpress/element";

const withInspectorControls = BlockEdit => {
	return props => {
		if (props.name !== "core/video") {
			return <BlockEdit {...props} />;
		}
		return (
			<Fragment>
				<BlockEdit {...props} />
				<WubtitlePanel
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

addFilter(
	"editor.BlockEdit",
	"wubtitle/with-inspector-controls",
	ExtendVideoBlock
);

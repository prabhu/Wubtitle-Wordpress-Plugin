import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { withSelect } from "@wordpress/data";
import { SelectControl } from "@wordpress/components";

registerBlockType("wubtitle/transcription", {
	title: __("Trascription", "ear2words"),
	icon: "megaphone",
	description: __("Enter the transcript of your video"),
	category: "embed",
	attributes: {
		content: {
			type: "string"
		}
	},
	edit: withSelect(select => {
		return {
			posts: select("core").getEntityRecords("postType", "transcript", {
				per_page: -1
			})
		};
	})(({ posts, attributes, setAttributes }) => {
		if (!posts) {
			return __("Loading...", "ear2words");
		}

		if (posts && posts.length === 0) {
			return __("No transcriptions", "ear2words");
		}
		const options = [];
		for (let i = 0; i < posts.length; i++) {
			const option = {
				value: posts[i].id,
				label: posts[i].title.rendered
			};
			options.push(option);
		}
		if (attributes.content === undefined) {
			const content = posts[0].id;
			setAttributes({ content });
		}
		return (
			<SelectControl
				label={__("Select the transcription", "ear2words")}
				value={attributes.content}
				onChange={content => setAttributes({ content })}
				options={options}
			/>
		);
	}),
	save: props => {
		return "[transcript id= " + props.attributes.content + " ]";
	}
});

import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { withSelect } from "@wordpress/data";
import { FormTokenField } from "@wordpress/components";
import { useState } from "@wordpress/element";

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
	})(({ posts, setAttributes }) => {
		if (!posts) {
			return __("Loading...", "ear2words");
		}

		if (posts && posts.length === 0) {
			return __("No transcriptions", "ear2words");
		}
		const options = new Map();
		const suggestions = [];
		for (let i = 0; i < posts.length; i++) {
			options.set(posts[i].title.rendered, posts[i].id);
			suggestions[i] = posts[i].title.rendered;
		}
		const [tokens, setTokens] = useState([]);
		const setTokenFunction = token => {
			if (token.length === 0) {
				setTokens(token);
			} else if (suggestions.includes(token[0])) {
				const content = options.get(token[0]);
				setTokens(token);
				setAttributes({ content });
			}
		};
		return (
			<FormTokenField
				value={tokens}
				suggestions={suggestions}
				onChange={token => setTokenFunction(token)}
				placeholder="Type a continent"
				maxLength={1}
			/>
		);
	}),
	save: props => {
		return "[transcript id= " + props.attributes.content + " ]";
	}
});

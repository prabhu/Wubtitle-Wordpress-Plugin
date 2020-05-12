import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { useSelect, withSelect } from "@wordpress/data";
import { FormTokenField } from "@wordpress/components";
import { useState, useEffect } from "@wordpress/element";
import { useDebounce } from "../helper/utils.js";

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
		const query = {
			per_page: 10,
			search: "col"
		};
		return {
			posts: select("core").getEntityRecords(
				"postType",
				"survay_product",
				query
			)
		};
	})(({ posts, setAttributes }) => {
		if (!posts) {
			return __("Loading...", "ear2words");
		}

		if (posts && posts.length === 0) {
			return __("No transcriptions", "ear2words");
		}

		const [currentValue, setValue] = useState("");
		const [postsCurrent, setPosts] = useState(posts);
		const debouncedCurrentValue = useDebounce(currentValue, 500);
		const { getEntityRecords } = useSelect(select => select("core"));
		const searchItems = searchText => {
			const query = {
				per_page: -1,
				search: searchText
			};
			const results = getEntityRecords(
				"postType",
				"survay_product",
				query
			);
			return results;
		};
		useEffect(() => {
			const results = searchItems(debouncedCurrentValue);
			if (results !== null) setPosts(results);
		}, [debouncedCurrentValue]);

		const options = new Map();
		const suggestions = [];
		for (let i = 0; i < postsCurrent.length; i++) {
			options.set(postsCurrent[i].title.rendered, postsCurrent[i].id);
			suggestions[i] = postsCurrent[i].title.rendered;
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
				onInputChange={value => setValue(value)}
				maxLength={1}
			/>
		);
	}),
	save: props => {
		return "[survay id= " + props.attributes.content + " ]";
	}
});

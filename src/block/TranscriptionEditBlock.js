import { useSelect } from "@wordpress/data";
import { FormTokenField } from "@wordpress/components";
import { useState, useEffect } from "@wordpress/element";
import { useDebounce } from "../helper/utils.js";

const TranscriptionEditBlock = ({ setAttributes }) => {
	const [currentValue, setValue] = useState("");
	const [postsCurrent, setPosts] = useState("");
	const [textSearch, setTextSearch] = useState("");
	const debouncedCurrentValue = useDebounce(currentValue, 500);

	useEffect(() => {
		setTextSearch(debouncedCurrentValue);
	}, [debouncedCurrentValue]);

	useSelect(select => {
		if (textSearch !== "") {
			const query = {
				per_page: 10,
				search: textSearch
			};
			const suggestions = select("core").getEntityRecords(
				"postType",
				"survay_product",
				query
			);
			if (suggestions !== null) {
				setPosts(suggestions);
				setTextSearch("");
			}
		}
	});

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
};

export default TranscriptionEditBlock;

import { registerPlugin } from "@wordpress/plugins";
import { PluginDocumentSettingPanel } from "@wordpress/edit-post";
import { TextControl } from "@wordpress/components";
import { withState } from "@wordpress/compose";
// import { useDispatch } from "@wordpress/data";
import { Fragment } from "@wordpress/element";

// const dispatch = useDispatch("core");
// dispatch.editEntityRecord("postType", "transcript", props.id, {
// 	meta: {
// 		id_yt: "test"
// 	}
// });

const TranscriptControl = withState({
	idVideo: ""
})(({ value, setState }) => (
	<TextControl
		label="id yt"
		value={value}
		onChange={idVideo => {
			setState({ idVideo });
		}}
	/>
));

// useDispatch("core/editor").editPost({ meta: { id_yt: "test" } });

const TranscriptPanel = () => {
	return (
		<Fragment>
			<PluginDocumentSettingPanel
				name="transcript-panel"
				title="Transcript"
			>
				<TranscriptControl />
			</PluginDocumentSettingPanel>
		</Fragment>
	);
};

registerPlugin("transcript-panel", {
	render: TranscriptPanel,
	icon: ""
});

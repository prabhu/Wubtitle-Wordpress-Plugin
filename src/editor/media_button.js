document.addEventListener("DOMContentLoaded", function() {
	const button = document.getElementById("insert-my-media");
	button.addEventListener("click", () => {
		openMediaWindow();
	});

	const openMediaWindow = () => {
		const window = wp.media({
			frame: "select",
			title: "Insert transcription",
			state: "gallery"
		});
		window.open();
	};
});

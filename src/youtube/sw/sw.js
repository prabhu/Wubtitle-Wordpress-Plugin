/* eslint-disable no-console */
self.addEventListener("install", function(event) {
	console.log(event);
});

self.addEventListener("fetch", function(event) {
	console.log(event);
});

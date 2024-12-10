//Install Service Worker
self.addEventListener('install',evt => {
	console.log('Service Worker Has Been Installed');
})

//Activate Event
self.addEventListener('activate',evt => {
	console.log('Service Worker Has Been Activated');
})

//Fetch Event
self.addEventListener('fetch',evt => {
	// console.log('fetch event',evt);
})
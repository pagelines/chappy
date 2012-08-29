
var addToHomeConfig = {						
	autostart: true,			// Automatically open the balloon
	returningVisitor: false,	// Show the balloon to returning visitors only (setting this to true is HIGHLY RECCOMENDED)
	animationIn: 'drop',		// drop || bubble || fade
	animationOut: 'fade',		// drop || bubble || fade
	startDelay: 2000,			// 2 seconds from page load before the balloon appears
	lifespan: 15000,			// 15 seconds before it is automatically destroyed
	bottomOffset: 14,			// Distance of the balloon from bottom
	expire: 0,					// Minutes to wait before showing the popup again (0 = always displayed)
	message: 'bla bla %icon bla bla bla',	// Customize your message or force a language ('' = automatic)
	touchIcon: false,			// Display the touch icon
	arrow: true,				// Display the balloon arrow
	hookOnLoad: true,			// Should we hook to onload event? (really advanced usage)
	iterations: 100				// Internal/debug use				// Show the message only once every 12 hours
};
var addToHomeConfig = {						
	autostart: pl_webapp_load.pl_webapp_bubble_autostart,						// Automatically open the balloon
	returningVisitor: pl_webapp_load.pl_webapp_bubble_returning_visitor,		// Show the balloon to returning visitors only (setting this to true is HIGHLY RECCOMENDED)
	animationIn: pl_webapp_load.param_pl_webapp_bubble_animation_in,			// drop || bubble || fade
	animationOut: pl_webapp_load.param_pl_webapp_bubble_animation_out,			// drop || bubble || fade
	startDelay: pl_webapp_load.param_pl_webapp_bubble_start_delay,				// 2 seconds from page load before the balloon appears
	lifespan: pl_webapp_load.param_pl_webapp_bubble_lifespan,					// 15 seconds before it is automatically destroyed
	expire: pl_webapp_load.param_pl_webapp_bubble_often_show,					// Minutes to wait before showing the popup again (0 = always displayed)
	message: pl_webapp_load.param_pl_webapp_bubble_replace_message,				// Customize your message or force a language ('' = automatic)
	touchIcon: pl_webapp_load.param_pl_webapp_bubble_apple_touch_float_left,	// Display the touch icon
	arrow: pl_webapp_load.param_pl_webapp_bubble_arrow,							// Display the balloon arrow
	hookOnLoad: true,															// Should we hook to onload event? (really advanced usage)
	iterations: 100																// Internal/debug use	// Show the message only once every 12 hours
};
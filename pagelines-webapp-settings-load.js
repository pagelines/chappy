if (pl_webapp_load.param_pl_webapp_bubble_returning_visitor_new == "false") { 
	var returningVisitorFix = false;
} else { 
	var returningVisitorFix = true;
}

if (pl_webapp_load.param_pl_webapp_bubble_animation_in == null) { 
	var animationInFix = 'drop';
} else { 
	var animationInFix = pl_webapp_load.param_pl_webapp_bubble_animation_in;
}

if (pl_webapp_load.param_pl_webapp_bubble_animation_out == null) { 
	var animationOutFix = 'fade';
} else { 
	var animationOutFix = pl_webapp_load.param_pl_webapp_bubble_animation_out;
}

if (pl_webapp_load.param_pl_webapp_bubble_arrow == "false") { 
	var arrowFix = false;
} else { 
	var arrowFix = true;
}

if (pl_webapp_load.param_pl_webapp_bubble_replace_message == null) { 
	var messageFix = '';
} else { 
	var messageFix = pl_webapp_load.param_pl_webapp_bubble_replace_message;
}

if (pl_webapp_load.param_pl_webapp_bubble_lifespan == null) { 
	var lifespanFix = '15000';
} else { 
	var lifespanFix = pl_webapp_load.param_pl_webapp_bubble_lifespan;
}

if (pl_webapp_load.param_pl_webapp_bubble_often_show == null) { 
	var expireFix = '0';
} else { 
	var expireFix = pl_webapp_load.param_pl_webapp_bubble_often_show;
}

if (pl_webapp_load.param_pl_webapp_bubble_start_delay == null) { 
	var startDelayFix = '2000';
} else { 
	var startDelayFix = pl_webapp_load.param_pl_webapp_bubble_start_delay;
}

var addToHomeConfig = {						
	returningVisitor: returningVisitorFix,		// Show the balloon to returning visitors only (setting this to true is HIGHLY RECCOMENDED)
	animationIn: animationInFix,			// drop || bubble || fade
	animationOut: animationOutFix,			// drop || bubble || fade
	startDelay: startDelayFix,				// 2 seconds from page load before the balloon appears
	lifespan: lifespanFix,					// 15 seconds before it is automatically destroyed
	expire: expireFix,					// Minutes to wait before showing the popup again (0 = always displayed)
	message: messageFix,				// Customize your message or force a language ('' = automatic)
	arrow: arrowFix,
	hookOnLoad: true,															// Should we hook to onload event? (really advanced usage)
	iterations: 100,
	touchIcon: true															// Internal/debug use	// Show the message only once every 12 hours
};
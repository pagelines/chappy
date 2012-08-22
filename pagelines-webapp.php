<?php
/*
Plugin Name: Webapp for iOS & Android
Description: 
Version: 0.1
Author: Aleksander Hansson & Mike Zielonka
Author URI: 
Demo: 
PageLines: true
Tags: extension
*/

//function for loading the bookmark_bubble.js.
add_action( 'wp_enqueue_scripts', 'pl_webapp_js' );

function pl_webapp_js() {
	
	$plugin_path = sprintf( '%s/%s', WP_PLUGIN_URL, basename(dirname( __FILE__ )));
	
	$bubble_script = sprintf( '%s/%s', $plugin_path, 'bookmark_bubble.js' );
		
	wp_enqueue_script( 'pl-webapp-bubble', $bubble_script);
	
	$load_script = sprintf( '%s/%s/%s', $plugin_path, 'load', 'load.js' );
		
	wp_enqueue_script( 'pl-webapp-load', $load_script);

//used to pass settings into js. (taken from social excerpts)
	$params = array(
		'appid' => ( ploption( 'facebook_appid' ) ) ? sprintf( '&appid=%s', ploption( 'facebook_appid' ) ) : '',
		'lang'	=> ploption( 'facebook_language' )
	);
	
	wp_localize_script( 'pl-webapp-bubble', 'pl_webapp-bubble', $params );	
	wp_localize_script( 'pl-webapp-bubble', 'pl_webapp-load', $params );	

}

// registration of settings: (taken from social excerpts)
// add action to settings
add_action( 'admin_init', 'social_excerpts_settings' );
/**
 * Function for loading custom settings
 */
function social_excerpts_settings() {
	// settings icon path
	$icon_path = sprintf( '%s/%s', WP_PLUGIN_URL, basename(dirname( __FILE__ )));
	// options array for creating the settings tab
	$options = array(
		// icon for the settings tab
		'icon' => $icon_path . '/settings-icon.png',


		// edit text on pop up and replace the "this web app" with user text	
		'webapp_replace_this_web_app'		=> 	array(
			'default'		=> 	'this_web_app',
			'version'		=> 	'pro',
			'type'			=> 	'text',
			'inputlabel' 	=> 	__('Your Text to Replace "This Web App"', 'pagelines'),
			'title'     	=> 	__('Website Name', 'pagelines' ),
			'shortexp' 		=> 	__('Add your own custom text to replace the standard "This Web App" verbiage with your own.  Example replacement: PageLines.com', 'pagelines'),
		),
		
		
		// add the apple touch icon to float left of the text
		'webapp_apple_touch_float_left'		=> 	array(
			'default'		=> 	'True',
			'version'		=> 	'pro',
			'type'			=> 	'select',
			'selectvalues'	=> 	array(
				'like'		=> 	array(	'name'	=> __(	'True'	,	'pagelines'	)),
				'recommend'	=> 	array(	'name'	=> __(	'Flase'	,	'pagelines'	))
			),
			'inputlabel' 	=> 	__('Shuold your apple touch icon float to the left of the text?', 'pagelines'),
			'title'     	=> 	__('Add Apple Touch Icon', 'pagelines' ),
			'shortexp' 		=> 	__('Select true if you want your apple touch icon to float to the left of the text.', 'pagelines'),
		),
		
		// edit how many seconds the button stays on screen
		'webapp_stay_on_screen'	=> 	array(
			'default' 		=> 	'',
			'version' 		=> 	'pro',
			'type' 			=> 	'text',
			'title' 		=> 	__('Length The Button Stays On The Screen', 'pagelines'),
			'inputlabel' 	=> 	__('Enter in ms', 'pagelines'),
			'shortexp' 		=> 	__('Enter the ms you would like the button to stay on the screen before it automatically closes.  The default is 2000ms ', 'pagelines'),
		),
		
		
			// edit how often the button is shown
		'webapp_often_show'	=> 	array(
			'default' 		=> 	'',
			'version' 		=> 	'pro',
			'type' 			=> 	'text',
			'title' 		=> 	__('How Often The Button Appears', 'pagelines'),
			'inputlabel' 	=> 	__('Enter in mins', 'pagelines'),
			'shortexp' 		=> 	__('Enter the mins you would like the button to delay before the button shows again.  The default is 240mins.', 'pagelines'),
		),
		
		
		
		
	);

// add options page to pagelines settings
pl_add_options_page( array( 'name' => 'pl_webapp', 'array' => $options ) );
}

add_action( 'wp_footer', 'pl_webapp_show' );

function pl_webapp_show() { ?>
	<script type="text/javascript" language="JavaScript">
    	showBookmarkBubble();
    </script>
<?php }

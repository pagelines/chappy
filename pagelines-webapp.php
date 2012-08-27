<?php
/*
Plugin Name: Webapp for iOS & Android
Description: write me and get uri
Version: 0.1
Author: Aleksander Hansson & Mike Zielonka
Author URI: http://need-one.com
Plugin URI: http://need-one.com
Demo: http://need-one.com
PageLines: true
Tags: extension
*/

//function for loading the bookmark_bubble.js.
add_action( 'wp_enqueue_scripts', 'pl_webapp_bubble_js' );

function pl_webapp_bubble_js() {

	$plugin_path = sprintf( '%s/%s', WP_PLUGIN_URL, basename(dirname( __FILE__ )));

	$bubble_script = sprintf( '%s/%s', $plugin_path, 'bookmark_bubble.js' );

	wp_enqueue_script( 'pl-webapp-bubble', $bubble_script);

	$load_script = sprintf( '%s/%s/%s', $plugin_path, 'load', 'load.js' );

	wp_enqueue_script( 'pl-webapp-load', $load_script);

	//used to pass settings into js. (taken from social excerpts http://www.pagelines.com/store/plugins/social-excerpts/)
	$params = array(

		'param_pl_webapp_bubble_replace_this_web_app' 		=> ploption( 'pl_webapp_bubble_replace_this_web_app' ) ,
		'param_pl_webapp_bubble_apple_touch_float_left' 	=> ploption( 'pl_webapp_bubble_apple_touch_float_left' ) ,
		'param_pl_webapp_bubble_stay_on_screen' 			=> ploption( 'pl_webapp_bubble_stay_on_screen' ) ,
		'param_pl_webapp_bubble_often_show' 				=> ploption( 'pl_webapp_bubble_often_show' ) ,
		'param_pl_webapp_bubble_border_color' 				=> ploption( 'pl_webapp_bubble_border_color' ) ,
		'param_pl_webapp_bubble_bold_color' 				=> ploption( 'pl_webapp_bubble_bold_color' ) 
		
	);

	wp_localize_script( 'pl-webapp-bubble', 'pl_webapp_bubble', $params );
	wp_localize_script( 'pl-webapp-bubble', 'pl_webapp_load', $params );

}

// registration of settings: (taken from social excerpts http://www.pagelines.com/store/plugins/social-excerpts/)
// add action to settings
add_action( 'admin_init', 'pl_webapp_bubble_settings' );
/**
 * Function for loading custom settings
 */
function pl_webapp_bubble_settings() {
	// settings icon path
	$icon_path = sprintf( '%s/%s', WP_PLUGIN_URL, basename(dirname( __FILE__ )));
	// options array for creating the settings tab
	$options = array(
		// icon for the settings tab
		'icon' => $icon_path . '/settings-icon.png',


		// edit text on pop up and replace the "this web app" with user text
		'pl_webapp_bubble_replace_this_web_app'  =>  array(
			'default'  =>  'this_web_app',
			'version'  =>  'pro',
			'type'   =>  'text',
			'inputlabel'  =>  __('Your Text to Replace "This Web App"', 'pagelines'),
			'title'      =>  __('Website Name', 'pagelines' ),
			'shortexp'   =>  __('Add your own custom text to replace the standard "This Web App" verbiage with your own.  Example replacement: PageLines.com', 'pagelines'),
		),


		// add the apple touch icon to float left of the text
		'pl_webapp_bubble_apple_touch_float_left'  =>  array(
			'default'  =>  'True',
			'version'  =>  'pro',
			'type'   =>  'select',
			'selectvalues' =>  array(
				'true'  =>  array( 'name' => __( 'True' , 'pagelines' )),
				'false' =>  array( 'name' => __( 'False' , 'pagelines' ))
			),
			'inputlabel'  =>  __('Shuold your apple touch icon float to the left of the text?', 'pagelines'),
			'title'      =>  __('Add Apple Touch Icon', 'pagelines' ),
			'shortexp'   =>  __('Select true if you want your apple touch icon to float to the left of the text.', 'pagelines'),
		),

		// edit how many seconds the button stays on screen
		'pl_webapp_bubble_stay_on_screen' =>  array(
			'default'   =>  '',
			'version'   =>  'pro',
			'type'    =>  'text',
			'title'   =>  __('Length The Button Stays On The Screen', 'pagelines'),
			'inputlabel'  =>  __('Enter in ms', 'pagelines'),
			'shortexp'   =>  __('Enter the ms you would like the button to stay on the screen before it automatically closes.  The default is 2000ms ', 'pagelines'),
		),


		// edit how often the button is shown
		'pl_webapp_bubble_often_show' =>  array(
			'default'   =>  '',
			'version'   =>  'pro',
			'type'    =>  'text',
			'title'   =>  __('How Often The Button Appears', 'pagelines'),
			'inputlabel'  =>  __('Enter in mins', 'pagelines'),
			'shortexp'   =>  __('Enter the mins you would like the button to delay before the button shows again.  The default is 240mins.', 'pagelines'),
		),


		// pick the color of the border
		'pl_webapp_bubble_border_color' =>  array(
			'default'   =>  '',
			'version'   =>  'pro',
			'type'    =>  'colorpicker',
			'title'   =>  __('Border Color', 'pagelines'),
			'inputlabel'  =>  __('Select the border color', 'pagelines'),
			'shortexp'   =>  __('Select the color you would like your border around the button.', 'pagelines'),
		),


		// pick the color of the bold call to action
		'pl_webapp_bubble_bold_color' =>  array(
			'default'   =>  '',
			'version'   =>  'pro',
			'type'    =>  'colorpicker',
			'title'   =>  __('Call to Action Text Color', 'pagelines'),
			'inputlabel'  =>  __('Select the Call to Action Text color', 'pagelines'),
			'shortexp'   =>  __('Select the color you would like your call to action text to be.', 'pagelines'),
		),




	);

	// add options page to pagelines settings
	pl_add_options_page( array( 'name' => 'pl_webapp_bubble', 'array' => $options ) );
}

add_action( 'wp_footer', 'pl_webapp_bubble_show' );

function pl_webapp_bubble_show() { ?>
	<script type="text/javascript" language="JavaScript">
    	showBookmarkBubble();
    </script>
<?php }

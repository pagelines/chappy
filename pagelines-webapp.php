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

//function for loading style.less file
function custom_less() {
	$file = sprintf( '%sstyle.less', plugin_dir_path( __FILE__ ) );
	pagelines_insert_core_less( $file );
}
add_action( 'template_redirect', 'custom_less' );

//function for loading the bookmark_bubble.js.
add_action( 'wp_enqueue_scripts', 'pl_webapp_bubble_js' );

function pl_webapp_bubble_js() {

	$plugin_path = sprintf( '%s/%s', WP_PLUGIN_URL, basename(dirname( __FILE__ )));
	
	$load_script = sprintf( '%s/%s', $plugin_path, 'pagelines-webapp-settings-load.js' );

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

	wp_localize_script( 'pl-webapp-bubble', 'pl_webapp_load', $params );

	$bubble_script = sprintf( '%s/%s', $plugin_path, 'add2home.js' );

	wp_enqueue_script( 'pl-webapp-bubble', $bubble_script);

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
		'icon' => $icon_path . '/icon.png',

		///////////Below are meta settings/////////
		
		

		///////////Below are js settings/////////

		// edit text on pop up and replace the "this web app" with user text
		'pl_webapp_bubble_replace_message'  =>  array(
			'default'  =>  'this_web_app',
			'version'  =>  'pro',
			'type'   =>  'text',
			'inputlabel'  =>  __('Your Text to Replace the Default Message. (this will override the language option)', 'pagelines'),
			'title'      =>  __('Custom message', 'pagelines' ),
			'shortexp'   =>  __('Add your own custom text to replace the standard message verbiage with your own.  Example replacement: This is a custom message. Your device is an <strong>%device</strong>. The action icon is %icon.', 'pagelines'),
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
			'inputlabel'  =>  __('Should your apple touch icon float to the left of the text?', 'pagelines'),
			'title'      =>  __('Add Apple Touch Icon', 'pagelines' ),
			'shortexp'   =>  __('Select true if you want your apple touch icon to float to the left of the text.', 'pagelines'),
		),
		
		// add the apple touch icon to float left of the text
		'pl_webapp_bubble_animation_in'  =>  array(
			'default'  =>  'True',
			'version'  =>  'pro',
			'type'   =>  'select',
			'selectvalues' =>  array(
				'drop'  =>  array( 'name' => __( 'Drop' , 'pagelines' )),
				'bubble'  =>  array( 'name' => __( 'Bubble' , 'pagelines' )),
				'fade' =>  array( 'name' => __( 'Fade' , 'pagelines' ))
			),
			'inputlabel'  =>  __('Which animation would you like when bubble is appearing?', 'pagelines'),
			'title'      =>  __('Appearing animation', 'pagelines' ),
			'shortexp'   =>  __('Select one of the three options', 'pagelines'),
		),
		
		// add the apple touch icon to float left of the text
		'pl_webapp_bubble_animation_out'  =>  array(
			'default'  =>  'True',
			'version'  =>  'pro',
			'type'   =>  'select',
			'selectvalues' =>  array(
				'drop'  =>  array( 'name' => __( 'Drop' , 'pagelines' )),
				'bubble'  =>  array( 'name' => __( 'Bubble' , 'pagelines' )),
				'fade' =>  array( 'name' => __( 'Fade' , 'pagelines' ))
			),
			'inputlabel'  =>  __('Which animation would you like when bubble is dissapearing?', 'pagelines'),
			'title'      =>  __('Dissappearing animation', 'pagelines' ),
			'shortexp'   =>  __('Select one of the three options', 'pagelines'),
		),

		// edit how many seconds the button stays on screen
		'pl_webapp_bubble_start_delay' =>  array(
			'default'   =>  '',
			'version'   =>  'pro',
			'type'    =>  'text',
			'title'   =>  __('Delay before bubble is appearing', 'pagelines'),
			'inputlabel'  =>  __('Enter in ms', 'pagelines'),
			'shortexp'   =>  __('Enter the ms you would like the button to delay before automaticly closing.  The default is 2000ms ', 'pagelines'),
		),
		
		// edit how many seconds the button stays on screen
		'pl_webapp_bubble_lifespan' =>  array(
			'default'   =>  '',
			'version'   =>  'pro',
			'type'    =>  'text',
			'title'   =>  __('Length The Button Stays On The Screen', 'pagelines'),
			'inputlabel'  =>  __('Enter in ms', 'pagelines'),
			'shortexp'   =>  __('Enter the ms you would like the button to stay on the screen before it automatically closes.  The default is 150000ms ', 'pagelines'),
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
		
		// add the apple touch icon to float left of the text
		'pl_webapp_bubble_arrow'  =>  array(
			'default'  =>  'True',
			'version'  =>  'pro',
			'type'   =>  'select',
			'selectvalues' =>  array(
				'true'  =>  array( 'name' => __( 'True' , 'pagelines' )),
				'false' =>  array( 'name' => __( 'False' , 'pagelines' ))
			),
			'inputlabel'  =>  __('Choose True or False', 'pagelines'),
			'title'      =>  __('Display bubble arrow', 'pagelines' ),
			'shortexp'   =>  __('Select true if you want your apple touch icon to float to the left of the text.', 'pagelines'),
		),
		
		// add the apple touch icon to float left of the text
		'pl_webapp_bubble_arrow'  =>  array(
			'default'  =>  'True',
			'version'  =>  'pro',
			'type'   =>  'select',
			'selectvalues' =>  array(
				'true'  =>  array( 'name' => __( 'True' , 'pagelines' )),
				'false' =>  array( 'name' => __( 'False' , 'pagelines' ))
			),
			'inputlabel'  =>  __('Choose True or False', 'pagelines'),
			'title'      =>  __('Autostart (For Advanced Users)', 'pagelines' ),
			'shortexp'   =>  __('You can call it anywhere with the javascript function: addToHome.show()', 'pagelines'),
		),

		///////Below is LESS settings////////////

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
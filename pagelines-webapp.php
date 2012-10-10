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
add_action( 'wp_enqueue_scripts', 'pl_webapp_load_js' );

function pl_webapp_load_js() {

	if (ploption( 'pl_webapp_bubble_is_frontpage' )) {
		if (is_front_page()){

			wp_enqueue_script( 'pl-webapp-load', plugins_url('/pagelines-webapp-settings-load.js', __FILE__));

			//used to pass settings into js. (taken from social excerpts http://www.pagelines.com/store/plugins/social-excerpts/)

			wp_localize_script( 'pl-webapp-load', 'pl_webapp_load', array(

					'param_pl_webapp_bubble_replace_message'   => ploption( 'pl_webapp_bubble_replace_message' ) ,
					'param_pl_webapp_bubble_animation_in'    => ploption( 'pl_webapp_bubble_animation_in' ) ,
					'param_pl_webapp_bubble_animation_out'    => ploption( 'pl_webapp_bubble_animation_out' ) ,
					'param_pl_webapp_bubble_start_delay'    => ploption( 'pl_webapp_bubble_start_delay' ) ,
					'param_pl_webapp_bubble_lifespan'     => ploption( 'pl_webapp_bubble_lifespan' ) ,
					'param_pl_webapp_bubble_often_show'    => ploption( 'pl_webapp_bubble_often_show' ) ,
					'param_pl_webapp_bubble_arrow'      => ploption( 'pl_webapp_bubble_arrow' ) ,
					'param_pl_webapp_bubble_returning_visitor'  => ploption( 'pl_webapp_bubble_returning_visitor')

				));

			wp_enqueue_script( 'pl-webapp-bubble', plugins_url('/add2home.js', __FILE__));

		}
	} else {

		wp_enqueue_script( 'pl-webapp-load', plugins_url('/pagelines-webapp-settings-load.js', __FILE__));

		//used to pass settings into js. (taken from social excerpts http://www.pagelines.com/store/plugins/social-excerpts/)

		wp_localize_script( 'pl-webapp-load', 'pl_webapp_load', array(

				'param_pl_webapp_bubble_replace_message'   => ploption( 'pl_webapp_bubble_replace_message' ) ,
				'param_pl_webapp_bubble_animation_in'    => ploption( 'pl_webapp_bubble_animation_in' ) ,
				'param_pl_webapp_bubble_animation_out'    => ploption( 'pl_webapp_bubble_animation_out' ) ,
				'param_pl_webapp_bubble_start_delay'    => ploption( 'pl_webapp_bubble_start_delay' ) ,
				'param_pl_webapp_bubble_lifespan'     => ploption( 'pl_webapp_bubble_lifespan' ) ,
				'param_pl_webapp_bubble_often_show'    => ploption( 'pl_webapp_bubble_often_show' ) ,
				'param_pl_webapp_bubble_arrow'      => ploption( 'pl_webapp_bubble_arrow' ) ,
				'param_pl_webapp_bubble_returning_visitor'  => ploption( 'pl_webapp_bubble_returning_visitor')

			));

		wp_enqueue_script( 'pl-webapp-bubble', plugins_url('/add2home.js', __FILE__));

	}

}

add_action( 'pagelines_head', 'pl_webapp_meta');

function pl_webapp_meta() {

	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">';

	// Of course it is advisable to have touch icons ready for each device
	//<!-- Standard iPhone -->
	if (ploption('touch_icon_iphone')) printf('<link rel="apple-touch-icon" sizes="57x57" href="%s" />', ploption( 'touch_icon_iphone' )) ;
	//<!-- Retina iPhone -->
	if (ploption('touch_icon_iphone')) printf('<link rel="apple-touch-icon" sizes="114x114" href="%s" />', ploption( 'touch_icon_iphone' )) ;
	//<!-- Standard iPad -->
	if (ploption('touch_icon_ipad')) printf('<link rel="apple-touch-icon" sizes="72x72" href="%s" />', ploption( 'touch_icon_ipad' )) ;
	//<!-- Retina iPad -->
	if (ploption('touch_icon_ipad')) printf('<link rel="apple-touch-icon" sizes="144x144" href="%s" />', ploption( 'touch_icon_ipad' )) ;

	//Splash screen
	//<!-- iPhone (Retina) SPLASHSCREEN-->
	if (ploption('splash_screen_iphone')) printf('<link href="%s" media="(device-width: 320px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">', ploption( 'splash_screen_iphone' )) ;
	//<!-- iPad (Retina, portrait) SPLASHSCREEN-->
	if (ploption('splash_screen_ipad')) printf('<link href="%s" media="(device-width: 1536px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">', ploption( 'splash_screen_ipad' )) ;

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
			'default'  =>  '',
			'version'  =>  'pro',
			'type'   =>  'text',
			'inputlabel'  =>  __('Your Text to Replace the Default Message. (this will override the language option)', 'pagelines'),
			'title'      =>  __('Custom message', 'pagelines' ),
			'shortexp'   =>  __('Add your own custom text to replace the standard message verbiage with your own.  Example replacement: This is a custom message. Your device is an <strong>%device</strong>. The action icon is %icon.', 'pagelines'),
		),

		// add the apple touch icon to float left of the text
		'pl_webapp_bubble_is_frontpage'  =>  array(
			'default'  =>  '',
			'version'  =>  'pro',
			'type'   =>  'check',
			'inputlabel'  =>  __('Only show on frontpage?', 'pagelines'),
			'title'      =>  __('Show on frontpage', 'pagelines' ),
			'exp' => __('Checking this option will do so the balloon is only showed on frontpage!','pagelines'),
		),

		'pl_webapp_bubble_returning_visitor'  =>  array(
			'default'  =>  'true',
			'version'  =>  'pro',
			'type'   =>  'select',
			'selectvalues' =>  array(
				'true'  =>  array( 'name' => __( 'True' , 'pagelines' )),
				'false' =>  array( 'name' => __( 'False' , 'pagelines' ))
			),
			'inputlabel'  =>  __('Only show to returning visitors?', 'pagelines'),
			'title'      =>  __('Returnign visitors', 'pagelines' ),
			'shortexp'   =>  __('Show the balloon to returning visitors only (setting this to true is HIGHLY RECCOMENDED)', 'pagelines'),
		),
		'touch_icons'   => array(
			'default'    => '',
			'type'     => 'multi_option',
			'title'     =>  __('Images', 'pagelines'),
			'selectvalues'   => array(
				'touch_icon_iphone' => array(
					'default'          => '',
					'type'              => 'image_upload',
					'imagepreview'      => '270',
					'inputlabel'      => __( 'Upload your Apple touch icon for iPhone. The touch icon is the icon that your iPhone saves on the homescreen. The touch icon needs to be 114x114 pixel and should be named "touch-icon-iphone-114.png".', 'pagelines' ),
				),
				'touch_icon_ipad' => array(
					'default'          => '',
					'type'              => 'image_upload',
					'imagepreview'      => '270',
					'inputlabel'      => __( 'Upload your Apple touch icon for iPad. The touch icon is the icon that your iPad saves on the homescreen. The touch icon needs to be 144x144 pixel and should be named "touch-icon-ipad-144.png".', 'pagelines' ),
				),
				'splash_screen_iphone' => array(
					'default'          => '',
					'type'              => 'image_upload',
					'imagepreview'      => '270',
					'inputlabel'      => __( 'Upload your Apple splash screen image iPhone. The splash screen for iPhone shows up while your site is loading. The splash screen image needs to be 640x960 pixel and should be named "apple-touch-startup-image-640x920.png"', 'pagelines' ),
				),
				'splash_screen_ipad' => array(
					'default'          => '',
					'type'              => 'image_upload',
					'imagepreview'      => '270',
					'inputlabel'      => __( 'Upload your Apple splash screen image iPad. The splash screen for iPad shows up while your site is loading. The splash screen image needs to be 640x960 pixel and should be named "apple-touch-startup-image-1536x2008.png".', 'pagelines' ),
				)
			)
		),
		// add the apple touch icon to float left of the text
		'pl_webapp_bubble_animation_in'  =>  array(
			'default'  =>  'drop',
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
			'default'  =>  'fade',
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
			'default'  =>  'true',
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

		///////Below is LESS settings////////////


		// select the color of the bold call to action
		'pl_webapp_bubble_bold_color' =>  array(
			'default'   =>  '',
			'version'   =>  'pro',
			'type'    =>  'colorpicker',
			'title'   =>  __('Call to Action Text Color', 'pagelines'),
			'inputlabel'  =>  __('Select the Call to Action Text color', 'pagelines'),
			'shortexp'   =>  __('Select the color you would like your call to action text to be.', 'pagelines'),
		),
<<<<<<< HEAD
		
		
		//select the color of the border
		'pl_webapp_border'          => array(                   
=======

		'pl_webapp_border' => array(
>>>>>>> cleanup
			'default'   =>  '',
			'version'   =>  'pro',
			'type'    =>  'colorpicker',
			'title'   =>  __('Border Color', 'pagelines'),
			'inputlabel'  =>  __('Select the border color', 'pagelines'),
			'shortexp'   =>  __('Select the color you would like your border around the button.', 'pagelines'),

<<<<<<< HEAD
                         ),               
                         
        //select the background color
		'pl_webapp_bg'          => array(                   
			'default'   =>  '',
			'version'   =>  'pro',
			'type'    =>  'colorpicker',
			'title'   =>  __('Background Color', 'pagelines'),
			'inputlabel'  =>  __('Select the background color', 'pagelines'),
			'shortexp'   =>  __('Select the color you would like your background of the bubble.', 'pagelines'),

                         ),


=======
		),
>>>>>>> cleanup

	);

	// add options page to pagelines settings
	pl_add_options_page( array( 'name' => 'pl_webapp_bubble', 'array' => $options ) );
}

<<<<<<< HEAD


// add less for PageLines plugin
=======
// add less support for PageLines plugin
>>>>>>> cleanup

add_filter('pless_vars', 'pl_webapp_mixin');

function pl_webapp_mixin( $constants ){

<<<<<<< HEAD
	 //define the border color varible
     $pl_webapp_border = (ploption('pl_webapp_border')) ? ploption('pl_webapp_border') : '@dark-base';
     
     //define the bubble text color
     $pl_webapp_bubble_bold_color = (ploption('pl_webapp_bubble_bold_color')) ? ploption('pl_webapp_bubble_bold_color') : '@dark-base';
     
     //define the background color
     $pl_webapp_bg = (ploption('pl_webapp_bg')) ? ploption('pl_webapp_bg') : '@dark-base';
=======

	$pl_webapp_border = (ploption('pl_webapp_border')) ? ploption('pl_webapp_border') : '@dark-base';
	$pl_webapp_bubble_bold_color = (ploption('pl_webapp_bubble_bold_color')) ? ploption('pl_webapp_bubble_bold_color') : '@dark-base';
>>>>>>> cleanup

	$newvars = array(

<<<<<<< HEAD
          'pl_webapp_border' 			=> $pl_webapp_border,
          'pl_webapp_bubble_bold_color' => $pl_webapp_bubble_bold_color,
          'pl_webapp_bg'				=> $pl_webapp_bg,
     );
=======
		'pl_webapp_border' => $pl_webapp_border,
		'pl_webapp_bubble_bold_color' => $pl_webapp_bubble_bold_color,
	);
>>>>>>> cleanup

	$lessvars = array_merge($newvars, $constants);

	return $lessvars;

}

<?php
/*
Plugin Name: Chappy, The Cheap Web App
Description: Turn Your PageLines Site Into an iPhone Web App! Over 12 Different Options, Customize the the Image Added to the iPhone Homescrean, Customize Everything About the Pop Up & Advance Behavior Control.
Version: 69.7
Author: Aleksander Hansson & Mike Zielonka
Author URI: http://chappy.ahansson.com/developers/
Plugin URI: http://chappy.ahansson.com/
Demo: http://chappy.ahansson.com/
Tags: extension
V3: true
*/

class Chappy {

	function __construct() {

		add_filter( 'pless_vars', array( &$this, 'mixins' ) );

		add_action( 'template_redirect', array( &$this, 'custom_less' ) );

		add_action( 'wp_enqueue_scripts', array( &$this, 'js' ) );

		add_action( 'pagelines_head', array( &$this, 'meta' ) );

		add_action( 'pagelines_head', array( &$this, 'js_settings' ) );

		add_action( 'init', array( &$this, 'init' ) );

		add_action( 'init', array( &$this, 'ah_updater_init' ) );

	}

	function ah_updater_init() {

		require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/plugin-updater.php' );

		$config = array(
			'base'      => plugin_basename( __FILE__ ), 
			'repo_uri'  => 'http://shop.ahansson.com',  
			'repo_slug' => 'chappy',
		);

		new AH_Chappy_Plugin_Updater( $config );
	}

	function init() {

		add_filter( 'pl_settings_array', array(&$this, 'options') );

	}

	function custom_less() {
		$file = sprintf( '%sstyle.less', plugin_dir_path( __FILE__ ) );
		pagelines_insert_core_less( $file );
	}

	function js() {

		if (pl_setting( 'pl_webapp_bubble_is_frontpage' )) {

			if (is_front_page()) {

				$this->js_load();

			}

		} else {

			$this->js_load();

		}

	}

	function js_load() {

			wp_enqueue_script( 'jquery' );

			wp_enqueue_script( 'chappy-keep-in-app', sprintf( '%s/%s/%s', WP_PLUGIN_URL, basename(dirname( __FILE__ )), 'chappy-keep-in-app.js' ) );

			wp_enqueue_script( 'chappy-add2home', sprintf( '%s/%s/%s', WP_PLUGIN_URL, basename(dirname( __FILE__ )), 'add2home.js' ) );

	}

	function meta() {

		echo '<meta name="apple-mobile-web-app-capable" content="yes">';

		echo '<meta name="apple-mobile-web-app-status-bar-style" content="black">';

	}

	function js_settings () {

		$icon = ( pl_setting( 'pl_webapp_bubble_icon' ) ) ? pl_setting( 'pl_webapp_bubble_icon' ) : 'false';
		$arrow = ( pl_setting( 'pl_webapp_bubble_arrow' ) ) ? pl_setting( 'pl_webapp_bubble_arrow' ) : 'false';
		$returning_visitor = ( pl_setting( 'pl_webapp_bubble_returning_visitor' ) ) ? pl_setting( 'pl_webapp_bubble_returning_visitor' ) : 'false';
		$animation_in = ( pl_setting( 'pl_webapp_bubble_animation_in' ) ) ? pl_setting( 'pl_webapp_bubble_animation_in' ) : "'bubble'";
		$animation_out = ( pl_setting( 'pl_webapp_bubble_animation_out' ) ) ? pl_setting( 'pl_webapp_bubble_animation_out' ) : "'drop'";
		$start_delay = ( pl_setting( 'pl_webapp_bubble_start_delay' ) ) ? pl_setting( 'pl_webapp_bubble_start_delay' ) : '2000';
		$lifespan = ( pl_setting( 'pl_webapp_bubble_lifespan' ) ) ? pl_setting( 'pl_webapp_bubble_lifespan' ) : '15000';
		$helpire = ( pl_setting( 'pl_webapp_bubble_often_show' ) ) ? pl_setting( 'pl_webapp_bubble_often_show' ) : '0';
		$message = ( pl_setting( 'pl_webapp_bubble_replace_message' ) ) ? pl_setting( 'pl_webapp_bubble_replace_message' ) : '';

		?>

			<script type="text/javascript">

				var addToHomeConfig = {
					returningVisitor: <?php echo $returning_visitor; ?>,	// Show the balloon to returning visitors only (setting this to true is HIGHLY RECCOMENDED)
					animationIn: "<?php echo $animation_in; ?>",				// drop || bubble || fade
					animationOut: "<?php echo $animation_out; ?>",			// drop || bubble || fade
					startDelay: <?php echo $start_delay; ?>,				// 2 seconds from page load before the balloon appears
					lifespan: <?php echo $lifespan; ?>,						// 15 seconds before it is automatically destroyed
					expire: <?php echo $helpire; ?>,							// Minutes to wait before showing the popup again (0 = always displayed)
					message: '<?php echo $message; ?>',						// Customize your message or force a language ('' = automatic)
					arrow: <?php echo $arrow; ?>,
					touchIcon: <?php echo $icon; ?>,
				};

			</script>

		<?php
	}

	function options( $settings ){

		$how_to_use = __( '
		<strong>Watch the video before asking for additional help:</strong>
		</br></br>
			[pl_video type="youtube" id="Ly0q1bsGfx8"]
		', 'chappy' );

        $settings['Chappy'] = array(
            'name'  => 'Chappy',
            'icon'  => 'icon-mobile-phone',
            'pos'   => 5,
            'opts'  => array(

            	array(
					'key' => 'pl_webapp_help',
					'type'     => 'template',
					'template'      => do_shortcode ($how_to_use),
					'title' =>__( 'How to use:', 'chappy' ) ,
				),

				array(
					'key' 			=> 	'pl_webapp_settings',
					'type'	  		=> 'multi',
					'title'	   		=>  __('Text and When To Show', 'chappy'),
					'opts'			=> array(

						array(
							'key' 			=> 	'pl_webapp_bubble_replace_message',
							'type'			=>	'text',
							'label'			=>	__('Your Text to Replace the Default Message. (this will override the language option)', 'chappy'),
							'title'			=>	__('Custom message', 'chappy' ),
							'help'			=>	__('Example replacement: This is a custom message. Your device is an <strong>%device</strong>. The action icon is %icon.','chappy'),
						),

						array(
							'key' 			=> 	'pl_webapp_bubble_is_frontpage',
							'type'			=>	'check',
							'label'			=>	__('Only show on frontpage?', 'chappy'),
							'title'			=>	__('Show on frontpage', 'chappy' ),
							'help'			=>	__('Checking this option will do so the balloon is only showed on frontpage!','chappy'),
						),

						array(
							'key' 			=> 	'pl_webapp_bubble_returning_visitor',
							'default'  		=>	false,
							'type'			=>	'check',
							'label'			=>	__('Only show to returning visitors?', 'chappy'),
							'title'			=>	__('Returning visitors', 'chappy' ),
							'help'			=>	__('Will not show to visitors who visits first time, but will do to visitors who visits second time. (Checking this is HIGHLY RECCOMENDED)','chappy'),
						)
					),
				),

				array(
					'key' 			=> 	'pl_webapp_bubble_settings',
					'type'	  		=> 'multi',
					'title'	   		=>  __('Animations', 'chappy'),
					'opts'			=> array(
						array(
							'key'			=>	'pl_webapp_bubble_animation_in',
							'default'		=>	'drop',
							'type'	  		=>  'select',
							'opts' 			=>	array(
								'drop'  		=>  array( 'name' => __( 'Drop' , 'chappy' )),
								'bubble'		=>	array( 'name' => __( 'Bubble' , 'chappy' )),
								'fade' 			=>	array( 'name' => __( 'Fade' , 'chappy' ))
							),
							'label'  		=>  __('Which animation would you like when bubble is appearing?', 'chappy'),
							'title'		 	=>	__('Appearing animation', 'chappy' ),
						),

						array(
							'key' 			=> 	'pl_webapp_bubble_animation_out',
							'default'		=>	'fade',
							'type'	  		=>  'select',
							'opts' 			=>	array(
								'drop'  		=>  array( 'name' => __( 'Drop' , 'chappy' )),
								'bubble'		=>	array( 'name' => __( 'Bubble' , 'chappy' )),
								'fade' 			=>	array( 'name' => __( 'Fade' , 'chappy' ))
							),
							'label'  		=>  __('Which animation would you like when bubble is disappearing?', 'chappy'),
							'title'	  		=>  __('Disappearing animation', 'chappy' ),
						),

						array(
							'key' 			=> 	'pl_webapp_bubble_start_delay',
							'type'	  		=>  'text',
							'label'  		=>  __('Delay before bubble is appearing', 'chappy'),
							'help'	 		=>	__('Enter the ms you would like the button to delay before automaticly closing.  The default is 2000ms ', 'chappy'),
						),

						array(
							'key' 			=> 	'pl_webapp_bubble_lifespan',
							'type'	  		=>  'text',
							'label'  		=>  __('Length The Button Stays On The Screen', 'chappy'),
							'help'	 		=>	__('Enter the ms you would like the button to stay on the screen before it automatically closes.  The default is 15000ms ', 'chappy'),
						),

						array(
							'key' 			=>	'pl_webapp_bubble_often_show',
							'type'	  		=>	'text',
							'label'  		=> 	__('How Often The Button Appears', 'chappy'),
							'help'	 		=>	__('Enter the minutes you would like the button to delay before the button shows again. The default is 0mins which means everytime.', 'chappy'),
						),

						array(
							'key' 			=> 	'pl_webapp_bubble_arrow',
							'default'  		=>  true,
							'type'			=>	'check',
							'label'  		=>  __('Arrow on bubble?', 'chappy'),
							'help'	 		=>	__('Unceck this if you want to remove the arrow from the bubble!', 'chappy'),
						),
						array(
							'key' 			=> 	'pl_webapp_bubble_icon',
							'default'  		=>  true,
							'type'			=>	'check',
							'label'  		=>  __('Display touch icon?', 'chappy'),
							'help'	 		=>	__('Unceck this if you want to remove the touch icon! Touch icon can be changed from Global Options -> Site Images', 'chappy'),
						),
					)
				),

				array(
					'key' 				=> 	'pl_webapp_styling',
					'type'	  			=> 	'multi',
					'title'	   			=>  __('Style', 'chappy'),
					'opts'				=> 	array(

						array(
							'key' 			=> 	'pl_webapp_bubble_bold_color',
						    'default'   	=>  '#505050',
						    'type'	 		=>	'color',
						    'label'			=>	__('Select the text color', 'chappy'),
						    'help'			=>	__('Select the color you would like your call to action text to be.', 'chappy'),
						),

						array(
							'key' 			=> 	'pl_webapp_border',
						    'default'   	=>  '#333',
						    'type'	 		=>	'color',
						    'label'			=>	__('Select the border color', 'chappy'),
						    'help'			=>	__('Select the color you would like your border around the button.', 'chappy'),
						),

						array(
							'key' 			=>	'pl_webapp_bg',
						    'default'   	=> 	'#a3a3a3',
						    'type'	 		=>	'color',
						    'label'			=>	__('Select the background color', 'chappy'),
						    'help'			=>	__('Select the color you would like your background of the bubble.', 'chappy'),
						)
					)
				),
			),
        );

        return $settings;
    }

	function mixins( $less ){

		$less['pl_webapp_bubble_bold_color'] = ( pl_setting('pl_webapp_bubble_bold_color') ) ? pl_hashify( pl_setting( 'pl_webapp_bubble_bold_color' ) ) : '#333';
		$less['pl_webapp_bg'] = ( pl_setting('pl_webapp_bg') ) ? pl_hashify( pl_setting( 'pl_webapp_bg' ) ) : '#a3a3a3';
		$less['pl_webapp_border'] = ( pl_setting('pl_webapp_border') ) ? pl_hashify( pl_setting( 'pl_webapp_border' ) ) : '#505050';

		return $less;

	}
}
new Chappy;
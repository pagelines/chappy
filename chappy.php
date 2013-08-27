<?php
/*
Plugin Name: Chappy, The Cheap Web App
Description: Turn Your PageLines Site Into an iPhone Web App! Over 12 Different Options, Customize the the Image Added to the iPhone Homescrean, Customize Everything About the Pop Up & Advance Behavior Control.
Version: 69.2
Author: Aleksander Hansson & Mike Zielonka
Author URI: http://chappy.ahansson.com/developers/
Plugin URI: http://chappy.ahansson.com/
Demo: http://chappy.ahansson.com/
PageLines: true
Tags: extension
V3: true
*/

define( 'AH_CHAPPY_STORE_URL', 'http://shop.ahansson.com' );
define( 'AH_CHAPPY_NAME', 'Chappy' );

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}

// retrieve our license key from the DB
$license_key = trim( get_option( 'ah_chappy_license_key' ) );

// setup the updater
$edd_updater = new EDD_SL_Plugin_Updater( AH_CHAPPY_STORE_URL, __FILE__, array(
		'version' 	=> '69.1', 				// current version number
		'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
		'item_name' => AH_CHAPPY_NAME, 		// name of this plugin
		'author' 	=> 'Aleksander Hansson' // author of this plugin
	)
);


function ah_chappy_license_menu() {
	add_plugins_page( 'Chappy License', 'Chappy License', 'manage_options', 'chappy-license', 'ah_chappy_license_page' );
}
add_action('admin_menu', 'ah_chappy_license_menu');

function ah_chappy_license_page() {
	$license 	= get_option( 'ah_chappy_license_key' );
	$status 	= get_option( 'ah_chappy_license_status' );
	?>
	<div class="wrap">
		<h2><?php _e('Chappy License Options'); ?></h2>
		<form method="post" action="options.php">

			<?php settings_fields('ah_chappy_license'); ?>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('License Key'); ?>
						</th>
						<td>
							<input id="ah_chappy_license_key" name="ah_chappy_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
							<label class="description" for="ah_chappy_license_key"><?php _e('Enter your license key'); ?></label>
						</td>
					</tr>
					<?php if( false !== $license ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('Activate License'); ?>
							</th>
							<td>
								<?php if( $status !== false && $status == 'valid' ) { ?>
									<span style="color:green;"><?php _e('active'); ?></span>
									<?php wp_nonce_field( 'ah_chappy_nonce', 'ah_chappy_nonce' ); ?>
									<input type="submit" class="button-secondary" name="ah_chappy_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
								<?php } else {
									wp_nonce_field( 'ah_chappy_nonce', 'ah_chappy_nonce' ); ?>
									<input type="submit" class="button-secondary" name="ah_chappy_license_activate" value="<?php _e('Activate License'); ?>"/>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php submit_button(); ?>

		</form>
	<?php
}

function ah_chappy_register_option() {
	// creates our settings in the options table
	register_setting('ah_chappy_license', 'ah_chappy_license_key', 'ah_chappy_sanitize_license' );
}
add_action('admin_init', 'ah_chappy_register_option');

function ah_chappy_sanitize_license( $new ) {
	$old = get_option( 'ah_chappy_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'ah_chappy_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}

function ah_chappy_activate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['ah_chappy_license_activate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'ah_chappy_nonce', 'ah_chappy_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'ah_chappy_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'activate_license',
			'license' 	=> $license,
			'item_name' => urlencode( AH_CHAPPY_NAME ) // the name of our product in EDD
		);

		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, AH_CHAPPY_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "active" or "inactive"

		update_option( 'ah_chappy_license_status', $license_data->license );

	}
}
add_action('admin_init', 'ah_chappy_activate_license');


/***********************************************
* Illustrates how to deactivate a license key.
* This will descrease the site count
***********************************************/

function ah_chappy_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['ah_chappy_license_deactivate'] ) ) {

		// run a quick security  check
	 	if( ! check_admin_referer( 'ah_chappy_nonce', 'ah_chappy_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'ah_chappy_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( AH_CHAPPY_NAME ) // the name of our product in EDD
		);

		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, AH_CHAPPY_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' )
			delete_option( 'ah_chappy_license_status' );

	}
}
add_action('admin_init', 'ah_chappy_deactivate_license');


function ah_chappy_check_license() {

	global $wp_version;

	$license = trim( get_option( 'ah_chappy_license_key' ) );

	$api_params = array(
		'edd_action' => 'check_license',
		'license' => $license,
		'item_name' => urlencode( AH_CHAPPY_NAME )
	);

	// Call the custom API.
	$response = wp_remote_get( add_query_arg( $api_params, AH_CHAPPY_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );


	if ( is_wp_error( $response ) )
		return false;

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if( $license_data->license == 'valid' ) {
		echo 'valid'; exit;
		// this license is still valid
	} else {
		echo 'invalid'; exit;
		// this license is no longer valid
	}
}

class Chappy {

	function __construct() {

		add_action( 'template_redirect', array( &$this, 'custom_less' ) );

		add_action( 'wp_enqueue_scripts', array( &$this, 'js' ) );

		add_action( 'pagelines_head', array( &$this, 'meta' ) );

		add_action( 'wp_head', array( &$this, 'js_settings' ) );

		add_filter( 'pless_vars', array( &$this, 'mixins' ) );

		add_action( 'init', array( &$this, 'settings' ) );

	}

	function custom_less() {
		$file = sprintf( '%sstyle.less', plugin_dir_path( __FILE__ ) );
		pagelines_insert_core_less( $file );
	}

	function js() {

		if (ploption( 'pl_webapp_bubble_is_frontpage' )) {

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

		// Of course it is advisable to have touch icons ready for each device
		//<!-- Standard iPhone -->
		if (ploption('touch_icon_iphone')) {
			printf('<link rel="apple-touch-icon" sizes="57x57" href="%s" />', ploption( 'touch_icon_iphone' )) ;
		}

		//<!-- Retina iPhone -->
		if (ploption('touch_icon_iphone')) {
			printf('<link rel="apple-touch-icon" sizes="114x114" href="%s" />', ploption( 'touch_icon_iphone' )) ;
		}

		//<!-- Standard iPad -->
		if (ploption('touch_icon_ipad')) {
			printf('<link rel="apple-touch-icon" sizes="72x72" href="%s" />', ploption( 'touch_icon_ipad' )) ;
		}

		//<!-- Retina iPad -->
		if (ploption('touch_icon_ipad')) {
			printf('<link rel="apple-touch-icon" sizes="144x144" href="%s" />', ploption( 'touch_icon_ipad' )) ;
		}

		echo '<meta name="apple-mobile-web-app-capable" content="yes">';

		echo '<meta name="apple-mobile-web-app-status-bar-style" content="black">';

	}

	function js_settings () {

		if ( ploption( 'pl_webapp_bubble_returning_visitor' ) == 'y') {
			$returning_visitor = 'true';
		} else {
			$returning_visitor = 'false';
		}

		if ( ploption( 'pl_webapp_bubble_arrow' ) == 'y') {
			$arrow = 'true';
		} else {
			$arrow = 'false';
		}

		$animation_in = ( ploption( 'pl_webapp_bubble_animation_in' ) ) ? ploption( 'pl_webapp_bubble_animation_in' ) : "'bubble'";
		$animation_out = ( ploption( 'pl_webapp_bubble_animation_out' ) ) ? ploption( 'pl_webapp_bubble_animation_out' ) : "'drop'";
		$start_delay = ( ploption( 'pl_webapp_bubble_start_delay' ) ) ? ploption( 'pl_webapp_bubble_start_delay' ) : '2000';
		$lifespan = ( ploption( 'pl_webapp_bubble_lifespan' ) ) ? ploption( 'pl_webapp_bubble_lifespan' ) : '15000';
		$expire = ( ploption( 'pl_webapp_bubble_often_show' ) ) ? ploption( 'pl_webapp_bubble_often_show' ) : '0';
		$message = ( ploption( 'pl_webapp_bubble_replace_message' ) ) ? ploption( 'pl_webapp_bubble_replace_message' ) : '""';

		?>

			<script type="text/javascript">

				var addToHomeConfig = {
					returningVisitor: <?php echo $returning_visitor; ?>,	// Show the balloon to returning visitors only (setting this to true is HIGHLY RECCOMENDED)
					animationIn: "<?php echo $animation_in; ?>",				// drop || bubble || fade
					animationOut: "<?php echo $animation_out; ?>",			// drop || bubble || fade
					startDelay: <?php echo $start_delay; ?>,				// 2 seconds from page load before the balloon appears
					lifespan: <?php echo $lifespan; ?>,						// 15 seconds before it is automatically destroyed
					expire: <?php echo $expire; ?>,							// Minutes to wait before showing the popup again (0 = always displayed)
					message: '<?php echo $message; ?>',						// Customize your message or force a language ('' = automatic)
					arrow: <?php echo $arrow; ?>,
					hookOnLoad: true,										// Should we hook to onload event? (really advanced usage)
					iterations: 100,
					touchIcon: true											// Internal/debug use	// Show the message only once every 12 hours
				};

			</script>

		<?php
	}

	function settings() {

		// settings icon path
		$icon_path = sprintf( '%s/%s', WP_PLUGIN_URL, basename(dirname( __FILE__ )));

		// options array for creating the settings tab
		$options = array(

			// edit text on pop up and replace the "this web app" with user text
			'pl_webapp_bubble_replace_message'  =>  array(
				'type'	=>	'text',
				'inputlabel'	 =>	 __('Your Text to Replace the Default Message. (this will override the language option)', 'pagelines-webapp'),
				'title'		=>	__('Custom message', 'pagelines-webapp' ),
				'shortexp'	=>	__('Add your own custom text to replace the standard message verbiage with your own.  ', 'pagelines-webapp'),
				'exp'	=>	__('Example replacement: This is a custom message. Your device is an <strong>%device</strong>. The action icon is %icon.','pagelines-webapp'),

			),

			'pl_webapp_bubble_is_frontpage'  =>  array(
				'type'	=>	'check',
				'inputlabel'	 =>	 __('Only show on frontpage?', 'pagelines-webapp'),
				'title'		=>	__('Show on frontpage', 'pagelines-webapp' ),
				'exp'	=>	__('Checking this option will do so the balloon is only showed on frontpage!','pagelines-webapp'),
			),

			'pl_webapp_bubble_returning_visitor'	=>	array(
				'default'  =>  'true',
				'type'	=>	'select',
				'selectvalues' =>  array(
					'y'	=>	array( 'name' => __( 'True' , 'pagelines-webapp' )),
					'n' =>	array( 'name' => __( 'False' , 'pagelines-webapp' ))
				),
				'inputlabel'	 =>	 __('Only show to returning visitors?', 'pagelines-webapp'),
				'title'		=>	__('Returning visitors', 'pagelines-webapp' ),
				'shortexp'	=>	__('Show the balloon to returning visitors only.', 'pagelines-webapp'),
				'exp'	=>	__('Will not show to visitors who visits first time, but will do to visitors who visits second time. (Setting this to true is HIGHLY RECCOMENDED)','pagelines-webapp'),

			),
			'pl_webapp_images'   => array(
				'type'	  => 'multi_option',
				'title'	   =>  __('Images', 'pagelines-webapp'),
				'exp'	=>	__('The touch icon is the icon that your iPhone saves on the homescreen. The touch icon needs to be 114x114. </br></br></br> The touch icon is the icon that your iPad saves on the homescreen. The touch icon needs to be 144x144 pixel.','pagelines-webapp'),
				'selectvalues'	=> array(
					'touch_icon_iphone' => array(
						'type'				 => 'image_upload',
						'inputlabel'	   => __( 'Upload your Apple touch icon for iPhone.', 'pagelines-webapp' ),
					),

					'touch_icon_ipad' => array(
						'type'				 => 'image_upload',
						'inputlabel'	   => __( 'Upload your Apple touch icon for iPad.', 'pagelines-webapp' ),
					)
				)
			),

			'pl_webapp_js_settings'	=> array(
				'type'	  => 'multi_option',
				'title'	   =>  __('Bubble', 'pagelines-webapp'),
				'selectvalues'	=> array(
					'pl_webapp_bubble_animation_in'	 =>	 array(
						'default'	=>	'drop',
						'type'	  =>  'select',
						'selectvalues' =>	array(
							'drop'  =>  array( 'name' => __( 'Drop' , 'pagelines-webapp' )),
							'bubble'	=>	array( 'name' => __( 'Bubble' , 'pagelines-webapp' )),
							'fade' =>	 array( 'name' => __( 'Fade' , 'pagelines-webapp' ))
						),
					'inputlabel'  =>  __('Which animation would you like when bubble is appearing?', 'pagelines-webapp'),
					'title'		 =>	 __('Appearing animation', 'pagelines-webapp' ),

				),

				'pl_webapp_bubble_animation_out'  =>  array(
					'default'	=>	'fade',
					'type'	  =>  'select',
					'selectvalues' =>	array(
						'drop'  =>  array( 'name' => __( 'Drop' , 'pagelines-webapp' )),
						'bubble'	=>	array( 'name' => __( 'Bubble' , 'pagelines-webapp' )),
						'fade' =>	 array( 'name' => __( 'Fade' , 'pagelines-webapp' ))
					),
					'inputlabel'  =>  __('Which animation would you like when bubble is disappearing?', 'pagelines-webapp'),
					'title'	  =>  __('Disappearing animation', 'pagelines-webapp' ),
				),

				'pl_webapp_bubble_start_delay' =>  array(
					'type'	  =>  'text',
					'inputlabel'  =>  __('Delay before bubble is appearing', 'pagelines-webapp'),
					'shortexp'	 =>	 __('Enter the ms you would like the button to delay before automaticly closing.  The default is 2000ms ', 'pagelines-webapp'),
				),

			// edit how many seconds the button stays on screen
				'pl_webapp_bubble_lifespan' =>  array(
					'type'	  =>  'text',
					'inputlabel'  =>  __('Length The Button Stays On The Screen', 'pagelines-webapp'),
					'shortexp'	 =>	 __('Enter the ms you would like the button to stay on the screen before it automatically closes.  The default is 15000ms ', 'pagelines-webapp'),
				),

			// edit how often the button is shown
				'pl_webapp_bubble_often_show' =>	 array(
					'type'	  =>  'text',
					'inputlabel'  =>  __('How Often The Button Appears', 'pagelines-webapp'),
					'shortexp'	 =>	 __('Enter the minutes you would like the button to delay before the button shows again. The default is 0mins which means everytime.', 'pagelines-webapp'),
				),

			// add the apple touch icon to float left of the text
				'pl_webapp_bubble_arrow'	=>	array(
					'default'  =>  'true',
					'type'	 =>	 'select',
					'selectvalues' =>  array(
						'y'	 =>	 array( 'name' => __( 'True' , 'pagelines-webapp' )),
						'n' =>	 array( 'name' => __( 'False' , 'pagelines-webapp' ))
					),
					'inputlabel'  =>  __('Choose True or False', 'pagelines-webapp'),
					'title'		 =>	 __('Display bubble arrow', 'pagelines-webapp' ),
					'shortexp'	 =>	 __('Select true if you want your apple touch icon to float to the left of the text.', 'pagelines-webapp'),
					)
				)
			),

			'pl_webapp_style_options'	  => array(
				'type'	  => 'multi_option',
				'title'	   =>  __('Style', 'pagelines-webapp'),
				'selectvalues'	=> array(

					// select the color of the bold call to action
					'pl_webapp_bubble_bold_color' =>	array(
					    'default'   =>  '',
					    'type'	 =>	 'colorpicker',
					    'title'	 =>	 __('Text Color', 'pagelines-webapp'),
					    'inputlabel'	 =>	 __('Select the text color', 'pagelines-webapp'),
					    'shortexp'	=>	__('Select the color you would like your call to action text to be.', 'pagelines-webapp'),
					),

					//select the color of the border
					'pl_webapp_border'		  => array(
					    'default'   =>  '',
					    'type'	 =>	 'colorpicker',
					    'title'	 =>	 __('Border Color', 'pagelines-webapp'),
					    'inputlabel'	 =>	 __('Select the border color', 'pagelines-webapp'),
					    'shortexp'	=>	__('Select the color you would like your border around the button.', 'pagelines-webapp'),
					),

					//select the background color
					'pl_webapp_bg'		  => array(
					    'default'   =>  '',
					    'type'	 =>	 'colorpicker',
					    'title'	 =>	 __('Background Color', 'pagelines-webapp'),
					    'inputlabel'	 =>	 __('Select the background color', 'pagelines-webapp'),
					    'shortexp'	=>	__('Select the color you would like your background of the bubble.', 'pagelines-webapp'),
					)
				)
			),

		);

		// add options page to pagelines settings
		pl_add_options_page(
			array(
				'name' => 'Chappy',
				'array' => $options
			)
		);
	}



	function mixins( $less ){

		$less['pl_webapp_border'] = ( ploption('pl_webapp_border') ) ? pl_hashify( ploption( 'pl_webapp_border' ) ) : '#505050';
		$less['pl_webapp_bubble_bold_color'] = ( ploption('pl_webapp_bubble_bold_color') ) ? pl_hashify( ploption( 'pl_webapp_bubble_bold_color' ) ): '#333';
		$less['pl_webapp_bg'] = ( ploption('pl_webapp_bg') ) ? pl_hashify( ploption( 'pl_webapp_bg' ) ): '#a3a3a3';

		return $less;

	}
}
new Chappy;
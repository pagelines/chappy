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
		// facebook language option
		'facebook_language' 	=> array(
			'default'     		=> 'en_US',
			'version'    		=> 'pro',
			'type'         		=> 'select',
			'selectvalues'    	=> array(
				'en_US'	=> array(	'name'	=> __(	'English(US)'			,	'pagelines'	)),
				'en_GB'	=> array(	'name'	=> __(	'English(UK)'			,	'pagelines'	)),
				'ar_AR'	=> array(	'name'	=> __(	'Arabic'				,	'pagelines'	)),
				'de_DE'	=> array(	'name'	=> __(	'German'				,	'pagelines'	)),
				'fr_FR'	=> array(	'name'	=> __(	'French'				,	'pagelines'	)),
				'it_IT'	=> array(	'name'	=> __(	'Italian'				,	'pagelines'	)),
				'ru_RU'	=> array(	'name'	=> __(	'Russian'				,	'pagelines'	)),
				'es_ES'	=> array(	'name'	=> __(	'Spanish(Spain)'		,	'pagelines'	)),
				'af_ZA'	=> array(	'name'	=> __(	'Afrikaans'				,	'pagelines'	)),
				'sq_AL'	=> array(	'name'	=> __(	'Albanian'				,	'pagelines'	)),
				'hy_AM'	=> array(	'name'	=> __(	'Armenian'				,	'pagelines'	)),
				'ay_BO'	=> array(	'name'	=> __(	'Aymara'				,	'pagelines'	)),
				'az_AZ'	=> array(	'name'	=> __(	'Azeri'					,	'pagelines'	)),
				'be_BY'	=> array(	'name'	=> __(	'Belarusian'			,	'pagelines'	)),
				'bn_IN'	=> array(	'name'	=> __(	'Bengali'				,	'pagelines'	)),
				'bs_BA'	=> array(	'name'	=> __(	'Bosnian'				,	'pagelines'	)),
				'bg_BG'	=> array(	'name'	=> __(	'Bulgarian'				,	'pagelines'	)),
				'ca_ES'	=> array(	'name'	=> __(	'Catalan'				,	'pagelines'	)),
				'ck_US'	=> array(	'name'	=> __(	'Cherokee'				,	'pagelines'	)),
				'zh_CN'	=> array(	'name'	=> __(	'Chinese(China)'		,	'pagelines'	)),
				'zh_HK'	=> array(	'name'	=> __(	'Chinese(HongKong)'		,	'pagelines'	)),
				'zh_TW'	=> array(	'name'	=> __(	'Chinese(Taiwan)'		,	'pagelines'	)),
				'hr_HR'	=> array(	'name'	=> __(	'Croatian'				,	'pagelines'	)),
				'cs_CZ'	=> array(	'name'	=> __(	'Czech'					,	'pagelines'	)),
				'da_DK'	=> array(	'name'	=> __(	'Danish'				,	'pagelines'	)),
				'nl_NL'	=> array(	'name'	=> __(	'Dutch'					,	'pagelines'	)),
				'nl_BE'	=> array(	'name'	=> __(	'Dutch(Belgi&euml;)'	,	'pagelines'	)),
				'en_PL'	=> array(	'name'	=> __(	'English(Pirate)'		,	'pagelines'	)),
				'en_UD'	=> array(	'name'	=> __(	'English(UpsideDown)'	,	'pagelines'	)),
				'eo_EO'	=> array(	'name'	=> __(	'Esperanto'				,	'pagelines'	)),
				'et_EE'	=> array(	'name'	=> __(	'Estoninan'				,	'pagelines'	)),
				'fo_FO'	=> array(	'name'	=> __(	'Faroese'				,	'pagelines'	)),
				'tl_PH'	=> array(	'name'	=> __(	'Filipino'				,	'pagelines'	)),
				'fi_FI'	=> array(	'name'	=> __(	'Finnish'				,	'pagelines'	)),
				'fr_CA'	=> array(	'name'	=> __(	'French(Canada)'		,	'pagelines'	)),
				'gl_ES'	=> array(	'name'	=> __(	'Galician'				,	'pagelines'	)),
				'ka_GE'	=> array(	'name'	=> __(	'Georgian'				,	'pagelines'	)),
				'el_GR'	=> array(	'name'	=> __(	'Greek'					,	'pagelines'	)),
				'gn_PY'	=> array(	'name'	=> __(	'Guaran&iacute;'		,	'pagelines'	)),
				'gu_IN'	=> array(	'name'	=> __(	'Gujarati'				,	'pagelines'	)),
				'he_IL'	=> array(	'name'	=> __(	'Hebrew'				,	'pagelines'	)),
				'hi_IN'	=> array(	'name'	=> __(	'Hindi'					,	'pagelines'	)),
				'hu_HU'	=> array(	'name'	=> __(	'Hungarian'				,	'pagelines'	)),
				'is_IS'	=> array(	'name'	=> __(	'Icelandic'				,	'pagelines'	)),
				'id_ID'	=> array(	'name'	=> __(	'Indonesian'			,	'pagelines'	)),
				'ga_IE'	=> array(	'name'	=> __(	'Irish'					,	'pagelines'	)),
				'ja_JP'	=> array(	'name'	=> __(	'Japanese'				,	'pagelines'	)),
				'jv_ID'	=> array(	'name'	=> __(	'Javanese'				,	'pagelines'	)),
				'kn_IN'	=> array(	'name'	=> __(	'Kannada'				,	'pagelines'	)),
				'kk_KZ'	=> array(	'name'	=> __(	'Kazakh'				,	'pagelines'	)),
				'km_KH'	=> array(	'name'	=> __(	'Khmer'					,	'pagelines'	)),
				'tl_ST'	=> array(	'name'	=> __(	'Klingon'				,	'pagelines'	)),
				'ko_KR'	=> array(	'name'	=> __(	'Korean'				,	'pagelines'	)),
				'ku_TR'	=> array(	'name'	=> __(	'Kurdish'				,	'pagelines'	)),
				'la_VA'	=> array(	'name'	=> __(	'Latin'					,	'pagelines'	)),
				'lv_LV'	=> array(	'name'	=> __(	'Latvian'				,	'pagelines'	)),
				'fb_LT'	=> array(	'name'	=> __(	'LeetSpeak'				,	'pagelines'	)),
				'li_NL'	=> array(	'name'	=> __(	'Limburgish'			,	'pagelines'	)),
				'lt_LT'	=> array(	'name'	=> __(	'Lithuanian'			,	'pagelines'	)),
				'mk_MK'	=> array(	'name'	=> __(	'Macedonian'			,	'pagelines'	)),
				'mg_MG'	=> array(	'name'	=> __(	'Malagasy'				,	'pagelines'	)),
				'ms_MY'	=> array(	'name'	=> __(	'Malay'					,	'pagelines'	)),
				'ml_IN'	=> array(	'name'	=> __(	'Malayalam'				,	'pagelines'	)),
				'mt_MT'	=> array(	'name'	=> __(	'Maltese'				,	'pagelines'	)),
				'mr_IN'	=> array(	'name'	=> __(	'Marathi'				,	'pagelines'	)),
				'mn_MN'	=> array(	'name'	=> __(	'Mongolian'				,	'pagelines'	)),
				'ne_NP'	=> array(	'name'	=> __(	'Nepali'				,	'pagelines'	)),
				'mg_MG'	=> array(	'name'	=> __(	'Malagasy'				,	'pagelines'	)),
				'ko_KR'	=> array(	'name'	=> __(	'Korean'				,	'pagelines'	)),
				'nb_NO'	=> array(	'name'	=> __(	'Norwegian(bokmal)'		,	'pagelines'	)),
				'nn_NO'	=> array(	'name'	=> __(	'Norwegian(nynorsk)'	,	'pagelines'	)),
				'se_NO'	=> array(	'name'	=> __(	'NorthernS&aacute;mi'	,	'pagelines'	)),
				'ps_AF'	=> array(	'name'	=> __(	'Pashto'				,	'pagelines'	)),
				'fa_IR'	=> array(	'name'	=> __(	'Persian'				,	'pagelines'	)),
				'pl_PL'	=> array(	'name'	=> __(	'Polish'				,	'pagelines'	)),
				'pt_BR'	=> array(	'name'	=> __(	'Portugese(Brazil)'		,	'pagelines'	)),
				'pt_PT'	=> array(	'name'	=> __(	'Portugese(Portugal)'	,	'pagelines'	)),
				'qu_PE'	=> array(	'name'	=> __(	'Quechua'				,	'pagelines'	)),
				'pa_IN'	=> array(	'name'	=> __(	'Punjabi'				,	'pagelines'	)),
				'ro_RO'	=> array(	'name'	=> __(	'Romanian'				,	'pagelines'	)),
				'rm_CH'	=> array(	'name'	=> __(	'Romansh'				,	'pagelines'	)),
				'sa_IN'	=> array(	'name'	=> __(	'Sanskrit'				,	'pagelines'	)),
				'sr_RS'	=> array(	'name'	=> __(	'Serbian'				,	'pagelines'	)),
				'so_SO'	=> array(	'name'	=> __(	'Somali'				,	'pagelines'	)),
				'sk_SK'	=> array(	'name'	=> __(	'Slovak'				,	'pagelines'	)),
				'sl_SL'	=> array(	'name'	=> __(	'Slovenian'				,	'pagelines'	)),
				'es_CL'	=> array(	'name'	=> __(	'Spanish(Chile)'		,	'pagelines'	)),
				'es_CO'	=> array(	'name'	=> __(	'Spanish(Colombia)'		,	'pagelines'	)),
				'es_MX'	=> array(	'name'	=> __(	'Spanish(Mexico)'		,	'pagelines'	)),
				'es_VE'	=> array(	'name'	=> __(	'Spanish(Venezuela)'	,	'pagelines'	)),
				'sy_SY'	=> array(	'name'	=> __(	'Syriac'				,	'pagelines'	)),
				'sw_KE'	=> array(	'name'	=> __(	'Swahili'				,	'pagelines'	)),
				'sv_SE'	=> array(	'name'	=> __(	'Swedish'				,	'pagelines'	)),
				'tg_TJ'	=> array(	'name'	=> __(	'Tajik'					,	'pagelines'	)),
				'ta_IN'	=> array(	'name'	=> __(	'Tamil'					,	'pagelines'	)),
				'tt_RU'	=> array(	'name'	=> __(	'Tatar'					,	'pagelines'	)),
				'te_IN'	=> array(	'name'	=> __(	'Telugu'				,	'pagelines'	)),
				'th_TH'	=> array(	'name'	=> __(	'Thai'					,	'pagelines'	)),
				'tr_TR'	=> array(	'name'	=> __(	'Turkish'				,	'pagelines'	)),
				'uk_UA'	=> array(	'name'	=> __(	'Ukrainian'				,	'pagelines'	)),
				'ur_PK'	=> array(	'name'	=> __(	'Urdu'					,	'pagelines'	)),
				'uz_UZ'	=> array(	'name'	=> __(	'Uzbek'					,	'pagelines'	)),
				'vi_VN'	=> array(	'name'	=> __(	'Vietnamese'			,	'pagelines'	)),
				'cy_GB'	=> array(	'name'	=> __(	'Welsh'					,	'pagelines'	)),
				'xh_ZA'	=> array(	'name'	=> __(	'Xhosa'					,	'pagelines'	)),
				'yi_DE'	=> array(	'name'	=> __(	'Yiddish'				,	'pagelines'	)),
				'zu_ZA'	=> array(	'name'	=> __(	'Zulu'					,	'pagelines'	))
			),
			'inputlabel' 	=> 	__('Your Facebook buttons language:', 'pagelines'),
			'title'     	=> 	__('Facebook button language', 'pagelines' ),
			'shortexp' 		=> 	__('Here you have to put in your local language. You need to do this in order to localize your Facebook button. By default the language is English. You can change the Facebook buttons localization to any language listed here. If your language is not supported yet, Facebook will choose English until they support your language!', 'pagelines'),
		),

		// facebook button term		
		'facebook_term'		=> 	array(
			'default'		=> 	'like',
			'version'		=> 	'pro',
			'type'			=> 	'select',
			'selectvalues'	=> 	array(
				'like'		=> 	array(	'name'	=> __(	'Like'	,	'pagelines'	)),
				'recommend'	=> 	array(	'name'	=> __(	'Recommend'	,	'pagelines'	))
			),
			'inputlabel' 	=> 	__('Choose the term used by your Facebook button', 'pagelines'),
			'title'     	=> 	__('Facebook button term', 'pagelines' ),
			'shortexp' 		=> 	__('You can choose "Like" or "Reccomend" as labels for your Facebook button', 'pagelines'),
		),
		
		// facebook app id
		'facebook_appid'	=> 	array(
			'default' 		=> 	'',
			'version' 		=> 	'pro',
			'type' 			=> 	'text',
			'title' 		=> 	__('Facebook app id', 'pagelines'),
			'inputlabel' 	=> 	__('Your Facebook app id:', 'pagelines'),
			'shortexp' 		=> 	__('Enter your APP ID to see the number of likes on your domain each day and the demographics of who is clicking the Like button on Facebook.com/insights.', 'pagelines'),
		),
		
		// facebook like button width
		'facebook_width'	=> 	array(
			'default' 		=> 	'',
			'version' 		=> 	'pro',
			'type' 			=> 	'text',
			'title' 		=> 	__('Facebook like button width', 'pagelines'),
			'inputlabel' 	=> 	__('Facebook button custom width:', 'pagelines'),
			'shortexp' 		=> 	__('Put in your Facebook buttons custom width in pixels.', 'pagelines'),
		),
		
		// tweet via option
		'tweet_via' 		=> 	array(
			'default' 		=> 	'',
			'version' 		=> 	'pro',
			'type' 			=> 	'text',
			'title' 		=> 	__('Tweet via.', 'pagelines'),
			'inputlabel' 	=> 	__('Type in your Twitter name: @', 'pagelines'),
			'shortexp' 		=> 	__('If you want your Twitter name to get mentioned by your readers, please type in your Twitter name', 'pagelines'),
		),
		
		// share text option
		'share_text' 		=> 	array(
			'default' 		=> 	'',
			'version' 		=> 	'pro',
			'type' 			=> 	'text',
			'title' 		=> 	__('Share text', 'pagelines'),
			'inputlabel' 	=> 	__('Type in the text you want to have before your buttons:', 'pagelines'),
			'shortexp' 		=> 	__('Fill in the text that you want your readers to see infront of the social buttons. If you do not type in something there will be no text in front of the buttons.', 'pagelines'),
		),
		//pinterest image
		'custom_pinit_image'	=> array(
			'default'         	=> '',
			'type'             	=> 'image_upload',
			'imagepreview'     	=> '270',
			'inputlabel'     	=> __( 'Upload custom Pinterest image', 'pagelines' ),
			'title'            	=> __( 'Custom Pinterest Image if there is no thumbnail attached to the post', 'pagelines' ),                        
			'shortexp'         	=> __( 'Input Full URL to your custom Pinterest image.', 'pagelines' ),
 		),
		
		'where_to_show_buttons'		=>	array(
			'default'				=>	'',
			'type'					=>	'check_multi',
			'selectvalues'			=>	array(
			// show in top of excerpts
			'show_in_excerpts_top'		=>	array(
				'default'		=>	true,
				'type'			=>	'check',
				'scope'			=>	'',
				'inputlabel'	=>	__( 'Show buttons in top of excerpts.', 'pagelines' ),
				'title'			=>	__( 'Show buttons in top of excerpts', 'pagelines' ),
				'shortexp'		=>	__( 'Show buttons in top of excerpts.', 'pagelines' ),
				'exp'			=>	__( 'Checking this option will show the social buttons in top of excerpts.', 'pagelines')
			),
			// show in bottom of excerpts
			'show_in_excerpts_bottom'		=>	array(
				'default'		=>	true,
				'type'			=>	'check',
				'scope'			=>	'',
				'inputlabel'	=>	__( 'Show buttons in bottom of excerpts.', 'pagelines' ),
				'title'			=>	__( 'Show buttons in bottom of excerpts.', 'pagelines' ),
				'shortexp'		=>	__( 'Show buttons in bottom of excerpts.', 'pagelines' ),
				'exp'			=>	__( 'Checking this option will show the social buttons in bottom of excerpts.', 'pagelines')
			),
			// show in top of posts
			'show_in_posts_top'		=>	array(
				'default'		=>	true,
				'type'			=>	'check',
				'scope'			=>	'',
				'inputlabel'	=>	__( 'Show buttons in top of posts and pages.', 'pagelines' ),
				'title'			=>	__( 'Show buttons in top of posts and pages.', 'pagelines' ),
				'shortexp'		=>	__( 'Show buttons in top of posts and pages.', 'pagelines' ),
				'exp'			=>	__( 'Checking this option will show the social buttons in top of posts and pages.', 'pagelines')
			),		
			// show in bottom of posts
			'show_in_posts_bottom'		=>	array(
				'default'		=>	true,
				'type'			=>	'check',
				'scope'			=>	'',
				'inputlabel'	=>	__( 'Show buttons in bottom of posts and pages.', 'pagelines' ),
				'title'			=>	__( 'Show buttons in bottom of posts and pages.', 'pagelines' ),
				'shortexp'		=>	__( 'Show buttons in bottom of posts and pages.', 'pagelines' ),
				'exp'			=>	__( 'Checking this option will show the social buttons in bottom of posts and pages.', 'pagelines')
			)),
		'inputlabel'				=>	__( 'Select where you want to show your buttons.', 'pagelines' ),
		'title'						=>	__( 'Where to show buttons.', 'pagelines' ),                        
		'shortexp'					=>	__( 'Where to show buttons.', 'pagelines' ),
		'exp'						=>	__( 'Gives you the option to choose where you wants the buttons to be displayed. It is not recommended to show your buttons in top- and in bottom of excerpts because the buttons will load slower as it needs to show the iframes twice per post instead of one.', 'pagelines' )               
		),
		
		// enable buttons
		'enable_social_buttons'		=>	array(
			'default'				=>	'',
			'type'					=>	'check_multi',
			'selectvalues'			=>	array(
			
				'enable_facebook'	=>	array(
					'default'		=>	false,
					'type'			=>	'check',
					'scope'			=>	'',
					'inputlabel'	=>	__( 'Enable Facebook button', 'pagelines' ),
					'title'			=>	__( 'Facebook button', 'pagelines' ),
					'shortexp'		=>	__( 'Enable Facebook button', 'pagelines' ),
					'exp'			=>	__( 'Checking this option will enable the Facebook button.', 'pagelines')
				),			
				'enable_twitter'	=>	array(
					'default'		=>	true,
					'type'			=>	'check',
					'scope'			=>	'',
					'inputlabel'	=>	__( 'Enable Twitter button', 'pagelines' ),
					'title'			=>	__( 'Twitter button', 'pagelines' ),
					'shortexp'		=>	__( 'Enable Twitter button', 'pagelines' ),
					'exp'			=>	__( 'Checking this option will enable the Twitter button.', 'pagelines')
				),
				'enable_linkedin'	=>	array(
					'default'		=>	true,
					'type'			=>	'check',
					'scope'			=>	'',
					'inputlabel'	=>	__( 'Enable LinkedIn button', 'pagelines' ),
					'title'			=>	__( 'LinkedIn button', 'pagelines' ),
					'shortexp'		=>	__( 'Enable LinkedIn button', 'pagelines' ),
					'exp'			=>	__( 'Checking this option will enable the LinkedIn button.', 'pagelines')
				),				
				'enable_pinterest'	=>	array(
					'default'		=>	true,
					'type'			=>	'check',
					'scope'			=>	'',
					'inputlabel'	=>	__( 'Enable Pinterest button', 'pagelines' ),
					'title'			=>	__( 'Pinterest button', 'pagelines' ),
					'shortexp'		=>	__( 'Enable Pinterest button', 'pagelines' ),
					'exp'			=>	__( 'Checking this option will enable the Pinterest button.', 'pagelines')
				),
				/*
				'enable_stumbleupon'=>	array(
					'default'		=>	true,
					'type'			=>	'check',
					'scope'			=>	'',
					'inputlabel'	=>	__( 'Enable Stumbleupon button', 'pagelines' ),
					'title'			=>	__( 'Stumbleupon button', 'pagelines' ),
					'shortexp'		=>	__( 'Enable Stumbleupon button', 'pagelines' ),
					'exp'			=>	__( 'Checking this option will enable the Stumbleupon button.', 'pagelines')
				),
				//stumbleupon is not supported at this time
				*/
				'enable_googleplus'	=>	array(
					'default'		=>	true,
					'type'			=>	'check',
					'scope'			=>	'',
					'inputlabel'	=>	__( 'Enable Google+1 button', 'pagelines' ),
					'title'			=>	__( 'Google+1 button', 'pagelines' ),
					'shortexp'		=>	__( 'Enable Google+1 button', 'pagelines' ),
					'exp'			=>	__( 'Checking this option will enable the Google+1 button.', 'pagelines')
				),
				'enable_printfriendly'	=>	array(
					'default'		=>	true,
					'type'			=>	'check',
					'scope'			=>	'',
					'inputlabel'	=>	__( 'Enable Printfriendly button', 'pagelines' ),
					'title'			=>	__( 'Printfriendly button', 'pagelines' ),
					'shortexp'		=>	__( 'Enable Printfriendly button', 'pagelines' ),
					'exp'			=>	__( 'Checking this option will enable the printfriendly button.', 'pagelines')				
				)),
		'inputlabel'				=>	__( 'Select which social buttons you want to show with social excerpts', 'pagelines' ),
		'title'						=>	__( 'Enable social buttons', 'pagelines' ),                        
		'shortexp'					=>	__( 'Select Which To Show', 'pagelines' ),
		'exp'						=>	__( 'Enable social buttons and choose which buttons you want to show', 'pagelines' ) 
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

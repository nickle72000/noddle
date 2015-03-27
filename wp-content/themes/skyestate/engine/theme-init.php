<?php

add_action( 'after_setup_theme', 'nvr_setup' );

if ( ! function_exists( 'nvr_setup' ) ):

function nvr_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	if ( function_exists( 'add_theme_support' ) ) { // Added in 2.9
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'woocommerce' );
		add_image_size( 'blog-post-image', 800, 410, true ); // Blog Image
		add_image_size( 'post-thumb', 100, 100, true ); // Recent Post Widget Image
		add_image_size( 'testimonial-thumb', 100, 100, true ); // Testimonial Image
		add_image_size( 'property-image', 700, 424, true ); // Portfolio Image Original
		add_image_size( 'portfolio-image', 700, 424, true ); // Portfolio Image Original
		add_image_size( 'portfolio-image-square', 700, 700, true ); // Portfolio Image Square
		add_image_size( 'portfolio-image-portrait', 700, 1100, true ); // Portfolio Image Portrait
		add_image_size( 'portfolio-image-landscape', 700, 424, true ); // Portfolio Image Landscape
		add_image_size( 'brand-image', 220, 104, true ); // Portfolio Image Col 4
		add_image_size( 'product-big-image', 700, 700, true ); // Product Big Image
	}

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );
	
	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'gallery', 'video', 'audio' ) );
	
	// Removing the sidebar in woocommerce
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	/* 270x330 16px 11px 16px 11px*/
	/*remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );*/

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'mainmenu' => __( 'Main Menu', THE_LANG )
	) );
	register_nav_menus( array(
		'secondarymenu' => __( 'Secondary Menu', THE_LANG )
	) );
	
	//function for woocommerce customization
	nvr_woocommerce();
}
endif;

function exceptation(){
	add_theme_support( 'custom-header' );
	add_theme_support( 'custom-background' );
}

/***** START - All functions, hooks, and filters for simple share buttons customization *****/
remove_filter( 'the_content', 'show_share_buttons');	
remove_filter( 'the_excerpt', 'show_share_buttons');
/***** END - All functions, hooks, and filters for simple share buttons customization *****/

if(function_exists( 'set_revslider_as_theme' )){
	add_action( 'init', 'nvr_disable_autoupdate' );
	function nvr_disable_autoupdate() {
		set_revslider_as_theme();
	}
}

/***** START - All functions for woocommerce customization *****/
function nvr_woocommerce(){
	add_filter('woocommerce_show_page_title', 'nvr_woocommerce_show_page_title');
	add_filter('woocommerce_breadcrumb_defaults', 'nvr_woocommerce_breadcrumb_defaults');
	remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb' , 20);
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	
	add_action( 'woocommerce_after_single_product_summary', 'nvr_woocommerce_upsell_display', 15 );
	add_filter( 'woocommerce_output_related_products_args', 'nvr_woocommerce_output_related_products_args' );
	
}

function nvr_woocommerce_show_page_title(){
	return false;
}

function nvr_woocommerce_breadcrumb_defaults(){
	return array(
		'delimiter'   => ' &nbsp;&#47;&nbsp; ',
		'wrap_before' => '<nav class="nvr-breadcrumb" itemprop="breadcrumb">',
		'wrap_after'  => '</nav>',
		'before'      => '',
		'after'       => '',
		'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
	);
}

function nvr_woocommerce_output_related_products_args() {
  global $product;
	
	$args = array(
		'posts_per_page' => 3,
		'columns' => 3,
		'orderby' => 'rand'
	);
	return $args;
}

function nvr_woocommerce_output_related_products(){
	woocommerce_related_products( 3, 3 );
}

function nvr_woocommerce_upsell_display(){
	woocommerce_upsell_display( 4, 4);
}
/***** END - All functions for woocommerce customization *****/

if(!function_exists('nvr_add_contact_info')){
	function nvr_add_contact_info($profile_fields) {
	
		// Add new fields
		$profile_fields['user_group']   				= __('User Group', THE_LANG);
		$profile_fields['userb_pass']   				= __('UserB Pass',THE_LANG);
		$profile_fields['phone']                        = __('Phone',THE_LANG);
		$profile_fields['mobile']                       = __('Mobile',THE_LANG);
		$profile_fields['skype']                        = __('Skype',THE_LANG);
		$profile_fields['title']                        = __('Title/Position',THE_LANG);
		$profile_fields['custom_picture']               = __('Picture Url',THE_LANG);
		$profile_fields['package_id']                   = __('Package Id',THE_LANG);
		$profile_fields['package_activation']           = __('Package Activation',THE_LANG);
		$profile_fields['package_listings']             = __('Listings available',THE_LANG);
		$profile_fields['package_featured_listings']    = __('Featured Listings available',THE_LANG);
		$profile_fields['profile_id']                   = __('Paypal Recuring Profile',THE_LANG);
		$profile_fields['user_agent_id']                = __('User Agent Id',THE_LANG);
		return $profile_fields;
	}
	add_filter('user_contactmethods', 'nvr_add_contact_info',99);
}

/***** START - All functions, hooks, and filters for visual composer customization *****/
if(function_exists('vc_map')){
	add_action('init', 'nvr_vc_update');
	function nvr_vc_update(){
		$vcrow_params = array(
			'params' => array(
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Font Color', THE_LANG ),
					'param_name' => 'font_color',
					'description' => __( 'Select font color', THE_LANG ),
					'edit_field_class' => 'vc_col-md-6 vc_column'
				),
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Custom Background Color', THE_LANG ),
					'param_name' => 'bg_color',
					'description' => __( 'Select backgound color for your row', THE_LANG ),
					'edit_field_class' => 'col-md-6'
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Padding', THE_LANG ),
					'param_name' => 'padding',
					'description' => __( 'You can use px, em, %, etc. or enter just number and it will use pixels.', THE_LANG ),
					'edit_field_class' => 'col-md-6'
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Top margin', THE_LANG ),
					'param_name' => 'margin_top',
					'description' => __( 'You can use px, em, %, etc. or enter just number and it will use pixels.', THE_LANG ),
					'edit_field_class' => 'col-md-6'
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Bottom margin', THE_LANG ),
					'param_name' => 'margin_bottom',
					'description' => __( 'You can use px, em, %, etc. or enter just number and it will use pixels.', THE_LANG ),
					'edit_field_class' => 'col-md-6'
				),
				array(
					'type' => 'attach_image',
					'heading' => __( 'Background Image', THE_LANG ),
					'param_name' => 'bg_image',
					'description' => __( 'Select background image for your row', THE_LANG )
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Background Repeat', THE_LANG ),
					'param_name' => 'bg_image_repeat',
					'value' => array(
									  __( 'Default', THE_LANG ) => '',
									  __( 'Cover', THE_LANG ) => 'cover',
								  __('Contain', THE_LANG) => 'contain',
								  __('No Repeat', THE_LANG) => 'no-repeat'
								),
					'description' => __( 'Select how a background image will be repeated', THE_LANG ),
					'dependency' => array( 'element' => 'bg_image', 'not_empty' => true)
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', THE_LANG ),
					'param_name' => 'el_class',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', THE_LANG ),
				)
			)
		);
		vc_map_update('vc_row', $vcrow_params);
		
		vc_add_param("vc_row", array(
			"type" => "dropdown",
			"class" => "",
			"show_settings_on_create"=>true,
			"heading" => __("Row Type", THE_LANG),
			"param_name" => "row_type",
			"value" => array(
				"Row" => "row",
				"Section" => "section"
			)
			
		));
		vc_add_param("vc_row", array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Anchor ID", THE_LANG),
			"param_name" => "anchor",
			"value" => ""
		));
		vc_add_param("vc_row", array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Use Video background?", THE_LANG),
			"param_name" => "video",
			"description" => "",
			"value" => array(
				"No" => "no",
				"Yes" => "show_video"
				
			),
			"dependency" => Array('element' => "row_type", 'value' => array('section'))
		));
		vc_add_param("vc_row", array(
			"type" => "dropdown",
			"class" => "",
			"show_settings_on_create"=>true,
			"heading" => __("Section Type", THE_LANG),
			"param_name" => "type",
			"value" => array(
				"Full-width" => "fullwidth",
				"On-grid" => "ongrid"
			),
			"dependency" => Array('element' => "row_type", 'value' => array('section'))
		));
		vc_add_param("vc_row", array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Video overlay", THE_LANG),
			"value" => array(__("Use transparent overlay over video?", THE_LANG) => "show_video_overlay" ),
			"param_name" => "video_overlay",
			"description" => "",
			"dependency" => Array('element' => "video", 'value' => array('show_video'))
		));
		vc_add_param("vc_row", array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Video background (webm) file url", THE_LANG),
			"value" => "",
			"param_name" => "video_webm",
			"description" => "",
			"dependency" => Array('element' => "video", 'value' => array('show_video'))
		));
		vc_add_param("vc_row", array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Video background (mp4) file url", THE_LANG),
			"value" => "",
			"param_name" => "video_mp4",
			"description" => "",
			"dependency" => Array('element' => "video", 'value' => array('show_video'))
		));
		vc_add_param("vc_row", array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Video background (ogv) file url", THE_LANG),
			"value" => "",
			"param_name" => "video_ogv",
			"description" => "",
			"dependency" => Array('element' => "video", 'value' => array('show_video'))
		));
		vc_add_param("vc_row", array(
			"type" => "attach_image",
			"class" => "",
			"heading" => __("Video preview image", THE_LANG),
			"value" => "",
			"param_name" => "video_image",
			"description" => "",
			"dependency" => Array('element' => "video", 'value' => array('show_video'))
		));
	}
}

function novaro_row_buildStyle( $bg_image = '', $bg_color = '', $bg_image_repeat = '', $font_color = '', $padding = '', $margin_bottom = '', $margin_top = '' ) {
	$has_image = false;
	$style = '';
	if ( (int)$bg_image > 0 && ( $image_url = wp_get_attachment_url( $bg_image, 'large' ) ) !== false ) {
		$has_image = true;
		$style .= "background-image: url(" . $image_url . ");";
	}
	if ( ! empty( $bg_color ) ) {
		$style .= vc_get_css_color( 'background-color', $bg_color );
	}
	if ( ! empty( $bg_image_repeat ) && $has_image ) {
		if ( $bg_image_repeat === 'cover' ) {
			$style .= "background-repeat:no-repeat;background-size: cover;";
		} elseif ( $bg_image_repeat === 'contain' ) {
			$style .= "background-repeat:no-repeat;background-size: contain;";
		} elseif ( $bg_image_repeat === 'no-repeat' ) {
			$style .= 'background-repeat: no-repeat;';
		}
	}
	if ( ! empty( $font_color ) ) {
		$style .= vc_get_css_color( 'color', $font_color ); // 'color: '.$font_color.';';
	}
	if ( $padding != '' ) {
		$style .= 'padding: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $padding ) ? $padding : $padding . 'px' ) . ';';
	}
	if ( $margin_bottom != '' ) {
		$style .= 'margin-bottom: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $margin_bottom ) ? $margin_bottom : $margin_bottom . 'px' ) . ';';
	}
	if( $margin_top != '' ) {
		$style .= 'margin-top: '.(preg_match('/(px|em|\%|pt|cm)$/', $margin_top) ? $margin_top : $margin_top.'px').';';
	}
	return empty( $style ) ? $style : ' style="' . $style . '"';
}
/***** END - All functions, hooks, and filters for visual composer customization *****/

function novaro_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $nvr_plugins = array(
		/*
        // This is an example of how to include a plugin pre-packaged with a theme.
        array(
            'name'               => 'Contact Form 7', // The plugin name.
            'slug'               => 'contact-form-7', // The plugin slug (typically the folder name).
            'source'             => get_stylesheet_directory() . '/lib/plugins/tgm-example-plugin.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),

        // This is an example of how to include a plugin from a private repo in your theme.
        array(
            'name'               => 'TGM New Media Plugin', // The plugin name.
            'slug'               => 'tgm-new-media-plugin', // The plugin slug (typically the folder name).
            'source'             => 'https://s3.amazonaws.com/tgm/tgm-new-media-plugin.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'external_url'       => 'https://github.com/thomasgriffin/New-Media-Image-Uploader', // If set, overrides default API URL and points to an external URL.
        ),
		*/
        // This is an example of how to include a plugin from the WordPress Plugin Repository.
        array(
            'name'      => 'Visual Composer',
            'slug'      => 'js_composer',
            'required'  => true,
        ),
		array(
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'required'  => true,
        ),
		array(
            'name'      => 'Wordpress Importer',
            'slug'      => 'wordpress-importer',
            'required'  => true,
        ),
		array(
            'name'      => 'WP Page Navi',
            'slug'      => 'wp-pagenavi',
            'required'  => false,
        ),
		array(
            'name'      => 'Really Simple CAPTCHA',
            'slug'      => 'really-simple-captcha',
            'required'  => false,
        ),
		array(
            'name'      => 'WP Retina 2x',
            'slug'      => 'wp-retina-2x',
            'required'  => false,
        ),
		array(
            'name'      => 'Regenerate Thumbnails',
            'slug'      => 'regenerate-thumbnails',
            'required'  => false,
        ),
		array(
            'name'      => 'Flickr Photos',
            'slug'      => 'simple-flickr-plugin',
            'required'  => false,
        )

    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $nvr_config = array(
        'id'           => 'novaro',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', THE_LANG ),
            'menu_title'                      => __( 'Install Plugins', THE_LANG ),
            'installing'                      => __( 'Installing Plugin: %s', THE_LANG ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', THE_LANG ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', THE_LANG ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', THE_LANG ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', THE_LANG ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', THE_LANG ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', THE_LANG ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', THE_LANG ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', THE_LANG ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', THE_LANG ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', THE_LANG ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', THE_LANG ),
            'return'                          => __( 'Return to Required Plugins Installer', THE_LANG ),
            'plugin_activated'                => __( 'Plugin activated successfully.', THE_LANG ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', THE_LANG ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );

    tgmpa( $nvr_plugins, $nvr_config );

}
add_action( 'tgmpa_register', 'novaro_register_required_plugins' );
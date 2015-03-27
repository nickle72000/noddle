<?php

/**
  ReduxFramework Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */
 
$nvr_shortname = THE_SHORTNAME;
$nvr_initial = THE_INITIAL;

if (!class_exists('Redux_Framework_sample_config')) {

    class Redux_Framework_sample_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        public function setSections() {

            $nvr_shortname = THE_SHORTNAME;
			$nvr_initial = THE_INITIAL;
			
			$nvr_optLogotype 	= array(
				'imagelogo' 	=> __('Image logo', THE_LANG),
				'textlogo' 		=> __('Text-based logo', THE_LANG)
				 );
			
			$nvr_optWebLayout	= array(
				'nvrlayout1'	=> __('Version 1', THE_LANG),
				'nvrlayout2'	=> __('Version 2', THE_LANG),
				'nvrlayout3'	=> __('Version 3', THE_LANG),
				'nvrlayout4'	=> __('Version 4', THE_LANG),
				'nvrlayout5'	=> __('Version 5', THE_LANG),
				'nvrlayout6'	=> __('Version 6', THE_LANG),
				'nvrlayout7'	=> __('Version 7', THE_LANG)
			);
			
			$nvr_opt2ndbarloc 	= array(
				'nvrtopbar' 	=> __('Above the logo', THE_LANG),
				'nvrbelowbar' => __('Below the logo', THE_LANG)
				 );
			
			$nvr_optHeaderPos 	= array(
				'fixed' 	=> __('Fixed', THE_LANG),
				'absolute' 	=> __('Absolute', THE_LANG)
				 );
			
			$nvr_optLayout 	= array(
				'nvrfullwidth' 	=> __('Full-Width Layout', THE_LANG),
				'nvrboxed' 	=> __('Boxed Layout', THE_LANG)
				 );
			
			$nvr_google_api_output = nvr_googlefontjson();
			$nvr_google_font_array = json_decode ($nvr_google_api_output,true) ;
			//print_r( json_decode ($nvr_google_api_output) );
			
			$nvr_google_items = $nvr_google_font_array['items'];
			
			$nvr_optGoogleFonts = array();
			array_push($nvr_optGoogleFonts, "Default Font");
			$nvr_fontID = 0;
			foreach ($nvr_google_items as $nvr_google_item) {
				$nvr_fontID++;
				$nvr_variants='';
				$nvr_variantCount=0;
				foreach ($nvr_google_item['variants'] as $nvr_variant) {
					$nvr_variantCount++;
					if ($nvr_variantCount>1) { $nvr_variants .= '|'; }
					$nvr_variants .= $nvr_variant;
				}
				$nvr_variantText = ' (' . $nvr_variantCount . ' Variants' . ')';
				if ($nvr_variantCount <= 1) $nvr_variantText = '';
				$nvr_optGoogleFonts[ $nvr_google_item['family'] . ':' . $nvr_variants ] = $nvr_google_item['family']. $nvr_variantText;
			}
			
			$nvr_optArrBlog = array(
				'classic' => 'Classic',
				'2col-masonry' => '2 Columns Masonry',
				'3col-masonry' => '3 Columns Masonry'
			);
			
			$nvr_optCurPlace = array(
				'before' => __('Before', THE_LANG),
				'after' => __('After', THE_LANG)
			);
			
			$nvr_optPropUnit = array(
				'sq ft' => __('Square Feet', THE_LANG),
				'sq mtr' => __('Square Meters', THE_LANG)
			);
			
			$nvr_optArrSlider = array(
				'ASC' => 'Ascending',
				'DESC' => 'Descending'
				 );
			
			$nvr_optSliderEffect 	= array(
					'fade'=>'Fade',
					'slide'=>'Slide'
					 );
		
			// Background Defaults
			$nvr_background_defaults = array(
				'color' => '',
				'image' => '',
				'repeat' => 'repeat',
				'position' => 'top center',
				'attachment'=>'scroll'
			);
						 
			$nvr_optBackgroundStyle = array(
				'repeat' => "Repeat",
				'repeat-x' => "Repeat Horizontal",
				'repeat-y' => "Repeat Vertical",
				'no-repeat' => "No Repeat",
				'fixed' => "Fixed"
				);
				
			$nvr_optBackgroundPosition = array(
			'left' => "Left",
			'center' => "Center",
			'right' => "Right",
			'top left' => "Top",
			'top center' => "Top Center",
			'top right' => "Top Right",
			'bottom left' => "Bottom",
			'bottom center' => "Bottom Center",
			'bottom right' => "Bottom Right"
			);
			
			$nvr_selectTextDefault = array(
				'text' => '',
				'select' => ''
			);
			
			$nvr_selectTopBar = array(
				'nvrshowtopbar' => 'On',
				'nvrnotopbar' => 'Off'
			);
			
			$nvr_selectPaidSubm = array(
				'no' => __('No', THE_LANG),
				'per listing' => __('Per Listing', THE_LANG),
				'membership' => __('Membership', THE_LANG)
			);
			
			$nvr_optSocialIcons = array();
			
			if(function_exists('nvr_fontsocialicon')){
				$nvr_optSocialIcons = nvr_fontsocialicon();
			}
			
		
			// Pull all the categories into an array
			$nvr_options_categories = array();
			$nvr_options_categories_obj = get_categories();
			foreach ($nvr_options_categories_obj as $nvr_category) {
				$nvr_options_categories[$nvr_category->cat_ID] = $nvr_category->cat_name;
			}
		
			// Pull all the pages into an array
			$nvr_options_pages = array();
			$nvr_options_pages_obj = get_pages('sort_column=post_parent,menu_order');
			$nvr_options_pages[''] = 'Select a page:';
			foreach ($nvr_options_pages_obj as $nvr_page) {
				$nvr_options_pages[$nvr_page->ID] = $nvr_page->post_title;
			}
		
			// If using image radio buttons, define a directory path
			$nvr_imagepath =  get_template_directory_uri() . '/images/backendimage/';
			
			$nvr_optmainlayout = array(
				'one-col' => array('alt' => 'Full Width', 'img' => $nvr_imagepath . '1col.png'),
				'two-col-left' => array('alt' => '2 Column Left', 'img' => $nvr_imagepath . '2cl.png'),
				'two-col-right' => array('alt' => '2 Column Right',  'img' => $nvr_imagepath . '2cr.png')
			);
			
			
			
			$nvr_optfooterlayout = array(
				'0' => array('alt' => 'No Footer Sidebar',  'img' => $nvr_imagepath . 'footer-0.gif'),
				'1' => array('alt' => 'Footer Layout 1',  'img' => $nvr_imagepath . 'footer-1.gif'),
				'2' => array('alt' => 'Footer Layout 2',  'img' => $nvr_imagepath . 'footer-2.gif'),
				'3' => array('alt' => 'Footer Layout 3',  'img' => $nvr_imagepath . 'footer-3.gif'),
				'4' => array('alt' => 'Footer Layout 4',  'img' => $nvr_imagepath . 'footer-4.gif'),
				'5' => array('alt' => 'Footer Layout 5',  'img' => $nvr_imagepath . 'footer-5.gif'),
				'6' => array('alt' => 'Footer Layout 6',  'img' => $nvr_imagepath . 'footer-6.gif'),
				'7' => array('alt' => 'Footer Layout 7',  'img' => $nvr_imagepath . 'footer-7.gif'),
				'8' => array('alt' => 'Footer Layout 8',  'img' => $nvr_imagepath . 'footer-8.gif'),
				'9' => array('alt' => 'Footer Layout 9',  'img' => $nvr_imagepath . 'footer-9.gif'),
			);
			
			$nvr_curency_paypal = array(
				'USD' => 'USD',
				'EUR' => 'EUR',
				'AUD' => 'AUD',
				'BRL' => 'BRL',
				'CAD' => 'CAD',
				'CZK' => 'CZK',
				'DKK' => 'DKK',
				'HKD' => 'HKD',
				'HUF' => 'HUF',
				'ILS' => 'ILS',
				'JPY' => 'JPY',
				'MYR' => 'MYR',
				'MXN' => 'MXN',
				'NOK' => 'NOK',
				'NZD' => 'NZD',
				'PHP' => 'PHP',
				'PLN' => 'PLN',
				'RUB' => 'RUB',
				'GBP' => 'GBP',
				'SGD' => 'SGD',
				'SEK' => 'SEK',
				'CHF' => 'CHF',
				'TWD' => 'TWD',
				'THB' => 'THB',
				'TRY' => 'TRY'
			);
			$nvr_api_paypal = array(
				'sandbox' => 'Sandbox',
				'live' => 'Live'
			);
			
			// ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'title'     => __('General Settings', THE_LANG),
                'desc'      => __('', THE_LANG),
                'icon'      => 'el-icon-home',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(

                    array(
                        'id'        => $nvr_shortname."_sidebar_position",
                        'type'      => 'image_select',
                        'title'     => __('Column Layout', THE_LANG),
                        'subtitle'  => __('Select the default column layout. Default layout is Two Column Left.', THE_LANG),
						'options'   => $nvr_optmainlayout,
                        'default'   => 'two-col-left'
                    ),
					
					array(
                        'id'        => $nvr_shortname."_footer_sidebar_layout",
                        'type'      => 'image_select',
                        'title'     => __('Footer Sidebar Layout', THE_LANG),
                        'subtitle'  => __('Select footer sidebar layout. Default sidebar is four column.', THE_LANG),
						'options'   => $nvr_optfooterlayout,
                        'default'   => '9'
                    ),
					
					array(
                        'id'        => $nvr_shortname."_disable_viewport",
                        'type'      => 'checkbox',
                        'title'     => __('Disable Responsive Feature?', THE_LANG),
                        'subtitle'  => __('Select this checkbox to disable the responsive website feature.', THE_LANG),
                        'default'   => '0',
                    ),
					
					array(
                        'id'        => $nvr_shortname."_disable_retinadisplay",
                        'type'      => 'checkbox',
                        'title'     => __('Disable Retina Display Feature?', THE_LANG),
                        'subtitle'  => __('Select this checkbox to disable the retina display compatibility feature.', THE_LANG),
                        'default'   => '1',
                    ),
					
					array(
                        'id'        => $nvr_shortname."_logo_type",
                        'type'      => 'select',
                        'title'     => __('Logo Type', THE_LANG),
                        'subtitle'  => __('If text-based logo is activated, enter the logo name and logo tagline in the fields below.', THE_LANG),
                        
                        //Must provide key => value pairs for select options
                        'options'   => $nvr_optLogotype,
                        'default'   => 'imagelogo'
                    ),
					
					array(
                        'id'        => $nvr_shortname."_site_name",
                        'type'      => 'text',
                        'title'     => __('Logo Name', THE_LANG),
                        'subtitle'  => __('Put your logo name in here.', THE_LANG),
                        'default'   => ''
                    ),
					
					array(
                        'id'        => $nvr_shortname."_tagline",
                        'type'      => 'text',
                        'title'     => __('Logo Tagline', THE_LANG),
                        'subtitle'  => __('Put your tagline in here.', THE_LANG),
                        'default'   => ''
                    ),
					
					array(
                        'id'        => $nvr_shortname."_logo_image",
                        'type'      => 'media',
                        'title'     => __('Dark Logo Image', THE_LANG),
                        'compiler'  => 'true',
                        'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'subtitle'  => __('If image logo is activated, upload the logo image.', THE_LANG),
                    ),
					
					array(
                        'id'        => $nvr_shortname."_logo_image_light",
                        'type'      => 'media',
                        'title'     => __('Light Logo Image', THE_LANG),
                        'compiler'  => 'true',
                        'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'subtitle'  => __('If image logo is activated, upload the logo image.', THE_LANG),
                    ),
					
					array(
                        'id'        => $nvr_shortname."_logo_image_height",
                        'type'      => 'text',
						'validate'	=> 'numeric',
                        'title'     => __('Logo\'s height', THE_LANG),
                        'subtitle'  => __('You can change the logo\'s height in here and the width will follow automatically. Input the numbers only.', THE_LANG),
						'default'   => ''
                    ),
					
					array(
                        'id'        => $nvr_shortname."_favicon",
                        'type'      => 'media',
                        'title'     => __('Favicon', THE_LANG),
                        'compiler'  => 'true',
                        'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'subtitle'  => __('Upload the favicon image.', THE_LANG),
                    ),
					
					array(
                        'id'        => $nvr_shortname."_disable_footer_sidebar",
                        'type'      => 'checkbox',
                        'title'     => __('Disable Footer Sidebar?', THE_LANG),
                        'subtitle'  => __('Select this checkbox to disable footer sidebar feature.', THE_LANG),
                        'default'   => false,
                    ),
					
					array(
                        'id'            => $nvr_shortname."_enable_submission",
                        'type'          => 'switch',
						'on'			=> __('Enabled', THE_LANG),
						'off'			=> __('Disabled', THE_LANG),
                        'title'         => __('Enable Front-end Login?', THE_LANG),
                        'desc'      => __('switch to enable the login from the front end.', THE_LANG),
                        'default'       => '0'
                    ),
					
					array(
                        'id'        => $nvr_shortname."_loginlink",
                        'type'      => 'text',
                        'title'     => __('Login URL', THE_LANG),
                        'subtitle'  => __('please input the login url in here.', THE_LANG),
                        'validate'  => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
                        'default'   => ''
                    ),
					
					array(
                        'id'        => $nvr_shortname."_registerlink",
                        'type'      => 'text',
                        'title'     => __('Register URL', THE_LANG),
                        'subtitle'  => __('please input the login url in here.', THE_LANG),
                        'validate'  => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
                        'default'   => ''
                    ),
					
					array(
                        'id'        => $nvr_shortname."_footer",
                        'type'      => 'textarea',
                        'title'     => __('Footer Text', THE_LANG),
                        'subtitle'  => __('You can use html tag in here.', THE_LANG),
                        'validate'  => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
                        'default'   => ''
                    ),
					
					array(
                        'id'        => $nvr_shortname."_trackingcode",
                        'type'      => 'textarea',
                        'title'     => __('Tracking Code', THE_LANG),
                        'subtitle'  => __('You can use html tag in here.', THE_LANG),
                        'default'   => ''
                    ),
                )
            );
			
			$this->sections[] = array(
                'type' => 'divide',
            );
			
			$this->sections[] = array(
                'title'     => __('Style Settings', THE_LANG),
                'desc'      => __('', THE_LANG),
                'icon'      => 'el-icon-adjust-alt',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(

                    array(
                        'id'            => $nvr_shortname."_container_width",
                        'type'          => 'slider',
                        'title'         => __('Container\'s Width', THE_LANG),
                        'subtitle'      => __('You can change the global container\s width from here', THE_LANG),
                        'desc'          => __('Set the length of your container\'s width between 940px - 1200px', THE_LANG),
                        'default'       => 1170,
                        'min'           => 940,
                        'step'          => 1,
                        'max'           => 1200,
                        'display_value' => 'text'
                    ),
					
					array(
                        'id'        => $nvr_shortname."_web_layout",
                        'type'      => 'select',
                        'title'     => __('Theme Layout', THE_LANG),
                        'subtitle'  => __('Choose the layout style that suits your need', THE_LANG),
                        
                        //Must provide key => value pairs for select options
                        'options'   => $nvr_optWebLayout,
                        'default'   => 'nvrlayout1'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_topbar",
                        'type'          => 'select',
                        'title'         => __('Topbar', THE_LANG),
                        'desc'      	=> __('Choose whether to remove the topbar or not.', THE_LANG),
						'options'		=> $nvr_selectTopBar,
                        'default'       => 'nvrshowtopbar'
                    ),
					
					array(
                        'id'        => $nvr_shortname."_container_layout",
                        'type'      => 'select',
                        'title'     => __('Container Layout', THE_LANG),
                        'subtitle'  => __('Choose the location of your container layout.', THE_LANG),
                        
                        //Must provide key => value pairs for select options
                        'options'   => $nvr_optLayout,
                        'default'   => 'nvrfullwidth'
                    ),
					
					array(
                        'id'        => $nvr_shortname."_color_theme",
                        'type'      => 'color',
                        'title'     => __('Theme Color Options', THE_LANG),
                        'subtitle'  => __('Choose the color that suit your need', THE_LANG),
                        'default'   => '',
                        'validate'  => 'color'
                    ),
					
					array(
                        'id'        => $nvr_shortname."_general_font",
                        'type'      => 'typography',
                        'title'     => __('General Fonts', THE_LANG),
                        'subtitle'  => __('Specify the body font properties.', THE_LANG),
                        'google'    => true,
                        'default'   => array(
                            'color'         => '',
                            'font-size'     => '',
							'google'		=> true,
                            'font-family'   => 'Open Sans',
                            'font-weight'   => 'Normal',
                        ),
                    ),
					
					array(
                        'id'        => $nvr_shortname."_bigtext_font",
                         'type'      => 'typography',
                        'title'     => __('Bigtext Shortcode Fonts', THE_LANG),
                        'subtitle'  => __('Choose the font for [bigtext] shortcode.', THE_LANG),
						'google'    => true,
                        'default'   => array(
                            'color'         => '',
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => '',
                        ),
                    ),
					
					array(
                        'id'        => $nvr_shortname."_heading_font",
                         'type'      => 'typography',
                        'title'     => __('Heading Fonts', THE_LANG),
                        'subtitle'  => __('Choose the font styles for h1, h2, h3, h4, h5, h6.', THE_LANG),
						'font-size' => false,
						'line-height' => false,
						'text-align' => false,
						'google'    => true,
                        'default'   => array(
                            'color'         => '',
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => '',
                        ),
                    ),
					
					array(
                        'id'        => $nvr_shortname."_menunav_font",
                        'type'      => 'select',
                        'title'     => __('Menu Navigation Fonts', THE_LANG),
                        'subtitle'  => __('Choose the font for main menu.', THE_LANG),
						'options'   => $nvr_optGoogleFonts,
                        'default'   => ''
                    ),
					
					array(
                        'id'        => $nvr_shortname."_secondnav_font",
                        'type'      => 'select',
                        'title'     => __('Secondary Menu Navigation Fonts', THE_LANG),
                        'subtitle'  => __('Choose the font for secondary menu.', THE_LANG),
						'options'   => $nvr_optGoogleFonts,
                        'default'   => ''
                    ),
					
					array(
                        'id'        => $nvr_shortname."_secondary_font",
                         'type'      => 'typography',
                        'title'     => __('Secondary Fonts', THE_LANG),
                        'subtitle'  => __('Choose the font for your secondary font. You can use it by using "nvrsecondfont" class in your tag.', THE_LANG),
						'font-size' => false,
						'font-weight' => false,
						'line-height' => false,
						'text-align' => false,
						'google'    => true,
                        'default'   => array(
                            'color'         => '',
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => '',
                        ),
                    ),
					
					array(
                        'id'        => $nvr_shortname."_body_background",
                        'type'      => 'background',
                        'title'     => __('Background Settings', THE_LANG),
                        'subtitle'  => __('Change the background CSS.', THE_LANG),
                        //'default'   => '#FFFFFF',
                    ),
					
					array(
                        'id'        => $nvr_shortname."_header_background",
                        'type'      => 'background',
                        'title'     => __('Background Header', THE_LANG),
                        'subtitle'  => __('Change the background on header.', THE_LANG),
                        //'default'   => '#FFFFFF',
                    ),
					
					array(
                        'id'        => $nvr_shortname."_footer_background",
                        'type'      => 'background',
                        'title'     => __('Background Footer', THE_LANG),
                        'subtitle'  => __('Change the background on footer.', THE_LANG),
                        //'default'   => '#FFFFFF',
                    )
                )
            );
			
			$nvr_socialarr = array();
			foreach($nvr_optSocialIcons as $nvr_optSocialIcon => $nvr_optSocialText){
				$nvr_socialarr[] = array(
                        'id'        => $nvr_shortname."_socialicon_".$nvr_optSocialIcon,
                        'type'      => 'text',
                        'title'     => $nvr_optSocialText ." ". __('Icon', THE_LANG),
                        'subtitle'  => sprintf( __('Input your %s URL in here', THE_LANG), $nvr_optSocialText),
                        'default'   => ''
              	);	
			}
			
			$this->sections[] = array(
                'title'     => __('Social Network', THE_LANG),
                'desc'      => __('', THE_LANG),
                'icon'      => 'el-icon-network',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => $nvr_socialarr
            );
			
			$this->sections[] = array(
                'title'     => __('Property Settings', THE_LANG),
                'desc'      => __('', THE_LANG),
                'icon'      => 'el-icon-shopping-cart',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
					
					array(
                        'id'            => $nvr_shortname."_googlemaps_api_key",
                        'type'          => 'text',
                        'title'         => __('Google Maps API Key', THE_LANG),
                        'desc'      => __('The Google Maps JavaScript API v3 does not require an API key to function correctly. However, we strongly encourage you to get an APIs Console key and post the code in Theme Options. You can get it from : ', THE_LANG).'<a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key" target="_blank">https://developers.google.com/maps/documentation/javascript/tutorial#api_key</a>',
                        'default'       => ''
                    ),
					array(
                        'id'            => $nvr_shortname."_default_latitude",
                        'type'          => 'text',
                        'title'         => __('Google Maps Default Latitude', THE_LANG),
                        'desc'      	=> __('Please set the default latitude.', THE_LANG),
                        'default'       => '51.507167'
                    ),
					array(
                        'id'            => $nvr_shortname."_default_longitude",
                        'type'          => 'text',
                        'title'         => __('Google Maps Default Longitude', THE_LANG),
                        'desc'      	=> __('Please set the default longitude.', THE_LANG),
                        'default'       => '-0.127997'
                    ),
					array(
                        'id'            => $nvr_shortname."_default_zoom",
                        'type'          => 'text',
                        'title'         => __('Google Maps Default Zoom', THE_LANG),
                        'desc'      	=> __('Please set the default zoom.', THE_LANG),
                        'default'       => '15'
                    ),
					array(
                        'id'            => $nvr_shortname."_currency_symbol",
                        'type'          => 'text',
                        'title'         => __('Currency Symbol', THE_LANG),
                        'desc'      	=> __('Please set the currency that you use.', THE_LANG),
                        'default'       => '$'
                    ),
					array(
                        'id'            => $nvr_shortname."_currency_place",
                        'type'          => 'select',
                        'title'         => __('Currency Placement', THE_LANG),
                        'desc'      	=> __('Where to show the currency symbol?', THE_LANG),
						'options'   	=> $nvr_optCurPlace,
                        'default'       => 'before'
                    ),
					array(
                        'id'            => $nvr_shortname."_measurement_unit",
                        'type'          => 'select',
                        'title'         => __('Measurement Unit', THE_LANG),
                        'desc'      	=> __('What is the unit that will be displayed?', THE_LANG),
						'options'   	=> $nvr_optPropUnit,
                        'default'       => 'sq mtr'
                    ),
					array(
                        'id'            => $nvr_shortname."_property_status",
                        'type'          => 'multi_text',
                        'title'         => __('Property Status', THE_LANG),
                        'desc'     		=> __('You can add the property status in here.', THE_LANG),
                        'default'       => ''
                    ),
					array(
                        'id'            => $nvr_shortname."_property_amenities",
                        'type'          => 'multi_text',
                        'title'         => __('Property Features &amp; Amenities', THE_LANG),
                        'desc'      => __('You can add a new features or amenities for the property in here.', THE_LANG),
                        'default'       => ''
                    ),
					array(
                        'id'            => $nvr_shortname."_property_custom",
                        'type'          => 'multi_text',
                        'title'         => __('Property Custom Field', THE_LANG),
                        'desc'      => __('You can add a new field for the property in here.', THE_LANG),
                        'default'       => ''
                    ),
					array(
                        'id'            => $nvr_shortname."_pin_cluster",
                        'type'          => 'checkbox',
                        'title'         => __('Use Pin Cluster?', THE_LANG),
                        'desc'      => __('If you tick this checkbox, the google maps will group the pin location at certain distance.', THE_LANG),
                        'default'       => '0'
                    ),
					array(
                        'id'            => $nvr_shortname."_maxzoom_cluster",
                        'type'          => 'text',
                        'title'         => __('Zoom Level Cluster', THE_LANG),
                        'desc'      	=> __('Please set the maximum zoom level of the cloud cluster to be shown in your maps.', THE_LANG),
                        'default'       => ''
                    ),
					array(
                        'id'            => $nvr_shortname."_enable_geolocation",
                        'type'          => 'checkbox',
                        'title'         => __('Enable Geolocation?', THE_LANG),
                        'desc'      => __('If you tick this checkbox, the google maps will use geolocation.', THE_LANG),
                        'default'       => '0'
                    ),
					array(
                        'id'            => $nvr_shortname."_geolocation_radius",
                        'type'          => 'text',
                        'title'         => __('Geolocation Radius', THE_LANG),
                        'desc'      	=> __('Please set the geolocation radius over map.', THE_LANG),
                        'default'       => ''
                    ),
					array(
                        'id'            => $nvr_shortname."_minprice",
                        'type'          => 'text',
                        'title'         => __('Minimum Price in the Search Form', THE_LANG),
                        'desc'      	=> __('Please set the minimum price in search form.', THE_LANG),
                        'default'       => '0'
                    ),
					array(
                        'id'            => $nvr_shortname."_maxprice",
                        'type'          => 'text',
                        'title'         => __('Maximum Price in the Search Form', THE_LANG),
                        'desc'      	=> __('Please set the maximum price in search form.', THE_LANG),
                        'default'       => '1000000'
                    )
                )
            );
			
			$this->sections[] = array(
                'title'     => __('Membership and Payment Settings', THE_LANG),
                'desc'      => __('', THE_LANG),
                'icon'      => 'el-icon-shopping-cart',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
					
					array(
                        'id'            => $nvr_shortname."_paid_submission",
                        'type'          => 'select',
                        'title'         => __('Enable Paid Submission?', THE_LANG),
                        'desc'      	=> __('Please choose the paid submission system that suit your need.', THE_LANG),
						'options'   	=> $nvr_selectPaidSubm,
                        'default'       => 'no'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_enable_paypal",
                        'type'          => 'switch',
						'on'			=> __('Enabled', THE_LANG),
						'off'			=> __('Disabled', THE_LANG),
                        'title'         => __('Enable Paypal?', THE_LANG),
                        'desc'      => __('switch to enable the paid submission using paypal', THE_LANG),
                        'default'       => '0'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_enable_stripe",
                        'type'          => 'switch',
						'on'			=> __('Enabled', THE_LANG),
						'off'			=> __('Disabled', THE_LANG),
                        'title'         => __('Enable Stripe?', THE_LANG),
                        'desc'      => __('switch to enable the paid submission using stripe', THE_LANG),
                        'default'       => '0'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_admin_submission",
                        'type'          => 'switch',
						'on'			=> __('Yes', THE_LANG),
						'off'			=> __('No', THE_LANG),
                        'title'         => __('Need Administrator approval for submitted listing?', THE_LANG),
                        'desc'      => __('switch to Yes if you want the submitted listing should be approved by administrator.', THE_LANG),
                        'default'       => '0'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_user_agent",
                        'type'          => 'switch',
						'on'			=> __('Yes', THE_LANG),
						'off'			=> __('No', THE_LANG),
                        'title'         => __('Front end registered users will automatically become an agent?', THE_LANG),
                        'desc'      => __('switch to Yes if you want the registered user become an agent automatically.', THE_LANG),
                        'default'       => '0'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_price_submission",
                        'type'          => 'text',
                        'title'         => __('Price per Submission', THE_LANG),
                        'desc'      => __('please input the price per submission.', THE_LANG),
                        'default'       => '0'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_price_featured_submission",
                        'type'          => 'text',
                        'title'         => __('Price to make featured listings', THE_LANG),
                        'desc'      => __('please input the price to make featured listings.', THE_LANG),
                        'default'       => '0'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_submission_currency",
                        'type'          => 'select',
                        'title'         => __('Currency for payment?', THE_LANG),
                        'desc'      	=> __('Please choose the currency for payment using paypal or stripe.', THE_LANG),
						'options'   	=> $nvr_curency_paypal,
                        'default'       => 'USD'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_paypal_client_id",
                        'type'          => 'text',
                        'title'         => __('Paypal Client ID', THE_LANG),
                        'desc'      	=> __('Please input the paypal client ID.', THE_LANG),
                        'default'       => ''
                    ),
					
					array(
                        'id'            => $nvr_shortname."_paypal_client_secret",
                        'type'          => 'text',
                        'title'         => __('Paypal Client Secret Key', THE_LANG),
                        'desc'      	=> __('Please input the paypal client Secret Key.', THE_LANG),
                        'default'       => ''
                    ),
					
					array(
                        'id'            => $nvr_shortname."_paypal_api",
                        'type'          => 'select',
                        'title'         => __('Paypal API', THE_LANG),
                        'desc'      	=> __('Please choose the paypal API that you want to us.', THE_LANG),
						'options'   	=> $nvr_api_paypal,
                        'default'       => ''
                    ),
					
					array(
                        'id'            => $nvr_shortname."_paypal_api_username",
                        'type'          => 'text',
                        'title'         => __('Paypal API Username', THE_LANG),
                        'desc'      	=> __('Please input the paypal api username.', THE_LANG),
                        'default'       => ''
                    ),
					
					array(
                        'id'            => $nvr_shortname."_paypal_api_password",
                        'type'          => 'text',
                        'title'         => __('Paypal API Password', THE_LANG),
                        'desc'      	=> __('Please input the paypal api password.', THE_LANG),
                        'default'       => ''
                    ),
					
					array(
                        'id'            => $nvr_shortname."_paypal_api_signature",
                        'type'          => 'text',
                        'title'         => __('Paypal API Signature', THE_LANG),
                        'desc'      	=> __('Please input the paypal api signature.', THE_LANG),
                        'default'       => ''
                    ),
					
					array(
                        'id'            => $nvr_shortname."_paypal_rec_email",
                        'type'          => 'text',
                        'title'         => __('Paypal Receiving Email', THE_LANG),
                        'desc'      	=> __('Please input the paypal receiving email.', THE_LANG),
                        'default'       => ''
                    ),
					
					array(
                        'id'            => $nvr_shortname."_stripe_secret_key",
                        'type'          => 'text',
                        'title'         => __('Stripe Secret Key', THE_LANG),
                        'desc'      	=> __('Please input the stripe secret key.', THE_LANG),
                        'default'       => ''
                    ),
					
					array(
                        'id'            => $nvr_shortname."_stripe_publishable_key",
                        'type'          => 'text',
                        'title'         => __('Stripe Publishable Key', THE_LANG),
                        'desc'      	=> __('Please input the stripe publishable key.', THE_LANG),
                        'default'       => ''
                    ),
					
					array(
                        'id'            => $nvr_shortname."_free_mem_list",
                        'type'          => 'text',
                        'title'         => __('Number of Free Membership listing', THE_LANG),
                        'desc'      	=> __('Please input the number of listing that is allowed for free membership. Put -1 if you want to make it unlimited', THE_LANG),
                        'default'       => ''
                    ),
					
					array(
                        'id'            => $nvr_shortname."_free_feat_list",
                        'type'          => 'text',
                        'title'         => __('Number of Free Membership Featured Listing', THE_LANG),
                        'desc'      	=> __('Please input the number of featured listing that is allowed for free membership. Put -1 if you want to make it unlimited', THE_LANG),
                        'default'       => ''
                    ),
				)
            );
			
			$this->sections[] = array(
                'title'     => __('Blog Settings', THE_LANG),
                'desc'      => __('', THE_LANG),
                'icon'      => 'el-icon-book',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(

                    array(
                        'id'            => $nvr_shortname."_blog_layout",
                        'type'          => 'select',
                        'title'         => __('Blog Layouts', THE_LANG),
                        'subtitle'      => __('Select the default layout for your blog page.', THE_LANG),
                        'default'       => 'classic',
						'options'   	=> $nvr_optArrBlog
                    ),
					
					array(
                        'id'            => $nvr_shortname."_blog_infscrolls",
                        'type'          => 'checkbox',
                        'title'         => __('Use Infinite Scrolls?', THE_LANG),
                        'desc'      => __('If you tick this checkbox, the blog page will replace the pagination with Infinite Scrolls ("Load More" Button).', THE_LANG),
                        'default'       => '0'
                    )
                )
            );
			
			$this->sections[] = array(
                'title'     => __('Slider Settings', THE_LANG),
                'desc'      => __('', THE_LANG),
                'icon'      => 'el-icon-website',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(

                    array(
                        'id'            => $nvr_shortname."_slider_arrange",
                        'type'          => 'select',
                        'title'         => __('Arrange Slider Post', THE_LANG),
                        'subtitle'      => __('Select the order for your slider. the default is Ascending', THE_LANG),
                        'default'       => 'ASC',
						'options'   	=> $nvr_optArrSlider
                    ),
					
					array(
                        'id'            => $nvr_shortname."_slider_effect",
                        'type'          => 'select',
                        'title'         => __('Slider Effect', THE_LANG),
                        'subtitle'      => __('Please select transition effect. The default is fade', THE_LANG),
                        'default'       => 'fade',
						'options'   	=> $nvr_optSliderEffect
                    ),
					
					array(
                        'id'        => $nvr_shortname."_slider_interval",
                        'type'      => 'text',
                        'title'     => __('Slider Interval', THE_LANG),
                        'subtitle'  => __('Please enter number for slider interval. Default is 8000', THE_LANG),
                        'default'   => '8000'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_slider_disable_text",
                        'type'          => 'checkbox',
                        'title'         => __('Disable Slider Text', THE_LANG),
                        'desc'      => __('Select this checkbox to disable the slider text.', THE_LANG),
                        'default'       => '0'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_slider_disable_nav",
                        'type'          => 'checkbox',
                        'title'         => __('Disable Slider Navigation', THE_LANG),
                        'desc'      => __('Select this checkbox to disable navigation.', THE_LANG),
                        'default'       => '0'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_slider_disable_prevnext",
                        'type'          => 'checkbox',
                        'title'         => __('Disable Slider Previous/Next Navigation', THE_LANG),
                        'desc'      => __('Select this checkbox to disable previous/next navigation.', THE_LANG),
                        'default'       => '0'
                    )
                )
            );
			
			$this->sections[] = array(
                'title'     => __('Miscellaneous', THE_LANG),
                'desc'      => __('', THE_LANG),
                'icon'      => ' el-icon-asterisk',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(

                    array(
                        'id'            => $nvr_shortname."_headertext",
                        'type'          => 'textarea',
                        'title'         => __('Header Text', THE_LANG),
                        'desc'      	=> __('Please input your own header text. (it will be placed on top of the logo).', THE_LANG),
                        'default'       => ''
                    ),
					
					array(
                        'id'            => $nvr_shortname."_headertext2",
                        'type'          => 'textarea',
                        'title'         => __('Secondary Header Text', THE_LANG),
                        'desc'      	=> __('Please input your own secondary header text. (it will be placed on top of the logo and on the right side of primary header text).', THE_LANG),
                        'default'       => ''
                    ),
					
					array(
                        'id'            => $nvr_shortname."_disable_minicart",
                        'type'          => 'checkbox',
                        'title'         => __('Disable Minicart', THE_LANG),
                        'desc'      	=> __('Select this checkbox to disable minicart at the top.', THE_LANG),
                        'default'       => '0'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_disable_topsearch",
                        'type'          => 'checkbox',
                        'title'         => __('Disable Top Search', THE_LANG),
                        'desc'      	=> __('Select this checkbox to disable searchbox at the top.', THE_LANG),
                        'default'       => '0'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_enable_aftercontent",
                        'type'          => 'checkbox',
                        'title'         => __('Enable After Content Section', THE_LANG),
                        'desc'      	=> __('Select this checkbox to enable the After Content Section.', THE_LANG),
                        'default'       => '0'
                    ),
					
					array(
                        'id'            => $nvr_shortname."_aftercontent_background",
                        'type'          => 'background',
                        'title'         => __('Background After Content Section', THE_LANG),
                        'desc'     		=> __('Change the background on after content section.', THE_LANG),
                        'default'       => ''
                    ),
					
					array(
                        'id'            => $nvr_shortname."_aftercontent_text",
                        'type'          => 'textarea',
                        'title'         => __('After Content Text', THE_LANG),
                        'desc'      	=> __('you can use html tag in here.', THE_LANG),
                        'default'       => ''
                    ),
					
					array(
                        'id'            => $nvr_shortname."_demo_mode",
                        'type'          => 'checkbox',
                        'title'         => __('Demo Mode', THE_LANG),
                        'desc'      	=> __('For demonstration purpose only.', THE_LANG),
                        'default'       => '0'
                    )
                )
            );
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', THE_LANG),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', THE_LANG)
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', THE_LANG),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', THE_LANG)
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', THE_LANG);
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'nvr_option',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => $theme->get('Name'),     // Name that appears at the top of your panel
                'display_version'   => $theme->get('Version'),  // Version that appears at the top of your panel
                'menu_type'         => 'menu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title'        => __('Theme Options', THE_LANG),
                'page_title'        => __('Theme Options', THE_LANG),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => 'AIzaSyBYxSvnMu_mABEzbRRUcGRxNTZdAnz1Rgo', // Must be defined to add google fonts to the typography module
                
                'async_typography'  => false,                    // Use a asynchronous font on the front end or font string
                'admin_bar'         => true,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => true,                    // Enable basic customizer support
                
                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'themes.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                      // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => '_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => true,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,                   // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE

                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );


            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
                'title' => 'Visit us on GitHub',
                'icon'  => 'el-icon-github'
                //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/reduxframework',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://www.linkedin.com/company/redux-framework',
                'title' => 'Find us on LinkedIn',
                'icon'  => 'el-icon-linkedin'
            );

            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace('-', '_', $this->args['opt_name']);
                }
                //$this->args['intro_text'] = sprintf(__('<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', THE_LANG), $v);
            } else {
                $this->args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', THE_LANG);
            }

            // Add content after the form.
           // $this->args['footer_text'] = __('<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', THE_LANG);
        }

    }
    
    global $reduxConfig;
    $reduxConfig = new Redux_Framework_sample_config();
}
<?php
function nvr_script() {
	if (!is_admin()) {

		wp_enqueue_script('jquery');
		
		$nvr_shortname = THE_SHORTNAME;
		$nvr_initial = THE_INITIAL;
		$nvr_siteurl = THE_SITEURL;
		$nvr_adminurl = THE_ADMINURL;
		$nvr_themeurl = THE_STYLEURI;
		
		global $current_user;
		get_currentuserinfo();
		
		$nvr_pid = nvr_get_postid();
		$nvr_custom = nvr_get_customdata($nvr_pid);
		
		if( is_page_template('template-dashboard-profile.php') || is_page_template('template-dashboard-add.php')  ){
             wp_enqueue_script('plupload-handlers');
    	}
		
		wp_register_script('jeasing', THE_JSURI .'jquery.easing.js', array('jquery'), '1.2', true);
		wp_enqueue_script('jeasing');
		
		wp_register_script('jcolor', THE_JSURI .'jquery.color.js', array('jquery'), '2.0', true);
		wp_enqueue_script('jcolor');
		
		wp_register_script('jcookie', THE_JSURI .'jquery.cookie.js', array('jquery'), '1.0', true);
		if(nvr_get_option( THE_SHORTNAME . '_enable_switcher') ){
			wp_enqueue_script('jcookie');
		}
		
		wp_register_script('modernizr', THE_JSURI .'modernizr.js', array('jquery'), '2.5.3');
		wp_enqueue_script('modernizr');
		
		wp_register_script('jappear', THE_JSURI .'appear.js', array('jquery'), '1.0', true);
		wp_enqueue_script('jappear');
		
		wp_register_script('jparallax', THE_JSURI .'jquery.parallax-1.1.3.js', array('jquery'), '1.1.3', true);
		wp_enqueue_script('jparallax');
		
		wp_register_script('jisotope', THE_JSURI .'jquery.isotope.min.js', array('jquery'), '1.0', true);
		wp_enqueue_script('jisotope');
		
		wp_register_script('jCountTo', THE_JSURI .'jquery.countTo.js', array('jquery'), '1.0', true);
		wp_enqueue_script('jCountTo');
		
		wp_register_script('infinite-scroll', THE_JSURI .'jquery.infinitescroll.js', array('jquery'), '2.0b2', true);
		wp_enqueue_script('infinite-scroll');
		
		wp_register_script('jprettyPhoto', THE_JSURI .'jquery.prettyPhoto.js', array('jquery'), '3.0', true);
		wp_enqueue_script('jprettyPhoto');
		
		wp_register_script('jsuperfish', THE_JSURI .'superfish.js', array('jquery'), '1.4.8', true);
		wp_enqueue_script('jsuperfish');
		
		wp_register_script('jsupersubs', THE_JSURI .'supersubs.js', array('jquery'), '0.2', true);
		wp_enqueue_script('jsupersubs');
		
		wp_register_script('jPerfectScrollbar', THE_JSURI .'perfect-scrollbar.with-mousewheel.min.js', array('jquery'), '0.4.9', true);
		wp_enqueue_script('jPerfectScrollbar');
		
		wp_register_script('jImagesLoaded', THE_JSURI .'imagesloaded.pkgd.min.js', array('jquery'), '3.0.4', true);
		wp_enqueue_script('jImagesLoaded');
		
		wp_register_script('jflexslider', THE_JSURI .'jquery.flexslider-min.js', array('jquery'), '1.8', true);
		wp_enqueue_script('jflexslider');
		
		$nvr_dis_retina = nvr_get_option(THE_SHORTNAME . '_disable_retinadisplay');
		
		wp_register_script('jretina', THE_JSURI .'retina.min.js', array('jquery'), '1.1.0', true);
		if(!$nvr_dis_retina){
			wp_enqueue_script('jretina');
		}
		
		wp_register_script('jnouislider', THE_JSURI .'jquery.nouislider.all.min.js', array('jquery'), '1.0', true);
		wp_enqueue_script('jnouislider');
		
		$nvr_cf_sliderType 		= (isset($nvr_custom["slider_type"][0]))? $nvr_custom["slider_type"][0] : "";
		if(is_singular('peoplepost') || is_singular('propertys')){
			$nvr_cf_sliderType = 'mapslider';
		}
		
		$nvr_gmapsapikey = nvr_get_option( $nvr_shortname . '_googlemaps_api_key' );
		wp_register_script('googlemapsapi', 'http://maps.googleapis.com/maps/api/js?key='.esc_html( $nvr_gmapsapikey ).'&amp;sensor=true', array('jquery'), '1.0', true);
		wp_register_script('gmapsfrontend', THE_JSURI .'googlescript/google_frontend.js', array('jquery'), '1.0', true);
		wp_register_script('ginfobox', THE_JSURI .'googlescript/infobox.js', array('jquery'), '1.0', true);
		wp_register_script('gmapsextended', THE_JSURI .'googlescript/gmapsextended.js', array('jquery'), '1.0', true);
		wp_register_script('gmarkclusterer', THE_JSURI .'googlescript/markerclusterer.min.js', array('jquery'), '1.0', true);
		
		if($nvr_cf_sliderType=='mapslider'){
		
			$nvr_gmapsLatitude = nvr_get_option( $nvr_shortname . '_default_latitude' ,'51.5227676'); 
			$nvr_gmapsLongitude = nvr_get_option( $nvr_shortname . '_default_longitude' ,'-0.114644912');
			$nvr_gmapsZoom = nvr_get_option( $nvr_shortname . '_default_zoom');
			$nvr_gmapsFilter = nvr_get_option( $nvr_shortname . '_filter_map');
			$nvr_gmapsPincluster = nvr_get_option( $nvr_shortname . '_pin_cluster');
			$nvr_gmapsMaxZoomCluster = nvr_get_option( $nvr_shortname . '_maxzoom_cluster');
			
			$nvr_markerquery = nvr_property_mapquery();
			$nvr_markers = nvr_json_encode(nvr_property_latlng($nvr_markerquery));
			
			$nvr_gmapsvar = array( 
				'gmaps_latitude' 			=> $nvr_gmapsLatitude,
				'gmaps_longitude' 			=> $nvr_gmapsLongitude,
				'enable_pincluster' 		=>  $nvr_gmapsPincluster,
				'maxzoom_cluster' 			=>  $nvr_gmapsMaxZoomCluster,
				'gmaps_zoom' 				=> $nvr_gmapsZoom,
				'gmaps_filter' 				=> $nvr_gmapsFilter,
				'gmaps_markers'				=> $nvr_markers
			);
			
			
			wp_enqueue_script('googlemapsapi');
			wp_enqueue_script('gmapsfrontend');
			
			wp_localize_script( 'gmapsfrontend', 'gmaps_var', $nvr_gmapsvar );
			
			wp_enqueue_script('ginfobox');
			wp_enqueue_script('gmapsextended');
			
			$nvr_gmapsUseGeo = nvr_get_option( $nvr_shortname . '_enable_geolocation');
			$nvr_gmapsGeoRadius = nvr_get_option( $nvr_shortname . '_geolocation_radius');
			
			$nvr_gmapsextendvar = array( 
				'enable_geolocation'	=>  $nvr_gmapsUseGeo,
				'geolocation_radius'	=>  $nvr_gmapsGeoRadius,
				'in_text'           	=>  __('in', THE_LANG),
				'radius'           		=>  __('radius', THE_LANG)
			);
			wp_localize_script( 'gmapsextended', 'gmapsextended_var', $nvr_gmapsextendvar );
			
			wp_enqueue_script('gmarkclusterer');
			
		}
		
		wp_register_script('ajax-upload', THE_JSURI .'ajax-upload.js', array('jquery','plupload-handlers'), '1.0', true);
		wp_register_script('gmapsadmin', THE_JSURI .'googlescript/google_admin.js', array('jquery'), '1.0', true);
		
		if( is_page_template('template-dashboard-add.php') || is_page_template('template-dashboard-profile.php') ){
   			
			wp_enqueue_script('googlemapsapi');
			wp_enqueue_script('gmapsadmin');
			
			$nvr_gmapsLatitude = nvr_get_option( $nvr_shortname . '_default_latitude' ,'51.5227676'); 
			$nvr_gmapsLongitude = nvr_get_option( $nvr_shortname . '_default_longitude' ,'-0.114644912');
			$nvr_gmapsZoom = nvr_get_option( $nvr_shortname . '_default_zoom');
			
			$gmaps_latitude = $nvr_gmapsLatitude;
			$gmaps_longitude = $nvr_gmapsLongitude;
			$gmaps_zoom = $nvr_gmapsZoom;
			
			if(isset($nvr_custom[$nvr_initial."_latitude"][0]) && $nvr_custom[$nvr_initial."_latitude"][0]!=''){
				$gmaps_latitude = $nvr_custom[$nvr_initial."_latitude"][0];
			}
			if(isset($nvr_custom[$nvr_initial."_longitude"][0]) && $nvr_custom[$nvr_initial."_longitude"][0]!=''){
				$gmaps_longitude = $nvr_custom[$nvr_initial."_longitude"][0];
			}
			
			
			$nvr_interfeisvar = array( 
				'siteurl' 					=> $nvr_siteurl, 
				'adminurl' 					=> $nvr_adminurl,
				'themeurl'					=> $nvr_themeurl,
				'gmaps_latitude'			=> $gmaps_latitude,
				'gmaps_longitude'			=> $gmaps_longitude
			);
			wp_localize_script( 'gmapsadmin', 'admin_interfeis_var', $nvr_interfeisvar );
			
			$max_file_size = 100 * 1000 * 1000;
			wp_enqueue_script('ajax-upload');
			wp_localize_script('ajax-upload', 'ajax_vars', 
				array(  
					'ajaxurl'           => admin_url('admin-ajax.php'),
					'nonce'             => wp_create_nonce('aaiu_upload'),
					'remove'            => wp_create_nonce('aaiu_remove'),
					'number'            => 1,
					'upload_enabled'    => true,
					'confirmMsg'        => __('Are you sure you want to delete this?', THE_LANG),
					'plupload'          => array(
						'runtimes' => 'html5,flash,html4',
						'browse_button' => 'aaiu-uploader',
						'container' => 'aaiu-upload-container',
						'file_data_name' => 'aaiu_upload_file',
						'max_file_size' => $max_file_size . 'b',
						'url' => admin_url('admin-ajax.php') . '?action=me_upload&nonce=' . wp_create_nonce('aaiu_allow'),
						'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
						'filters' => array(
							array(
								'title' => __('Allowed Files', THE_LANG), 
								'extensions' => "jpg,gif,png"
							)
						),
						'multipart' => true,
						'urlstream_upload' => true,
					)
				)
			);

		}
		
		wp_register_script('jcustom', THE_JSURI .'custom.js', array('jquery'), '1.0', true);
		wp_enqueue_script('jcustom');
		
		$nvr_sliderEffect = nvr_get_option( $nvr_shortname . '_slider_effect' ,'fade'); 
		$nvr_sliderInterval = nvr_get_option( $nvr_shortname . '_slider_interval' ,600);
		$nvr_sliderDisableNav = nvr_get_option( $nvr_shortname . '_slider_disable_nav');
		$nvr_sliderDisablePrevNext = nvr_get_option( $nvr_shortname . '_slider_disable_prevnext');
		$nvr_minprice = nvr_get_option( $nvr_shortname . '_min_price','0');
		$nvr_maxprice = nvr_get_option( $nvr_shortname . '_max_price','1000000');
		
		$nvr_domainFormLink = nvr_get_option( $nvr_shortname . '_domainform_link','');
		if(!$nvr_domainFormLink){
			$nvr_domainFormLink = $nvr_siteurl;
		}
		
		$nvr_interfeisvar = array( 
			'siteurl' 					=> $nvr_siteurl, 
			'userid'					=> $current_user->ID,
			'adminurl' 					=> $nvr_adminurl,
			'themeurl'					=> $nvr_themeurl,
			'pfloadmore'				=> __('Loading More Portfolio', THE_LANG),
			'postloadmore'				=> __('Loading More Posts', THE_LANG),
			'loadfinish'				=> __('All Items are Loaded', THE_LANG),
			'sendingtext'				=> __('Sending...', THE_LANG),
			'slidereffect' 				=> $nvr_sliderEffect,
			'slider_interval' 			=> $nvr_sliderInterval,
			'slider_disable_nav' 		=> $nvr_sliderDisableNav,
			'slider_disable_prevnext' 	=> $nvr_sliderDisablePrevNext,
			'search_minprice'			=> $nvr_minprice,
			'search_maxprice'			=> $nvr_maxprice,
			'domainformlink'			=> $nvr_domainFormLink
		);
		wp_localize_script( 'jcustom', 'interfeis_var', $nvr_interfeisvar );
		
		wp_enqueue_script( 'wc-add-to-cart-variation');
		
	}
}
add_action('wp_enqueue_scripts', 'nvr_script');

function nvr_admin_script(){
	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	$nvr_siteurl = THE_SITEURL;
	$nvr_adminurl = THE_ADMINURL;
	$nvr_themeurl = THE_STYLEURI;
	
	global $pagenow, $typenow, $post;
	if (empty($typenow) && !empty($_GET['post'])) {
        $post = get_post($_GET['post']);
        $typenow = $post->post_type;
    }
	
	if(is_admin() && $pagenow=='post-new.php' || $pagenow=='post.php' && $typenow=='propertys'){
		
		$nvr_pid = $post->ID;
		$nvr_custom = nvr_get_customdata($nvr_pid);
		
		$gmapsapikey = nvr_get_option( $nvr_shortname . '_googlemaps_api_key' );
		wp_register_script('googlemapsapi', 'http://maps.googleapis.com/maps/api/js?key='.esc_html( $gmapsapikey ).'&amp;sensor=true', array('jquery'), '1.0', true);
		wp_enqueue_script('googlemapsapi');
		
		wp_register_script('jinfobox', THE_JSURI .'googlescript/infobox.js', array('jquery'), '1.1.5', true);
		wp_enqueue_script('jinfobox');
		
		wp_register_script('gmapsadmin', THE_JSURI .'googlescript/google_admin.js', array('jquery'), '1.0', true);
		wp_enqueue_script('gmapsadmin');
		
		$nvr_gmapsLatitude = nvr_get_option( $nvr_shortname . '_default_latitude' ,'51.5227676'); 
		$nvr_gmapsLongitude = nvr_get_option( $nvr_shortname . '_default_longitude' ,'-0.114644912');
		$nvr_gmapsZoom = nvr_get_option( $nvr_shortname . '_default_zoom');
		
		$gmaps_latitude = $nvr_gmapsLatitude;
		$gmaps_longitude = $nvr_gmapsLongitude;
		$gmaps_zoom = $nvr_gmapsZoom;
		
		if(isset($nvr_custom[$nvr_initial."_latitude"][0]) && $nvr_custom[$nvr_initial."_latitude"][0]!=''){
			$gmaps_latitude = $nvr_custom[$nvr_initial."_latitude"][0];
		}
		if(isset($nvr_custom[$nvr_initial."_longitude"][0]) && $nvr_custom[$nvr_initial."_longitude"][0]!=''){
			$gmaps_longitude = $nvr_custom[$nvr_initial."_longitude"][0];
		}
		
		
		$nvr_interfeisvar = array( 
			'siteurl' 					=> $nvr_siteurl, 
			'adminurl' 					=> $nvr_adminurl,
			'themeurl'					=> $nvr_themeurl,
			'gmaps_latitude'			=> $gmaps_latitude,
			'gmaps_longitude'			=> $gmaps_longitude
		);
		wp_localize_script( 'gmapsadmin', 'admin_interfeis_var', $nvr_interfeisvar );
	}
}
add_action('admin_enqueue_scripts', 'nvr_admin_script');
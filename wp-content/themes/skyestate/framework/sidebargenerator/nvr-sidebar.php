<?php
if( ! function_exists("nvr_sidebar_admin")){
	function nvr_sidebar_admin(){
		$nvr_submenu_slug = 'nvr-themesidebar';
		$nvr_shortname = THE_SHORTNAME;
		
		$nvr_optionstheme = array();
		
		$nvr_optionstheme['sidebar'] = array (
			
			array ( "name" => __("Sidebar Manager",THE_LANG), 
					"type" => "open"),
			
			array(	"name" => __('Sidebar', THE_LANG),
										"type" => "heading",
										"desc" => __('', THE_LANG)),
			
			array( 	"name" => __('Sidebar Generator', THE_LANG),
										"desc" => __('Please enter name of new sidebar', THE_LANG),
										"id" => $nvr_shortname."_sidebar",
										"std" => "fade",
										"type" => "textarray"),
			
			array(	"type" 	=> "close"),
		);
	
		nvr_form_admin($nvr_optionstheme['sidebar'], $nvr_submenu_slug);
	}
}

if ( ! function_exists( 'nvr_sidebargen_menu' ) ) {
	function nvr_sidebargen_menu(){
		
		$nvr_submenu_slug = "nvr-themesidebar";
		$nvr_submenu_function = "nvr_sidebar_admin";
		add_theme_page( __('Sidebar Manager',THE_LANG), __('Sidebar Manager',THE_LANG), 'edit_themes', $nvr_submenu_slug, $nvr_submenu_function);
		
	}
	add_action('admin_menu', 'nvr_sidebargen_menu');
}
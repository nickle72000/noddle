<?php

$nvr_func_path = THE_FRAMEWORKPATH . 'functions/';
require_once($nvr_func_path. "metaboxes/metaboxes.php");

if ( !function_exists( 'nvr_functions_init' ) ) {

	function nvr_functions_init() {		
		// Load the required CSS and javscript
		add_action('admin_enqueue_scripts', 'nvr_load_scripts');
	}
	add_action('admin_init','nvr_functions_init');
}

/* Loads the javascript */
function nvr_load_scripts($hook) {
	// Enqueued scripts
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('nvr-metaboxes-js', THE_FRAMEWORKURI .'functions/metaboxes/metaboxes.js', array('jquery'));
	
	wp_enqueue_style('nvr-metaboxes-css', THE_FRAMEWORKURI .'functions/metaboxes/metaboxes.css');
}
?>
<?php
/**
 * connecting to all framework files
 */

/****************Connecting to Reduxframework***********************/ 
if ( !class_exists( 'ReduxFramework' ) && file_exists( THE_FRAMEWORKPATH . 'ReduxCore/framework.php' ) ) {
    require_once( THE_FRAMEWORKPATH . 'ReduxCore/framework.php' );
}

require_once THE_FRAMEWORKPATH . 'nvr-general-functions.php';
require_once THE_FRAMEWORKPATH . 'nvr-googlefontjson.php';

/****************Connecting to Classes***********************/ 
require_once THE_FRAMEWORKPATH . 'classes/class-tgm-plugin-activation.php';
 
/****************Connecting to Sidebar Generator***********************/ 
require_once THE_FRAMEWORKPATH . 'sidebargenerator/nvr-form.php';
require_once THE_FRAMEWORKPATH . 'sidebargenerator/nvr-sidebar.php';

/****************Connecting to Functions***********************/ 
$func_path = THE_FRAMEWORKPATH . 'functions/';
require_once($func_path. "functions.php");

/****************Connecting to Standards Shortcodes***********************/ 
$shortcode_path = THE_FRAMEWORKPATH . 'shortcodes/';
require_once($shortcode_path. "columns.php" );
require_once($shortcode_path. "dropcap.php" );
require_once($shortcode_path. "tabs.php" );
require_once($shortcode_path. "toggles.php" );
require_once($shortcode_path. "highlight.php" );
require_once($shortcode_path. "quote.php" );
require_once($shortcode_path. "separator.php" );
require_once($shortcode_path. "pre.php" );
require_once($shortcode_path. "content-title.php" );
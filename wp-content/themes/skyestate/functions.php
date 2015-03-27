<?php

/********** NOVARO DEFINITION *************/
define('THE_THEMENAME', 'skyestate');
define('THE_SHORTNAME', 'novaro');
define('THE_INITIAL', 'nvr');
define('THE_LANG', 'novaro');
define('THE_PARENTMENU_SLUG', 'nvrtheme-settings' );
define('THE_SITEURL', site_url() );
define('THE_ADMINURL', admin_url() );
define('THE_TEMPLATEURI', get_template_directory_uri() . '/');
define('THE_TEMPLATEPATH', get_template_directory() . '/');
define('THE_FRAMEWORKPATH', get_template_directory() . '/framework/' );
define('THE_FRAMEWORKURI', get_template_directory_uri() . '/framework/' );
define('THE_STYLEURI', get_stylesheet_directory_uri() . '/');
define('THE_STYLEPATH', get_stylesheet_directory() . '/');
define('THE_CSSURI', get_template_directory_uri() . '/css/' );
define('THE_JSURI', get_template_directory_uri() . '/js/' );
define('THE_ENGINEPATH', get_template_directory() . '/engine/' );
define('THE_WIDGETPATH', get_template_directory() . '/widgets/' );
/********** END NOVARO DEFINITION *************/

//Connecting to Novaro Framework
require_once THE_FRAMEWORKPATH . 'framework-connector.php';

//Settings the theme options
require_once THE_ENGINEPATH . 'theme-options.php';

//Starting the theme setting
require_once THE_ENGINEPATH . 'engine-start.php';

//you can add your own function in here
if(file_exists(THE_STYLEPATH . 'functions-custom.php')){
	include_once THE_STYLEPATH . 'functions-custom.php';
}
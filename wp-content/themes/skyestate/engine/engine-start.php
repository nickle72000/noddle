<?php 

//Theme init
require_once THE_ENGINEPATH . 'theme-init.php';

//Custom Post Type init
require_once THE_ENGINEPATH . 'cp-init-portfolio.php'; // Portofolio
require_once THE_ENGINEPATH . 'cp-init-property.php'; // Property
require_once THE_ENGINEPATH . 'cp-init-testimonial.php'; // Testimonials
require_once THE_ENGINEPATH . 'cp-init-slider.php'; // Slider
require_once THE_ENGINEPATH . 'cp-init-people.php'; // People
require_once THE_ENGINEPATH . 'cp-init-brand.php'; // Brand
require_once THE_ENGINEPATH . 'cp-init-membership.php'; // Membership
require_once THE_ENGINEPATH . 'cp-init-invoices.php'; // Invoice

//Metaboxes
require_once THE_ENGINEPATH . 'theme-metaboxes.php';

//Widget and Sidebar
require_once THE_ENGINEPATH . 'sidebar-init.php';
require_once THE_ENGINEPATH . 'widgets-init.php';

//Theme Functions
require_once THE_ENGINEPATH . 'theme-functions.php';

//Header function
require_once THE_ENGINEPATH . 'header-functions.php';

//Footer function
require_once THE_ENGINEPATH . 'footer-functions.php';

//Loading jQuery
require_once THE_ENGINEPATH . 'theme-scripts.php';

//Loading Style Css
require_once THE_ENGINEPATH . 'theme-styles.php';

//Loading Theme Shortcodes
require_once THE_ENGINEPATH . 'theme-shortcodes.php';
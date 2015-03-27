<?php

/*********For Localization**************/
add_action('after_setup_theme', 'nvr_load_textdomain');
function nvr_load_textdomain(){
	load_theme_textdomain( THE_LANG, get_template_directory().'/languages' );
	load_theme_textdomain( THE_LANG, get_stylesheet_directory().'/languages' );
}
/*********End For Localization**************/

if(!function_exists('nvr_get_term_name')){
	function nvr_get_term_name( $id=0, $taxonomy ) {
		$cat = get_term_by( 'id', $id, $taxonomy );
		if ( $cat )
			return $cat->slug;
		return '';
	}
}
if( !function_exists('nvr_register_font') ){
	function nvr_register_font ( $nvr_sectionName ) {		
		$nvr_got_font = nvr_get_option($nvr_sectionName);
		
		if ($nvr_got_font!="0" && $nvr_got_font!='') {
			$nvr_font_pieces = explode(":", $nvr_got_font);
			
			$nvr_font_name = $nvr_font_pieces[0];
			$nvr_font_name = str_replace (" ","+", $nvr_font_pieces[0] );
			
			if( isset($nvr_font_pieces[1]) ){
				$nvr_font_variants = $nvr_font_pieces[1];
				$nvr_font_variants = ":" . str_replace ("|",",", $nvr_font_pieces[1] );
			}else{
				$nvr_font_variants = "";
			}
			
			wp_register_style( $nvr_sectionName, '//fonts.googleapis.com/css?family='.$nvr_font_name . $nvr_font_variants );
			return true;
		}else{
			return false;
		}
		
	}
}

// The excerpt based on character
if(!function_exists("nvr_string_limit_char")){
	function nvr_string_limit_char($nvr_excerpt, $nvr_substr=0, $nvr_strmore = "..."){
		$nvr_string = strip_tags(str_replace('...', '', $nvr_excerpt));
		if ($nvr_substr>0) {
			$nvr_string = substr($nvr_string, 0, $nvr_substr);
		}
		if(strlen($nvr_excerpt)>=$nvr_substr){
			$nvr_string .= $nvr_strmore;
		}
		return $nvr_string;
	}
}
// The excerpt based on words
if(!function_exists("nvr_string_limit_words")){
	function nvr_string_limit_words($nvr_string, $nvr_word_limit){
	  $nvr_words = explode(' ', $nvr_string, ($nvr_word_limit + 1));
	  if(count($nvr_words) > $nvr_word_limit)
	  array_pop($nvr_words);
	  
	  return implode(' ', $nvr_words);
	}
}

if ( ! isset( $content_width ) )
	$content_width = 610;


/* Remove inline styles printed when the gallery shortcode is used.*/
function nvr_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'nvr_remove_gallery_css' );

if(!function_exists('nvr_readsocialicon')){
	function nvr_readsocialicon(){
		$nvr_opt_social_icons_path = get_template_directory() . '/images/social/';
		$nvr_optSocialIcons = array();
		
		if ( is_dir($nvr_opt_social_icons_path) ) {
			if ($nvr_opt_social_icons_dir = opendir($nvr_opt_social_icons_path) ) { 
				$nvr_optSocialIcons[] = "None";
				while ( ($nvr_opt_social_icons_file = readdir($nvr_opt_social_icons_dir)) !== false ) {
					if(stristr($nvr_opt_social_icons_file, ".png") !== false && stristr($nvr_opt_social_icons_file, "@2x.") === false) {
						$nvr_optSocialIcons[$nvr_opt_social_icons_file] = $nvr_opt_social_icons_file;
					}
				}    
			}
		}
		return $nvr_optSocialIcons;
	}
}

if(!function_exists('nvr_gen_slug')){
	function nvr_gen_slug($str){
		$a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','Ð','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','?','?','J','j','K','k','L','l','L','l','L','l','?','?','L','l','N','n','N','n','N','n','?','O','o','O','o','O','o','Œ','œ','R','r','R','r','R','r','S','s','S','s','S','s','Š','š','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Ÿ','Z','z','Z','z','Ž','ž','?','ƒ','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','?','?','?','?','?','?');
		$b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');
		return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/','/[ -]+/','/^-|-$/'),array('','-',''),str_replace($a,$b,$str)));
	}
}

if(!function_exists('nvr_fontsocialicon')){
	function nvr_fontsocialicon(){
		$nvr_optSocialIcons = array(
			'fa-dribbble' => 'Dribbble',
			'fa-facebook' => 'Facebook',
			'fa-flickr' => 'Flickr',
			'fa-foursquare' => 'Foursquare',
			'fa-github' => 'Github',
			'fa-google-plus' => 'Google Plus',
			'fa-instagram' => 'Instagram',
			'fa-linkedin' => 'LinkedIn',
			'fa-pinterest' => 'Pinterest',
			'fa-skype' => 'Skype',
			'fa-tumblr' => 'Tumblr',
			'fa-twitter' => 'Twitter',
			'fa-vimeo-square' => 'Vimeo',
			'fa-youtube' => 'Youtube'
		);
		
		
		return $nvr_optSocialIcons;
	}
}

/*Prints HTML with meta information for the current post (category, tags and permalink).*/
if ( ! function_exists( 'nvr_posted_in' ) ) :
function nvr_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$nvr_tag_list = get_the_tag_list( '', ', ' );
	if ( $nvr_tag_list ) {
		$nvr_posted_in = __( 'Categories: %1$s <br/> Tags: %2$s', THE_LANG );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$nvr_posted_in = __( 'Categories: %1$s', THE_LANG );
	} else {
		$nvr_posted_in = __( '', THE_LANG );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$nvr_posted_in,
		get_the_category_list( ', ' ),
		$nvr_tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;

/*Clearing the automatic paragraphs and breaks on shortcodes that WordPress is adding automatically when filtering content.*/
function nvr_content_formatter($nvr_content) { 
	$nvr_content = do_shortcode(shortcode_unautop($nvr_content)); 
	$nvr_content = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $nvr_content);
	$nvr_content = str_replace('<br />', '', $nvr_content);
	$nvr_content = str_replace('<p><div', '<div', $nvr_content);
	return $nvr_content;
}

/* for top menu */
function nav_page_fallback() {
if(is_front_page()){$nvr_class="current_page_item";}else{$nvr_class="";}
print '<ul class="topnav sf-menu"><li class="'.esc_attr( $nvr_class ).'"><a href=" '.esc_url( home_url( '/') ).' " title=" '.esc_attr__('Click for Home',THE_LANG).' ">'.__('Home',THE_LANG).'</a></li>';
    wp_list_pages( 'title_li=&sort_column=menu_order' );
print '</ul>';
}

function nav_2nd_fallback() {
	print '';
}

/* for tagcloud widget  */
add_filter( 'widget_tag_cloud_args', 'nvr_tag_cloud_args' );
function nvr_tag_cloud_args( $nvr_args ) {
	$nvr_args['number'] = 20; // show less tags
	$nvr_args['largest'] = 12; // make largest and smallest the same - i don't like the varying font-size look
	$nvr_args['smallest'] = 12;
	$nvr_args['unit'] = 'px';
	$nvr_args['format'] = 'array';
	return $nvr_args;
}

add_filter('wp_tag_cloud','wp_tag_cloud_filter', 10, 2);
function wp_tag_cloud_filter($return, $args)
{
  $nvr_strreturn = "";
  if(is_array($return)){
	  foreach($return as $nvr_ret){
		$nvr_strreturn .="<span class='tag'>".$nvr_ret."</span>";
	  }
  }
  echo '<div id="tag-cloud">'.$nvr_strreturn.'</div>';
}

/* for shortcode widget  */
add_filter('widget_text', 'do_shortcode');

function change_posttype() {
  if( is_tax('portfoliocat') && !is_admin() ) {
    set_query_var( 'post_type', array( 'post', 'portofolio' ) );
  }
  return;
}
add_action( 'parse_query', 'change_posttype' );

if( !function_exists('nvr_check_pagepost')){
	function nvr_check_pagepost(){
		global $post;
		
		if( is_404() || is_archive() || is_tax() || is_attachment() || is_search() ){
			$nvr_custom = false;
		}else{
			$nvr_custom = true;
		}
		
		return $nvr_custom;
	}
}

if( !function_exists('nvr_country_list')){
	function nvr_country_list() {
		$countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia","Serbia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
		return $countries;
	}
}

if( !function_exists('nvr_get_postid')){
	function nvr_get_postid(){
		global $post;
		
		if( is_home() ){
			$nvr_pid = get_option('page_for_posts');
		}elseif( function_exists( 'is_woocommerce' ) && is_shop() ){
			$nvr_pid = woocommerce_get_page_id( 'shop' );
		}elseif( function_exists( 'is_woocommerce' ) && is_product_category() ){
			$nvr_pid = woocommerce_get_page_id( 'shop' );
		}elseif( function_exists( 'is_woocommerce' ) && is_product_tag() ){
			$nvr_pid = woocommerce_get_page_id( 'shop' );
		}elseif(!nvr_check_pagepost()){
			$nvr_pid = 0;
		}else{
			$nvr_pid = get_the_ID();
		}
		
		return $nvr_pid;
	}
}

if( !function_exists('nvr_get_customdata')){
	function nvr_get_customdata($nvr_pid=""){
		global $post;
		
		if($nvr_pid!=""){
			$nvr_custom = get_post_custom($nvr_pid);
			return $nvr_custom;
		}
		
		if($nvr_pid==""){
			$nvr_pid = nvr_get_postid();
		}

		if( nvr_check_pagepost() ){
			$nvr_custom = get_post_custom($nvr_pid);
		}else{
			$nvr_custom = array();
		}
		
		return $nvr_custom;
	}
}

if( !function_exists('nvr_get_bodycontainer')){
	function nvr_get_bodycontainer(){
		$nvr_txtContainerWidth = intval( nvr_get_option( THE_SHORTNAME . '_container_width') );
		if($nvr_txtContainerWidth<940){
			$nvr_txtContainerWidth = 940;
		}elseif($nvr_txtContainerWidth >1200){
			$nvr_txtContainerWidth = 1200;
		}
		return $nvr_txtContainerWidth;
	}
}

if( !function_exists('nvr_update_option')){
	function nvr_update_coption($key,$value){
		$nvr_optionname = THE_SHORTNAME;
		
		$nvr_arrval = array(
			$key => $value
		);
		if(get_option( $nvr_optionname )){
			$nvr_options = get_option($nvr_optionname);
			$nvr_options[$key] = $value;
			update_option($nvr_optionname, $nvr_options);
		}else{
			add_option($nvr_optionname, $nvr_arrval);
		}
	}
}

if( !function_exists('nvr_use_option')){
	function nvr_get_coption($key){
		$nvr_optionname = THE_SHORTNAME;
		
		$nvr_options = get_option( $nvr_optionname );
		if ( isset( $nvr_options[$key] ) ) {
			return $nvr_options[$key];
		}
		return false;
	}
}

if(!function_exists('nvr_get_option')){
	function nvr_get_option( $nvr_name, $nvr_default = false ) {
		global $nvr_option;
		$nvr_config = $nvr_option;
		if ( !isset($nvr_config[$nvr_name]) ) {
			return $nvr_default;
		}

		if ( isset( $nvr_config[$nvr_name] ) ) {
			return $nvr_config[$nvr_name];
		}
	
		return $nvr_default;
	}
}
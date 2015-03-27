<?php 
// get website title
if(!function_exists("nvr_wp_title")){
	function nvr_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}
		
		global $page, $paged;
		
		if( !defined('WPSEO_URL') ){
			// Add the blog name
			$title .= get_bloginfo( 'name', 'display' );
		
			// Add the blog description for the home/front page.
			$nvr_site_description = get_bloginfo( 'description', 'display' );
			if ( $nvr_site_description && ( is_home() || is_front_page() ) ) {
				$title .= " $sep $nvr_site_description";
			}
		
			// Add a page number if necessary:
			if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
				$title .= " $sep " . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );
			}
		}
	
		return $title;
	}
	add_filter( 'wp_title', 'nvr_wp_title', 10, 2 );
}

// head action hook
if(!function_exists("nvr_head")){
	function nvr_head(){
		do_action("nvr_head");
	}
	add_action('wp_head', 'nvr_head', 20);
}

if(!function_exists("nvr_metaviewport")){
	function nvr_metaviewport(){
		$nvr_dis_viewport = nvr_get_option(THE_SHORTNAME . '_disable_viewport');
		if(!$nvr_dis_viewport){
			echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />';
		}
	}
	add_action('nvr_head', 'nvr_metaviewport', 5);
}

if(!function_exists("nvr_print_headmiscellaneous")){
	function nvr_print_headmiscellaneous(){
	
		echo "<!--[if lt IE 9]>\n";
		echo "<script src='". esc_url( THE_JSURI."html5shiv.js" ) ."' type='text/javascript'></script>\n";
		echo "<![endif]-->\n";

        $nvr_favicon = nvr_get_option( THE_SHORTNAME . '_favicon');
		
		$nvr_favicon_url = get_template_directory_uri() . '/images/favicon.ico';
        if(is_array($nvr_favicon)){
			if(isset($nvr_favicon['url']) && $nvr_favicon['url']!=''){
            	$nvr_favicon_url = $nvr_favicon['url'];
			}
        }
		echo '<link rel="shortcut icon" href="' . esc_url( $nvr_favicon_url ) . '" />';
        
	}
	add_action('nvr_head', 'nvr_print_headmiscellaneous', 6);
}

// print the logo html
if(!function_exists("nvr_logo")){
	function nvr_logo(){ 
		
		$nvr_shortname = THE_SHORTNAME;
		
		$nvr_defaultlogo = array(
			'url' => get_stylesheet_directory_uri() . "/images/logo.png",
			'id' => '',
			'width' => '',
			'height' => '',
			'thumbnail' => ''
		);
		$nvr_logotype = nvr_get_option( $nvr_shortname . '_logo_type');
		$nvr_logoimage = nvr_get_option( $nvr_shortname . '_logo_image', $nvr_defaultlogo);
		$nvr_logoimagelight = nvr_get_option( $nvr_shortname . '_logo_image_light', $nvr_defaultlogo);
		$nvr_sitename =  nvr_get_option( $nvr_shortname . '_site_name');
		$nvr_tagline = nvr_get_option( $nvr_shortname . '_tagline');
		
		if($nvr_sitename=="") $nvr_sitename = get_bloginfo('name');
		if($nvr_tagline=="") $nvr_tagline = get_bloginfo('description'); 
		if($nvr_logoimage['url'] == "") $nvr_logoimage['url'] = get_stylesheet_directory_uri() . "/images/logo.png";
		if($nvr_logoimagelight['url'] =="") $nvr_logoimagelight['url'] = $nvr_logoimage['url'];
?>
		<?php if($nvr_logotype == 'textlogo'){ ?>
			
			<h1><a href="<?php echo esc_url( home_url( '/') ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', THE_LANG ) ); ?>"><?php echo $nvr_sitename; ?></a></h1><span class="desc"><?php echo $nvr_tagline; ?></span>
        
        <?php } else { ?>
        	
            <div class="logoimg">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( $nvr_sitename ); ?>" >
                <img src="<?php echo esc_url( $nvr_logoimage['url'] ); ?>" alt="<?php echo esc_attr( $nvr_sitename ); ?>" class="darklogo" />
                <img src="<?php echo esc_url( $nvr_logoimagelight['url'] ); ?>" alt="<?php echo esc_attr( $nvr_sitename ); ?>" class="lightlogo" />
            </a>
            </div>
            
		<?php } ?>
        
<?php 
	}
}

if(!function_exists("nvr_searchform")){
	function nvr_searchform($nvr_id="", $nvr_class=""){
		if(function_exists('is_woocommerce')){
			$nvr_outputposttype = '<input type="hidden" name="post_type" value="product" />';
			$nvr_searchtext = __('Search product...', THE_LANG );
		}else{
			$nvr_outputposttype = '';
			$nvr_searchtext = __('Search...', THE_LANG );
		}
		if($nvr_id==''){
			$nvr_id = 'topsearchform';
		}
		$nvr_output = '<div class="'. esc_attr( $nvr_class ).'">';
			$nvr_output .= '<form method="get" id="'.esc_attr( $nvr_id ).'" class="btntoppanel" action="'. esc_url( home_url( '/' ) ) .'">';
				$nvr_output .= '<input type="submit" class="submit" name="submit" value="" />';
				$nvr_output .= '<div class="searcharea">';
					$nvr_output .= '<input type="text" name="s" class="txtsearch" placeholder="'. esc_attr( $nvr_searchtext ) .'" value="" />';
					$nvr_output .= $nvr_outputposttype;
				$nvr_output .= '</div>';
			$nvr_output .= '</form>';
		$nvr_output .= '</div>';
		
		return $nvr_output;
	}
}

if(!function_exists('nvr_breadcrumb')){
	function nvr_breadcrumb(){
		if(function_exists('woocommerce_breadcrumb')){
			woocommerce_breadcrumb(array(
				'delimiter' => ' &nbsp;&nbsp;/&nbsp;&nbsp; '
			));
		}elseif(function_exists('yoast_breadcrumb')){
			yoast_breadcrumb('<nav class="nvr-breadcrumb">','</nav><div class="clearfix"></div>');
		}
	}
}

if(!function_exists("nvr_minicart")){
	function nvr_minicart($nvr_id="",$nvr_class=""){
		
		global $woocommerce;
		$nvr_cart_subtotal = $woocommerce->cart->get_cart_subtotal();
		$nvr_link = $woocommerce->cart->get_cart_url();
		$nvr_cart_items = $woocommerce->cart->get_cart_item_quantities();
		
		$nvr_totalqty = 0;
		if(is_array($nvr_cart_items)){
			foreach($nvr_cart_items as $nvr_cart_item){
				$nvr_totalqty += (is_numeric($nvr_cart_item))? $nvr_cart_item : 0;
			}
		}
		
		ob_start();
		the_widget('WC_Widget_Cart', '', array('widget_id'=>'cart-dropdown',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<span class="hidden">',
			'after_title' => '</span>'
		));
		$nvr_widget = ob_get_clean();
	
		$nvr_output = '<div id="'.esc_attr( $nvr_id ).'" class="'.esc_attr( $nvr_class ).'">';
			$nvr_output .= '<a class="topcartbutton btnpanel" href="'.esc_url( $nvr_link ).'"><i class="shopicon fa fa-shopping-cart"></i><span class="cart_subqty"><i class="cart_totalqty">'.$nvr_totalqty.' '.__('Items', THE_LANG).'</i><i class="cart_subtotal">'.$nvr_cart_subtotal.'</i></span></a>';
			$nvr_output .= '<span class="arrowpanel"></span>';
			$nvr_output .= '<div class="cartlistwrapper">';
				$nvr_output .= $nvr_widget;
			$nvr_output .= '</div>';
		$nvr_output .= '</div>';
		
		return $nvr_output;
	}
}

if(!function_exists('nvr_primary_menu')){
	function nvr_primary_menu($nvr_class=''){
		echo '<section class="navigation '.esc_attr( $nvr_class ).'">';
			echo '<a class="nav-toggle fa"></a>';
			echo '<nav>';
			wp_nav_menu( array(
			  'container'       => 'ul', 
			  'menu_class'      => 'topnav sf-menu', 
			  'depth'           => 0,
			  'sort_column'    => 'menu_order',
			  'fallback_cb'     => 'nav_page_fallback',
			  'theme_location' => 'mainmenu' 
			)); 
			echo '</nav><!-- nav -->';	
			echo '<div class="clearfix"></div>';
		echo '</section>';
	}
}

if(!function_exists('nvr_secondary_menu')){
	function nvr_secondary_menu(){
		
		wp_nav_menu( array(
		  'container'       => 'ul', 
		  'menu_class'      => 'gn-menu',
		  'menu_id'         => 'secondarynav', 
		  'depth'           => 2,
		  'sort_column'    => 'menu_order',
		  'fallback_cb'     => 'nav_2nd_fallback',
		  'theme_location' => 'secondarymenu' 
		));

	}
	add_action('nvr_output_toparea','nvr_secondary_menu',30);
}

if(!function_exists('nvr_front_submission_menu')){
	function nvr_front_submission_menu(){
		$nvr_shortname = THE_SHORTNAME;
		
		$nvr_submission = nvr_get_option( $nvr_shortname.'_enable_submission', '');
		$nvr_loginlink = nvr_get_option( $nvr_shortname.'_loginlink', '');
		$nvr_reglink = nvr_get_option( $nvr_shortname.'_registerlink', '');
		
		if( (is_user_logged_in() || $nvr_loginlink!='' || $nvr_reglink!='') && $nvr_submission=='1' ){
		
			echo '<ul class="gn-menu frontendmenu">';
				if($nvr_loginlink!=''){
					echo '<li class="fa fa-book beforelogin"><a href="'.$nvr_loginlink.'">'.__('Login', THE_LANG).'</a></li>';
				}
				if($nvr_reglink!=''){
					echo '<li class="fa fa-sign-in beforelogin"><a href="'.$nvr_reglink.'">'.__('Register', THE_LANG).'</a></li>';
				}
				echo '<li class="fa fa-sign-out afterlogin"><a href="'.wp_logout_url( home_url() ).'">'.__('Logout', THE_LANG).'</a></li>';
				echo '<li class="fa fa-user afterlogin"><a href="'.nvr_dashboard_link('profile').'">'.__('My Account', THE_LANG).'</a></li>';
			echo '</ul>';
			
		}

	}
	add_action('nvr_output_toparea','nvr_front_submission_menu',50);
}

if (!function_exists('nvr_socialicon')){
	function nvr_socialicon($usetext=false, $vertical=false){
		
		$nvr_shortname = THE_SHORTNAME;
		$nvr_optSocialIcons = nvr_fontsocialicon();
		
		$nvr_outputli = "";
		foreach($nvr_optSocialIcons as $nvr_optSocialIcon => $nvr_optSocialText){
			$nvr_sociallink = nvr_get_option( $nvr_shortname . '_socialicon_'.$nvr_optSocialIcon, "" );
			$socialtext = '';
			if($usetext==true){
				$socialtext = $nvr_optSocialText;
			}
			if(isset($nvr_sociallink) && $nvr_sociallink!=''){
				$nvr_outputli .= '<li><a href="'.esc_url( $nvr_sociallink ).'"><i class="fa '.esc_attr( $nvr_optSocialIcon ).'"></i> '.$socialtext.'</a></li>'."\n";
			}
		}
		$nvr_output = "";
		if($nvr_outputli!=""){
			if($vertical==true){
				$vertclass = 'vertical';
			}else{
				$vertclass = '';
			}
			$nvr_output .= '<ul class="sn '.$vertclass.'">';
			$nvr_output .= $nvr_outputli;
			$nvr_output .= '</ul>';
		}
		return $nvr_output;
	}
}//end if(!function_exists('nvr_get_socialicon'))

if(!function_exists('nvr_output_socialicon')){
	function nvr_output_socialicon(){
		/*=====SOCIALICON======*/
		$nvr_socialiconoutput = nvr_socialicon();
		if($nvr_socialiconoutput!=''){				
			// get the social network icon
			echo '<div class="topicon">'. $nvr_socialiconoutput .'</div>';
		}
	}
}

if(!function_exists('nvr_output_headertext')){
	function nvr_output_headertext(){
		$nvr_shortname = THE_SHORTNAME;
		/*=====HEADERTEXT======*/
		$nvr_headertext = stripslashes(nvr_get_option( $nvr_shortname . '_headertext',''));
		if($nvr_headertext){
			echo '<div class="toptext"><div>'. do_shortcode($nvr_headertext) .'</div></div>';
		}
	}
	add_action('nvr_output_toparea','nvr_output_headertext',5);
}

if(!function_exists('nvr_output_headertext2')){
	function nvr_output_headertext2(){
		$nvr_shortname = THE_SHORTNAME;
		/*=====HEADERTEXT======*/
		$nvr_headertext = stripslashes(nvr_get_option( $nvr_shortname . '_headertext2',''));
		if($nvr_headertext){
			echo '<div class="toptext toptext2"><div>'. do_shortcode($nvr_headertext) .'</div></div>';
		}
	}
	add_action('nvr_output_toparea','nvr_output_headertext2',8);
}

if(!function_exists('nvr_output_wpmlselector')){
	function nvr_output_wpmlselector(){
		do_action('icl_language_selector');
	}
	add_action('nvr_output_toparea','nvr_output_wpmlselector',20);
}

if(!function_exists('nvr_output_searchform')){
	function nvr_output_searchform(){
		$nvr_shortname = THE_SHORTNAME;
		
		$nvr_disable_topsearch = nvr_get_option($nvr_shortname . '_disable_topsearch');
		if($nvr_disable_topsearch!=true){
			echo nvr_searchform("","searchbox"); 
		}
	}
}

if(!function_exists('nvr_output_minicart')){
	function nvr_output_minicart(){
		$nvr_shortname = THE_SHORTNAME;
		
		$nvr_disable_minicart = nvr_get_option($nvr_shortname . '_disable_minicart');
		if($nvr_disable_minicart!=true && function_exists('is_woocommerce')){
			echo nvr_minicart("topminicart","commercepanel");
		}
	}
}
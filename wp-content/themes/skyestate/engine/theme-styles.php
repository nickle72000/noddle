<?php
function nvr_styles() {
	if (!is_admin()) {
		
		wp_register_style('reset-css', THE_CSSURI . 'reset.css', '', '', 'screen, all');
		wp_enqueue_style('reset-css');
		
		wp_register_style('normalize-css', THE_CSSURI . 'normalize.css', 'reset-css', '', 'screen, all');
		wp_enqueue_style('normalize-css');
		
		if( nvr_register_font( THE_SHORTNAME . '_menunav_font') ){
			wp_enqueue_style( THE_SHORTNAME . '_menunav_font');
		}
		
		if( nvr_register_font( THE_SHORTNAME . '_secondnav_font') ){
			wp_enqueue_style( THE_SHORTNAME . '_secondnav_font');
		}

		
		wp_register_style('skeleton-css', THE_CSSURI . '1140.css', 'normalize-css', '', 'screen, all');
		wp_enqueue_style('skeleton-css');
		
		wp_register_style('font-awesome-css', THE_CSSURI . 'font-awesome.min.css', 'skeleton-css', '', 'screen, all');
		wp_enqueue_style('font-awesome-css');
		
		wp_register_style('PerfectScrollbar-css', THE_CSSURI . 'perfect-scrollbar.min.css', 'skeleton-css', '', 'screen, all');
		wp_enqueue_style('PerfectScrollbar-css');
		
		wp_register_style('main-css', THE_CSSURI . 'main.css', 'skeleton-css', '', 'screen, all');
		wp_enqueue_style('main-css');
		
		wp_register_style('idxlisting-css', THE_CSSURI . 'idxlisting.css', '', '', 'screen, all');
		wp_enqueue_style('idxlisting-css');
		
		wp_register_style('prettyPhoto-css', THE_CSSURI . 'prettyPhoto.css', '', '', 'screen, all');
		wp_enqueue_style('prettyPhoto-css');
		
		wp_register_style('flexslider-css', THE_CSSURI .'flexslider.css', '', '', 'screen, all');
		wp_enqueue_style('flexslider-css');
		
		wp_register_style('nouislider-css', THE_CSSURI .'jquery.nouislider.min.css', '', '', 'screen, all');
		wp_enqueue_style('nouislider-css');
		
		wp_register_style('layout-css', THE_CSSURI . 'layout.css', '', '', 'screen, all');
		wp_enqueue_style('layout-css');
		
		wp_register_style('color-css', THE_CSSURI . 'color.css', '', '', 'screen, all');
		wp_enqueue_style('color-css');
		
		wp_register_style('woocommerce-css', THE_CSSURI . 'woocommerce.css', '', '', 'screen, all');
		if(function_exists('is_woocommerce')){
			wp_enqueue_style('woocommerce-css');
		}
		
		wp_register_style('stylecustom', THE_STYLEURI . 'style-custom.css', '', '', 'screen, all');
		wp_enqueue_style('stylecustom');
		
		$nvr_custom_css = nvr_print_stylesheet();
		wp_add_inline_style( 'stylecustom', $nvr_custom_css );
		
		wp_register_style('switcher-css', THE_CSSURI . 'style-switcher.css', '', '', 'screen, all');
		if( nvr_get_option( THE_SHORTNAME . '_enable_switcher')){
			wp_enqueue_style('switcher-css');
		}
		
		wp_register_style('noscript-css', THE_CSSURI .'noscript.css', '', '', 'screen, all');
		wp_enqueue_style('noscript-css');
		
	}
}
add_action('wp_enqueue_scripts', 'nvr_styles');

// get style
if(!function_exists("nvr_print_stylesheet")){
	function nvr_print_stylesheet(){
		
		$nvr_shortname = THE_SHORTNAME;
		
		/* Get Theme Color Options */
		$nvr_opt_colorTheme		= nvr_get_option($nvr_shortname. '_color_theme');    
		
		$nvr_textcolorcss  = '';
		if($nvr_opt_colorTheme!=''){
$nvr_textcolorcss .= 'a:hover, a.colortext:hover, .colortext a:hover, .colortext{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.button, .button:visited, #maincontent input[type="submit"], #maincontent input[type="reset"], button{background: '.$nvr_opt_colorTheme.';border-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '#headertext #lang_sel .lang_sel_sel{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.topnav > li:hover, .topnav > .current_page_item, .topnav > .current_page_parent, .topnav > .current-menu-parent, .topnav > .current-menu-ancestor, .topnav > .current_page_ancestor, .topnav > .current-menu-item{border-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.topnav li li a:hover, .topnav li .current_page_item > a, .topnav li .current_page_item > a:hover, .topnav li .current_page_parent > a, .topnav li .current_page_parent > a:hover, .topnav li .current-menu-parent > a, .topnav li .current-menu-parent > a:hover, .topnav li .current-menu-item > a, .topnav li .current-menu-item > a:hover{color:'.$nvr_opt_colorTheme.' !important;}';
$nvr_textcolorcss .= '#subbody .flex-direction-nav a:hover{color:#fff; background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '#slider .flex-control-paging li a.flex-active {background-color: '.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '#slider .caption-content .toptext1{background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '#slider .caption-content .toptext2{background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.maps-nav-next:hover, .maps-nav-prev:hover{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.advanced-search .noUi-connect{background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '#outerbeforecontent{background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.highlight2{background:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.icn-container{border-color:'.$nvr_opt_colorTheme.';color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.icn-container.type2{border-color:'.$nvr_opt_colorTheme.';background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.icn-container.type3{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= 'ul.tabs li:hover{border-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= 'ul.tabs	 li.active{border-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= 'html ul.tabs li.active, html ul.tabs li.active a:hover, ul.tabs li a:hover{background: '.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.nvr-pf-img a.image, div.frameimg a.image{ background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.nvr-pf-img a .rollover, div.frameimg a .rollover{background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.nvr-pf-box:hover .nvr-pf-text .nvr-pf-title a:hover{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.nvr-pf-text .nvr-pf-cat a:hover{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.nvr-pf-container .masonry .nvr-pf-img div.rollover{background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '#filters li:hover, .filterlist li:hover, #filters li.selected, .filterlist li.selected{border-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.rp-shortcode h3 a:hover{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.archives_list li a:hover{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= 'div.meter div{background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= 'div.bordersep .bordershow{background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.flexslider-carousel h3 a:hover{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.nvr-heading.white .hborder{background-color:'.$nvr_opt_colorTheme.' !important;}';
$nvr_textcolorcss .= '.nvr-people li:hover .imgcontainer{background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.nvr-people .peoplesocial a{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.nvr-people .peoplesocial a:hover{background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '#subbody .nvr-agent-lists-widget li h6 a:hover{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '#subbody .nvr-agent-lists-widget li .agentmail{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '#subbody .nvr-property-lists-widget li h6 a:hover{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '#subbody .nvr-property-lists-widget li .propprice{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.posttitle a:hover{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.meta-author a, .meta-author a:visited{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= 'article.format-quote .entry-content{background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.nav-previous a:hover, .nav-next a:hover{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.commentlist .comment-body .reply a{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '#infocloser:hover{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.nvr-upper-meta span {background-color: '.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.nvr-upper-meta .meta-price{background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.nvr-prop-img a.image:hover{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.prop-single-head .prop-price-container {background-color: '.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.prop-single-head .prop-price-container .prop-single-price{background-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.prop-single-summary .prop-category{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.prop-single-feature .columns .fa-times{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.people-single-agent .agent-info{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '.wp-pagenavi a:hover{background-color:'.$nvr_opt_colorTheme.'; border-color:'.$nvr_opt_colorTheme.' !important;}';
$nvr_textcolorcss .= '.wp-pagenavi span.current{background:'.$nvr_opt_colorTheme.';border-color:'.$nvr_opt_colorTheme.' !important;}';
$nvr_textcolorcss .= '.nvr-recentposts .nvr-rp-morelink{color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '#megaMenu li.menu-item.ss-nav-menu-mega ul.sub-menu.sub-menu-1, #megaMenu li.menu-item.ss-nav-menu-reg ul.sub-menu.sub-menu-1{border-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= '#megaMenu.megaMenu-withjs ul li.menu-item.ss-nav-menu-reg ul.sub-menu > li.menu-item > ul.sub-menu{border-color:'.$nvr_opt_colorTheme.';}';
$nvr_textcolorcss .= 'body.novaro .vc_custom_heading .headingarrow a, body.novaro .vc_custom_heading .headingarrow a:visited{color:'.$nvr_opt_colorTheme.';}';

			$nvr_textcolorcss .= '@media only screen and (min-width: 1024px) {
				.nvrlayout2 #outerheader .topnav li a:hover, .nvrlayout2 #outerheader .topnav .current-menu-item > a, .nvrlayout2 #outerheader .topnav .current_page_item > a, .nvrlayout2 #outerheader .topnav .current-menu-parent > a, .nvrlayout2 #outerheader .topnav .current-menu-ancestor > a, .nvrlayout2 #outerheader .topnav .current_page_parent > a, .nvrlayout2 #outerheader .topnav .current_page_ancestor > a{color:'.$nvr_opt_colorTheme.';}
			}';
			$nvr_textcolorcss .= '@media only screen and (max-width: 767px) {
				.js .topnav a:hover, .js .topnav a.current-menu-item{background-color:'.$nvr_opt_colorTheme.' !important;}
			}';
		}
		
		/* Get Header Background Option */
		$nvr_opt_bgHeader 		= nvr_get_option($nvr_shortname. '_header_background');    
		$nvr_cf_bgHeader 		= '';
		$nvr_cf_bgRepeat 		= "no-repeat";
		$nvr_cf_bgPos	 		= "center";
		$nvr_cf_bgAttch	 		= "";
		$nvr_cf_bgColor	 		= "transparent";
		
		if( $nvr_opt_bgHeader){
			if($nvr_opt_bgHeader["background-image"]!=""){
				$nvr_cf_bgHeader 	= $nvr_opt_bgHeader["background-image"];
				$nvr_cf_bgRepeat 		= $nvr_opt_bgHeader["background-repeat"];
				$nvr_cf_bgPos	 		= $nvr_opt_bgHeader["background-position"];
				$nvr_cf_bgAttch	 		= $nvr_opt_bgHeader["background-attachment"];
			}
			$nvr_cf_bgColor	 		= ($nvr_opt_bgHeader["background-color"]!="")? $nvr_opt_bgHeader["background-color"] : "";
		}
		
		/* Get Body Background Option */
		$nvr_optBodyBGColor = '';
		$nvr_optBodyBGImage = '';
		$nvr_optBodyBGPosition = 'center';
		$nvr_optBodyBGStyle = 'repeat';
		$nvr_optBodyBGattachment = '';
		$nvr_optBodyBGSize = '';
		
		$nvr_optBodyBG = nvr_get_option( $nvr_shortname . '_body_background');
		if($nvr_optBodyBG){
			$nvr_optBodyBGColor = $nvr_optBodyBG['background-color'];
			$nvr_optBodyBGImage = $nvr_optBodyBG['background-image'];
			$nvr_optBodyBGPosition = $nvr_optBodyBG['background-position'];
			$nvr_optBodyBGStyle = $nvr_optBodyBG['background-repeat'];
			$nvr_optBodyBGattachment = $nvr_optBodyBG['background-attachment'];
			$nvr_optBodyBGSize = $nvr_optBodyBG['background-size'];
		}
		
		/* Get After Content Background Option */
		$nvr_opt_bgAfterC = nvr_get_option( $nvr_shortname . '_aftercontent_background');
		$nvr_cf_bgAfterC 		= "";
		$nvr_cf_bgRepeatAfterC	= "repeat";
		$nvr_cf_bgPosAfterC		= "center";
		$nvr_cf_bgColorAfterC	= "transparent";

		if( $nvr_opt_bgAfterC ){
			if($nvr_opt_bgAfterC["background-image"]!=""){
				$nvr_cf_bgAfterC 	= $nvr_opt_bgAfterC["background-image"];
			}
			$nvr_cf_bgRepeatAfterC	= $nvr_opt_bgAfterC["background-repeat"];
			$nvr_cf_bgPosAfterC		= $nvr_opt_bgAfterC["background-position"];
			$nvr_cf_bgAttchAfterC	= $nvr_opt_bgAfterC["background-attachment"];
			$nvr_cf_bgColorAfterC	= ($nvr_opt_bgAfterC["background-color"]!="")? $nvr_opt_bgAfterC["background-color"] : "#333333";
		}
		
		/* Get Footer Background Option */
		$nvr_opt_bgFooter 		= nvr_get_option($nvr_shortname. '_footer_background');
		$nvr_cf_bgFooter 		= "";
		$nvr_cf_bgRepeatFooter	= "repeat";
		$nvr_cf_bgPosFooter		= "center";
		$nvr_cf_bgColorFooter	= "#353535";
		
		if( $nvr_opt_bgFooter ){
			if($nvr_opt_bgFooter["background-image"]!=""){
				$nvr_cf_bgFooter 	= $nvr_opt_bgFooter["background-image"];
			}
			$nvr_cf_bgRepeatFooter	= $nvr_opt_bgFooter["background-repeat"];
			$nvr_cf_bgPosFooter		= $nvr_opt_bgFooter["background-position"];
			$nvr_cf_bgAttchFooter	= $nvr_opt_bgFooter["background-attachment"];
			$nvr_cf_bgColorFooter	= ($nvr_opt_bgFooter["background-color"]!="")? $nvr_opt_bgFooter["background-color"] : $nvr_cf_bgColorFooter;
		}
		
		/* Get Google Font Option */
		$nvr_optGeneralTextFont = nvr_get_option( THE_SHORTNAME . '_general_font');
		
		$nvr_optBigTextFont = nvr_get_option( THE_SHORTNAME . '_bigtext_font');

		$nvr_optHeadingFont = nvr_get_option( THE_SHORTNAME . '_heading_font');
		
		$nvr_optMenuFont = nvr_get_option( THE_SHORTNAME . '_menunav_font');
		if($nvr_optMenuFont!="0"){
			$nvr_MenuFont = explode(":",$nvr_optMenuFont);
			$nvr_MenuOutput = "'". $nvr_MenuFont[0] . "',";
		}
		
		$nvr_opt2ndMenuFont = nvr_get_option( THE_SHORTNAME . '_secondnav_font');
		if($nvr_opt2ndMenuFont!="0"){
			$nvr_2ndMenuFont = explode(":",$nvr_opt2ndMenuFont);
			$nvr_2ndMenuOutput = "'". $nvr_2ndMenuFont[0] . "',";
		}
		
		$nvr_opt2ndFont = nvr_get_option( THE_SHORTNAME . '_secondary_font');
		
		$nvr_txtContainerWidth = intval( nvr_get_option( THE_SHORTNAME . '_container_width') );
		
		//get background from metabox
		$nvr_pid = nvr_get_postid();
		$nvr_custom = nvr_get_customdata($nvr_pid);
		$nvr_cf_bgHeader 		= (isset($nvr_custom["bg_header"][0]))? $nvr_custom["bg_header"][0] : $nvr_cf_bgHeader;
		$nvr_cf_bgRepeat 		= (isset($nvr_custom["bg_repeat"][0]) && trim($nvr_custom["bg_repeat"][0])!="")? $nvr_custom["bg_repeat"][0] : $nvr_cf_bgRepeat;
		$nvr_cf_bgPos	 		= (isset($nvr_custom["bg_pos"][0]) && trim($nvr_custom["bg_pos"][0])!="")? $nvr_custom["bg_pos"][0] : $nvr_cf_bgPos;
		$nvr_cf_bgAttch	 		= (isset($nvr_custom["bg_attch"][0]) && trim($nvr_custom["bg_attch"][0])!="")? $nvr_custom["bg_attch"][0] : $nvr_cf_bgAttch;
		$nvr_cf_bgColor	 		= (isset($nvr_custom["bg_color"][0]) && trim($nvr_custom["bg_color"][0])!="")? $nvr_custom["bg_color"][0] : $nvr_cf_bgColor;
		
		$nvr_cf_pagebgimg = (isset($nvr_custom["page-bgimg"][0]))? $nvr_custom["page-bgimg"][0] : "";
		$nvr_cf_pagebgposition = (isset($nvr_custom["page-bgposition"][0]))? $nvr_custom["page-bgposition"][0] : "";
		$nvr_cf_pagebgstyle = (isset($nvr_custom["page-bgstyle"][0]))? $nvr_custom["page-bgstyle"][0] : "";
		$nvr_cf_pagebgattch = (isset($nvr_custom["page-bgattch"][0]))? $nvr_custom["page-bgattch"][0] : "";
		$nvr_cf_pagebgcolor = (isset($nvr_custom["page-bgcolor"][0]))? $nvr_custom["page-bgcolor"][0] : "";
		
		$nvr_cf_bgMainC  		= (isset($nvr_custom["bg_maincontent"][0]))? $nvr_custom["bg_maincontent"][0] : '';
		$nvr_cf_bgRepeatMainC 	= (isset($nvr_custom["bg_repeat_maincontent"][0]) && trim($nvr_custom["bg_repeat_maincontent"][0])!="")? $nvr_custom["bg_repeat_maincontent"][0] : 'repeat';
		$nvr_cf_bgPosMainC 	= (isset($nvr_custom["bg_pos_maincontent"][0]) && trim($nvr_custom["bg_pos_maincontent"][0])!="")? $nvr_custom["bg_pos_maincontent"][0] : 'center';
		$nvr_cf_bgColorMainC 	= (isset($nvr_custom["bg_color_maincontent"][0]) && trim($nvr_custom["bg_color_maincontent"][0])!="")? $nvr_custom["bg_color_maincontent"][0] : 'transparent';
		
		$nvr_cf_bgAfterC  		= (isset($nvr_custom["bg_aftercontent"][0]))? $nvr_custom["bg_aftercontent"][0] : $nvr_cf_bgAfterC;
		$nvr_cf_bgRepeatAfterC 	= (isset($nvr_custom["bg_repeat_aftercontent"][0]) && trim($nvr_custom["bg_repeat_aftercontent"][0])!="")? $nvr_custom["bg_repeat_aftercontent"][0] : $nvr_cf_bgRepeatAfterC;
		$nvr_cf_bgPosAfterC 	= (isset($nvr_custom["bg_pos_aftercontent"][0]) && trim($nvr_custom["bg_pos_aftercontent"][0])!="")? $nvr_custom["bg_pos_aftercontent"][0] : $nvr_cf_bgPosAfterC;
		$nvr_cf_bgColorAfterC 	= (isset($nvr_custom["bg_color_aftercontent"][0]) && trim($nvr_custom["bg_color_aftercontent"][0])!="")? $nvr_custom["bg_color_aftercontent"][0] : $nvr_cf_bgColorAfterC;
		
		$nvr_cf_bgFooter 		= (isset($nvr_custom["bg_footer"][0]))? $nvr_custom["bg_footer"][0] : $nvr_cf_bgFooter;
		$nvr_cf_bgRepeatFooter	= (isset($nvr_custom["bg_repeat_footer"][0]) && trim($nvr_custom["bg_repeat_footer"][0])!="")? $nvr_custom["bg_repeat_footer"][0] : $nvr_cf_bgRepeatFooter;
		$nvr_cf_bgPosFooter		= (isset($nvr_custom["bg_pos_footer"][0]) && trim($nvr_custom["bg_pos_footer"][0])!="")? $nvr_custom["bg_pos_footer"][0] : $nvr_cf_bgPosFooter	;
		$nvr_cf_bgColorFooter	= (isset($nvr_custom["bg_color_footer"][0]) && trim($nvr_custom["bg_color_footer"][0])!="")? $nvr_custom["bg_color_footer"][0] : $nvr_cf_bgColorFooter;
		
		$nvr_print_custom_css = '';
		
		$nvr_print_custom_css .= $nvr_textcolorcss;
		
		$nvr_bodycss = '';
		$nvr_generalTextOutput = '';
		if($nvr_optGeneralTextFont){
			foreach($nvr_optGeneralTextFont as $nvr_generalTextFont => $nvr_generalTextFontVal){
				if($nvr_generalTextFont!='google' && $nvr_generalTextFont!='subsets' && $nvr_generalTextFont!='font-options' && $nvr_generalTextFontVal!=''){
					if($nvr_generalTextFont=='font-family'){
						if(strpos($nvr_generalTextFontVal,",")>0){
							$nvr_generalTextOutput = $nvr_generalTextFontVal;
						}else{
							$nvr_generalTextFontVal = $nvr_generalTextOutput = "'".$nvr_generalTextFontVal."'";
						}
					}
					$nvr_bodycss .= $nvr_generalTextFont. ": ".$nvr_generalTextFontVal.";";
				}
			}
		}
		
		if($nvr_cf_pagebgimg!="" || $nvr_cf_pagebgcolor!=''){
			$nvr_bodycss .= 'background-image:url('. $nvr_cf_pagebgimg .');';
			$nvr_bodycss .= 'background-repeat:'. $nvr_cf_pagebgstyle  .';';
			$nvr_bodycss .= 'background-attachment:'. $nvr_cf_pagebgattch  .';';
			$nvr_bodycss .= 'background-position:'. $nvr_cf_pagebgposition .';';
			$nvr_bodycss .= 'background-color:'. $nvr_cf_pagebgcolor .';';
		
		}else{
			if($nvr_optBodyBGImage!="" || $nvr_optBodyBGColor!=""){
				$nvr_bodycss .= 'background-color:'. $nvr_optBodyBGColor .';';
				$nvr_bodycss .= 'background-image:url('. $nvr_optBodyBGImage .');';
				$nvr_bodycss .= 'background-repeat:'. $nvr_optBodyBGStyle .';';
				$nvr_bodycss .= 'background-position:'. $nvr_optBodyBGPosition .';';
				$nvr_bodycss .= 'background-attachment:'. $nvr_optBodyBGattachment .';';
			}
		}
		$nvr_print_custom_css .='body{'.$nvr_bodycss.'}';
		
		$nvr_outertopcss = '';
		if($nvr_cf_bgHeader){
			$nvr_outertopcss .='background-image:url('. $nvr_cf_bgHeader .');';
		}
		$nvr_outertopcss .='background-repeat:'. $nvr_cf_bgRepeat .'; background-position:'. $nvr_cf_bgPos .'; background-color:'. $nvr_cf_bgColor .';';
		$nvr_print_custom_css .='#outertop{'.$nvr_outertopcss.'}';
		
		$nvr_logo_height = nvr_get_option( $nvr_shortname . '_logo_image_height');
		if($nvr_logo_height!='' && is_numeric($nvr_logo_height) && $nvr_logo_height>0){
			$nvr_header_height = 28 + 28 + $nvr_logo_height;
			
			$nvr_print_custom_css .='div.logoimg img{height:'. $nvr_logo_height .'px;}';
			$nvr_print_custom_css .='.nvrlayout1 .sf-menu li:hover ul, .nvrlayout1 .sf-menu li.sfHover ul,
									.nvrlayout2 .sf-menu li:hover ul, .nvrlayout2 .sf-menu li.sfHover ul,
									.nvrlayout3 .sf-menu li:hover ul, .nvrlayout3 .sf-menu li.sfHover ul {top: '. $nvr_header_height .'px;}';
			$nvr_print_custom_css .='.nvrlayout1 .sf-menu > li > a, .nvrlayout2 .sf-menu > li > a, .nvrlayout3 .sf-menu > li > a{height:'. $nvr_header_height .'px; line-height:'. $nvr_header_height .'px;}';
			$nvr_print_custom_css .='.nvrlayout1 #topminicart, .nvrlayout2 #topminicart, .nvrlayout3 #topminicart{height:'. $nvr_header_height .'px;}';
			$nvr_print_custom_css .='.nvrlayout1 .searchbox input.submit, .nvrlayout2 .searchbox input.submit, .nvrlayout3 .searchbox input.submit{height:'. $nvr_header_height .'px;}';
			$nvr_print_custom_css .='.nvrlayout1 .searchbox .searcharea.shown, .nvrlayout2 .searchbox .searcharea.shown, .nvrlayout3 .searchbox .searcharea.shown{top:'. $nvr_header_height .'px;}';
			$nvr_print_custom_css .='.nvrlayout1 .cartlistwrapper, .nvrlayout2 .cartlistwrapper, .nvrlayout3 .cartlistwrapper{top:'. $nvr_header_height .'px;}';
			$nvr_print_custom_css .='a.nav-toggle{height:'. $nvr_header_height .'px; line-height:'. $nvr_header_height .'px;}';
		}
		
		if(strlen($nvr_optMenuFont)>2){
			$nvr_print_custom_css .='.topnav li a, .topnav li a:visited, .gn-menu li a, .gn-menu li a:visited{font-family:'. $nvr_MenuOutput .' sans-serif !important;}';
		}elseif($nvr_generalTextOutput!=''){
			$nvr_print_custom_css .='.topnav li a, .topnav li a:visited, .gn-menu li a, .gn-menu li a:visited{font-family:'. $nvr_generalTextOutput .', sans-serif !important;}';
		}
		
		if(strlen($nvr_opt2ndMenuFont)>2){
			$nvr_print_custom_css .='#secondarynav li a, #secondarynav li a:visited{font-family:'. $nvr_2ndMenuOutput .' sans-serif !important;}';
		}elseif($nvr_generalTextOutput!=''){
			$nvr_print_custom_css .='#secondarynav li a, #secondarynav li a:visited{font-family:'. $nvr_generalTextOutput .', sans-serif !important;}';
		}
		
		$nvr_2ndfontcss = '';
		if($nvr_opt2ndFont){
			foreach($nvr_opt2ndFont as $nvr_2ndFont => $nvr_2ndFontVal){
				if($nvr_2ndFont!='google' && $nvr_2ndFont!='subsets' && $nvr_2ndFont!='font-options' && $nvr_2ndFontVal!=''){
					if($nvr_2ndFont=='font-family'){$nvr_2ndFontVal = "'".$nvr_2ndFontVal."'";}
					$nvr_2ndfontcss .= $nvr_2ndFont. ": ".$nvr_2ndFontVal.";";
				}
			}
		}
		$nvr_print_custom_css .='.nvrsecondfont{'. $nvr_2ndfontcss .'}';
		
		$nvr_bigtextcss = '';
		if($nvr_optBigTextFont){
			foreach($nvr_optBigTextFont as $nvr_bigTextFont => $nvr_bigTextFontVal){
				if($nvr_bigTextFont!='google' && $nvr_bigTextFont!='subsets' && $nvr_bigTextFont!='font-options' && $nvr_bigTextFontVal!=''){
					if($nvr_bigTextFont=='font-family'){$nvr_bigTextFontVal = "'".$nvr_bigTextFontVal."'";}
					$nvr_bigtextcss .= $nvr_bigTextFont. ": ".$nvr_bigTextFontVal.";";
				}
			}
		}
		$nvr_print_custom_css .='.bigtext{'. $nvr_bigtextcss .'}';
		
		$nvr_headingfontcss = '';
		if($nvr_optHeadingFont){
			foreach($nvr_optHeadingFont as $nvr_HeadingFont => $nvr_HeadingFontVal){
				if($nvr_HeadingFont!='google' && $nvr_HeadingFont!='subsets' && $nvr_HeadingFont!='font-options' && $nvr_HeadingFontVal!=''){
					if($nvr_HeadingFont=='font-family'){$nvr_HeadingFontVal = "'".$nvr_HeadingFontVal."'";}
					$nvr_headingfontcss .= $nvr_HeadingFont. ": ".$nvr_HeadingFontVal.";";
				}
			}
		}
		$nvr_print_custom_css .='h1, h2, h3, h4, h5, h6{'. $nvr_headingfontcss .'}';
		
		$nvr_print_custom_css .='#subbody .container{max-width:'. $nvr_txtContainerWidth.'px;}';
		$nvr_print_custom_css .='.nvrlayout1.nvrboxed #subbody, .nvrlayout3.nvrboxed #subbody, .nvrlayout5.nvrboxed #subbody, .nvrlayout6.nvrboxed #subbody, .nvrlayout7.nvrboxed #subbody{max-width:'. $nvr_txtContainerWidth.'px;}';
		
		$nvr_outermain = '';
		if($nvr_cf_bgMainC){
			$nvr_outermain .='background-image:url('. $nvr_cf_bgMainC .');';
		}
		$nvr_outermain .='background-repeat:'. $nvr_cf_bgRepeatMainC .'; background-position:'. $nvr_cf_bgPosMainC .'; background-color:'. $nvr_cf_bgColorMainC .';';
		$nvr_print_custom_css .= '#outermain{'.$nvr_outermain.'}';
		
		
		$nvr_outeraftercontent = '';
		if($nvr_cf_bgAfterC){
			$nvr_outeraftercontent .= 'background-image:url('. $nvr_cf_bgAfterC .');';
		}
		$nvr_outeraftercontent .= 'background-repeat:'. $nvr_cf_bgRepeatAfterC .'; background-position:'. $nvr_cf_bgPosAfterC .'; background-color:'. $nvr_cf_bgColorAfterC .';';
		$nvr_print_custom_css .= '#outeraftercontent{'. $nvr_outeraftercontent .'}';
		
		$nvr_footerwrapper = '';
		if($nvr_cf_bgFooter){
			$nvr_footerwrapper .= 'background-image:url('. $nvr_cf_bgFooter .');';
		}
		$nvr_footerwrapper .= 'background-repeat:'. $nvr_cf_bgRepeatFooter .'; background-position:'. $nvr_cf_bgPosFooter .'; background-color:'. $nvr_cf_bgColorFooter .';';
		$nvr_print_custom_css .= '#footerwrapper{'. $nvr_footerwrapper .'}';
		
		return $nvr_print_custom_css;
		
	}// end function nvr_print_stylesheet
}
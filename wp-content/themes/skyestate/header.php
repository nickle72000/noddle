<?php
/**
 * The Header for our theme.
 *
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?> class="no-js">
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?> class="no-js">
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?> class="no-js">
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?> class="no-js">
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php 
/* We add some JavaScript to pages with the comment form
 * to support sites with threaded comments (when in use).
 */
if ( is_singular() && get_option( 'thread_comments' ) )
	wp_enqueue_script( 'comment-reply' );

/* Always have wp_head() just before the closing </head>
 * tag of your theme, or you will break many plugins, which
 * generally use this hook to add elements to <head> such
 * as styles, scripts, and meta tags.
 */

$nvr_shortname = THE_SHORTNAME;
$nvr_initial = THE_INITIAL;

wp_head(); /* the novaro' custom content for wp_head is in includes/header-functions.php */ ?>
</head><?php 

$nvr_pid = nvr_get_postid();
$nvr_custom = nvr_get_customdata($nvr_pid);
if(isset($nvr_custom["show_breadcrumb"][0])){
	if($nvr_custom["show_breadcrumb"][0]=="true"){
		$nvr_showbc = true;
	}else{
		$nvr_showbc = false;
	}
}
$nvr_showbc = true;
$nvr_cf_enableSlider 	= (isset($nvr_custom["enable_slider"][0]))? $nvr_custom["enable_slider"][0] : "";
$nvr_cf_disableTitle 	= (isset($nvr_custom["disable_title"][0]))? $nvr_custom["disable_title"][0] : "";

if($nvr_cf_enableSlider=="true" && !is_search()){
	$nvr_issliderdisplayed = true;
}else{
	$nvr_issliderdisplayed = false;
}

if($nvr_cf_disableTitle=="true"){
	$nvr_istitledisplayed = false;
}else{
	$nvr_istitledisplayed = true;
}

$nvr_allsitelayout		= array('nvrlayout1','nvrlayout2','nvrlayout3','nvrlayout4','nvrlayout5','nvrlayout6','nvrlayout7');
$nvr_allcontlayout		= array('nvrboxed','nvrfullwidth');
$nvr_alltopbar			= array('nvrshowtopbar','nvrnotopbar');
$nvr_sitelayout			= nvr_get_option( $nvr_shortname . '_web_layout');
$nvr_topbar			= nvr_get_option( $nvr_shortname . '_topbar');
$nvr_contlayout			= nvr_get_option( $nvr_shortname . '_container_layout' );

if(function_exists('simpleSessionGet') && nvr_get_option( $nvr_shortname . '_demo_mode' )=="1"){
	$nvr_sitelayout = simpleSessionGet('site_layout', 'nvrlayout1');
	$nvr_topbar	= simpleSessionGet('topbar', 'nvrshowtopbar');;
	$nvr_contlayout = simpleSessionGet('container_layout', 'nvrfullwidth');
}
$nvr_cf_siteLayout	 	= (isset($nvr_custom["site_layout"][0]))? $nvr_custom["site_layout"][0] : $nvr_sitelayout;

if(!in_array($nvr_cf_siteLayout,$nvr_allsitelayout)){
	$nvr_cf_siteLayout = 'nvrlayout1';
}

$nvr_cf_contlayout	 	= (isset($nvr_custom["container_layout"][0]))? $nvr_custom["container_layout"][0] : $nvr_contlayout;
if(!in_array($nvr_cf_contlayout,$nvr_allcontlayout)){
	$nvr_cf_contlayout = 'nvrfullwidth';
}

$nvr_cf_topbar	 	= (isset($nvr_custom["topbar"][0]))? $nvr_custom["topbar"][0] : $nvr_topbar;
if(!in_array($nvr_cf_topbar,$nvr_alltopbar)){
	$nvr_cf_topbar = 'nvrshowtopbar';
}

if(function_exists('simpleSessionSet') && nvr_get_option( $nvr_shortname . '_demo_mode' )=="1"){
	simpleSessionSet('site_layout', $nvr_cf_siteLayout);
	simpleSessionSet('container_layout', $nvr_cf_contlayout);
	simpleSessionSet('topbar', $nvr_cf_topbar);
}
$nvr_txtContainerWidth = nvr_get_bodycontainer();

$nvr_bodyclass = array($nvr_shortname);
$nvr_bodyclass[] = $nvr_cf_siteLayout;
$nvr_bodyclass[] = $nvr_cf_contlayout;
$nvr_bodyclass[] = $nvr_cf_topbar;
if($nvr_issliderdisplayed){
	$nvr_bodyclass[] = 'nvrslideron';
}

if($nvr_txtContainerWidth>1100){
	$nvr_bodyclass[] = 'nvr1100more';
}
?>
<body <?php body_class($nvr_bodyclass); ?>>


<div id="subbody">
	<div id="outercontainer">
    	
        <div id="headertext">
            <div class="container">
                <div class="row">
                    <?php 
                    /***** file : engine/header-functions.php
                    - nvr_output_headertext - 5
                    - nvr_output_headertext2 - 8
                    - nvr_output_wpmlselector - 20
					- nvr_secondary_menu - 30
					- nvr_front_submission_menu - 40
                    *****/
                    do_action('nvr_output_toparea');
                    ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <!-- HEADER -->
        <header id="outertop">
        	<?php
			if($nvr_issliderdisplayed && $nvr_cf_siteLayout=='nvrlayout3'){
            	get_template_part( 'slider');
            }
			?>
            
            <div id="outerheaderwrapper">
                <div id="outerheader">
                    <div class="topcontainer container">
                        <div class="row">
                            <div class="logo columns">
								<?php nvr_logo(); // print the logo html ?>
                                <div class="clearfix"></div>
                            </div>
                            <?php
                                
                                if($nvr_cf_siteLayout=='nvrlayout4'){
                                    nvr_primary_menu(); /* file: engine/header-functions.php */
                                    nvr_output_minicart(); /* file: engine/header-functions.php */
                                }else{
									nvr_output_minicart(); /* file: engine/header-functions.php */
                                    nvr_primary_menu(); /* file: engine/header-functions.php */
                                }
                            ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>

			<?php 
            if($nvr_issliderdisplayed && $nvr_cf_siteLayout!='nvrlayout3'){
            	get_template_part( 'slider');
            }
			
			if(!$nvr_issliderdisplayed && $nvr_istitledisplayed) {
            ?>
            <!-- AFTER HEADER -->
            <div id="outerafterheader">
                <div class="container">
                    <div id="afterheader" class="row">
                        <section id="pagetitlecontainer" class=" seven columns">
                            <?php
                            get_template_part( 'title');
                            
							$nvr_pagedesc = (isset($nvr_custom['_'.$nvr_initial.'_pagedesc'][0]) && $nvr_custom['_'.$nvr_initial.'_pagedesc'][0]!="")? $nvr_custom['_'.$nvr_initial.'_pagedesc'][0] : "";
							if($nvr_pagedesc){
								echo '<span class="pagedesc">'.esc_html( $nvr_pagedesc ).'</span>';
							}
                            ?>
                        </section>
                        <?php if($nvr_showbc){ ?>
                        <div id="breadcrumbcontainer" class="five columns"><?php nvr_breadcrumb(); ?></div>
                        <?php } ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- END AFTER HEADER -->
            <?php 
            }/* end if( !$nvr_issliderdisplayed ) */ 
            ?>
		</header>
        <!-- END HEADER -->
        
        <!-- MIDDLE -->
        <div id="outermiddle">
        <!-- SECTION BUILDER BEFORE CONTENT -->
		<?php 
		$nvr_sectionbuilderb = array();
		if(isset( $nvr_custom['_'.$nvr_initial.'_sectionbuilder_before'][0] )){
			$nvr_sectionbuilderb = unserialize($nvr_custom['_'.$nvr_initial.'_sectionbuilder_before'][0]);
		}
		
        nvr_section_builder($nvr_sectionbuilderb);
        ?>
        <!-- END SECTION BUILDER BEFORE CONTENT -->
        
        <?php
	 	$nvr_pagelayout = nvr_get_sidebar_position($nvr_pid);
		$p_type=get_post_type( $nvr_pid );
		
		if($p_type=='propertys'){$nvr_pagelayout='one-col'; }
		//echo $nvr_pagelayout;
		if($nvr_pagelayout!='one-col'){
			$nvr_mcontentclass = "hassidebar";
			if($nvr_pagelayout=="two-col-left"){
				$nvr_mcontentclass .= " mborderright";
			}else{
				$nvr_mcontentclass .= " mborderleft";
			}
		}else{
			$nvr_mcontentclass = "twelve columns nosidebar";
		}
		
		$nvr_pagelayout = nvr_get_sidebar_position($nvr_pid);
		$numcol = 1;
		$p_type=get_post_type( $nvr_pid );
		
		if($p_type=='propertys'){$nvr_pagelayout='one-col'; }
		if($nvr_pagelayout!='one-col'){
			if($nvr_pagelayout=="two-col-left"){
				$numcol = 2;
			}elseif($nvr_pagelayout=="two-col-right"){
				$numcol = 2;
			}
			$nvr_mcontentclass = "hassidebar ".$nvr_pagelayout;
		}else{
			$numcol = 1;
			$nvr_mcontentclass = "twelve columns nosidebar";
		}
		?>
        <!-- MAIN CONTENT -->
        <div id="outermain">
        	<div id="main-gradienttop">
        	<div class="container">
            	<div class="row">
                
                <section id="maincontent" class="<?php echo esc_attr( $nvr_mcontentclass ); ?>">
                
                <?php if($nvr_pagelayout!='one-col'){ ?>
                        
                    <section id="content" class="eight columns">
                        <div class="main">
                
                <?php } ?>
                	
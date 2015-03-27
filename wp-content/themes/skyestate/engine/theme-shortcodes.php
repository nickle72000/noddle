<?php
$nvr_initial = THE_INITIAL;
$nvr_shortname = THE_SHORTNAME;

/******PORTFOLIO CAROUSEL******/
if(!function_exists('nvr_portfoliocarousel')){
	function nvr_portfoliocarousel($atts, $content = null) {
		extract(shortcode_atts(array(
					"title" => '',
					"cat" => '',
					"showposts" => '-1',
					"full" => 'yes',
					"link" => '',
					"linktext" => __('Show Portfolio', THE_LANG)
		), $atts));
			
			$nvr_pcclass = '';
			if($link!=''){
				$nvr_pcclass .= ' haslink';
			}
			if($linktext==''){
				$linktext = __('Show Portfolio', THE_LANG);
			}
			
			if($full=="yes"){
				$nvr_pcclass .= ' nvr-fullwidthwrap';
			}
			
			$nvr_output  ='<div class="pcarousel '.esc_attr( $nvr_pcclass ).'">';
				
			$i=1;
			$nvr_argquery = array(
				'post_type' => 'portofolio',
				'showposts' => $showposts
			);
			if($cat){
				$nvr_argquery['tax_query'] = array(
					array(
						'taxonomy' => 'portfoliocat',
						'field' => 'slug',
						'terms' => $cat
					)
				);
			}
			query_posts($nvr_argquery);
			global $post;
			
			$nvr_output  .='<div class="flexslider-carousel row">';
				$nvr_output  .='<ul class="slides">';
				
				$nvr_havepost = false;
				while (have_posts()) : the_post();
					$nvr_havepost = true;
					$imgsize	= 'portfolio-image-square';
					$pimgsize 	='square';
					$classpf	= 'imgsize-'.$pimgsize.' ';
					$nvr_output .= nvr_pf_get_box( $imgsize, get_the_ID(), $classpf );
				
				 	$i++; $addclass=""; 
				
				endwhile; wp_reset_query();
				 
				$nvr_output .='</ul>';
			 $nvr_output .='</div>';
			 if($link!=''){
				$nvr_output .= '<a class="button color2 pclink" href="'.esc_url( $link ).'">'.$linktext.'</a>';	
			}
			 $nvr_output .='</div>';
			 if($nvr_havepost){
			 	return do_shortcode($nvr_output);
			}else{
				return false;
			}
	}
	
	add_shortcode( 'portfolio_carousel', 'nvr_portfoliocarousel' );
}

if(!function_exists('nvr_portfoliofilter')){
	function nvr_portfoliofilter($atts, $content = null){
		extract(shortcode_atts(array(
					"title" => '',
					"cat" => '',
					'type' => 'grid',
					"col" => 4,
					'nospace' => '',
					"showposts" => '-1'
		), $atts));
		
		$nvr_initial = THE_INITIAL;
		
		$cats = $cat;
		$showpost = $showposts;
		$orderby = "date";
		$ordersort = "DESC";
		$categories = explode(",",$cats);
	
		if($type!= 'grid' && $type!='classic' && $type!='masonry'){
			$type = 'grid';
		}
		
		if($col<3 || $col>5){
			$col = 4;
		}
		$type.='-'.$col;
		$arrtype = explode("-",$type);
		$ptype = $arrtype[0];
		if($nospace=="yes"){
			$pspace = 'nospace';
		}else{
			$pspace = 'space';
		}
		$column = intval($arrtype[1]);
		$freelayout = false;
		
		$approvedcats = array();
		foreach($categories as $category){
			$catname = get_term_by('slug',$category,"portfoliocat");
			if($catname!=false){
				$approvedcats[] = $catname;
			}
		}
		
		$catslugs = array();
		$nvr_outputfilter = '';
		if(count($approvedcats)>1){
			$nvr_outputfilter .= '<ul class="filterlist portfolio-cat-filter option-set clearfix " data-option-key="filter">';
				$nvr_outputfilter .= '<li class="alpha selected"><a href="#filter" data-option-value="*">'. __('All Categories', THE_LANG ).'</a></li>';
				$filtersli = '';
				$numli = 1;
				foreach($approvedcats as $approvedcat){
					if($numli==1){
						$liclass = 'omega';
					}else{
						$liclass = '';
					}
					$filtersli = '<li class="'.esc_attr( $liclass ).'"><a href="#filter" data-option-value=".'. esc_attr( $approvedcat->slug ).'">'.$approvedcat->name.'</a></li>'.$filtersli;
					$catslugs[] = $approvedcat->slug;
					$numli++;
				}
				$nvr_outputfilter .= $filtersli;
			$nvr_outputfilter .= '</ul>';
			$hasfilter = true;
		}elseif(count($approvedcats)==1){
			$catslugs[] = $approvedcats[0]->slug;
			$hasfilter = false;
		}else{
			$hasfilter = false;
		}
	
		$idnum = 0;
	
		if($column!= 3 && $column!= 4 && $column!= 5 ){
			$column = 4;
		}
		$pfcontainercls = "nvr-pf-col-".$column;
		$pfcontainercls .= " ".$ptype;
		$pfcontainercls .= " ".$pspace;
		$imgsize = "portfolio-image";
		
		if($showpost==""){$showpost="-1";}
		
		$nvr_argquery = array(
			'post_type' => 'portofolio',
			'orderby' => $orderby,
			'order' => $ordersort
		);
		$nvr_argquery['showposts'] = $showpost;
		
		if(count($catslugs)>0){
			$nvr_argquery['tax_query'] = array(
				array(
					'taxonomy' => 'portfoliocat',
					'field' => 'slug',
					'terms' => $catslugs
				)
			);
		}
	
		query_posts($nvr_argquery); 
		global $post, $wp_query;
		
		$nvr_output = '<div class="portfolio_filter">';
			$nvr_output .= $nvr_outputfilter;
			$nvr_output .= '<div class="nvr-pf-container row">';
				$nvr_output .= '<ul id="nvr-pf-filter" class="'. esc_attr( $pfcontainercls ) .'">';
			
				while ( have_posts() ) : the_post(); 
						
						$idnum++;
						if(!$freelayout){
							if($column=="5"){
								$classpf = 'one_fifth columns ';
							}elseif($column=="4"){
								$classpf = 'three columns ';
							}else{
								$classpf = 'four columns ';
							}
						}else{
							$classpf = 'free columns ';
						}
						
						if(($idnum%$column) == 1){ $classpf .= "first ";}
						if(($idnum%$column) == 0){$classpf .= "last ";}
						
						$custompf = get_post_custom( get_the_ID() );
						
						$pimgsize = '';
						if($ptype=='masonry'){
							$pimgsize = (isset($custompf["_".$nvr_initial."_pimgsize"][0]))? $custompf["_".$nvr_initial."_pimgsize"][0] : "";
							
							if($pimgsize=='square'){
								$imgsize = 'portfolio-image-square';
							}elseif($pimgsize=='portrait'){
								$imgsize = 'portfolio-image-portrait';
							}elseif($pimgsize=='landscape'){
								$imgsize = 'portfolio-image-landscape';
							}
							$classpf .= $pimgsize.' ';
						}elseif($ptype=='grid'){
							$imgsize = 'portfolio-image-square';
							$pimgsize='square';
						}
						$classpf .= 'imgsize-'.$pimgsize.' ';
						
						$thepfterms = get_the_terms( get_the_ID(), 'portfoliocat');
						
						$literms = "";
						if ( $thepfterms && ! is_wp_error( $thepfterms ) ){
			
							$approvedterms = array();
							foreach ( $thepfterms as $term ) {
								$approvedterms[] = $term->slug;
							}			
							$literms = implode( " ", $approvedterms );
						}
						
						$nvr_output .= nvr_pf_get_box( $imgsize, get_the_ID(), $classpf.' element '.$literms );
							
						$classpf=""; 
							
				endwhile; // End the loop. Whew.
	
				$nvr_output .= '<li class="pf-clear"></li>';
				$nvr_output .= '</ul>';
				$nvr_output .= '<div class="clearfix"></div>';
			$nvr_output .= '</div><!-- end #nvr-portfolio -->';
		$nvr_output .= '</div>';
		wp_reset_query();
		return $nvr_output;
	}
	add_shortcode( 'portfolio_filter', 'nvr_portfoliofilter' );
}

if(!function_exists('nvr_propertyfilter')){
	function nvr_propertyfilter($atts, $content = null){
		extract(shortcode_atts(array(
					"title" => '',
					"term" => '',
					"cat" => '',
					"showposts" => '-1'
		), $atts));
		
		$nvr_initial = THE_INITIAL;
		$nvr_shortname = THE_SHORTNAME;
		
		$nvr_cursymbol = nvr_get_option( $nvr_shortname . '_currency_symbol');
		$nvr_curplace = nvr_get_option( $nvr_shortname . '_currency_place');
		$nvr_unit = nvr_get_option( $nvr_shortname . '_measurement_unit');
		
		$cats = $cat;
		$showpost = $showposts;
		$orderby = "date";
		$ordersort = "DESC";
		$categories = explode(",",$cats);
		
		if($term=='category' || $term=='purpose'){
			$propterm = 'property_'.$term;
		}else{
			$propterm = 'property_category';
		}
		
		$approvedcats = array();
		foreach($categories as $category){
			$catname = get_term_by('slug',$category,$propterm);
			if($catname!=false){
				$approvedcats[] = $catname;
			}
		}
		
		$catslugs = array();
		$nvr_outputfilter = '';
		if(count($approvedcats)>1){
			$nvr_outputfilter .= '<ul class="filterlist property-cat-filter" class="option-set clearfix " data-option-key="filter">';
				$nvr_outputfilter .= '<li class="alpha selected"><a href="#filter" data-option-value="*">'. __('All', THE_LANG ).'</a></li>';
				$filtersli = '';
				$numli = 1;
				foreach($approvedcats as $approvedcat){
					if($numli==1){
						$liclass = 'omega';
					}else{
						$liclass = '';
					}
					$filtersli = '<li class="'.esc_attr( $liclass ).'"><a href="#filter" data-option-value=".'. esc_attr( $approvedcat->slug ).'">'.$approvedcat->name.'</a></li>'.$filtersli;
					$catslugs[] = $approvedcat->slug;
					$numli++;
				}
				$nvr_outputfilter .= $filtersli;
			$nvr_outputfilter .= '</ul>';
			$hasfilter = true;
		}elseif(count($approvedcats)==1){
			$catslugs[] = $approvedcats[0]->slug;
			$hasfilter = false;
		}else{
			$hasfilter = false;
		}
	
		$idnum = 0;
	
		$nvr_pfcontainercls = "nvr-prop-col";
		$nvr_imgsize = "property-image";
		
		if($showpost==""){$showpost="-1";}
		
		$nvr_argquery = array(
			'post_type' => 'propertys',
			'orderby' => $orderby,
			'order' => $ordersort
		);
		$nvr_argquery['showposts'] = $showpost;
		
		if(count($catslugs)>0){
			$nvr_argquery['tax_query'] = array(
				array(
					'taxonomy' => $propterm,
					'field' => 'slug',
					'terms' => $catslugs
				)
			);
		}
	
		query_posts($nvr_argquery); 
		global $post, $wp_query;
		
		$nvr_output = '<div class="property_filter">';
			$nvr_output .= $nvr_outputfilter;
			$nvr_output .= '<div class="nvr-prop-container row">';
				$nvr_output .= '<ul id="nvr-prop-filter" class="'. esc_attr( $nvr_pfcontainercls ) .'">';
			
				while ( have_posts() ) : the_post(); 
						
						$idnum++;
						
						$custompf = get_post_custom( get_the_ID() );
						
						$thepfterms = get_the_terms( get_the_ID(), $propterm );
						
						$literms = "";
						if ( $thepfterms && ! is_wp_error( $thepfterms ) ){
			
							$approvedterms = array();
							foreach ( $thepfterms as $term ) {
								$approvedterms[] = $term->slug;
							}			
							$literms = implode( " ", $approvedterms );
						}
						
						$nvr_output .= nvr_prop_get_box( $nvr_imgsize, get_the_ID(), 'element columns '.$literms, $nvr_unit, $nvr_cursymbol, $nvr_curplace );
							
						$classpf=""; 
							
				endwhile; // End the loop. Whew.
	
				$nvr_output .= '<li class="nvr-prop-clear"></li>';
				$nvr_output .= '</ul>';
				$nvr_output .= '<div class="clearfix"></div>';
			$nvr_output .= '</div><!-- end .nvr-property-container -->';
		$nvr_output .= '</div>';
		wp_reset_query();
		return $nvr_output;
	}
	add_shortcode( 'property_filter', 'nvr_propertyfilter' );
}

if(!function_exists('nvr_propertycarousel')){
	function nvr_propertycarousel($atts, $content = null) {
		extract(shortcode_atts(array(
					"title" => '',
					"term" => '',
					"cat" => '',
					"class" => '',
					"showposts" => '-1',
		), $atts));
		
		$nvr_shortname = THE_SHORTNAME;
		$nvr_initial = THE_INITIAL;
		
		$nvr_cursymbol = nvr_get_option( $nvr_shortname . '_currency_symbol');
		$nvr_curplace = nvr_get_option( $nvr_shortname . '_currency_place');
		$nvr_unit = nvr_get_option( $nvr_shortname . '_measurement_unit');
		
		if($term=='category' || $term=='purpose'){
			$propterm = 'property_'.$term;
		}else{
			$propterm = 'property_category';
		}
			
			$nvr_pcclass = $class;
			
			$nvr_output  ='<div class="propcarousel '.esc_attr( $nvr_pcclass ).'">';
				
    			$i=1;
    			$nvr_argquery = array(
    				'post_type' => 'propertys',
    				'showposts' => $showposts
    			);
    			if($cat){
    				$nvr_argquery['tax_query'] = array(
    					array(
    						'taxonomy' => $propterm,
    						'field' => 'slug',
    						'terms' => $cat
    					)
    				);
    			}
    			query_posts($nvr_argquery);
    			global $post;
    			
    			$nvr_output  .='<div class="flexslider-carousel row">';
    				$nvr_output  .='<ul class="slides">';
    				
    				$nvr_havepost = false;
    				while (have_posts()) : the_post();
    					$nvr_havepost = true;
    					$imgsize	= 'property-image';
    					$pimgsize 	='default';
    					$classpf	= 'imgsize-'.$pimgsize.' ';
    					$nvr_output .= nvr_prop_get_box( $imgsize, get_the_ID(), $classpf, $nvr_unit, $nvr_cursymbol, $nvr_curplace );
    				
    				 	$i++; $addclass=""; 
    				
    				endwhile; wp_reset_query();
    				 
    				$nvr_output .='</ul>';
    			 $nvr_output .='</div>';
			 
			 $nvr_output .='</div>';
			 if($nvr_havepost){
			 	return do_shortcode($nvr_output);
			}else{
				return false;
			}
	}
	
	add_shortcode( 'property_carousel', 'nvr_propertycarousel' );
}

if(!function_exists('nvr_socialnetwork')){
	function nvr_socialnetwork($atts, $content) {
		extract(shortcode_atts(array(
					"usetext" => '',
					"vertical" => '',
		), $atts));
		
		if($usetext=='yes'){
			$usetext = true;
		}else{
			$usetext = false;
		}
		
		if($vertical=='yes'){
			$vertical = true;
		}else{
			$vertical = false;
		}
		
		return nvr_socialicon($usetext, $vertical);
	}
	add_shortcode('socialnetwork','nvr_socialnetwork');
}

/******COUNTERS******/
if(!function_exists('nvr_counters')){
	function nvr_counters($atts, $content = null) {
		extract(shortcode_atts(array(
			'color' => '',
			'align' => '',
			'size' => '',
			'class' => ''
		), $atts));
		
		$style = '';
		if($color!=''){
			$style .= 'color:'.$color.';';
		}
		if($align!=''){
			$style .= 'text-align:'.$align.';';
		}
		if(is_numeric($size)){
			$style .= 'font-size:'.$size.'px;';
			$style .= 'height:'.$size.'px;';
			$style .= 'line-height:'.$size.'px;';
		}
		$style = 'style="'.esc_attr( $style ).'"';
		$nvr_output = '<div class="counters '.esc_attr( $class ).'" '.$style.'>'.$content.'</div>';
		return do_shortcode($nvr_output);
	}
	add_shortcode( 'counters', 'nvr_counters' );
}

/******BANNER******/
if(!function_exists('nvr_bannerimg')){
	function nvr_bannerimg($atts, $content = null) {
		extract(shortcode_atts(array(
			'src' => '',
			'link' => '',
			'margin' => '',
			'padding' => '',
			'class' => ''
		), $atts));
		
		$nvr_style = '';
		if($margin!=''){
			$nvr_style .= 'top:'.$margin.'px;';
			$nvr_style .= 'left:'.$margin.'px;';
			$nvr_style .= 'right:'.$margin.'px;';
			$nvr_style .= 'bottom:'.$margin.'px;';
		}
		
		if($padding!=''){
			$nvr_style .= 'padding:'.$padding.'px;';
		}
		
		if($nvr_style!=''){
			$nvr_style = 'style="'.esc_attr( $nvr_style ).'"';
		}
		
		$nvr_output = '<div class="bannerimg"><a href="'.esc_url( $link ).'" class="linkrow"><div class="cellcontent" '.$nvr_style.'><table><tr><td>'.$content.'</td></tr></table></div><img src="'.$src.'" alt="" /></a></div>';
		return do_shortcode($nvr_output);
	}
	add_shortcode( 'bannerimg', 'nvr_bannerimg' );
}

/******BIGTEXT******/
if(!function_exists('nvr_bigtext')){
	function nvr_bigtext($atts, $content = null) {
		extract(shortcode_atts(array(
		), $atts));
		$nvr_output = '<h2 class="bigtext"><span>'.$content.'</span></h2>';
		return do_shortcode($nvr_output);
	}
	add_shortcode( 'bigtext', 'nvr_bigtext' );
}
/******SECONDARYTEXT******/
if(!function_exists('nvr_secondarytext')){
	function nvr_secondarytext($atts, $content = null) {
		extract(shortcode_atts(array(
		), $atts));
		$nvr_output = '<span class="secondarytext">'.$content.'</span>';
		return do_shortcode($nvr_output);
	}
	add_shortcode( 'secondarytext', 'nvr_secondarytext' );
}

/******HEADING******/
if(!function_exists('nvr_heading')){
	function nvr_heading($atts, $content = null) {
		extract(shortcode_atts(array(
			'level' => '3',
			'align' => 'center',
			'class' => ''
		), $atts));
		
		$arrH = array('1','2','3','4','5','6');
		if(!in_array($level,$arrH)){
			$level = 3;
		}
		if($align!='center' || $align!='left' || $align!='right'){
			$align = 'center';
		}
		$nvr_output = '<div class="nvr-heading '.esc_attr( $class.' '.$align ).'"><h'.$level.'><span>'.$content.'</span></h'.$level.'><span class="hborder"></span></div>';
		return do_shortcode($nvr_output);
	}
	add_shortcode( 'heading', 'nvr_heading' );
}

/******METER******/
if(!function_exists('nvr_meter')){
	function nvr_meter($atts, $content = null) {
		extract(shortcode_atts(array(
			'title' => 'Skills',
			'percent' => 100
		), $atts));
		
		if(!is_numeric($percent) || $percent > 100){
			$percent = 100;
		}
		
		$nvr_output = '';
		
		$nvr_output .= '<h6 class="marginsmall metertitle">'.$title.'&nbsp;<span>'.$percent.'%</span></h6>';
		$nvr_output .= '<div class="meter"><div style="width:'.esc_attr( $percent ).'%;"></div></div>';
		return $nvr_output;
	}
	add_shortcode( 'meter', 'nvr_meter' );
}

/******BORDER SEPARATOR******/
if(!function_exists('nvr_borderseparator')){
	function nvr_borderseparator($atts, $content = null) {
		extract(shortcode_atts(array(
			'width' => 100,
			'height' => 2,
			'color' => '',
			'align' => 'left',
			'class' => ''
		), $atts));
		
		if(!is_numeric($width)){
			$width = 100;
		}
		
		if(!is_numeric($height)){
			$height = 2;
		}
		
		if($align!='left' && $align!='right' && $align!='center'){
			$align = 'left';
		}
		
		$colortext = '';
		if(!empty($color)){
			$colortext = 'background-color:'.$color.';';
		}
		$nvr_style = 'width:'.$width.'px; height:'.$height.'px; '.$colortext;
		$nvr_output = '';
		$nvr_output .= '<div class="bordersep '.esc_attr( $class.' '.$align ).'"><div class="bordershow" style="'.esc_attr( $nvr_style ).'"></div><div class="clearfix"></div></div>';
		return $nvr_output;
	}
	add_shortcode( 'bordercustom', 'nvr_borderseparator' );
}

/******SLIDER******/
if(!function_exists('nvr_sliders')){
	function nvr_sliders($atts, $content = null) {
		extract(shortcode_atts(array(
			'id' => '',
			'title' => '',
		), $atts));
		if($id!=""){
			$ids = 'id="'.esc_attr( $id ).'" ';
		}else{
			$ids = '';
		}
		$nvr_output  = '<div '.$ids.' class="minisliders flexslider">';
		
		if($title!=""){
			$nvr_output  .='<div class="titlecontainer"><h2 class="contenttitle"><span>'.$title.'</span></h2></div>';
		}
		
		$nvr_output	.= '<ul class="slides">';
		$nvr_output	.= $content;
		$nvr_output	.= '</ul>';
		$nvr_output	.= '<div class="clearfix"></div>';
		$nvr_output .= '</div>';
		return do_shortcode($nvr_output);
	}
	add_shortcode( 'sliders', 'nvr_sliders' );
}
if(!function_exists('nvr_slide')){
	function nvr_slide($atts, $content = null) {
		extract(shortcode_atts(array(
			'id' 	=> '',
			'class'	=> ''
		), $atts));
		if($id!=""){
			$ids = 'id="'.esc_attr( $id ).'" ';
		}else{
			$ids = '';
		}
		$classes = 'class="slide '.esc_attr( $class ).'" ';
		
		$nvr_output  = '<li '.$ids.$classes.'>';
		$nvr_output	.= $content;
		$nvr_output	.= '</li>';
		return do_shortcode($nvr_output);
	}
	add_shortcode( 'slide', 'nvr_slide' );
}

if(!function_exists('nvr_people')){
	function nvr_people($atts, $content = null) {
		extract(shortcode_atts(array(
			'id' 	=> '',
			'class'	=> '',
			'col' => '3',
			'cat' => '',
			'showposts' => 3,
			'showtitle' => 'yes',
			'showinfo' => 'yes',
			'showthumb' => 'yes'
		), $atts));
		
		$nvr_initial = THE_INITIAL;
		$nvr_shortname = THE_SHORTNAME;
		
		$catname = get_term_by('slug', $cat, 'peoplecat');
		$showtitle = ($showtitle=='yes')? true : false;
		$showinfo = ($showinfo=='yes')? true : false;
		$showthumb = ($showthumb=='yes')? true : false;
		$showposts = (is_numeric($showposts))? $showposts : 5;
		
		if($col!='3' && $col!='4'){
			$col = '3';
		}
		
		if($col=='3'){
			$col = 3;
		}elseif($col=='4'){
			$col = 4;
		}else{
			$col = 3;
		}
		
		$imgsize = 'portfolio-image';
		
		$qryargs = array(
			'post_type' => 'peoplepost',
			'showposts' => $showposts
		);
		if($catname!=false){
			$qryargs['tax_query'] = array(
				array(
					'taxonomy' => 'peoplecat',
					'field' => 'slug',
					'terms' => $catname->slug
				)
			);
		}
		
		query_posts( $qryargs ); 
		global $post;
		
		$nvr_output = "";
		if( have_posts() ){
			$nvr_output .= '<div class="nvr-people '.esc_attr( $class ).'">';
			$nvr_output .= '<ul class="row">';
			$i = 1;
			while ( have_posts() ) : the_post(); 
				
				if($col==3){
					$liclass = 'four columns';
				}elseif($col==4){
					$liclass = 'three columns';
				}else{
					$liclass = '';
				}
				
				$custom = get_post_custom($post->ID);
				$peopleinfo 	= (isset($custom['_'.$nvr_initial.'_people_info'][0]))? $custom['_'.$nvr_initial.'_people_info'][0] : "";
				$peoplethumb 	= (isset($custom['_'.$nvr_initial.'_people_thumb'][0]))? $custom['_'.$nvr_initial.'_people_thumb'][0] : "";
				$peoplepinterest= (isset($custom['_'.$nvr_initial.'_people_pinterest'][0]))? $custom['_'.$nvr_initial.'_people_pinterest'][0] : "";
				$peoplefacebook	= (isset($custom['_'.$nvr_initial.'_people_facebook'][0]))? $custom['_'.$nvr_initial.'_people_facebook'][0] : "";
				$peopletwitter 	= (isset($custom['_'.$nvr_initial.'_people_twitter'][0]))? $custom['_'.$nvr_initial.'_people_twitter'][0] : "";
				$peoplemail 	= (isset($custom['_'.$nvr_initial.'_people_email'][0]))? $custom['_'.$nvr_initial.'_people_email'][0] : "";
				$peopleskype	= (isset($custom['_'.$nvr_initial.'_people_skype'][0]))? $custom['_'.$nvr_initial.'_people_skype'][0] : "";
				$peoplephone 	= (isset($custom['_'.$nvr_initial.'_people_phone'][0]))? $custom['_'.$nvr_initial.'_people_phone'][0] : "";
				$peoplelinkedin = (isset($custom['_'.$nvr_initial.'_people_linkedin'][0]))? $custom['_'.$nvr_initial.'_people_linkedin'][0] : "";
				$peopleyoutube	= (isset($custom['_'.$nvr_initial.'_people_youtube'][0]))? $custom['_'.$nvr_initial.'_people_youtube'][0] : "";
				$peopleinstagram= (isset($custom['_'.$nvr_initial.'_people_instagram'][0]))? $custom['_'.$nvr_initial.'_people_instagram'][0] : "";
				
				if($i%$col==1){
					$liclass .= ' alpha';
				}elseif($i%$col==0 && $col>1){
					$liclass .= ' last';
				}
				
				$nvr_output .= '<li class="'.esc_attr( $liclass ).'">';
					$nvr_output .='<div class="peoplecontainer">';
						if($showthumb){
							$nvr_output .='<div class="imgcontainer">';
								$nvr_output .='<a href="'.esc_url( get_permalink() ).'">';
								if($peoplethumb){
									$nvr_output .= '<img src="'.esc_url( $peoplethumb ).'" alt="'.esc_attr( get_the_title( $post->ID ) ).'" title="'. esc_attr( get_the_title( $post->ID ) ) .'" class="scale-with-grid" />';
								}elseif( has_post_thumbnail( $post->ID ) ){
									$nvr_output .= get_the_post_thumbnail($post->ID, $imgsize, array('class' => 'scale-with-grid'));
								}else{
									$nvr_output .= '<img src="'.esc_url( get_template_directory_uri().'/images/testi-user.png' ).'" alt="'.esc_attr( get_the_title( $post->ID ) ).'" title="'. esc_attr( get_the_title( $post->ID ) ) .'" class="scale-with-grid" />';
								}
								$nvr_output .= '</a>';
								$nvr_output .= '<div class="clearfix"></div>';
							$nvr_output .='</div>';
							
							$bqclass="";
						}else{
							$bqclass="nomargin";
						}
				
						$nvr_output .= '<div class="peopletitle '.esc_attr( $bqclass ).'">';
							if($showtitle || $showinfo){
								if($showtitle){
									$nvr_output .= '<h5 class="fontbold marginoff"><a href="'. esc_url( get_permalink() ).'">'.get_the_title( $post->ID ).'</a></h5>';
								}
								if($showinfo){
									$nvr_output .= '<div class="peopleinfo">'.$peopleinfo.'</div>';
								}
							}
							$nvr_output .= '<div class="hborder"></div>';
							$nvr_output .= '<div class="clearfix"></div>';
						$nvr_output .= '</div>';
						$nvr_output .= '<div class="peoplecontent">';
							$nvr_output .= get_the_content();
						$nvr_output .= '</div>';
						$nvr_output .= '<div class="peoplesocial">';
							if($peoplemail){
								$nvr_output .= '<a href="mailto:'.esc_attr( $peoplemail ).'" target="_blank" title="'.esc_attr( $peoplemail ).'" class="nvrtooltip fa fa-envelope"></a>';
							}
							if($peoplephone){
								$nvr_output .= '<a href="'.esc_attr( $peoplephone ).'" target="_blank" title="'.esc_attr( $peoplephone ).'" class="nvrtooltip fa fa-phone"></a>';
							}
							if($peopleskype){
								$nvr_output .= '<a href="'.esc_attr( $peopleskype ).'" target="_blank" title="'.esc_attr( $peopleskype ).'" class="nvrtooltip fa fa-skype"></a>';
							}
							if($peoplelinkedin){
								$nvr_output .= '<a href="'.esc_url( $peoplelinkedin ).'" target="_blank" class="fa fa-linkedin"></a>';
							}
							if($peopleyoutube){
								$nvr_output .= '<a href="'.esc_url( "http://www.youtube.com/user/".$peopleyoutube ).'" title="'.esc_attr($peopleyoutube).'" target="_blank" class="nvrtooltip fa fa-youtube"></a>';
							}
							if($peopleinstagram){
								$nvr_output .= '<a href="'.esc_url( "http://www.instagram.com/".$peopleinstagram ).'" title="'.esc_attr( '@'.$peopleinstagram).'" target="_blank" class="nvrtooltip fa fa-instagram"></a>';
							}
							if($peoplepinterest){
								$nvr_output .= '<a href="'.esc_url( "http://www.pinterest.com/".$peoplepinterest ).'" title="'.esc_attr($peoplepinterest).'" target="_blank" class="nvrtooltip fa fa-pinterest"></a>';
							}
							if($peoplefacebook){
								$nvr_output .= '<a href="'.esc_url( "http://www.facebook.com/".$peoplefacebook ).'" title="'.esc_attr($peoplefacebook).'" target="_blank" class="nvrtooltip fa fa-facebook"></a>';
							}
							if($peopletwitter){
								$nvr_output .= '<a href="'.esc_url( "http://www.twitter.com/".$peopletwitter ).'" title="'.esc_attr( '@'.$peopletwitter).'" target="_blank" class="nvrtooltip fa fa-twitter"></a>';
							}
							$nvr_output .= '<div class="clearfix"></div>';
						$nvr_output .= '</div>';
						$nvr_output .= '<div class="clearfix"></div>';
					$nvr_output .= '</div>';
				$nvr_output .= '</li>';
				
				$i++;
			endwhile;
				$nvr_output .= '<li class="clearfix"></li>';
			$nvr_output .= '</ul>';
			$nvr_output .= '<div class="clearfix"></div>';
			$nvr_output .= "</div>";
		}else{
			$nvr_output .= '<!-- no people post -->';
		}
		wp_reset_query();
		
		return do_shortcode($nvr_output);
	}
	add_shortcode( 'people', 'nvr_people' );
}

/* Recent Posts */
if( !function_exists('nvr_recentposts') ){
	function nvr_recentposts($atts, $content = null) {
		extract(shortcode_atts(array(
					"cat" => '',
					"showposts" => '-1',
					'limitchar' => 100
		), $atts));
			
			if($content){
				$content = nvr_content_formatter($content);
			}
			$nvr_output  ='<div class="nvr-recentposts">';
	
			$i=1;
			$nvr_argquery = array(
				'showposts' => $showposts
			);
			if($cat){
				$nvr_argquery['category_name'] = $cat;
			}
			query_posts($nvr_argquery);
			global $post;
			
			$nvr_output  .='<div class="rp-grid">';
				$nvr_output  .='<ul class="row">';
				
				$nvr_havepost = false;
				while (have_posts()) : the_post();
				$nvr_havepost = true;
				$excerpt = get_the_excerpt(); 
				$custom = get_post_custom( get_the_ID() );
				$cthumb = (isset($custom["carousel_thumb"][0]))? $custom["carousel_thumb"][0] : "";
				$cimgbig = (isset($custom["lightbox_img"][0]))? $custom["lightbox_img"][0] : "";
				
				$liclass = '';
				if($i%4==1){
					$liclass = ' alpha';
				}elseif($i%4==0){
					$liclass = ' last';
				}
				
				$nvr_output  .='<li class="three columns'. esc_attr( $liclass ).'">';
					$nvr_output .= '<div class="rp-item-container">';
						if( has_post_thumbnail( get_the_ID() ) ){
							$nvr_output  .='<div class="nvr-rp-img">';
								$nvr_output  .='<a href="'.esc_url( get_permalink() ).'" title="'.esc_attr( get_the_title() ).'">'.get_the_post_thumbnail( get_the_ID(), 'blog-post-image', array('class' => 'scale-with-grid')).'</a>';
							$nvr_output  .='</div>';
						}
						
						$nvr_output  .='<div class="nvr-rp-text">';
							$nvr_output  .='<h4 class="nvrsecondfont"><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a></h4>';
							$nvr_output  .= '<div class="meta-date">'. __('By', THE_LANG) . ' <a href="'. esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) .'">'. get_the_author() . '</a> '. __('At', THE_LANG).' '. get_the_time('M d, Y').'</div>';
							$excerpt = nvr_string_limit_char( get_the_excerpt(), $limitchar );
							$nvr_output  .='<div>'.$excerpt.'</div>';
						$nvr_output .= '</div>';
						$nvr_output .= '<a href="'.esc_url( get_permalink() ).'" class="nvr-rp-morelink">'.__('Read More', THE_LANG).'</a>';
						/*
						$nvr_output  .='<div class="entry-utility">';
							$nvr_output .= '<span class="meta-author"><i class="fa fa-user"></i>&nbsp; <a href="'. get_author_posts_url( get_the_author_meta( 'ID' ) ) .'">'. get_the_author().'</a></span>'; 
							$nvr_output .= '<span class="meta-comment"><i class="fa fa-comments"></i>&nbsp; '.get_comments_number( get_the_ID() ). ' '.__('Comments', THE_LANG) .'</span>';
							$nvr_output  .='<div class="clearfix"></div>';
						$nvr_output  .='</div>';
						*/
					$nvr_output .= '</div>';
				$nvr_output  .='</li>';
				
				 $i++; $addclass=""; endwhile; wp_reset_query();
				 
				 $nvr_output .='</ul>';
			 $nvr_output .='</div>';
			 $nvr_output .='</div>';
			 if($nvr_havepost){
			 	return do_shortcode($nvr_output);
			}else{
				return false;
			}
	} 
	add_shortcode( 'recent_posts', 'nvr_recentposts' );
}

if(!function_exists('nvr_testimonial')){
	function nvr_testimonial($atts, $content = null) {
		
		$nvr_initial = THE_INITIAL;
		
		extract(shortcode_atts(array(
			'id' 	=> '',
			'class'	=> '',
			'col' => '1',
			'cat' => '',
			'showposts' => 5,
			'showtitle' => 'yes',
			'showinfo' => 'yes',
			'showthumb' => 'yes'
		), $atts));
		
		$catname = get_term_by('slug', $cat, 'testimonialcat');
		$showtitle = ($showtitle=='yes')? true : false;
		$showinfo = ($showinfo=='yes')? true : false;
		$showthumb = ($showthumb=='yes')? true : false;
		$showposts = (is_numeric($showposts))? $showposts : 5;
		
		if($col!='1' && $col!='2' && $col!='3'){
			$col = '1';
		}
		
		if($col=='3'){
			$col = 3;
		}elseif($col=='2'){
			$col = 2;
		}else{
			$col = 1;
		}
		
		$qryargs = array(
			'post_type' => 'testimonialpost',
			'showposts' => $showposts
		);
		if($catname!=false){
			$qryargs['tax_query'] = array(
				array(
					'taxonomy' => 'testimonialcat',
					'field' => 'slug',
					'terms' => $catname->slug
				)
			);
		}
		
		query_posts( $qryargs ); 
		global $post;
		
		$nvr_output = "";
		if( have_posts() ){
			$nvr_output .= '<div class="nvr-testimonial '.esc_attr( $class ).'">';
			$nvr_output .= '<ul class="row">';
			$i = 1;
			while ( have_posts() ) : the_post(); 
				
				if($col==3){
					$liclass = 'four columns';
				}elseif($col==2){
					$liclass = 'six columns';
				}else{
					$liclass = '';
				}
				
				$custom = get_post_custom($post->ID);
				$testiinfo 	= (isset($custom["_".$nvr_initial."_testi_info"][0]))? $custom["_".$nvr_initial."_testi_info"][0] : "";
				$testithumb = (isset($custom["testi_thumb"][0]))? $custom["testi_thumb"][0] : "";
				
				if(($i%$col) == 1){
					$liclass .= " alpha";
				}elseif($i%$col==0 && $col>1){
					$liclass .= ' last';
				}
				
				$nvr_output .= '<li class="'.esc_attr( $liclass ).'">';
				
				$bqclass = ($showthumb)? '' : 'nomargin';
				
				$nvr_output .= '<div class="testiwrapper">';
				
				if($showthumb){
					$nvr_output .='<div class="testiimg">';
					if($testithumb){
						$nvr_output .='<img src="'.esc_url( $testithumb ).'" width="50" height="50" alt="'.esc_attr( get_the_title( $post->ID ) ).'" title="'.esc_attr( get_the_title( $post->ID ) ).'" class="scale-with-grid" />';
					}elseif( has_post_thumbnail( $post->ID ) ){
						$nvr_output .= get_the_post_thumbnail($post->ID, 'testimonial-thumb', array('class' => 'scale-with-grid'));
					}else{
						$nvr_output .='<img src="'. esc_attr( get_template_directory_uri().'/images/testi-user.png' ) .'" width="50" height="50" alt="'.esc_attr( get_the_title( $post->ID ) ).'" title="'. esc_attr( get_the_title( $post->ID ) ) .'" class="scale-with-grid" />';
					}
					$nvr_output .='<span class="insetshadow"></span>';
					$nvr_output .='</div>';
				}
				
				if($showtitle || $showinfo){
					$nvr_output .= '<div class="testiinfo">';
					if($showtitle){
						$nvr_output .= '<h4 class="testititle">'.get_the_title( $post->ID ).'</h4>';
					}
					if($testiinfo){
						$nvr_output .= $testiinfo;
					}
					$nvr_output .= '</div>';
				}
				
				$nvr_output .= '<div class="clearfix"></div>';
				
				$nvr_output .= '<blockquote class="'.esc_attr( $bqclass ).'">'.get_the_content().'<span class="arrowbubble"></span></blockquote>';
				
				$nvr_output .= '<div class="clearfix"></div>';
				
				$nvr_output .= '</div>';
				
				$nvr_output .= '</li>';
				
				$i++;
			endwhile;
			$nvr_output .= '<li class="clearfix"></li></ul>';
			$nvr_output .= '<div class="clearfix"></div>';
			$nvr_output .= "</div>";
		}else{
			$nvr_output .= '<!-- no testimonial post -->';
		}
		wp_reset_query();
		
		return do_shortcode($nvr_output);
	}
	add_shortcode( 'testimonial', 'nvr_testimonial' );
}

if(!function_exists('nvr_rotatingtestimonial')){
	function nvr_rotatingtestimonial($atts, $content = null) {
		
		$nvr_initial = THE_INITIAL;
		
		extract(shortcode_atts(array(
			'id' 	=> '',
			'class'	=> '',
			'cat' => '',
			'showposts' => 5,
			'showinfo' => 'yes',
			'showthumb' => 'yes'
		), $atts));
		
		$catname = get_term_by('slug', $cat, 'testimonialcat');
		$showinfo = ($showinfo=='yes')? true : false;
		$showthumb = ($showthumb=='yes')? true : false;
		$showposts = (is_numeric($showposts))? $showposts : 5;
		
		$qryargs = array(
			'post_type' => 'testimonialpost',
			'showposts' => $showposts
		);
		if($catname!=false){
			$qryargs['tax_query'] = array(
				array(
					'taxonomy' => 'testimonialcat',
					'field' => 'slug',
					'terms' => $catname->slug
				)
			);
		}
		
		query_posts( $qryargs ); 
		global $post;
		
		$nvr_output = '';
		if( have_posts() ){
			$nvr_output .= '<div class="nvr-trotating flexslider '.esc_attr( $class ).'">';
				$nvr_output .= '<ul class="slides">';
					while ( have_posts() ) : the_post(); 
						$custom = get_post_custom($post->ID);
						$testiinfo 	= (isset($custom["_".$nvr_initial."_testi_info"][0]))? $custom["_".$nvr_initial."_testi_info"][0] : "";
						$testithumb = (isset($custom["testi_thumb"][0]))? $custom["testi_thumb"][0] : "";
						
						$nvr_output .= '<li>';
							
							$nvr_output .= '<blockquote>'.get_the_content().'<span class="arrowbubble"></span></blockquote>';
							$nvr_output .= '<div class="clearfix"></div>';
							
							$nvr_output .= '<div class="testiinfo">';
								$nvr_output .= '<span class="testititle">'.get_the_title( $post->ID ).'</span>';
								if($testiinfo){
									$nvr_output .= ' - '.$testiinfo;
								}
							$nvr_output .= '</div>';
							
							if($showthumb){
								$nvr_output .='<div class="testiimg">';
								if($testithumb){
									$nvr_output .='<img src="'.esc_url( $testithumb ).'" width="50" height="50" alt="'. esc_attr( get_the_title( $post->ID ) ).'" title="'. esc_attr( get_the_title( $post->ID ) ) .'" class="scale-with-grid" />';
								}elseif( has_post_thumbnail( $post->ID ) ){
									$nvr_output .= get_the_post_thumbnail($post->ID, 'testimonial-thumb', array('class' => 'scale-with-grid'));
								}else{
									$nvr_output .='<img src="'. esc_url( get_template_directory_uri().'/images/testi-user.png' ) .'" width="50" height="50" alt="'. esc_attr( get_the_title( $post->ID ) ).'" title="'. esc_attr( get_the_title( $post->ID ) ) .'" class="scale-with-grid" />';
								}
								$nvr_output .='<span class="insetshadow"></span>';
								$nvr_output .='</div>';
							}
							$nvr_output .= '<div class="clearfix"></div>';
						$nvr_output .= '</li>';
						
					endwhile;
				$nvr_output .= '</ul>';
				$nvr_output .= '<div class="clearfix"></div>';
			$nvr_output .= "</div>";
		}else{
			$nvr_output .= '<!-- no testimonial post -->';
		}
		wp_reset_query();
		
		return do_shortcode($nvr_output);
	}
	add_shortcode( 'testimonial360', 'nvr_rotatingtestimonial' );
}

if(!function_exists('nvr_featuredslider')){
	function nvr_featuredslider($atts, $content = null) {
		extract(shortcode_atts(array(
			'id' => '',
			'class' => 'minisliders',
			'moreproperties' => ''
		), $atts));
		
		$nvr_initial = THE_INITIAL;
		$nvr_shortname = THE_SHORTNAME;
		global $post;
		
		if($id!=""){
			$ids = 'id="'.esc_attr( $id ).'" ';
			$theid = $id;
		}else{
			$ids = 'id="'.esc_attr( $post->ID ).'" ';
			$theid = $post->ID;
		}
		
		$nvr_custom = nvr_get_customdata($theid);
		$nvr_cf_imagegallery	= (isset($nvr_custom[$nvr_initial."_imagesgallery"][0]))? $nvr_custom[$nvr_initial."_imagesgallery"][0] : "";
		
		$cf_thumb2 = array(); $cf_full2 = "";
		$imgsize = "portfolio-image";
		if($nvr_cf_imagegallery!=""){
			$nvr_attachments = $nvr_cf_imagegallery;
			$nvr_attachmentids = explode(",",$nvr_attachments);
			
			foreach ( $nvr_attachmentids as $att_id ) {
				if($att_id==''){continue;}
				$getimage = wp_get_attachment_image_src($att_id, $imgsize, true);
				if($getimage){
					$portfolioimage = $getimage[0];
					$alttext = get_post_meta( $att_id , '_wp_attachment_image_alt', true);
					$cf_thumb2[] ='<img src="'.esc_url( $portfolioimage ).'" alt="'.esc_attr( $alttext ).'" title="'. esc_attr( $alttext ) .'" class="scale-with-grid" />';
					
					$getfullimage = wp_get_attachment_image_src($att_id, 'full', true);
					$fullimage = $getfullimage[0];
					
					$cf_full2 .='<li class="slide" id="'.esc_attr( $att_id ).'"><img src="'.esc_url( $fullimage ).'" alt="'.esc_attr( $alttext ).'" title="'. esc_attr( $alttext ).'" /></li>';
				}
			}
		}else{
			$qrychildren = array(
				'post_parent' => $theid,
				'post_status' => null,
				'post_type' => 'attachment',
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'post_mime_type' => 'image'
			);
	
			$attachments = get_children( $qrychildren );
			
			foreach ( $attachments as $att_id => $attachment ) {
				$getimage = wp_get_attachment_image_src($att_id, $imgsize, true);
				$portfolioimage = $getimage[0];
				$alttext = get_post_meta( $attachment->ID , '_wp_attachment_image_alt', true);
				$image_title = $attachment->post_title;
				$caption = $attachment->post_excerpt;
				$description = $attachment->post_content;
				$cf_thumb2[] ='<img src="'.esc_url( $portfolioimage ).'" alt="'.esc_attr( $alttext ).'" title="'. esc_attr( $image_title ) .'" class="scale-with-grid" />';
				
				$getfullimage = wp_get_attachment_image_src($att_id, 'full', true);
				$fullimage = $getfullimage[0];
				
				$cf_full2 .='<li class="slide" id="'.esc_attr( $att_id ).'"><img src="'.esc_url( $fullimage ).'" alt="'.esc_attr( $alttext ).'" title="'. esc_attr( $image_title ).'" /></li>';
			}
		}
		
		$nvr_output  = '<div '.$ids.' class="'.esc_attr( $class ).' flexslider" '.$moreproperties.'>';
		$nvr_output	.= '<ul class="slides">';
		$nvr_output	.= $cf_full2;
		$nvr_output	.= '</ul>';
		$nvr_output	.= '</div>';
		return $nvr_output;
	}
	add_shortcode( 'featuredslider', 'nvr_featuredslider' );
}

if(!function_exists('nvr_featuredgallery')){
	function nvr_featuredgallery($atts, $content = null) {
		extract(shortcode_atts(array(
			'id' => '',
			'class' => '',
			'column' => '4',
			'moreproperties' => ''
		), $atts));
		
		global $post;
		
		if($id!=""){
			$ids = 'id="'.esc_attr( $id ).'" ';
			$theid = $id;
		}else{
			$ids = 'id="'.esc_attr( $post->ID ).'" ';
			$theid = $post->ID;
		}
		
		$qrychildren = array(
			'post_parent' => $theid,
			'post_status' => null,
			'post_type' => 'attachment',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'post_mime_type' => 'image'
		);
		
		$column = intval($column);
		if($column!= 2 && $column!= 3 && $column!= 4 ){
			$column = 4;
		}

		$attachments = get_children( $qrychildren );
		
		$typecol = "nvr-pf-col-".$column;
		$imgsize = "portfolio-image";
		
		$lipf = "";
		$idnum = 0;
		foreach ( $attachments as $att_id => $attachment ) {
			$getimage = wp_get_attachment_image_src($att_id, $imgsize, true);
			$portfolioimage = $getimage[0];
			$alttext = get_post_meta( $attachment->ID , '_wp_attachment_image_alt', true);
			$image_title = $attachment->post_title;
			$caption = $attachment->post_excerpt;
			$description = $attachment->post_content;
			$cf_thumb ='<img src="'.esc_url( $portfolioimage ).'" alt="'.esc_attr( $alttext ).'" title="'. esc_attr( $image_title ).'" class="scale-with-grid" />';
			
			$getfullimage = wp_get_attachment_image_src($att_id, 'full', true);
			$fullimage = $getfullimage[0];
			
			if($column==2){
				$classpf = 'six columns ';
			}elseif($column==3){
				$classpf = 'four columns ';
			}else{
				$classpf = 'three columns ';
			}
			
			$rel = " ";

			if((($idnum+1)%$column) == 1){
				$classpf .= "alpha";
			}elseif((($idnum+1)%$column) == 0 && $idnum>0){
				$classpf .= "last";
			}else{
				$classpf .= "";
			}
			
			$lipf .='<li class="'.esc_attr( $classpf ).'">';
			$lipf .='<div class="nvr-pf-img">';
				$lipf .='<a href="'.esc_url( $fullimage ).'" data-rel="prettyPhoto['.esc_attr( $theid ).']" title="'.esc_attr( $image_title ).'">'.$cf_thumb.'</a>';
			$lipf .='</div>';
			$lipf .='</li>';
			
			$idnum++;
		}
		
		$nvr_output  = '<div '.$ids.' class="row '.esc_attr( $class ).' nvr-pf-container" '.$moreproperties.'>';
		$nvr_output	.= '<ul class="'.esc_attr( $typecol ).'">';
		$nvr_output	.= $lipf;
		$nvr_output	.= '<li class="pf-clear"></li></ul>';
		$nvr_output	.= '</div>';
		return $nvr_output;
	}
	add_shortcode( 'featuredgallery', 'nvr_featuredgallery' );
}

/******BRAND CAROUSEL******/
if(!function_exists('nvr_brandcarousel')){
	function nvr_brandcarousel($atts, $content = null) {
		extract(shortcode_atts(array(
					"class" => '',
					"cat" => '',
					"showposts" => '-1'
		), $atts));
			
			if($content){
				$content = nvr_content_formatter($content);
			}

			$nvr_output  ='<div class="brand '.esc_attr( $class ).'">';
			
			$i=1;
			$nvr_argquery = array(
				'post_type' => 'brand',
				'showposts' => $showposts
			);
			if($cat){
				$nvr_argquery['tax_query'] = array(
					array(
						'taxonomy' => 'brandcat',
						'field' => 'slug',
						'terms' => $cat
					)
				);
			}
			query_posts($nvr_argquery);
			global $post;
			
			$nvr_output  .='<div class="flexslider-carousel row">';
				$nvr_output  .='<ul class="slides">';
				
				$nvr_havepost = false;
				while (have_posts()) : the_post();
				$nvr_havepost = true;
				$excerpt = get_the_excerpt(); 
				$postid = get_the_ID();
				$custom = get_post_custom( $postid );
				$cthumb = (isset($custom["carousel_thumb"][0]))? $custom["carousel_thumb"][0] : "";
				$extlink = (isset($custom["external_link"][0]))? $custom["external_link"][0] : "";
				
				$thumbid = get_post_thumbnail_id( $postid );
				$alttext = get_post_meta($postid, '_wp_attachment_image_alt', true);
				$imagesrc = wp_get_attachment_image_src( $thumbid, 'brand-image' );
				
				if($cthumb!=""){
					$imagethumb = $cthumb;
					$alttext = get_the_title( $postid );
				}else{
					if($imagesrc!=false){
						$imagethumb = $imagesrc[0];
					}else{
						$imagethumb = get_template_directory_uri().'/images/noimage.png';
						$alttext = get_the_title( $postid );
					}
				}
				
				$nvr_output  .='<li>';
					$nvr_output .= '<div class="cr-item-container">';
						if($extlink){
							$nvr_output  .='<a href="'.esc_url( $extlink ).'" target="_blank"><img src="'.esc_url( $imagethumb ).'" alt="'.esc_attr( $alttext ).'" /></a>';
						}else{
							$nvr_output  .='<img src="'.esc_url( $imagethumb ).'" alt="'.esc_attr( $alttext ).'" />';
						}
					$nvr_output .= '</div>';
				$nvr_output  .='</li>';
				
				$i++; $addclass=""; endwhile; wp_reset_query();
				 
				$nvr_output .='</ul>';
			 $nvr_output .='</div>';
			 $nvr_output .='</div>';
			 if($nvr_havepost){
			 	return do_shortcode($nvr_output);
			}else{
				return false;
			}
	}
	
	add_shortcode( 'brand_carousel', 'nvr_brandcarousel' );
}

/******BRAND COLUMNS******/
if(!function_exists('nvr_brandcolumns')){
	function nvr_brandcolumns($atts, $content = null) {
		extract(shortcode_atts(array(
					"class" => '',
					"cat" => '',
					"col" => 4,
					"showposts" => '-1'
		), $atts));
			
			if($content){
				$content = nvr_content_formatter($content);
			}
			
			$i=1;
			$nvr_argquery = array(
				'post_type' => 'brand',
				'showposts' => $showposts
			);
			if($cat){
				$nvr_argquery['tax_query'] = array(
					array(
						'taxonomy' => 'brandcat',
						'field' => 'slug',
						'terms' => $cat
					)
				);
			}
			query_posts($nvr_argquery);
			global $post;
			
			$column = intval($col);
			
			if($column!= 2 && $column!= 3 && $column!= 4 ){
				$column = 3;
			}
			$nvr_output = '';
			
			$nvr_output  .='<div class="brand-container '.esc_attr( $class ).'">';
				$nvr_output  .='<div class="row brand-row">';
				
				$nvr_havepost = false;
				while (have_posts()) : the_post();
				$nvr_havepost = true;
				$excerpt = get_the_excerpt(); 
				$postid = get_the_ID();
				$custom = get_post_custom( $postid );
				$cthumb = (isset($custom["carousel_thumb"][0]))? $custom["carousel_thumb"][0] : "";
				$extlink = (isset($custom["external_link"][0]))? $custom["external_link"][0] : "";
				
				$thumbid = get_post_thumbnail_id( $postid );
				$alttext = get_post_meta($postid, '_wp_attachment_image_alt', true);
				$imagesrc = wp_get_attachment_image_src( $thumbid, 'brand-image' );
				
				if($cthumb!=""){
					$imagethumb = $cthumb;
					$alttext = get_the_title( $postid );
				}else{
					if($imagesrc!=false){
						$imagethumb = $imagesrc[0];
					}else{
						$imagethumb = get_template_directory_uri().'/images/noimage.png';
						$alttext = get_the_title( $postid );
					}
				}
				
				if($column=="2"){
					$classbr = 'six columns ';
				}elseif($column=="4"){
					$classbr = 'three columns ';
				}else{
					$classbr = 'four columns ';
				}
				
				if(($i%$column) == 1){ $classbr .= "first ";}
				if(($i%$column) == 0){$classbr .= "last ";}
				
				if(($i%$column) == 1 && $i>1){ 
					$nvr_output  .='</div>';
					$nvr_output  .='<div class="row brand-row">';
				}
				
				$nvr_output  .='<div class="'.esc_attr( $classbr ).'">';
					$nvr_output .= '<div class="br-item-container">';
						if($extlink){
							$nvr_output  .='<a href="'.esc_url( $extlink ).'" target="_blank"><img src="'.esc_url( $imagethumb ).'" alt="'.esc_attr( $alttext ).'" /></a>';
						}else{
							$nvr_output  .='<img src="'.esc_url( $imagethumb ).'" alt="'.esc_attr( $alttext ).'" />';
						}
					$nvr_output .= '</div>';
				$nvr_output  .='</div>';
				
				$i++; $addclass=""; endwhile; wp_reset_query();

			 	$nvr_output .='</div>';
			 $nvr_output .='</div>';
			 
			 if($nvr_havepost){
			 	return do_shortcode($nvr_output);
			}else{
				return false;
			}
	}
	
	add_shortcode( 'brand', 'nvr_brandcolumns' );
}

/******HOSTING TABLE******/
if(!function_exists('nvr_hostingtable')){
	function nvr_hostingtable($atts, $content) {
		
		extract(shortcode_atts(array(
					"title" => '',
					"price" => '',
					"priceinfo" => __('Permonth', THE_LANG),
					"buttontext" => __('Purchase Now', THE_LANG),
					'buttonlink' => ''
		), $atts));
		
		$return_html = '';
		
		$return_html .= '<div class="hostingtable">';
			$return_html .= '<div class="hostingtitle"><h4>'.$title.'</h4></div>';
			$return_html .= '<div class="hostingprice">';
			$return_html .= $price;
			$return_html .= '<span class="priceinfo">'.$priceinfo.'</span>';
			$return_html .= '</div>';
			$return_html .= '<div class="hostingcontent">';
			$return_html .= $content;
			$return_html .= '</div>';
			if($buttonlink!=''){
				$return_html .= '<div class="hostingbutton"><a href="'.esc_url( $buttonlink ).'" class="button">'.$buttontext.'</a></div>';
			}
		$return_html .= '</div>';
		
		return $return_html;
	}
	add_shortcode( 'hostingtable', 'nvr_hostingtable' );
}

/******ICON CONTAINER******/
if(!function_exists('nvr_iconcontainer')){
	function nvr_iconcontainer($atts, $content) {
		
		extract(shortcode_atts(array(
					"iconclass" => '',
					"align" => '',
					"size" => '',
					"color" => '',
					"padding" => '',
					"radius" => '',
					'type' => '1'
		), $atts));
		
		$class = '';
		if($iconclass!=""){
			$class .=' '.$iconclass;
		}
		if($align=='right'){
			$class .=' alignright';
		}elseif($align=='center'){
			$class .=' aligncenter';
		}else{
			$class .=' alignleft';
		}
		if($type=='2'){
			$class .=' type2';
		}elseif($type=='3'){
			$class .=' type3';
		}
		
		$style = '';
		if(is_numeric($size)){
			$style .= 'font-size:'.$size.'px;';
			$style .= 'width:'.$size.'px;';
			$style .= 'height:'.$size.'px;';
			$style .= 'line-height:'.$size.'px;';
		}
		if($color!=''){
			$style .= 'border-color:'.$color.';';
			if($type=='2'){
				$style .= 'background-color:'.$color.';';
			}else{
				$style .= 'color:'.$color.';';
			}
		}
		if($padding!=''){
			$padding = preg_match('/(px|em|\%|pt|cm)$/', $padding) ? $padding : $padding.'px';
			$style .='padding:'.$padding.';';
		}
		if(is_numeric($radius)){
			$style .='border-radius:'.$radius.'px;';
			$style .='-webkit-border-radius:'.$radius.'px;';
			$style .='-moz-border-radius:'.$radius.'px;';
		}
		if($style!=''){
			$style = ' style="'.esc_attr( $style ).'"';
		}
		$return_html = '<div class="icn-container fa'.esc_attr( $class ).'"'.$style.'></div>';
		
		return $return_html;
	}
	add_shortcode( 'iconcontainer', 'nvr_iconcontainer' );
}

if(!function_exists('nvr_login_form')){
	function nvr_login_form($attributes, $content = null) {
		 // get user dashboard link
		global $wpdb;
		$redirect='';
		$mess='';
	
		  $post_id=get_the_ID();
		  
		$nvr_nonce = wp_create_nonce("nvr_login_nonce");
		$return_string=' 
			<div class="login_form" id="login-div">
				<div class="loginalert" id="login_message_area" >'.$mess.'</div>
			
				<p><label for="username">'.__('Username', THE_LANG ).'</label><input type="text" class="textbox" name="log" id="login_user" value="" size="20" /></p>
				<p><label for="password">'.__('Password', THE_LANG ).'</label><input type="password" class="textbox" name="pwd" id="login_pwd" size="20" /></p>
		';
			$return_string .= '<input type="hidden" name="loginpop" id="loginpop" value="0">';
			$return_string .= '<input type="hidden" name="security-login" id="security-login" value="'.esc_attr( $nvr_nonce ).'">';
			$return_string .= '<input type="submit" value="'.__('Login', THE_LANG ).'" id="wp-login-but" name="submit" class="button">';
		$return_string .= '<div class="login-links">';
			
				$return_string.='<a href="#" id="forgot_pass">'.__('forgot password?', THE_LANG ).'</a>
				</div>
			</div>
		';
	
			
		$nvr_nonce = wp_create_nonce("nvr_forgot_nonce");
		$return_string.='                 
			<div class="login_form" id="forgot-pass-div">
				<div class="loginalert" id="forgot_pass_area" ></div>
				<p><label for="email">'.__('Enter Your Email Address', THE_LANG ).'</label><input type="text" class="textbox" name="forgot_email" id="forgot_email" value="" size="20" /></p>
		';
			$return_string .='<input type="hidden" id="security-forgot" name="security-forgot" value="'.esc_attr( $nvr_nonce ).'">';
			$return_string .='<input type="hidden" id="postid" value="'.esc_attr( $post_id ).'">';
			$return_string .='<input type="submit" value="'.esc_attr__('Reset Password', THE_LANG ).'" id="wp-forgot-but" name="forgot" class="button">';   
				$return_string .='<div class="login-links"><a href="#" id="return_login">'.__('return to login', THE_LANG ).'</a></div>
			</div>
		';
		return  $return_string;
	}
	add_shortcode('loginform','nvr_login_form');
}

if(!function_exists('nvr_register_form')){
	function nvr_register_form($attributes, $content = null) {
	 
		 $nvr_nonce = wp_create_nonce("nvr_register_nonce");
		 $return_string='
			  <div class="login_form">
				   <div class="loginalert" id="register_message_area" ></div>
				   
					<p><label for="user_login">'.__('Username', THE_LANG ).'</label><input type="text" name="user_login_register" id="user_login_register" class="textbox" value="" size="20" /></p>
					<p><label for="user_email">'.__('Email', THE_LANG ).'</label><input type="text" name="user_email_register" id="user_email_register" class="textbox" value="" size="20" /></p>
					 
					<p id="reg_passmail">'.__('A password will be e-mailed to you', THE_LANG ).'</p>
				 
					<input type="hidden" id="security-register" name="security-register" value="'.esc_attr( $nvr_nonce ).'">
					<p class="submit"><input type="submit" name="wp-submit" id="wp-submit-register" class="button" value="'.__('Register', THE_LANG ).'" /></p>
					
			</div>
						 
		';
		 return  $return_string;
	}
	add_shortcode('registerform','nvr_register_form');
}

// Actual processing of the shortcode happens here
function nvr_pre_shortcode( $content ) {
    global $shortcode_tags;
 
    // Backup current registered shortcodes and clear them all out
    $orig_shortcode_tags = $shortcode_tags;
 
    // Do the shortcode (only the one above is registered)
    $content = do_shortcode( $content );
 
    // Put the original shortcodes back
    $shortcode_tags = $orig_shortcode_tags;
 
    return $content;
}
 
add_filter( 'the_content', 'nvr_pre_shortcode', 7 );
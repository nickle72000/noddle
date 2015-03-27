<?php
/**
 * Template Name: Portfolio
 *
 * A custom page template for portfolio page.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */

get_header(); ?>

	<?php
    $nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
	
   	$nvr_pid = nvr_get_postid();
	$nvr_custom = nvr_get_customdata($nvr_pid);
    $nvr_type = (isset($nvr_custom["p_type"][0]))? $nvr_custom["p_type"][0] : "";
	$nvr_contlayout = (isset($nvr_custom["p_container"][0]))? $nvr_custom["p_container"][0] : "";
    $nvr_cats = (isset($nvr_custom["p_categories"][0]))? $nvr_custom["p_categories"][0] : "";
    $nvr_showpost = (isset($nvr_custom["p_showpost"][0]))? $nvr_custom["p_showpost"][0] : "";
    $nvr_orderby = (isset($nvr_custom["p_orderby"][0]))? $nvr_custom["p_orderby"][0] : "date";
    $nvr_ordersort = (isset($nvr_custom["p_sort"][0]))? $nvr_custom["p_sort"][0] : "DESC";
	$nvr_loadmore = (isset($nvr_custom["p_loadmore"][0]))? $nvr_custom["p_loadmore"][0] : "";
    $nvr_categories = explode(",",$nvr_cats);

	if($nvr_type==""){
		$nvr_type = 'classic-3-space';
	}
	$nvr_arrtype = explode("-",$nvr_type);
	
	$nvr_ptype = $nvr_arrtype[0];
	$nvr_column = intval($nvr_arrtype[1]);
	$nvr_pspace = (isset($nvr_arrtype[2]))? $nvr_arrtype[2] : 'space';
	if($nvr_column==0){
		$nvr_freelayout = true;
	}else{
		$nvr_freelayout = false;
	}
    
    $nvr_approvedcats = array();
    foreach($nvr_categories as $nvr_category){
        $nvr_catname = get_term_by('slug',$nvr_category,"portfoliocat");
        if($nvr_catname!=false){
            $nvr_approvedcats[] = $nvr_catname;
        }
    }
    
    $nvr_catslugs = array();
	$nvr_outputfilter = '';
    if(count($nvr_approvedcats)>1){
        $nvr_outputfilter .='<ul id="filters" class="portfolio-cat-filter option-set clearfix " data-option-key="filter">';
            $nvr_outputfilter .='<li class="alpha selected"><a href="#filter" data-option-value="*">'. __('All Categories', THE_LANG ).'</a></li>';
            $nvr_filtersli = '';
            $nvr_numli = 1;
            foreach($nvr_approvedcats as $nvr_approvedcat){
                if($nvr_numli==1){
                    $nvr_liclass = 'omega';
                }else{
                    $nvr_liclass = '';
                }
                $nvr_filtersli = '<li class="'.esc_attr( $nvr_liclass ).'"><a href="#filter" data-option-value=".'.esc_attr( $nvr_approvedcat->slug ).'">'.$nvr_approvedcat->name.'</a></li>'.$nvr_filtersli;
                $nvr_catslugs[] = $nvr_approvedcat->slug;
                $nvr_numli++;
            }
            $nvr_outputfilter .=$nvr_filtersli;
        $nvr_outputfilter .='</ul>';
		$nvr_hasfilter = true;
    }elseif(count($nvr_approvedcats)==1){
		$nvr_catslugs[] = $nvr_approvedcats[0]->slug;
		$nvr_hasfilter = false;
	}else{
		$nvr_hasfilter = false;
	}

    $nvr_idnum = 0;

    if($nvr_column!= 2 && $nvr_column!= 3 && $nvr_column!= 4 && $nvr_column!= 5 ){
        $nvr_column = 3;
    }
    $nvr_pfcontainercls = "nvr-pf-col-".$nvr_column;
	$nvr_pfcontainercls .= " ".$nvr_ptype;
	$nvr_pfcontainercls .= " ".$nvr_pspace;
	$nvr_pfcontainercls .= " ".$nvr_contlayout;
    $nvr_imgsize = "portfolio-image";
    
    if($nvr_showpost==""){$nvr_showpost="-1";}
    
    $nvr_argquery = array(
        'post_type' => 'portofolio',
        'orderby' => $nvr_orderby,
        'order' => $nvr_ordersort,
        'paged' => $paged
    );
	$nvr_argquery['showposts'] = $nvr_showpost;
    
    if(count($nvr_catslugs)>0){
        $nvr_argquery['tax_query'] = array(
            array(
                'taxonomy' => 'portfoliocat',
                'field' => 'slug',
                'terms' => $nvr_catslugs
            )
        );
    }

    query_posts($nvr_argquery); 
    global $post, $wp_query;
    ?>
    <div class="portfolio_filter">
    	<?php echo $nvr_outputfilter; ?>
        <div class="nvr-pf-container row">
            <ul id="nvr-pf-filter" class="<?php echo esc_attr( $nvr_pfcontainercls ); ?>">
        
            <?php
            while ( have_posts() ) : the_post(); 
                    
                    $nvr_idnum++;
                    if(!$nvr_freelayout){
                        if($nvr_column=="2"){
                            $nvr_classpf = 'six columns ';
                        }elseif($nvr_column=="4"){
                            $nvr_classpf = 'three columns ';
                        }elseif($nvr_column=="5"){
                            $nvr_classpf = 'one_fifth columns ';
                        }else{
                            $nvr_classpf = 'four columns ';
                        }
                    }else{
                        $nvr_classpf = 'free columns ';
                    }
                    
                    if(($nvr_idnum%$nvr_column) == 1){ $nvr_classpf .= "first ";}
                    if(($nvr_idnum%$nvr_column) == 0){$nvr_classpf .= "last ";}
                    
                    $nvr_custompf = get_post_custom( get_the_ID() );
                    
                    $nvr_pimgsize = '';
                    if($nvr_ptype=='masonry'){
                        $nvr_pimgsize = (isset($nvr_custompf["_".$nvr_initial."_pimgsize"][0]))? $nvr_custompf["_".$nvr_initial."_pimgsize"][0] : "";
                        
                        if($nvr_pimgsize=='square'){
                            $nvr_imgsize = 'portfolio-image-square';
                        }elseif($nvr_pimgsize=='portrait'){
                            $nvr_imgsize = 'portfolio-image-portrait';
                        }elseif($nvr_pimgsize=='landscape'){
                            $nvr_imgsize = 'portfolio-image-landscape';
                        }
                        $nvr_classpf .= $nvr_pimgsize.' ';
                    }elseif($nvr_ptype=='grid'){
                        $nvr_imgsize = 'portfolio-image-square';
                        $nvr_pimgsize='square';
                    }
                    $nvr_classpf .= 'imgsize-'.$nvr_pimgsize.' ';
                    
                    $nvr_thepfterms = get_the_terms( get_the_ID(), 'portfoliocat');
                    
                    $nvr_literms = "";
                    if ( $nvr_thepfterms && ! is_wp_error( $nvr_thepfterms ) ){
        
                        $nvr_approvedterms = array();
                        foreach ( $nvr_thepfterms as $nvr_term ) {
                            $nvr_approvedterms[] = $nvr_term->slug;
                        }			
                        $nvr_literms = implode( " ", $nvr_approvedterms );
                    }
                    
                    echo nvr_pf_get_box( $nvr_imgsize, get_the_ID(), $nvr_classpf.' element '.$nvr_literms );
                        
                    $nvr_classpf=""; 
                        
            endwhile; // End the loop. Whew.
            ?>
            <li class="pf-clear"></li>
            </ul>
            <div class="clearfix"></div>
        </div><!-- end #nvr-portfolio -->
    </div>
    
    <?php
	$nvr_infscrolls = ( $nvr_loadmore )? true : false;
	if( $nvr_infscrolls ){
	?>
	<div id="loadmore-paging">
	<div class="loadmorebutton"><?php next_posts_link( '<i class="fa fa-camera"></i>&nbsp; '.__( 'Load More', THE_LANG ) ); ?></div>
	</div>
	<?php
	}
	?>
              
    <?php /* Display navigation to next/previous pages when applicable */ ?>
    <?php if (  $wp_query->max_num_pages > 1 && !$nvr_infscrolls ) : ?>
     <?php if(function_exists('wp_pagenavi')) { ?>
         <?php wp_pagenavi(); ?>
     <?php }else{ ?>
        <div id="nav-below" class="navigation">
                <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Previous', THE_LANG ) ); ?></div>
                <div class="nav-next"><?php previous_posts_link( __( 'Next <span class="meta-nav">&rarr;</span>', THE_LANG ) ); ?></div>
        </div><!-- #nav-below -->
    <?php }?>
    <?php endif; wp_reset_query();?>
            
    <div class="clearfix"></div><!-- clear float -->
                
<?php get_footer(); ?>
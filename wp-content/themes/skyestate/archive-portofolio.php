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
        
        $nvr_pid = nvr_get_postid();
		$nvr_custom = nvr_get_customdata($nvr_pid);
        $nvr_column = (isset($nvr_custom["p_column"][0]))? intval($nvr_custom["p_column"][0]) : "";
        $nvr_cats = (isset($nvr_custom["p_category"][0]))? $nvr_custom["p_category"][0] : "";
        $nvr_showpost = (isset($nvr_custom["p_showpost"][0]))? $nvr_custom["p_showpost"][0] : "";
        $nvr_categories = $nvr_cats;

        if(is_front_page()){
            $nvr_paged = (get_query_var('page'))? get_query_var('page') : 1;
        }else{
            $nvr_paged = (get_query_var('paged'))? get_query_var('paged') : 1;
        }
        
        $nvr_idnum = 0;

        if($nvr_column!= 2 && $nvr_column!= 3 && $nvr_column!= 4 && $nvr_column!= 5 ){
            $nvr_column = 3;
        }
        $nvr_typecol = "nvr-pf-col-".$nvr_column;
        $nvr_imgsize = "portfolio-image";
        
        if($nvr_showpost==""){$nvr_showpost="-1";}
        
        $nvr_argquery = array(
            'post_type' => 'portofolio',
            'showposts' => $nvr_showpost,
            'orderby' => 'date',
            'paged' => $nvr_paged
        );
        
        $nvr_catname = get_term_by('slug',$nvr_categories,"portfoliocat");
        
        if($nvr_catname){
            $nvr_argquery['tax_query'] = array(
                array(
                    'taxonomy' => 'portfoliocat',
                    'field' => 'slug',
                    'terms' => $nvr_catname->slug
                )
            );
        }

        query_posts($nvr_argquery); 
        global $post, $wp_query;
        
        ?>
        
        <div class="nvr-pf-container row">
            <ul class="<?php echo esc_attr( $nvr_typecol ); ?>">
        
        <?php
        while ( have_posts() ) : the_post(); 
                $nvr_idnum++;
                
                if($nvr_column=="2"){
                    $nvr_classpf = 'six columns ';
                }elseif($nvr_column=="4"){
                    $nvr_classpf = 'three columns ';
                }elseif($nvr_column=="5"){
					$nvr_classpf = 'one_fifth columns ';
				}else{
                    $nvr_classpf = 'four columns ';
                }

                if(($nvr_idnum%$nvr_column) == 1){ $nvr_classpf .= "first ";}

                if(($nvr_idnum%$nvr_column) == 0){$nvr_classpf .= "last ";}
                
                echo nvr_pf_get_box( $nvr_imgsize, get_the_ID(), $nvr_classpf );
                    
                $nvr_idnum++; $nvr_classpf=""; 
                    
        endwhile; // End the loop. Whew.
        ?>
            <li class="pf-clear"></li>
            </ul>
            <div class="clearfix"></div>
        </div><!-- end #nvr-portfolio -->
                  
        <?php /* Display navigation to next/previous pages when applicable */ ?>
        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
         <?php if(function_exists('wp_pagenavi')) { ?>
             <?php wp_pagenavi(); ?>
         <?php }else{ ?>
            <div id="nav-below" class="navigation">
                    <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Previous', THE_LANG ) ); ?></div>
                    <div class="nav-next"><?php previous_posts_link( __( 'Next <span class="meta-nav">&rarr;</span>', THE_LANG ) ); ?></div>
            </div><!-- #nav-below -->
        <?php }?>
        <?php endif;  wp_reset_query();?>
                
        <div class="clearfix"></div><!-- clear float --> 
                
<?php get_footer(); ?>
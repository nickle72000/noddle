<?php
/**
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */

get_header(); ?>

		<?php
		
		$nvr_initial = THE_INITIAL;
		$nvr_shortname = THE_SHORTNAME;
		
		$nvr_cursymbol = nvr_get_option( $nvr_shortname . '_currency_symbol');
		$nvr_curplace = nvr_get_option( $nvr_shortname . '_currency_place');
		$nvr_unit = nvr_get_option( $nvr_shortname . '_measurement_unit');
        
        $nvr_idnum = 0;
        $nvr_typecol = "nvr-prop-col";
        $nvr_imgsize = "property-image";
        
        ?>
        
        <div class="nvr-prop-container row">
            <ul class="<?php echo esc_attr( $nvr_typecol ); ?>">
        
            <?php
            while ( have_posts() ) : the_post(); 
			
				$nvr_idnum++;

				echo nvr_prop_get_box( $nvr_imgsize, get_the_ID(), 'element columns', $nvr_unit, $nvr_cursymbol, $nvr_curplace );
					
				$nvr_classpf=""; 
                        
            endwhile; // End the loop. Whew.
            ?>
            <li class="pf-clear"></li>
            </ul>
            <div class="clearfix"></div>
        </div><!-- end #nvr-pf-container -->
                  
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
        <?php endif; wp_reset_query();?>
        <div class="clearfix"></div><!-- clear float --> 
                
<?php get_footer(); ?>
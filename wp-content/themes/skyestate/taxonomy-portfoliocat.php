<?php
/**
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */

get_header(); ?>

		<?php
        
        $nvr_idnum = 0;
        $nvr_column = 3;
        $nvr_typecol = "nvr-pf-col-".$nvr_column;
        $nvr_imgsize = "portfolio-image";
        
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
                    }else{
                        $nvr_classpf = 'four columns ';
                    }

                    if(($nvr_idnum%$nvr_column) == 1){ $nvr_classpf .= "first ";}
                    if(($nvr_idnum%$nvr_column) == 0){$nvr_classpf .= "last ";}
                    
                    echo nvr_pf_get_box( $nvr_imgsize, get_the_ID(), $nvr_classpf );
                        
                    $nvr_classpf=""; 
                        
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
        <?php endif; wp_reset_query();?>
        <div class="clearfix"></div><!-- clear float --> 
                
<?php get_footer(); ?>
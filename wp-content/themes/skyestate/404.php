<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */

get_header(); ?>

        <p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', THE_LANG ); ?></p>
        <?php get_template_part('searchform'); ?>
        
        <div class="clearfix"></div><!-- clear float --> 
                    
    
<?php get_footer(); ?>
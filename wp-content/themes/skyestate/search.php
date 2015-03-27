<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */

get_header(); ?>

    <div id="searchresult">
    <?php
        /* Queue the first post, that way we know who
         * the author is when we try to get their name,
         * URL, description, avatar, etc.
         *
         * We reset this later so we can run the loop
         * properly with a call to rewind_posts().
         */
        if ( have_posts() )
            the_post();
    ?>

    <?php
        /* Since we called the_post() above, we need to
         * rewind the loop back to the beginning that way
         * we can run the loop properly, in full.
         */
        rewind_posts();
    
        /* Run the loop for the author archive page to output the authors posts
         * If you want to overload this in a child theme then include a file
         * called loop-author.php and that will be used instead.
         */
         get_template_part( 'loop', 'search' );
         
    ?>
    </div>
<div class="clearfix"></div><!-- clear float --> 
                        
<?php get_footer(); ?>
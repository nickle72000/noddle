<?php
/**
 * The Template for displaying single portfolio posts.
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */
get_header(); ?>

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="entry-content">
            <?php the_content(); ?>
            <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', THE_LANG ), 'after' => '</div>' ) ); ?>
            <?php edit_post_link( __( 'Edit', THE_LANG ), '<span class="edit-link">', '</span>' ); ?>
            <div class="clearfix"></div>
        </div><!-- .entry-content -->
        
    </div><!-- #post -->
    
    <?php comments_template( '', true ); ?>
    
    <?php endwhile; ?>
	<div class="clearfix"></div><!-- clear float --> 
                
<?php get_footer(); ?>
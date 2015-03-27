<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */
 
 $nvr_initial = THE_INITIAL;
 $nvr_shortname = THE_SHORTNAME;
 
 global $nvr_blogparam;

 $nvr_bloglayout = isset($nvr_blogparam['bloglayout'])? $nvr_blogparam['bloglayout'] : nvr_get_option($nvr_shortname."_blog_layout");
 $nvr_infscrolls = isset($nvr_blogparam['infscrolls'])? $nvr_blogparam['infscrolls'] : nvr_get_option( $nvr_shortname . '_blog_infscrolls');
 $nvr_infscrolls = ( $nvr_infscrolls=="1" && $nvr_bloglayout!='classic' )? true : false;
?>
	
<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<article id="post-0" class="post error404 not-found">
		<h1 class="posttitle"><?php _e( 'Not Found', THE_LANG ); ?></h1>
		<div class="entry">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', THE_LANG ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</article>
<?php endif; ?>

<?php if ( have_posts() ) : ?>
	<?php
		if($nvr_bloglayout=='3col-masonry'){
			$nvr_containerclass = 'threecols mason';
		}elseif($nvr_bloglayout=='2col-masonry'){
			$nvr_containerclass = 'twocols mason';
		}else{
			$nvr_containerclass = 'classic';
		}
	?>
    <div class="postscontainer <?php echo esc_attr( $nvr_containerclass ); ?>">
		<?php while ( have_posts() ) : the_post(); ?>
            
            <div class="articlewrapper">
                <?php /* How to display all posts. */ 
                get_template_part( 'content', get_post_format() ); 
                ?>
                <div class="clearfix"></div>
            </div>
            
            <?php comments_template( '', true ); ?>
        
        <?php endwhile; // End the loop. Whew. ?>
    	<div class="clearfix"></div>
    </div>
    <?php
	if( $nvr_infscrolls ){
	?>
	<div id="loadmore-paging">
	<div class="loadmorebutton"><?php next_posts_link( __( 'Load More', THE_LANG ) ); ?></div>
	</div>
	<?php
	}
	?>
    
	<?php /* Display navigation to next/previous pages when applicable */ ?>
    <?php if (  $wp_query->max_num_pages > 1 && !$nvr_infscrolls ){ ?>
        <?php if(function_exists('wp_pagenavi')) { ?>
            <?php wp_pagenavi(); ?>
        <?php }else{ ?>
            <div id="nav-below" class="navigation">
                <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Previous', THE_LANG ) ); ?></div>
                <div class="nav-next"><?php previous_posts_link( __( 'Next <span class="meta-nav">&rarr;</span>', THE_LANG ) ); ?></div>
            </div><!-- #nav-below -->
        <?php }?>
    <?php } ?>

<?php endif; 
wp_reset_query();
?>
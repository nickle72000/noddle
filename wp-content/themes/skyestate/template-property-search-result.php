<?php
/**
 * Template Name: Property Search Result
 *
 * A custom page template for displaying property search result.
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
	
	$nvr_propstatuses = nvr_get_option($nvr_shortname.'_property_status');
	$nvr_propamenities = nvr_get_option($nvr_shortname.'_property_amenities');
	
	$nvr_cursymbol = nvr_get_option( $nvr_shortname . '_currency_symbol');
	$nvr_curplace = nvr_get_option( $nvr_shortname . '_currency_place');
	$nvr_unit = nvr_get_option( $nvr_shortname . '_measurement_unit');
	
    global $post, $more;
    $more = 0;
	
	$nvr_argquery = nvr_property_mapquery('search');
    query_posts($nvr_argquery); 
	?>
    
    <?php /* If there are no posts to display, such as an empty archive page */ ?>
	<?php if ( ! have_posts() ) : ?>
        <article id="post-0" class="post error404 not-found">
            <h1 class="posttitle"><?php _e( 'Not Found', THE_LANG ); ?></h1>
            <div class="entry">
                <p><?php _e( 'Apologies, but no results were found for the requested property archive. Perhaps searching will help find a related post.', THE_LANG ); ?></p>
            </div>
        </article>
    <?php endif; ?>
	
    <div class="nvr-prop-container row">
    <?php if( have_posts() ){ ?>
    	<div class="search-title twelve columns">
        	<h4><?php _e('Search Result', THE_LANG); ?> (<?php echo $wp_query->post_count; ?>)</h4>
        </div>
    	<?php
		$nvr_idnum = 0;
		$nvr_typecol = "nvr-prop-col";
		$nvr_imgsize = "property-image";
		?>
        <ul id="nvr-prop-search" class="<?php echo esc_attr( $nvr_typecol ); ?>">
    
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
    <?php } ?>
    
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
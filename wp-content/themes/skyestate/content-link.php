<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */
 
 global $post, $more;
 $more = 0;
 $nvr_initial = THE_INITIAL;
?>

    <?php /* How to display all posts. */ ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class("content-loop"); ?>>
    	<?php
		if(!is_search()){
			$nvr_custom = get_post_custom($post->ID);
			$nvr_cf_exturl = (isset($nvr_custom["_".$nvr_initial."_external_url"][0]))? $nvr_custom["_".$nvr_initial."_external_url"][0] : "";
			
			if($nvr_cf_exturl){
				$nvr_exturl = $nvr_cf_exturl;
			}else{
				$nvr_exturl = get_permalink();
			}
		}
		?>
        <div class="loopcontainer">
       		<div class="entry-content">
                <a href="<?php echo esc_url($nvr_exturl); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', THE_LANG ), the_title_attribute( 'echo=0' ) ); ?>" data-rel="bookmark">
				<?php 
				if(is_search()){
					the_excerpt();
				}else{
					the_content(); 
				}
				?>
                </a>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="entry-utility">
        	<div class="entry-icon fa fa-link"></div>
            <div class="meta-sticky"><i class="fa fa-bookmark"></i> <span class="grey"><?php _e('Sticky', THE_LANG); ?></span></div>
            <div class="meta-comment"><i class="fa fa-comment"></i> <?php comments_popup_link(__('No Comment', THE_LANG), __('1 Comment', THE_LANG), __('% Comments', THE_LANG)); ?></div>
            <div class="meta-cat"><i class="fa fa-folder-open"></i> <?php the_category(', '); ?></div>
            <div class="meta-tags"><i class="fa fa-tags"></i> <?php the_tags('',', '); ?></div>
            
            <div class="meta-share"><i class="fa fa-share-alt"></i> <?php _e('Share', THE_LANG); ?><?php do_action('nvr_share_button'); ?></div>
            <span class="clearfix"></span>
        </div>
		<div class="clearfix"></div>
        
	</article><!-- end post -->
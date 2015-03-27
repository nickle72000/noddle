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
			$nvr_cf_imgurl = (isset($nvr_custom["image_url"][0]))? $nvr_custom["image_url"][0] : "";
			$nvr_imgurl = "";
			/* temporary not used */
			if($nvr_cf_imgurl!=""){
				$nvr_imgurl = '<img src='. esc_url( $nvr_cf_imgurl ) .' alt="'. esc_attr( get_the_title( $post->ID ) ).'" class="scale-with-grid"/>';
			}elseif(has_post_thumbnail($post->ID) ){
				$nvr_imgurl = get_the_post_thumbnail($post->ID, 'blog-post-image', array('class' => 'scale-with-grid'));
			}else{
				$nvr_imgurl ="";
			}
			
			if($nvr_imgurl!=''){
				echo '<div class="imgcontainer">'.$nvr_imgurl."</div>";
			}
		}
		?>
        <div class="loopcontainer">
            <h2 class="posttitle"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', THE_LANG ), the_title_attribute( 'echo=0' ) ); ?>" data-rel="bookmark"><?php the_title(); ?></a></h2>
            <div class="meta-author"><i class="fa fa-user"></i> <?php _e("By", THE_LANG); ?> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>"><?php the_author();?></a></div> 
            <div class="meta-date"><?php _e("At", THE_LANG); ?> <?php the_time('M d, Y') ?></div>
       		<div class="entry-content">
                <?php the_excerpt(); ?>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="entry-utility">
        	<div class="entry-icon fa fa-camera"></div>
            <div class="meta-sticky"><i class="fa fa-bookmark"></i> <span class="grey"><?php _e('Sticky', THE_LANG); ?></span></div>
            <div class="meta-comment"><i class="fa fa-comment"></i> <?php comments_popup_link(__('No Comment', THE_LANG), __('1 Comment', THE_LANG), __('% Comments', THE_LANG)); ?></div>
            <div class="meta-cat"><i class="fa fa-folder-open"></i> <?php the_category(', '); ?></div>
            <div class="meta-tags"><i class="fa fa-tags"></i> <?php the_tags('',', '); ?></div>
            
            <div class="meta-share"><i class="fa fa-share-alt"></i> <?php _e('Share', THE_LANG); ?><?php do_action('nvr_share_button'); ?></div>
            <span class="clearfix"></span>
        </div>
		<div class="clearfix"></div>
        
	</article><!-- end post -->
<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */

get_header(); 

$nvr_initial = THE_INITIAL;
?>

        <div id="singlepost">
        
             <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
             <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
             	<?php
                 
				$nvr_custom = get_post_custom($post->ID);
				$nvr_post_format = get_post_format();
				switch ($nvr_post_format) {
					case "video":
						
						$nvr_cf_vidurl = (isset($nvr_custom["_".$nvr_initial."_video_url"][0]))? $nvr_custom["_".$nvr_initial."_video_url"][0] : "";
						if($nvr_cf_vidurl!=''){
							echo '<div class="mediacontainer">'.apply_filters('the_content', $nvr_cf_vidurl)."</div>";
						}
						$nvr_posticon = 'fa-film';
						
					break;
					case "audio":
					
						$nvr_cf_audurl = (isset($nvr_custom["_".$nvr_initial."_audio_url"][0]))? $nvr_custom["_".$nvr_initial."_audio_url"][0] : "";
						if($nvr_cf_audurl!=''){
							echo '<div class="mediacontainer">'.apply_filters('the_content', $nvr_cf_audurl)."</div>";
						}
						$nvr_posticon = 'fa-volume-up';
					
					break;
					case "gallery":
						
						$nvr_post_content = get_the_content();
						preg_match('/\[gallery.*ids=.(.*).\]/', $nvr_post_content, $ids);
						$nvr_array_id = explode(",", $ids[1]);
						
						$nvr_content =  str_replace($ids[0], "", $nvr_post_content);
						$nvr_filtered_content = apply_filters( 'the_content', $nvr_content);
						
						$nvr_sliderli = '';
						foreach($nvr_array_id as $nvr_img_id){
							$nvr_sliderli .= '<li><a href="'. esc_url( get_permalink() ) .'">'. wp_get_attachment_image( $nvr_img_id, 'blog-post-image' ) .'</a></li>';
						}
						
						if($nvr_sliderli!=''){
							echo '<div class="gallerycontainer"><div class="flexslider"><ul class="slides">'.$nvr_sliderli."</ul></div></div>";
						}
						$nvr_posticon = 'fa-image';
						
					break;
					case "image":
						
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
						$nvr_posticon = 'fa-camera';
						
					break;
					case "quote":
						$nvr_posticon = 'fa-quote-left';
						
					break;
					case "link":
						$nvr_posticon = 'fa-link';
						
					break;
					case "aside":
						$nvr_posticon = 'fa-bookmark';
						
					break;
					
					default :
						$nvr_posticon = 'fa-file-text';
					break;
				}
				?>
                <h1 class="posttitle nvrsecondfont"><?php the_title(); ?></h1>
                <div class="meta-author"><?php _e("By", THE_LANG); ?> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>"><?php the_author();?></a></div> 
                <div class="entry-utility">
                    <div class="entry-icon fa fa-file-text"></div>
                    <div class="meta-sticky"><i class="fa fa-bookmark"></i> <span class="grey"><?php _e('Sticky', THE_LANG); ?></span></div>
                    <div class="meta-date"><i class="fa fa-calendar"></i> <span class="grey"><?php the_time('M d, Y') ?></span></div>
                    <div class="meta-cat"><i class="fa fa-folder-open"></i> <?php the_category(', '); ?></div>
                    <div class="meta-tags"><i class="fa fa-tags"></i> <?php the_tags('',', '); ?></div>
                    
                    <div class="meta-comment"><i class="fa fa-comment"></i> <?php comments_popup_link(__('0', THE_LANG), __('1', THE_LANG), __('%', THE_LANG)); ?></div>
                    <span class="clearfix"></span>
                </div>
                
                 <div class="entry-content">
                    <?php 
					if(isset($nvr_filtered_content)){
						echo do_shortcode($nvr_filtered_content);
					}else{
						the_content();
					}
					?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', THE_LANG ) . '</span>', 'after' => '</div>' ) ); ?>
                    <div class="clearfix"></div>
                </div>
                
             </article>
             <div id="prevnext-post-link">
            	<?php
				$nvr_nextpost = get_next_post(true);
				$nvr_prevpost = get_previous_post(true);
				$nvr_hasprevpost = false;
                if(!empty($nvr_prevpost)){
					$nvr_hasprevpost =true;
					echo '<div class="nav-previous"><a href="'.esc_url( get_permalink($nvr_prevpost->ID) ).'"><span class="navarrow fa fa-angle-left"></span><span class="navtext">'. __( 'Previous Article', THE_LANG ) .'</span><br /><span class="prevnexttitle">'.get_the_title($nvr_prevpost->ID).'</span></a></div>';
                }
				if(!empty($nvr_nextpost)){
					$nvr_navclass = ($nvr_hasprevpost)? "positionleft" : '';
					echo '<div class="nav-next '.esc_attr($nvr_navclass).'"><a href="'.esc_url( get_permalink($nvr_nextpost->ID) ).'"><span class="navarrow fa fa-angle-right"></span><span class="navtext">'. __( 'Next Article', THE_LANG ) .'</span><br /><span class="prevnexttitle">'.get_the_title($nvr_nextpost->ID).'</span></a></div>';
                }
                ?>
                <div class="clearfix"></div>
            </div>
            <?php
            
            // If a user has filled out their description, show a bio on their entries.
            if ( get_the_author_meta( 'description' ) ) : ?>
            <div id="entry-author-info">
            	<h2 class="entry-author-title"><?php _e('About Author', THE_LANG); ?></h2>
                <div id="author-avatar">
                    <?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'novaro_author_bio_avatar_size',98 ) ); ?>
                </div><!-- author-avatar -->
                <div id="author-description">
                    <h2><span class="author"><?php the_author(); ?></span></h2>
                    <?php the_author_meta( 'description' ); ?>
                </div><!-- author-description	-->
            </div><!-- entry-author-info -->
            <?php endif; ?>
            <?php comments_template( '', true ); ?>
            
            <?php endwhile; ?>
        
        </div><!-- singlepost --> 
                   
<?php get_footer(); ?>
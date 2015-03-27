<?php
// =============================== Novaro Post Cycle widget ======================================
class NVR_PostRotatorWidget extends WP_Widget {
    /** constructor */
	function NVR_PostRotatorWidget() {
		$widget_ops = array('classname' => 'widget_nvr_post_rotator', 'description' => __('Novaro - Post Rotator', THE_LANG) );
		$this->WP_Widget('nvr-post-rotator', __('Novaro - Post Rotator', THE_LANG ), $widget_ops);
	}


    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$limit = apply_filters('widget_title', $instance['limit']);
		$cat = apply_filters('widget_title', $instance['cat']);
		$posttype = apply_filters('widget_posttype', $instance['posttype']);
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
	
						<?php if($posttype=="testimonial"){?>
							<div class="flexslider">
								
								<?php $limittext = $limit;?>
								<?php global $more;	$more = 0;?>
								<?php query_posts("post_type=" . $posttype);?>
								
								<?php while (have_posts()) : the_post(); ?>	
								
									<?php 
									$custom = get_post_custom($post->ID);
									$testiname = $custom["testimonial-name"][0];
									$testicompany = $custom["testimonial-company"][0];
									?>
								
								<div class="cycle">

								<?php if($limittext=="" || $limittext==0){ ?>
									<blockquote class="quote">
									<div>
									<?php the_excerpt(); ?>
									 </div></blockquote>
									 <div class="name-testi">
									 <span class="user"><?php echo $testiname; ?></span>
									 <br style="line-height:4px" /><?php echo $testicompany; ?>
									 </div>
								<?php }else{ ?>
									<blockquote class="quote">
									<div>
									<?php $excerpt = get_the_excerpt(); echo ts_string_limit_words($excerpt,$limittext).'...';?>
								
									 </div></blockquote>
									 <div class="name-testi">
									 <span class="user"><?php echo $testiname; ?></span>
									 <br style="line-height:4px" /><?php echo $testicompany; ?></div>
								<?php } ?>
								</div>
								<?php endwhile; ?>
								<?php wp_reset_query();?>
							</div>
							<!-- end of boxslideshow -->
						
						<?php } else { ?>
						
							<div class="flexslider">
								<?php $limittext = $limit;?>
								<?php global $more;	$more = 0;?>
							
								<?php if($posttype=="portfolio") { ?>
								<?php query_posts('&portfoliocat='.$cat);?>
								<?php } else { ?>
								<?php query_posts("category_name=" . $cat);?>
								<?php }?>
								
								<?php while (have_posts()) : the_post(); ?>	
								<?php
									$custom = get_post_custom($post->ID);
									$cf_thumb = $custom["thumb-cycle"][0];
									if($cf_thumb!=""){
										$cf_thumb = "<img src='" . esc_url( $cf_thumb ). "' alt=''  width='260' height='120' />";
									}
								?>	
								<div class="cycle">
								<?php if($cf_thumb!=""){ echo $cf_thumb; } ?>
								<span class="wdt-title"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></span>
									<?php if($limittext=="" || $limittext==0){
									the_excerpt();
									}else{
									$excerpt = get_the_excerpt(); echo ts_string_limit_words($excerpt,$limittext).'...';
									?>
									<?php } ?>
								</div>
								<?php endwhile; ?>
								<?php wp_reset_query();?>
							</div>
							<!-- end of boxslideshow -->
							<?php }?>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
		$limit = esc_attr($instance['limit']);
		$cat = esc_attr($instance['cat']);
		$posttype = esc_attr($instance['posttype']);
        ?>
            <p><label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php _e('Title:', THE_LANG); ?> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
			
 <p><label for="<?php echo esc_attr( $this->get_field_id('limit') ); ?>"><?php _e('Limit Text:', THE_LANG); ?> 
 <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('limit') ); ?>" name="<?php echo esc_attr( $this->get_field_name('limit') ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>" /></label></p>
 			
            <p><label for="<?php echo esc_attr( $this->get_field_id('posttype') ); ?>"><?php _e('Post Type:', THE_LANG); ?><br />

		<select id="<?php echo esc_attr( $this->get_field_id('posttype') ); ?>" name="<?php echo esc_attr( $this->get_field_name('posttype') ); ?>" style="width:150px;" > 
		<option value="testimonial" <?php echo ($posttype === 'testimonial' ? ' selected="selected"' : ''); ?>>Testimonials</option>
		<option value="portfolio" <?php echo ($posttype === 'portfolio' ? ' selected="selected"' : ''); ?> >Portfolio</option>
		<option value="" <?php echo ($posttype === '' ? ' selected="selected"' : ''); ?>>Default</option>
		</select>
			</label></p>
			
 <p><label for="<?php echo esc_attr( $this->get_field_id('cat') ); ?>"><?php _e('Category:', THE_LANG); ?> 
 <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('cat') ); ?>" name="<?php echo esc_attr( $this->get_field_name('cat') ); ?>" type="text" value="<?php echo esc_attr( $cat ); ?>" /></label></p>
			
        <?php 
    }

} // class Cycle Widget


?>
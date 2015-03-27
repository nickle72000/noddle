<?php
// =============================== Novaro Lattest Posts widget ======================================
class NVR_RecentPostWidget extends WP_Widget {
    /** constructor */

	function NVR_RecentPostWidget() {
		$widget_ops = array('classname' => 'widget_nvr_lattest_posts', 'description' => __('Novaro - Latest Posts',THE_LANG) );
		$this->WP_Widget('nvr-lattest-posts', __('Novaro - Latest Posts',THE_LANG), $widget_ops);
	}


  /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Latest Posts',THE_LANG) : $instance['title']);
		$category = apply_filters('widget_category', $instance['category']);
		$showpost = apply_filters('widget_showpost', $instance['showpost']);
		$disablethumb = apply_filters('widget_disablethumb', isset($instance['disablethumb']));
		$instance['category'] = esc_attr(isset($instance['category'])? $instance['category'] : "");
		global $wp_query;
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                        		<?php 
									$querycat = get_cat_name($category);
									if($showpost==""){$showpost=3;}
									$temp = $wp_query;
									$wp_query= null;
									$wp_query = new WP_Query();
									$wp_query->query("showposts=".$showpost."&category_name=" . $querycat);
									global $post;
								?>
								<?php  if ($wp_query->have_posts()) : ?>
                                <ul class="nvr-latest-post-widget">
                                    <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
                                    <li>
                                    	<?php if($disablethumb!="true") {?>
                                        <?php
                                        $custom = get_post_custom($post->ID);
                                        $cf_thumb = (isset($custom["custom_thumb"][0]))? $custom["custom_thumb"][0] : "";
                                        
                                        if($cf_thumb!=""){
                                            $thumb = '<img src='. esc_url( $cf_thumb ) .' alt="'. esc_attr( get_the_title( $post->ID ) ) .'" width="60" height="60" class="alignleft"/>';
                                        }elseif(has_post_thumbnail($post->ID) ){
                                            $thumb = get_the_post_thumbnail($post->ID, 'post-thumb', array('class' => 'alignleft'));
                                        }else{
                                            $thumb ="";
                                        }
                                        echo  $thumb;
                                        ?>
                                        <?php } ?>
                                            <span class="smalldate"><?php  the_time('F d, Y') ?></span>
                                            <h6>
                                            <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php esc_attr_e('Permanent Link to', THE_LANG);?> <?php the_title_attribute(); ?>">
                                            <?php the_title();?>
                                            </a>
                                            </h6>
                                        <div class="clearfix"></div>
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                                
                                <?php $wp_query = null; $wp_query = $temp; wp_reset_query();?>
                                
								<?php endif; ?>

								
								
              <?php echo $after_widget; ?>
			 
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
		$instance['title'] = (isset($instance['title']))? $instance['title'] : "";
		$instance['category'] = (isset($instance['category']))? $instance['category'] : "";
		$instance['showpost'] = (isset($instance['showpost']))? $instance['showpost'] : "";
		$instance['disablethumb'] = (isset($instance['disablethumb']))? $instance['disablethumb'] : "";
					
        $title = esc_attr($instance['title']);
		$category = esc_attr($instance['category']);
		$showpost = esc_attr($instance['showpost']);
		$disablethumb = esc_attr($instance['disablethumb']);
        ?>
            <p><label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php _e('Title:', THE_LANG); ?> <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
			
            <p><label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category:', THE_LANG); ?><br />
			<?php 
			$args = array(
			'selected'         => $category,
			'echo'             => 1,
			'name'             =>$this->get_field_name('category')
			);
			wp_dropdown_categories( $args );
			?>
			</label></p>
			
            <p><label for="<?php echo esc_attr( $this->get_field_id('showpost') ); ?>"><?php _e('Number of Post:', THE_LANG); ?> <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('showpost') ); ?>" name="<?php echo esc_attr( $this->get_field_name('showpost') ); ?>" type="text" value="<?php echo esc_attr( $showpost ); ?>" /></label></p>
            
            
            <p><label for="<?php echo esc_attr( $this->get_field_id('disablethumb') ); ?>"><?php _e('Disable Thumb:', THE_LANG); ?> 
			
			<?php if($instance['disablethumb']){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
                            <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name('disablethumb') ); ?>" id="<?php echo esc_attr( $this->get_field_id('disablethumb') ); ?>" value="true" <?php echo $checked; ?> />			</label></p>
        <?php 
    }

} // class  Widget
?>
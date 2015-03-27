<?php
// =============================== Novaro Agent Lists widget ======================================
class NVR_AgentListsWidget extends WP_Widget {
    /** constructor */

	function NVR_AgentListsWidget() {
		$widget_ops = array('classname' => 'widget_nvr_agent_lists', 'description' => __('Novaro - Agent Lists',THE_LANG) );
		$this->WP_Widget('nvr-agent-lists', __('Novaro - Agent Lists',THE_LANG), $widget_ops);
	}


  /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', empty($instance['title']) ? __('List of Agents',THE_LANG) : $instance['title']);
		$category = apply_filters('widget_category', isset( $instance['category'] )? $instance['category'] : '');
		$showpost = apply_filters('widget_showpost', $instance['showpost']);
		$disablethumb = apply_filters('widget_disablethumb', isset($instance['disablethumb']));
		global $wp_query;
		
		$nvr_initial = THE_INITIAL;
		$nvr_shortname = THE_SHORTNAME;
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                        		<?php 
									if($showpost==""){$showpost=3;}
									$category = nvr_get_term_name($category,'peoplecat');
									$nvr_taxarg = array();
									if($category!=''){
										$nvr_taxarg[] = array(
											'taxonomy' 	=> 'peoplecat',
											'field'		=> 'slug',
											'terms'		=> $category
										);
									}
									
									$nvr_queryarg = array(
										'post_type' => 'peoplepost',
										'posts_per_page' => $showpost
										
									);
									if(count($nvr_taxarg)){
										$nvr_queryarg['tax_query'] = $nvr_taxarg;
									}
	
									$agentlists = new WP_Query($nvr_queryarg);
									
									global $post;
								?>
								<?php  if ($agentlists->have_posts()) : ?>
                                <ul class="nvr-agent-lists-widget">
                                    <?php while ($agentlists->have_posts()) : $agentlists->the_post(); ?>
                                    <li>
                                    	<?php
										$custom = get_post_custom( get_the_ID() );
										$peopleinfo 	= (isset($custom['_'.$nvr_initial.'_people_info'][0]))? $custom['_'.$nvr_initial.'_people_info'][0] : "";
										$peoplethumb 	= (isset($custom['_'.$nvr_initial.'_people_thumb'][0]))? $custom['_'.$nvr_initial.'_people_thumb'][0] : "";
										$peoplepinterest= (isset($custom['_'.$nvr_initial.'_people_pinterest'][0]))? $custom['_'.$nvr_initial.'_people_pinterest'][0] : "";
										$peoplefacebook	= (isset($custom['_'.$nvr_initial.'_people_facebook'][0]))? $custom['_'.$nvr_initial.'_people_facebook'][0] : "";
										$peopletwitter 	= (isset($custom['_'.$nvr_initial.'_people_twitter'][0]))? $custom['_'.$nvr_initial.'_people_twitter'][0] : "";
										$peoplemail = (isset($custom['_'.$nvr_initial.'_people_email'][0]))? $custom['_'.$nvr_initial.'_people_email'][0] : "";
										$peopleskype	= (isset($custom['_'.$nvr_initial.'_people_skype'][0]))? $custom['_'.$nvr_initial.'_people_skype'][0] : "";
										$peoplephone 	= (isset($custom['_'.$nvr_initial.'_people_phone'][0]))? $custom['_'.$nvr_initial.'_people_phone'][0] : "";
 
										if($disablethumb!="true") {
										
											if(has_post_thumbnail( get_the_ID() ) ){
												$thumb = get_the_post_thumbnail( get_the_ID(), 'thumbnail', array('class' => 'alignleft'));
											}else{
												$thumb ="";
											}
											echo  '<a href="'.get_permalink().'">'.$thumb.'</a>';
										
                                        } 
										?>
                                            <h6>
                                            <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php esc_attr_e('Permanent Link to', THE_LANG);?> <?php the_title_attribute(); ?>">
                                            <?php the_title();?>
                                            </a>
                                            </h6>
                                            <span class="agentinfo"><?php  echo $peopleinfo; ?></span>
                                            <span class="agentphone"><?php  echo $peoplephone; ?></span>
                                            <a class="agentmail" href="mailto:<?php  echo $peoplemail; ?>" target="_blank"><?php  echo $peoplemail; ?></a>
                                        <div class="clearfix"></div>
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
								<?php endif; ?>
                                <?php wp_reset_query();?>

								
								
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
			'selected'         	=> $category,
			'show_option_all'	=> __('All Categories', THE_LANG),
			'echo'             	=> 1,
			'taxonomy'			=> 'peoplecat',
			'name'             	=> $this->get_field_name('category')
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
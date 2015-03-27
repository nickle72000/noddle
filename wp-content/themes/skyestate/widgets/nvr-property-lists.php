<?php
// =============================== Novaro Property Lists widget ======================================
class NVR_PropertyListsWidget extends WP_Widget {
    /** constructor */

	function NVR_PropertyListsWidget() {
		$widget_ops = array('classname' => 'widget_nvr_property_lists', 'description' => __('Novaro - Property Lists',THE_LANG) );
		$this->WP_Widget('nvr-property-lists', __('Novaro - Property Lists',THE_LANG), $widget_ops);
	}


  /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', empty($instance['title']) ? __('List of Properties',THE_LANG) : $instance['title']);
		$category = apply_filters('widget_category', isset($instance['category'])? $instance['category'] : '');
		$purpose = apply_filters('widget_purpose', isset($instance['purpose'])? $instance['purpose'] : '');
		$city = apply_filters('widget_city', isset($instance['city'])? $instance['city'] : '');
		$showpost = apply_filters('widget_showpost', $instance['showpost']);
		$disablethumb = apply_filters('widget_disablethumb', isset($instance['disablethumb']));
		
		$nvr_initial = THE_INITIAL;
		$nvr_shortname = THE_SHORTNAME;
		
		$nvr_cursymbol = nvr_get_option( $nvr_shortname . '_currency_symbol');
		$nvr_curplace = nvr_get_option( $nvr_shortname . '_currency_place');
		
		global $wp_query;
		
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                        		<?php 
									if($showpost==""){$showpost=3;}
									
									$nvr_taxarg = array();
									$category = nvr_get_term_name($category,'property_category');
									if($category!=''){
										$nvr_taxarg[] = array(
											'taxonomy' 	=> 'property_category',
											'field'		=> 'slug',
											'terms'		=> $category
										);
									}
									
									$purpose = nvr_get_term_name($purpose,'property_purpose');
									if($purpose!=''){
										$nvr_taxarg[] = array(
											'taxonomy' 	=> 'property_purpose',
											'field'		=> 'slug',
											'terms'		=> $purpose
										);
									}
									
									$city = nvr_get_term_name($city,'property_city');
									if($city!=''){
										$nvr_taxarg[] = array(
											'taxonomy' 	=> 'property_city',
											'field'		=> 'slug',
											'terms'		=> $city
										);
									}
									
									$nvr_queryarg = array(
										'post_type' => 'propertys',
										'posts_per_page' => $showpost
										
									);
									if(count($nvr_taxarg)){
										$nvr_queryarg['tax_query'] = $nvr_taxarg;
									}
									$proplists = new WP_Query($nvr_queryarg);
									
									global $post;
								?>
								<?php  if ($proplists->have_posts()) : ?>
                                <ul class="nvr-property-lists-widget">
                                    <?php while ($proplists->have_posts()) : $proplists->the_post(); ?>
                                    <li>
                                    	<?php
										$nvr_custom = nvr_get_customdata( get_the_ID() );
										$nvr_price = (isset($nvr_custom[$nvr_initial."_price"][0]))? $nvr_custom[$nvr_initial."_price"][0] : '';
										$nvr_plabel = (isset($nvr_custom[$nvr_initial."_price_label"][0]))? $nvr_custom[$nvr_initial."_price_label"][0] : '';
										$nvr_bed = (isset($nvr_custom[$nvr_initial."_room"][0]))? $nvr_custom[$nvr_initial."_room"][0] : '';
										$nvr_bath = (isset($nvr_custom[$nvr_initial."_bathroom"][0]))? $nvr_custom[$nvr_initial."_bathroom"][0] : '';
										$nvr_size = (isset($nvr_custom[$nvr_initial."_size"][0]))? $nvr_custom[$nvr_initial."_size"][0] : '';
										
										$nvr_address = (isset($nvr_custom[$nvr_initial."_address"][0]))? $nvr_custom[$nvr_initial."_address"][0] : '';
										$nvr_state = (isset($nvr_custom[$nvr_initial."_state"][0]))? $nvr_custom[$nvr_initial."_state"][0] : '';
										$nvr_country = (isset($nvr_custom[$nvr_initial."_country"][0]))? $nvr_custom[$nvr_initial."_country"][0] : '';
										
										$nvr_prop_cat = 'property_category';
										$nvr_categories = get_the_terms( get_the_ID(), $nvr_prop_cat );
										$nvr_categoryarr = array();
										if ( !empty( $nvr_categories ) ) {
											foreach ( $nvr_categories as $nvr_category ) {
												$nvr_categoryarr[] = $nvr_category->name;
											}
										  
											$nvr_type = implode(', ', $nvr_categoryarr);
										}else{
											$nvr_type = '';
										}
										
										$nvr_prop_purpose = 'property_purpose';
										$nvr_purposes = get_the_terms( get_the_ID(), $nvr_prop_purpose );
										$nvr_purposearr = array();
										if ( !empty( $nvr_purposes ) ) {
											foreach ( $nvr_purposes as $nvr_purpose ) {
												$nvr_purposearr[] = $nvr_purpose->name;
											}
										  
											$nvr_purpose = implode(", ", $nvr_purposearr);
										}else{
											$nvr_purpose = '';
										}
										
										$nvr_prop_city = 'property_city';
										$nvr_cities = get_the_terms( get_the_ID(), $nvr_prop_city );
										$nvr_cityarr = array();
										if ( !empty( $nvr_cities ) ) {
											foreach ( $nvr_cities as $nvr_city ) {
												$nvr_cityarr[] = $nvr_city->name;
											}
											$nvr_city = implode(', ', $nvr_cityarr);
										}else{
											$nvr_city = '';
										}
										
										$nvr_priceoutput = '';
										if(!empty($nvr_price)){
											$nvr_priceoutput = nvr_show_price($nvr_price, $nvr_cursymbol, $nvr_curplace).' '.$nvr_plabel;
										}
 
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
                                            <span class="propcity"><i class="fa fa-map-marker"></i> <?php  echo $nvr_city; ?></span>
                                            <span class="proptype"><?php  echo $nvr_type; ?></span>
                                            <span class="propprice"><?php  echo $nvr_priceoutput; ?></span>
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
		$instance['purpose'] = (isset($instance['purpose']))? $instance['purpose'] : "";
		$instance['city'] = (isset($instance['city']))? $instance['city'] : "";
		$instance['showpost'] = (isset($instance['showpost']))? $instance['showpost'] : "";
		$instance['disablethumb'] = (isset($instance['disablethumb']))? $instance['disablethumb'] : "";
					
        $title = esc_attr($instance['title']);
		$category = esc_attr($instance['category']);
		$purpose = esc_attr($instance['purpose']);
		$city = esc_attr($instance['city']);
		$showpost = esc_attr($instance['showpost']);
		$disablethumb = esc_attr($instance['disablethumb']);
        ?>
            <p><label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php _e('Title:', THE_LANG); ?> <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
			
            <p><label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Type:', THE_LANG); ?><br />
			<?php 
			$args = array(
			'selected'         => $category,
			'show_option_all'	=> __('All Types', THE_LANG),
			'echo'             => 1,
			'taxonomy'			=> 'property_category',
			'name'             => $this->get_field_name('category'),
			'hide_if_empty'		=> true
			);
			wp_dropdown_categories( $args );
			?>
			</label></p>
            
            <p><label for="<?php echo $this->get_field_id('purpose'); ?>"><?php _e('Purpose:', THE_LANG); ?><br />
			<?php 
			$args = array(
			'selected'         => $purpose,
			'show_option_all'	=> __('All Purposes', THE_LANG),
			'echo'             => 1,
			'taxonomy'			=> 'property_purpose',
			'name'             => $this->get_field_name('purpose'),
			'hide_if_empty'		=> true
			);
			wp_dropdown_categories( $args );
			?>
			</label></p>
            
            <p><label for="<?php echo $this->get_field_id('city'); ?>"><?php _e('City:', THE_LANG); ?><br />
			<?php 
			$args = array(
			'selected'         => $city,
			'show_option_all'	=> __('All Cities', THE_LANG),
			'echo'             => 1,
			'taxonomy'			=> 'property_city',
			'name'             => $this->get_field_name('city'),
			'hide_if_empty'		=> true
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
<?php
// =============================== Novaro Property Search widget ======================================
class NVR_PropertySearchWidget extends WP_Widget {
    /** constructor */

	function NVR_PropertySearchWidget() {
		$widget_ops = array('classname' => 'widget_nvr_property_search', 'description' => __('Novaro - Property Search',THE_LANG) );
		$this->WP_Widget('nvr-property-search', __('Novaro - Property Search',THE_LANG), $widget_ops);
	}


  /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Property Search',THE_LANG) : $instance['title']);
		
		global $wp_query;
		
		$nvr_initial = THE_INITIAL;
		$nvr_shortname = THE_SHORTNAME;
        
		$nvr_filterMap  =  nvr_get_option( $nvr_shortname . '_filter_map');
		$nvr_minprice = nvr_get_option( $nvr_shortname . '_min_price','0');
		$nvr_maxprice = nvr_get_option( $nvr_shortname . '_max_price','1000000');
		$nvr_cursymbol = nvr_get_option( $nvr_shortname . '_currency_symbol');
		$nvr_curplace = nvr_get_option( $nvr_shortname . '_currency_place');
		
		if(!is_numeric($nvr_minprice)){
			$nvr_minprice = 0;
		}
		if(!is_numeric($nvr_maxprice)){
			$nvr_maxprice = 1000000;
		}
		
		$nvr_search_page = nvr_get_propsearch_page();
		$nvr_adv_submit = $nvr_search_page['submiturl'];
		$nvr_page_id = $nvr_search_page['pageid'];
		
		$icons          =   array();
		$nvr_purposecat     =   'property_purpose';
		$nvr_purpose_terms	=   get_terms($nvr_purposecat);
		$nvr_propertycat	=   'property_category';
		$nvr_property_cats	=   get_terms($nvr_propertycat);
		$nvr_propertycity	=   'property_city';
		$nvr_property_cities=   get_terms($nvr_propertycity);
		
		$nvr_propstatuses = nvr_get_option($nvr_shortname.'_property_status');
		?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                        		
								
                                <!-- Advanced Search Container -->
                               <div class="nvr-widget-prop-search">
                               <?php
                               $filter_keywords = isset($_REQUEST['adv_filter_keywords'])? $_REQUEST['adv_filter_keywords'] : '';
                               $filter_purpose = isset($_REQUEST['adv_filter_purpose'])? $_REQUEST['adv_filter_purpose'] : '';
                               $filter_type = isset($_REQUEST['adv_filter_type'])? $_REQUEST['adv_filter_type'] : '';
                               $filter_city = isset($_REQUEST['adv_filter_city'])? $_REQUEST['adv_filter_city'] : '';
                               $filter_status = isset($_REQUEST['adv_filter_status'])? $_REQUEST['adv_filter_status'] : '';
                               $filter_numroom = isset($_REQUEST['adv_filter_numroom'])? $_REQUEST['adv_filter_numroom'] : '';
                               $filter_numbath = isset($_REQUEST['adv_filter_numbath'])? $_REQUEST['adv_filter_numbath'] : '';
                               $filter_price_min = isset($_REQUEST['adv_filter_price_min'])? $_REQUEST['adv_filter_price_min'] : $nvr_minprice;
                               $filter_price_max = isset($_REQUEST['adv_filter_price_max'])? $_REQUEST['adv_filter_price_max'] : $nvr_maxprice;
                               ?>
                                    <div class="advanced-search">
                                    <form id="frmwdgsearch" class="frmadvsearch" method="get" action="<?php echo esc_url( $nvr_adv_submit ); ?>">
                                        <div class="form-search container">
                                            <div class="row">
                                                <div class="twelve columns"><input type="text" class="nvrtextbox" name="adv_filter_keywords" id="wdg_filter_keywords" value="<?php echo esc_attr($filter_keywords); ?>" placeholder="<?php _e('Keywords', THE_LANG); ?>" /></div>
                                        	</div>
                                            <div class="row">
                                                <div class="twelve columns">
                                                    <select id="wdg_filter_city" name="adv_filter_city" class="nvrselector">
                                                        <option value="">-- <?php _e('All Cities', THE_LANG);?> --</option>
                                                        <?php
                                                            foreach ($nvr_property_cities as $nvr_property_city) {
                                                                $nvr_cityslug	= $nvr_property_city->slug;
                                                                $nvr_cityname	= $nvr_property_city->name;
                                                                
                                                                if($nvr_cityslug==$filter_city){
                                                                    $optselected = 'selected="selected"';
                                                                }else{
                                                                    $optselected = '';
                                                                }
                                                                echo '<option value="'.esc_attr( $nvr_cityslug ).'" '.$optselected.'>'.$nvr_cityname.'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                       		</div>
                                            <div class="row">
                                                <div class="twelve columns">
                                                    <select id="wdg_filter_purpose" name="adv_filter_purpose" class="nvrselector">
                                                        <option value="">-- <?php _e('Any Purpose', THE_LANG);?> --</option>
                                                        <?php
                                                            foreach ($nvr_purpose_terms as $nvr_purpose_term) {
                                                                $nvr_purposeslug	= $nvr_purpose_term->slug;
                                                                $nvr_purposename	= $nvr_purpose_term->name;
                                                                
                                                                if($nvr_purposeslug==$filter_purpose){
                                                                    $optselected = 'selected="selected"';
                                                                }else{
                                                                    $optselected = '';
                                                                }
                                                                echo '<option value="'.esc_attr( $nvr_purposeslug ).'" '.$optselected.'>'.$nvr_purposename.'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                       		</div>
                                            <div class="row">
                                                <div class="twelve columns">
                                                    <select id="wdg_filter_type" name="adv_filter_type" class="nvrselector">
                                                        <option value="">-- <?php _e('Any Type', THE_LANG);?> --</option>
                                                        <?php
                                                            foreach ($nvr_property_cats as $nvr_property_cat) {
                                                                $nvr_catslug	= $nvr_property_cat->slug;
                                                                $nvr_catname	= $nvr_property_cat->name;
                                                                
                                                                if($nvr_catslug==$filter_type){
                                                                    $optselected = 'selected="selected"';
                                                                }else{
                                                                    $optselected = '';
                                                                }
                                                                echo '<option value="'.esc_attr( $nvr_catslug ).'" '.$optselected.'>'.$nvr_catname.'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="twelve columns">
                                                    <select id="wdg_filter_status" name="adv_filter_status" class="nvrselector">
                                                        <option value="">-- <?php _e('Any Status', THE_LANG);?> --</option>
                                                        <option value="Normal"><?php _e('Normal', THE_LANG);?></option>
                                                        <?php
                                                        $nvr_optpropstatus = array();
                                                        for($i=0;$i<count($nvr_propstatuses);$i++){
                                                            $nvr_propstatus = $nvr_propstatuses[$i];
                                                            $nvr_optpropstatus[$nvr_propstatus] = $nvr_propstatus;
                                                            
                                                            if($nvr_propstatus==$filter_status){
                                                                $optselected = 'selected="selected"';
                                                            }else{
                                                                $optselected = '';
                                                            }
                                                            echo '<option value="'. esc_attr( $nvr_propstatus ).'" '.$optselected.'>'.$nvr_propstatus.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                         	</div>
                                            <div class="row">
                                                <div class="six columns"><input type="text" class="nvrtextbox" id="wdg_filter_numroom" name="adv_filter_numroom" value="<?php echo esc_attr($filter_numroom); ?>" placeholder="<?php _e('Number of Rooms', THE_LANG); ?>" /></div>
                                                <div class="six columns"><input type="text" class="nvrtextbox" id="wdg_filter_numbath" name="adv_filter_numbath" value="<?php echo esc_attr($filter_numbath); ?>" placeholder="<?php _e('Number of Bathrooms', THE_LANG); ?>" /></div>
                                        	</div>
                                            <div class="row">
                                                <div class="twelve columns rangeslidercontainer">
                                                    <div class="rangeslider"></div>
                                                    <div class="rangetext"><?php echo $nvr_cursymbol; ?><span class="text_price_min"><?php echo esc_attr($filter_price_min); ?></span> - <?php echo $nvr_cursymbol; ?><span class="text_price_max"><?php echo esc_attr($filter_price_max); ?></span></div>
                                                    <input type="hidden" name="adv_filter_price_min" class="adv_filter_price_min" value="<?php echo esc_attr($filter_price_min); ?>" />
                                                    <input type="hidden" name="adv_filter_price_max" class="adv_filter_price_max" value="<?php echo esc_attr($filter_price_max); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container">
                                            <div class="row">
                                                <div class="twelve columns"><input name="submit" type="submit" class="button" id="wdg_filter_submit" value="<?php _e('Submit Advanced Search',THE_LANG);?>"></div>
                                            </div>
                                        </div>
                                        <input name="page_id" id="nvr_page_id" type="hidden" value="<?php echo esc_attr( $nvr_page_id ); ?>" />
                                    </form>
                                    </div>
                               </div>
                               <!-- END Advanced Search Container -->

								
								
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
        $title = esc_attr($instance['title']);
        ?>
            <p><label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php _e('Title:', THE_LANG); ?> <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
        <?php 
    }

} // class  Widget
?>
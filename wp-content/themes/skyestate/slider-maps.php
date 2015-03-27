<?php /* Called from slider.php */

$nvr_shortname = THE_SHORTNAME;
$nvr_initial = THE_INITIAL;

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
?>

<!-- Google Map -->
<?php
$icons          =   array();
$nvr_purposecat     =   'property_purpose';
$nvr_purpose_terms	=   get_terms($nvr_purposecat);
$nvr_propertycat	=   'property_category';
$nvr_property_cats	=   get_terms($nvr_propertycat);
$nvr_propertycity	=   'property_city';
$nvr_property_cities=   get_terms($nvr_propertycity);

$nvr_propstatuses = nvr_get_option($nvr_shortname.'_property_status');
?>

<div class="outermaps_container">

    <?php 
    $nvr_gelocation	= nvr_get_option($nvr_shortname.'_enable_geolocation');

    if($nvr_gelocation){ 
      echo '<a id="btn-geolocation" href="#"><i class="fa fa-street-view"></i>&nbsp; '.__('My Location', THE_LANG).'</a>';
    } 
    ?>
    <div id="gMapsContainer"></div>    
    <div id="gmap-loader">
        <span class="loadertext"><?php _e('Loading Maps', THE_LANG);?></span>
        <img src="<?php echo esc_url( get_template_directory_uri().'/images/pf-loader.gif' ); ?>" alt="loader"/>
    </div>
   
   
   <?php if($nvr_filterMap==true){ ?>
   
       <div class="map-filter-wrapper" >   
            <div class="map-filter" id="map-filter">
                <a id="closefilters" href="#"></a>
                <div class="action_filter">
                <?php
                foreach ($nvr_purpose_terms as $nvr_purpose_term) {
                    $nvr_purposeslug	= $nvr_purpose_term->slug;
                    $nvr_purposename	= $nvr_purpose_term->name;
                        echo '<div class="checker"><input type="checkbox" checked="checked"  name="filter_purpose[]" id="'.esc_attr( $nvr_purposeslug ).'" class="'.esc_attr( $nvr_purposeslug ).'"  value="'.esc_attr( $nvr_purposename ).'"/><label for="'.esc_attr( $nvr_purposeslug ).'"><span>'.$nvr_purposename.'</span></label></div>';
                }
                ?>
                </div> 
    
                <div class="type-filters">
                <?php
                foreach ($nvr_property_cats as $nvr_property_cat) {
                     $nvr_catslug	= $nvr_property_cat->slug;
                     $nvr_catname	= $nvr_property_cat->name;
                     echo '<div class="checker"><input type="checkbox" checked="checked"  name="filter_cat[]" id="'. esc_attr( $nvr_catslug ).'"  class="'. esc_attr( $nvr_catslug ) .'" value="'. esc_attr( $nvr_catname ).'"/><label for="' . esc_attr( $nvr_catslug ) . '"><span>'. $nvr_catname .'</span></label></div>';
                }
                ?>
                </div> 
            </div>
        </div>
    
   <?php } ?>
   
   <!-- Advanced Search Container -->
   <div class="advanced-search-container" id="advanced-search-box">
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
        <a class="button" href="#" id="toggle-advanced-search"><span id="showsearch"><i class="fa fa-chevron-up"></i> <?php _e('Show Advanced Search', THE_LANG); ?></span><span id="closesearch"><i class="fa fa-chevron-down"></i> <?php _e('Close Advanced Search', THE_LANG); ?></span></a>
        <a class="maps-nav-next fa fa-chevron-right" id="maps-nav-next" href="#"></a>
    	<a class="maps-nav-prev fa fa-chevron-left" id="maps-nav-prev" href="#"></a>
        <form id="frmadvsearch" class="frmadvsearch" method="get" action="<?php echo esc_url( $nvr_adv_submit ); ?>">
            <div class="form-search container">
                <div class="row search-row-1">
                    <div class="three columns"><input type="text" class="nvrtextbox" name="adv_filter_keywords" id="adv_filter_keywords" value="<?php echo esc_attr($filter_keywords); ?>" placeholder="<?php esc_attr_e('Keywords', THE_LANG); ?>" /></div>
                    <div class="three columns">
                        <select id="adv_filter_city" name="adv_filter_city" class="nvrselector">
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
                    <div class="three columns">
                        <select id="adv_filter_purpose" name="adv_filter_purpose" class="nvrselector">
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
                    <div class="three columns">
                        <select id="adv_filter_type" name="adv_filter_type" class="nvrselector">
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
                <div class="row search-row-2">
                    <div class="three columns">
                        <select id="adv_filter_status" name="adv_filter_status" class="nvrselector">
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
                    <div class="three columns"><input type="text" class="nvrtextbox" id="adv_filter_numroom" name="adv_filter_numroom" value="<?php echo esc_attr($filter_numroom); ?>" placeholder="<?php esc_attr_e('Number of Rooms', THE_LANG); ?>" /></div>
                    <div class="three columns"><input type="text" class="nvrtextbox" id="adv_filter_numbath" name="adv_filter_numbath" value="<?php echo esc_attr($filter_numbath); ?>" placeholder="<?php esc_attr_e('Number of Bathrooms', THE_LANG); ?>" /></div>
                    <div class="three columns rangeslidercontainer">
                        <div class="rangeslider"></div>
                        <div class="rangetext"><?php echo $nvr_cursymbol; ?><span id="text_price_min" class="text_price_min"><?php echo esc_attr($filter_price_min); ?></span> - <?php echo $nvr_cursymbol; ?><span id="text_price_max" class="text_price_max"><?php echo esc_attr($filter_price_max); ?></span></div>
                        <input type="hidden" name="adv_filter_price_min" id="adv_filter_price_min" class="adv_filter_price_min" value="<?php echo esc_attr($filter_price_min); ?>" />
                        <input type="hidden" name="adv_filter_price_max" id="adv_filter_price_max" class="adv_filter_price_max" value="<?php echo esc_attr($filter_price_max); ?>" />
                    </div>
                </div>
            </div>
            <div id="advanced-search-ammenities" class="form-ammenities container">
                <?php
                $nvr_propamenities = nvr_get_option($nvr_shortname.'_property_amenities');
                
                if($nvr_propamenities){
                    echo '<div class="row">';
						echo '<div class="three columns">'.__('Amenities', THE_LANG).'</div>';
						echo '<div class="nine columns">';
							echo '<div class="row">';
							for($i=0;$i<count($nvr_propamenities);$i++){
								if($i%3==0 && $i>0){
									echo '</div><div class="row">';
								}
								$nvr_propamenity = $nvr_propamenities[$i];
								echo '<div class="three columns"><label for="'. esc_attr( 'adv_filter_ammenity'.$i ) .'"><input type="checkbox" class="adv_filter_ammenity" name="adv_filter_ammenity[]" id="'. esc_attr( 'adv_filter_ammenity'.$i ) .'" value="'.esc_attr( $nvr_propamenity ).'" /> '.__($nvr_propamenity, THE_LANG).'</label></div>';
							}
							echo '</div>';
						echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
            <div class="form-filter container">
                <div class="row">
                    <div class="six columns"><a href="#" id="filtertab" class="button"><span id="morefilter"><i class="fa fa-caret-up"></i> <?php _e('More Filters', THE_LANG); ?></span><span id="lessfilter"><i class="fa fa-caret-down"></i>  <?php _e('Less Filters', THE_LANG); ?></a></div>
                    <div class="three columns quicksearchbutton"><input name="quicksearch" type="button" class="button" id="adv_quick_search" value="<?php esc_attr_e('Quick Search',THE_LANG);?>"></div>
                    <div class="three columns"><input name="submit" type="submit" class="button" id="adv_filter_submit" value="<?php esc_attr_e('Submit Advanced Search',THE_LANG);?>"></div>
                </div>
                <?php $nvr_nonce = wp_create_nonce("nvr_propadvancefilter_nonce"); ?>
                <input name="adv_filter_nonce" id="adv_filter_nonce" type="hidden" value="<?php echo esc_attr( $nvr_nonce ); ?>" />
                <input name="page_id" id="nvr_page_id" type="hidden" value="<?php echo esc_attr( $nvr_page_id ); ?>" />
            </div>
        </form>
        </div>
   </div>
   <!-- END Advanced Search Container -->
   <?php wp_reset_query(); ?>
</div>
<!-- END Google Map -->
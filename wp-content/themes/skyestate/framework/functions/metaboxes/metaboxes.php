<?php
add_action('add_meta_boxes', 'nvr_add_metabox');

// Add meta box
function nvr_add_metabox() {
	global $nvr_meta_boxes;
	if(is_array($nvr_meta_boxes)){
		foreach($nvr_meta_boxes as $nvr_meta_box){
			$metaargs = array(
				'meta_array' => $nvr_meta_box
			);
			add_meta_box($nvr_meta_box['id'], $nvr_meta_box['title'], $nvr_meta_box['showbox'], $nvr_meta_box['page'], $nvr_meta_box['context'], $nvr_meta_box['priority'], $metaargs);
		}
	}
}

function meta_option_show_box($post, $metaargs) {
	global $nvr_meta_boxes;
	
	$meta_array = $metaargs['args']['meta_array'];
	// Use nonce for verification
	echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	echo nvr_create_metabox($meta_array);
}


// Create Metabox Form Table
function nvr_create_metabox($meta_box){

	global $post;
	
	$returnstring = "";
	$returnstring .='
					<style type="text/css">
						.optionimg{border:3px solid #cecece; margin-right:4px;cursor:pointer;}
						.optionimg.optselected{border-color:#ababab;}
						.form-table td em{font-style:normal;color:#999999;font-size:11px;}
					</style>
					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery( \'.optionimg\').click(function(){
							jQuery(this).parent().find( \'.optionimg\').removeClass( \'optselected\' );
							jQuery(this).addClass( \'optselected\' );
						});
					});
					</script>
				';
	$returnstring .= '<table class="form-table">';
 
	foreach ($meta_box['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
 
		$row2c = '<tr>'.
				 '<th style="width:20%;border-bottom:1px solid #e4e4e4;padding:15px 0px"><label for="'. $field['id']. '">'.$field['name']. '</label></th>'.
				 '<td style="border-bottom:1px solid #e4e4e4;padding:15px 0px">';
		
		$row1c = '<tr>'.
				 '<td colspan="2" style="border-bottom:1px solid #e4e4e4;padding:15px 0px">';
		
		switch ($field['type']) {
 
//If Text		
			case 'text':
				$textvalue = $meta ? $meta : $field['std'];
				$widthinput = "97%";
				$prefixinput = "";
				$postfixinput = "";
				if(isset($field['class'])){
					if($field['class']=="mini"){
						$widthinput = "20%";
					}
				}
				if(isset($field['prefix'])){
					$prefixinput = stripslashes(trim($field['prefix']));
				}
				if(isset($field['postfix'])){
					$postfixinput = stripslashes(trim($field['postfix']));
				}
				$returnstring .= $row2c;
				$returnstring .= $prefixinput.'<input type="text" name="'. $field['id']. '" id="'. $field['id']. '" value="'. esc_attr($textvalue) .'" size="30" style="width:'.$widthinput.'" /> '.$postfixinput.
					'<br />'.$field['desc'];
				break;
 
 
//If Text Area			
			case 'textarea':
				$textvalue = $meta ? $meta : $field['std'];
				$returnstring .= $row2c;
				$returnstring .= '<textarea name="'. $field['id']. '" id="'. $field['id']. '" cols="60" rows="4" style="width:97%">'. esc_textarea($textvalue) .'</textarea>'.
					'<br />'.$field['desc'];
				break;
			
			case 'gmaps':
				$returnstring .= $row1c;
				$returnstring .= '<div class="nvr_googlemap">';
				$returnstring .= '<div id="gMapContainer" style="width:100%;height:380px;margin-bottom:30px;"></div>    
									<div class="meta-options"> 
										<a class="button" href="#" id="admin_place_pin">'.__('Place Pin to Point Your Property','wpestate').'</a>
								  </div>';
				$returnstring .= '</div>';
				break;
			
			case 'imagegallery':
				$textvalue = $meta ? $meta : $field['std'];

				$returnstring .= $row1c;
				$returnstring .= '<div id="nvrpost_images_container">';
				$returnstring .= '<ul class="nvrpost_images">';
							$product_image_gallery = $textvalue;
							$attachments = array_filter( explode( ',', $product_image_gallery ) );
							$imagelists ='';
							if ( $attachments ) {
								foreach ( $attachments as $attachment_id ) { 
									$imagelists .='<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
										' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '
										<ul class="actions">
											<li><a href="#" class="delete tips" data-tip="' . __( 'Delete image', THE_LANG ) . '">' . __( 'Delete', THE_LANG ) . '</a></li>
										</ul>
									</li>';
								}
							}
				$returnstring .= $imagelists;
				$returnstring .= '</ul>';
		
				$returnstring .= '<input type="hidden" id="'.$field['id'].'" name="'.$field['id'].'" value="'. esc_attr( $textvalue ) .'" />';
		
				$returnstring .= '</div>';
				$returnstring .= '<p class="add_nvrpost_images hide-if-no-js">';
				$returnstring .= '<a href="#" data-choose="'. __( 'Add Images to Post Gallery', THE_LANG ) .'" data-update="'. __( 'Add to gallery', THE_LANG ) .'" data-delete="'. __( 'Delete image', THE_LANG ) .'" data-text="'. __( 'Delete', THE_LANG ) .'">'. __( 'Add post gallery images', THE_LANG ) .'</a>';
				$returnstring .= '</p>';
				break;
			
			case 'sectionbuilder':
				$textvalue = $meta ? $meta : $field['std'];
				$returnstring .= $row1c;
				$returnstring .= '<div class="nvr_sectionbuilder" id="sectionbuilder'.$field['id'].'">';
				$returnstring .= '<a class="button nvr_add_section_button" id="nvr_add_section_button'.$field['id'].'" href="'.$field['id'].'">'.__('Add Section', THE_LANG).'</a>';
				
				$sectionarrs = (is_array($textvalue) && count($textvalue)>0)? $textvalue : array( 0 => array('backgroundcolor' => '', 'background' => '', 'customclass' => '', 'content' => ''));
				$i=0;
				foreach($sectionarrs as $sectionar){
					$sectionno = $i+1;
					$returnstring .= '<div class="nvr_sectionrow" id="nvr_sectionrow'.$field['id'].'_'.$sectionno.'">';
						$returnstring .= '<a class="button nvr_remove_section_button" href="#nvr_sectionrow'.$field['id'].'_'.$sectionno.'">'.__('Remove Section', THE_LANG).'</a>';
						$returnstring .= '<table class="nvr_sectiontable" cellpadding="0" cellspacing="0" border="0">';
							$returnstring .= '<tr>';
								$returnstring .= '<td class="small"><label>'.__('BG Color', THE_LANG). '</label><br /><input type="text" name="'. $field['id']. '['.$i.'][backgroundcolor]" value="'.esc_attr($sectionar['backgroundcolor']).'" /></td>';
								$returnstring .= '<td class="large"><label>'.__('BG Image', THE_LANG). '</label><br /><input type="text" name="'. $field['id']. '['.$i.'][background]" value="'.esc_attr($sectionar['background']).'" /></td>';
								$returnstring .= '<td class="small"><label>'.__('Custom Class', THE_LANG). '</label><br /><input type="text" name="'. $field['id']. '['.$i.'][customclass]" value="'.esc_attr($sectionar['customclass']).'" /></td>';
							$returnstring .= '</tr>';
							$returnstring .= '<tr>';
								$returnstring .= '<td colspan="3"><label>'.__('Content', THE_LANG). '</label><br /><textarea name="'. $field['id']. '['.$i.'][content]" rows="9">'. esc_textarea($sectionar['content']) .'</textarea></td>';
							$returnstring .= '</tr>';
						$returnstring .= '</table>';
					$returnstring .= '</div>';
					$i++;
				}
				$returnstring .= '<input type="hidden" value="'.$sectionno.'" name="sectionrowcounter'.$field['id'].'" id="sectionrowcounter'.$field['id'].'" />';
				$returnstring .= '</div>';
				break;
 
//If Select Combobox			
			case 'select':
				$optvalue = $meta ? $meta : $field['std'];
				$returnstring .= $row2c;
				$returnstring .= '<select name="'. $field['id']. '" id="'. $field['id']. '">';
				foreach ($field['options'] as $option => $val){
					$selectedstr = ($optvalue==$option)? 'selected="selected"' : '';
					$returnstring .= '<option value="'.$option.'" '.$selectedstr.'>'. $val .'</option>';
				}
				$returnstring .= '</select>';
				$returnstring .= '<br />'.$field['desc'];
				break;
			
			case 'select-blog-category':
				$optvalue = $meta ? $meta : $field['std'];
				
				// Pull all the categories into an array
				$options_categories = array();
				$options_categories_obj = get_categories();
				$options_categories["allcategories"] =__('Select Post Category',THE_LANG);
				foreach ($options_categories_obj as $category) {
					$options_categories[$category->slug] = $category->cat_name;
				}
				
				$returnstring .= $row2c;
				$returnstring .= '<select name="'. $field['id']. '" id="'. $field['id']. '">';
				foreach ($options_categories as $option => $val){
					$selectedstr = ($optvalue==$option)? 'selected="selected"' : '';
					$returnstring .= '<option value="'.esc_attr( $option ).'" '.$selectedstr.'>'. $val .'</option>';
				}
				$returnstring .= '</select>';
				
				$returnstring .= '<br />'.$field['desc'];
				break;
				
//If Multiple Checkbox
			case 'checkbox-multiple':
				$chkvalue = $meta ? $meta : $field['std'];
				$chkvalue = explode(",",$chkvalue);
				
				
				$returnstring .= $row2c;
				foreach ($field['options'] as $option => $val){
					$checkedstr="";
					if(in_array($option,$chkvalue)){
						$checkedstr = 'checked="checked"';
					}
					$returnstring .= '<div style="float:left;width:30%;">';
					$returnstring .= '<input type="checkbox" value="'. $option .'" name="'. $field['id']. '[\''.$option.'\']" id="'. $field['id']."-". $option . '" '.$checkedstr.' />&nbsp;&nbsp;'. $val;
					$returnstring .= '</div>';
				}
				$returnstring .= '<div style="clear:both;"></div><br />'.$field['desc'];
				break;

//If Checkbox for Blog Categories
			case 'checkbox-blog-categories':
				$chkvalue = $meta ? $meta : $field['std'];
				$chkvalue = explode(",",$chkvalue);
				$args = array(
					"type" 			=> "post",
					"taxonomy" 	=> "category"
				);
				$portcategories = get_categories($args);
				
				$returnstring .= $row2c;
				foreach($portcategories as $category){
					$checkedstr="";
					if(in_array($category->slug,$chkvalue)){
						$checkedstr = 'checked="checked"';
					}
					$returnstring .= '<div style="float:left;width:30%;">';
					$returnstring .= '<input type="checkbox" value="'. esc_attr( $category->slug ) .'" name="'. $field['id']. '[\''.$category->slug.'\']" id="'. $field['id']."-". $category->name . '" '.$checkedstr.' />&nbsp;&nbsp;'. $category->name;
					$returnstring .= '</div>';
				}
				$returnstring .= '<div style="clear:both;"></div><br />'.$field['desc'];
				break;

//If Select Slider Combobox			
			case 'select-slider-category':
				$optvalue = $meta ? $meta : $field['std'];
				
				// Pull all the slider categories into an array
				$options_pcategory = array();
				$options_pcategory_obj = get_terms('slidercat');
				$options_pcategory[""] =__('Select Slider Category',THE_LANG);
				if($options_pcategory_obj){
					foreach ($options_pcategory_obj as $pcategory) {
						$options_pcategory[$pcategory->slug] = $pcategory->name;
					}
				}
				
				$returnstring .= $row2c;
				$returnstring .= '<select name="'. $field['id']. '" id="'. $field['id']. '">';
				foreach ($options_pcategory as $option => $val){
					$selectedstr = ($optvalue==$option)? 'selected="selected"' : '';
					$returnstring .= '<option value="'.esc_attr( $option ).'" '.$selectedstr.'>'. $val .'</option>';
				}
				$returnstring .= '</select>';
				
				$returnstring .= '<br />'.$field['desc'];
				break;

//If Checkbox for Slider Categories
			case 'checkbox-slider-categories':
				$chkvalue = $meta ? $meta : $field['std'];
				$chkvalue = explode(",",$chkvalue);
				$args = array(
					"type" 			=> "slider",
					"taxonomy" 	=> "slidercat"
				);
				$portcategories = get_categories($args);
				
				$returnstring .= $row2c;
				foreach($portcategories as $category){
					$checkedstr="";
					if(in_array($category->slug,$chkvalue)){
						$checkedstr = 'checked="checked"';
					}
					$returnstring .= '<div style="float:left;width:30%;">';
					$returnstring .= '<input type="checkbox" value="'. esc_attr( $category->slug ) .'" name="'. $field['id']. '[\''.$category->slug.'\']" id="'. $field['id']."-". $category->name . '" '.$checkedstr.' />&nbsp;&nbsp;'. $category->name;
					$returnstring .= '</div>';
				}
				$returnstring .= '<div style="clear:both;"></div><br />'.$field['desc'];
				break;
				
//If Select Portfolio Combobox			
			case 'select-portfolio-category':
				$optvalue = $meta ? $meta : $field['std'];
				
				// Pull all the slider categories into an array
				$options_pcategory = array();
				$options_pcategory_obj = get_terms('portfoliocat');
				$options_pcategory[""] =__('Select Portfolio Category',THE_LANG);
				if($options_pcategory_obj){
					foreach ($options_pcategory_obj as $pcategory) {
						$options_pcategory[$pcategory->slug] = $pcategory->name;
					}
				}
				
				$returnstring .= $row2c;
				$returnstring .= '<select name="'. $field['id']. '" id="'. $field['id']. '">';
				foreach ($options_pcategory as $option => $val){
					$selectedstr = ($optvalue==$option)? 'selected="selected"' : '';
					$returnstring .= '<option value="'.esc_attr( $option ).'" '.$selectedstr.'>'. $val .'</option>';
				}
				$returnstring .= '</select>';
				
				$returnstring .= '<br />'.$field['desc'];
				break;
				
//If Checkbox for Portfolio Categories
			case 'checkbox-portfolio-categories':
				$chkvalue = $meta ? $meta : $field['std'];
				$chkvalue = explode(",",$chkvalue);
				$args = array(
					"type" 			=> "portofolio",
					"taxonomy" 	=> "portfoliocat"
				);
				$portcategories = get_categories($args);
				
				$returnstring .= $row2c;
				foreach($portcategories as $category){
					$checkedstr="";
					if(in_array($category->slug,$chkvalue)){
						$checkedstr = 'checked="checked"';
					}
					$returnstring .= '<div style="float:left;width:30%;">';
					$returnstring .= '<input type="checkbox" value="'. esc_attr( $category->slug ) .'" name="'. $field['id']. '[\''.$category->slug.'\']" id="'. $field['id']."-". $category->name . '" '.$checkedstr.' />&nbsp;&nbsp;'. $category->name;
					$returnstring .= '</div>';
				}
				$returnstring .= '<div style="clear:both;"></div><br />'.$field['desc'];
				break;
				
//If Checkbox for Property Categories
			case 'checkbox-property-categories':
				$chkvalue = $meta ? $meta : $field['std'];
				$chkvalue = explode(",",$chkvalue);
				$args = array(
					"type" 			=> "propertys",
					"taxonomy" 	=> "property_category"
				);
				$portcategories = get_categories($args);
				
				$returnstring .= $row2c;
				foreach($portcategories as $category){
					$checkedstr="";
					if(in_array($category->slug,$chkvalue)){
						$checkedstr = 'checked="checked"';
					}
					$returnstring .= '<div style="float:left;width:30%;">';
					$returnstring .= '<input type="checkbox" value="'. esc_attr( $category->slug ) .'" name="'. $field['id']. '[\''.$category->slug.'\']" id="'. $field['id']."-". $category->name . '" '.$checkedstr.' />&nbsp;&nbsp;'. $category->name;
					$returnstring .= '</div>';
				}
				$returnstring .= '<div style="clear:both;"></div><br />'.$field['desc'];
				break;

//If Select Image			
			case 'selectimage':
				$optvalue = $meta ? $meta : $field['std'];
				
				$returnstring .= $row2c;
				foreach ($field['options'] as $option => $val){
					$selectedstr = ($optvalue==$option)? 'optselected' : '';
					$checkedstr = ($optvalue==$option)? 'checked="checked"' : '';
					$returnstring .= '<img src="'.$val.'" class="optionimg '.$selectedstr.'" onclick="document.getElementById(\''.$field['id'].$option.'\').checked=true" style="display:inline-block;" />';
					$returnstring .= '<input type="radio" name="'.$field['id'].'" id="'.$field['id'].$option.'" value="'.esc_attr( $option ).'" '.$checkedstr.' style="display:none;"/>';
				}
				$returnstring .= '<br />'.$field['desc'];
				break;

//If Checkbox			
			case 'checkbox':
				$chkvalue = $meta ? true : $field['std'];
				$checkedstr = ($chkvalue)? 'checked="checked"' : '';
				
				$returnstring .= $row2c;
				$returnstring .= '<input type="checkbox" name="'. $field['id']. '" id="'. $field['id']. '" '.$checkedstr.' />';
				$returnstring .= '<br />'.$field['desc'];
				break;
				 
//If Button	
			case 'button':
				$buttonvalue = $meta ? $meta : $field['std'] ;
				
				$returnstring .= $row2c;
				$returnstring .= '<input type="button" name="'. $field['id']. '" id="'. $field['id']. '"value="'. $buttonvalue. '" />';
				$returnstring .= '<br />'.$field['desc'];
				break;

 
				
		}
		$returnstring .= 	'</td>'.
						'</tr>';
	}
 
	$returnstring .= '</table>';
	
	return $returnstring;

}//END : nvr_create_metabox
 
 
add_action('save_post', 'nvr_meta_save_data');
 
 
// Save data from meta box
function nvr_meta_save_data($post_id) {
	global $nvr_meta_boxes;
 
	// verify nonce
	if(isset($_POST['mytheme_meta_box_nonce'])){
		if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
			return $post_id;
		}
	}
 
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
 
	// check permissions
	if ('page' == isset($_POST['post_type'])) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
 	
	if(is_array($nvr_meta_boxes)){
		foreach($nvr_meta_boxes as $meta_box){
			foreach ($meta_box['fields'] as $field) {
				$old = get_post_meta($post_id, $field['id'], true);
				$new = (isset($_POST[$field['id']]))? $_POST[$field['id']] : "";
				
				if($field['type']=='checkbox-multiple'){ 
					if(isset($_POST[$field['id']]) && is_array($_POST[$field['id']]) && count($_POST[$field['id']])>0){
						$values = array_values($_POST[$field['id']]);
						$valuestring = implode(",",$values);
						$new = $valuestring;
						
					}else{
						$_POST[$field['id']] = $new = "";
					}
				}
				
				if($field['type']=='checkbox-portfolio-categories'){ 
					if(isset($_POST[$field['id']]) && is_array($_POST[$field['id']]) && count($_POST[$field['id']])>0){
						$values = array_values($_POST[$field['id']]);
						$valuestring = implode(",",$values);
						$new = $valuestring;
						
					}else{
						$_POST[$field['id']] = $new = "";
					}
				}
				
				if($field['type']=='checkbox-property-categories'){ 
					if(isset($_POST[$field['id']]) && is_array($_POST[$field['id']]) && count($_POST[$field['id']])>0){
						$values = array_values($_POST[$field['id']]);
						$valuestring = implode(",",$values);
						$new = $valuestring;
						
					}else{
						$_POST[$field['id']] = $new = "";
					}
				}
				
				if($field['type']=='checkbox-blog-categories'){ 
					if(isset($_POST[$field['id']]) && is_array($_POST[$field['id']]) && count($_POST[$field['id']])>0){
						$values = array_values($_POST[$field['id']]);
						$valuestring = implode(",",$values);
						$new = $valuestring;
						
					}else{
						$_POST[$field['id']] = $new = "";
					}
				}
				
				if($field['type']=='sectionbuilder'){ 
					if(isset($_POST[$field['id']]) && is_array($_POST[$field['id']]) && count($_POST[$field['id']])>0){
						$values = $_POST[$field['id']];
						$sanitizearr = array();
						foreach($values as $value){
							$sanitizearr[] = array(
								'backgroundcolor' => $value['backgroundcolor'],
								'background' => $value['background'],
								'customclass' => $value['customclass'],
								'content' => $value['content']
							);
						}
						$new = $sanitizearr;
						
					}else{
						$_POST[$field['id']] = $new = "";
					}
				}
				
				if($field['type']=='checkbox'){
					if(!isset($_POST[$field['id']])){
						$_POST[$field['id']] = $new = false;
					}
				}
				
				if (isset($_POST[$field['id']]) && $new != $old && (!isset($_POST['_inline_edit']) && !isset($_GET['bulk_edit']))) {
					update_post_meta($post_id, $field['id'], $new);
				}
			}
		}
	}
}
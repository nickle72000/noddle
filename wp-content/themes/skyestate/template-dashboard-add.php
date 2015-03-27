<?php
/**
 * Template Name: Dashboard Add
 *
 * A custom page template for showing dashboard add property form page.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0.5
 */


$nvr_shortname = THE_SHORTNAME;
$nvr_initial = THE_INITIAL;

if ( !is_user_logged_in() ) {   
	wp_redirect( home_url('url') );
} 
set_time_limit (600);


global $current_user;
get_currentuserinfo();
$userID                         =   $current_user->ID;
$user_pack                      =   get_the_author_meta( 'package_id' , $userID );
$status_values                  =   nvr_get_option( $nvr_shortname.'_property_status');
$status_values_array            =   $status_values;
$feature_list_array             =   array();
$feature_list                   =   nvr_get_option( $nvr_shortname.'_property_amenities');
$feature_list_array             =   $feature_list;
$custom_fields                  =   nvr_get_option( $nvr_shortname.'_property_custom');
$custom_field_array             =   array();
$show_err                       =   '';
$action                         =   '';
$submit_title                   =   '';
$submit_description             =   '';
$property_address               =   '';
$property_county                =   '';
$property_state                 =   '';
$property_zip                   =   '';
$country_selected               =   '';
$prop_stat                      =   '';
$property_status                =   '';

$prop_category_selected			=	'';
$prop_action_category_selected	=	'';
$property_city					=	'';
$property_price                 =   '';
$price_per_value                =   '';
$property_label					=	'';
$property_size                  =   ''; 
$property_lot_size              =   '';
$property_rooms                 =   '';
$property_bathrooms             =   '';

$property_latitude              =   ''; 
$property_longitude             =   '';

$prop_featured                  =   '';
$prop_featured_check   			=	' ';

$moving_array = array();


if( isset( $_GET['listing_edit'] ) && is_numeric( $_GET['listing_edit'] ) ){
    ///////////////////////////////////////////////////////////////////////////////////////////
    /////// If we have edit
    ///////////////////////////////////////////////////////////////////////////////////////////
    $edit_id                        =   $_GET['listing_edit'];
    $option_slider                  =   '';
    $the_post= get_post( $edit_id); 
    if( $current_user->ID != $the_post->post_author ) {
        exit('You don\'t have the rights to edit this');
    }
    $show_err                       =   '';
    $action                         =   'edit';
    $submit_title                   =   get_the_title($edit_id);
    $submit_description             =   get_post_field('post_content', $edit_id);
    
   

  
    $prop_category_array            =   get_the_terms($edit_id, 'property_category');
    if(isset($prop_category_array[0])){
         $prop_category_selected   =   $prop_category_array[0]->term_id;
    }
    
    $prop_action_category_array     =   get_the_terms($edit_id, 'property_purpose');
    if(isset($prop_action_category_array[0])){
        $prop_action_category_selected           =   $prop_action_category_array[0]->term_id;
    }
   
    
    $property_city_array            =   get_the_terms($edit_id, 'property_city');
    if(isset($property_city_array [0])){
          $property_city                  =   $property_city_array [0]->name;
    }
  
    $property_address               =   esc_html( get_post_meta($edit_id, $nvr_initial.'_address', true) );
    $property_county                =   esc_html( get_post_meta($edit_id, $nvr_initial.'_county', true) );
    $property_state                 =   esc_html( get_post_meta($edit_id, $nvr_initial.'_state', true) );
    $property_zip                   =   esc_html( get_post_meta($edit_id, $nvr_initial.'_zipcode', true) );
    $country_selected               =   esc_html( get_post_meta($edit_id, $nvr_initial.'_country', true) );
    $prop_stat                      =   esc_html( get_post_meta($edit_id, $nvr_initial.'_status', true) );
    $property_status                =   '';



   foreach ($status_values_array as $key=>$value) {
        $value = trim($value);
        $value_wpml=$value;
        $slug_status=sanitize_title($value);
        $property_status.='<option value="' . $value . '"';
        if ($value == $prop_stat) {
            $property_status.='selected="selected"';
        }
        $property_status.='>' . $value_wpml . '</option>';
    }
    
    $property_price                 =   intval   ( get_post_meta($edit_id, $nvr_initial.'_price', true) );
    $property_label                =   esc_html ( get_post_meta($edit_id, $nvr_initial.'_price_label', true));
    
    $property_size                  =   intval   ( get_post_meta($edit_id, $nvr_initial.'_size', true) ); 
    $property_lot_size              =   intval   ( get_post_meta($edit_id, $nvr_initial.'_lot_size', true) );
    $property_rooms                 =   intval   ( get_post_meta($edit_id, $nvr_initial.'_room', true) );
    $property_bathrooms             =   intval   ( get_post_meta($edit_id, $nvr_initial.'_bathroom', true) );

    $property_latitude              =   esc_html( get_post_meta($edit_id, $nvr_initial.'_latitude', true)); 
    $property_longitude             =   esc_html( get_post_meta($edit_id, $nvr_initial.'_longitude', true));
    
    $prop_featured                  =   esc_html(get_post_meta($edit_id, $nvr_initial.'_featured', true));
     if($prop_featured=='true'){
        $prop_featured_check    =' checked="checked" ';
    }else{
         $prop_featured_check   =' ';
    }
   
    //  custom fields
	$nvr_textpropcustomfields = array();
	
	if($custom_fields){
		for($i=0;$i<count($custom_fields);$i++){
			$nvr_propcustom = $custom_fields[$i]; 
			$nvr_cusslug = nvr_gen_slug($nvr_propcustom);
			$nvr_propcusval = esc_html(get_post_meta($edit_id, $nvr_initial.'_custom_'.$nvr_cusslug, true));
			$nvr_textpropcustomfields[] = array(
				'name' => $nvr_propcustom,
				'id' => $nvr_initial.'_custom_'.$nvr_cusslug,
				'val' => $nvr_propcusval
			);
			$custom_fields_array[$nvr_cusslug]=esc_html(get_post_meta($edit_id, $nvr_initial.'_custom_'.$nvr_cusslug, true));
			
		}
	}
  

     

}else{ 
    
    ///////////////////////////////////////////////////////////////////////////////////////////
    /////// If default view
    ///////////////////////////////////////////////////////////////////////////////////////////
    $action                         =   'view';
    $submit_title                   =   ''; 
    $submit_description             =   ''; 
    $prop_category                  =   ''; 
    $property_address               =   '';
	$property_city					=	'';
    $property_county                =   ''; 
    $property_state                 =   ''; 
    $property_zip                   =   ''; 
    $country_selected               =   ''; 
    $prop_stat                      =   ''; 
    $property_status                =   '';
    $property_price                 =   ''; 
    $price_per_value                =   '';
    $guest_no                       =   '';
    $cleaning_fee                   =   '';
    $city_fee                       =   '';
    $property_label                 =   '';   
    $property_size                  =   ''; 
    $property_lot_size              =   ''; 
    $property_rooms                 =   ''; 
    $property_bedrooms              =   ''; 
    $property_bathrooms             =   ''; 
    $property_latitude              =   ''; 
    $property_longitude             =   '';  
    $prop_featured                  =   '';
    $prop_category                  =   '';   
    
	$edit_id='';
    $i=0;
	$nvr_textpropcustomfields = array();
	
	if($custom_fields){
		for($i=0;$i<count($custom_fields);$i++){
			$nvr_propcustom = $custom_fields[$i]; 
			$nvr_cusslug = nvr_gen_slug($nvr_propcustom);
			$nvr_textpropcustomfields[] = array(
				'name' 	=> $nvr_propcustom,
				'id' 	=> $nvr_initial.'_custom_'.$nvr_cusslug,
				'val' 	=> ''
			);
			$custom_fields_array[$nvr_cusslug]='';
			
		}
	}

    foreach ($status_values_array as $key=>$value) {
        $value = trim($value);
        $value_wpml=$value;
        $slug_status=sanitize_title($value);

        $property_status.='<option value="' . $value . '"';
        if ($value == $prop_stat) {
            $property_status.='selected="selected"';
        }
        $property_status.='>' . $value_wpml . '</option>';
    }
}


 

      

        
    


///////////////////////////////////////////////////////////////////////////////////////////
/////// Submit Code
///////////////////////////////////////////////////////////////////////////////////////////

if( 'POST' == $_SERVER['REQUEST_METHOD'] && $_POST['action']=='view' ) {
     
     $paid_submission_status    = esc_html ( nvr_get_option( $nvr_shortname.'_paid_submission','') );
     
    if ( $paid_submission_status!='membership' || ( $paid_submission_status== 'membership' || get_current_user_listings($userID) > 0)  ){ // if user can submit
        
        if ( !isset($_POST['new_property']) || !wp_verify_nonce($_POST['new_property'],'submit_new_property') ){
           exit('Sorry, your not submiting from site'); 
        }
   
        if( !isset($_POST['prop_category']) ) {
            $prop_category=0;           
        }else{
            $prop_category  =   intval($_POST['prop_category']);
        }
  
        if( !isset($_POST['prop_action_category']) ) {
            $prop_action_category=0;           
        }else{
            $prop_action_category  =   sanitize_text_field($_POST['prop_action_category']);
        }
        
        if( !isset($_POST['property_city']) ) {
            $property_city='';           
        }else{
            $property_city  =   sanitize_text_field($_POST['property_city']);
        }
       
        $show_err                       =   '';
        $post_id                        =   '';
        $submit_title                   =   sanitize_text_field( $_POST['title'] ); 
        $submit_description             =   wp_filter_nohtml_kses( $_POST['description']);     
        $property_address               =   sanitize_text_field( $_POST['property_address']);
        $property_county                =   sanitize_text_field( $_POST['property_county']);
        $property_state                 =   sanitize_text_field( $_POST['property_state']);
        $property_zip                   =   sanitize_text_field( $_POST['property_zip']);
        $country_selected               =   sanitize_text_field( $_POST['property_country']);     
        $prop_stat                      =   sanitize_text_field( $_POST['property_status']);
        $property_status                =   '';
        
        foreach ($status_values_array as $key=>$value) {
            $value = trim($value);
            $value_wpml=$value;
            $slug_status=sanitize_title($value);

            $property_status.='<option value="' . $value . '"';
            if ($value == $prop_stat) {
                $property_status.='selected="selected"';
            }
            $property_status.='>' . $value_wpml . '</option>';
        }

        $property_price                 =   sanitize_text_field( $_POST['property_price']);
        
        if( isset($_POST['property_label']) ){
            $property_label                 =   sanitize_text_field( $_POST['property_label']);    
        }else{
            $property_label='';
        }
        $property_size                  =   sanitize_text_field( $_POST['property_size']);  
        $property_lot_size              =   sanitize_text_field( $_POST['property_lot_size']); 
        $property_rooms                 =   sanitize_text_field( $_POST['property_rooms']); 
        $property_bathrooms             =   sanitize_text_field( $_POST['property_bathrooms']); 
        $has_errors                     =   false;
        $errors                         =   array();
        
        
        
        $moving_array=array();

		if(isset($_POST[$nvr_initial.'_amenities']) && is_array($_POST[$nvr_initial.'_amenities']) && count($_POST[$nvr_initial.'_amenities'])>0){
			$moving_array = array_values($_POST[$nvr_initial.'_amenities']);
			$valuestring = implode(",",$moving_array);
		}
        $property_latitude              =   sanitize_text_field( $_POST[ $nvr_initial.'_latitude']); 
        $property_longitude             =   sanitize_text_field( $_POST[ $nvr_initial.'_longitude']); 

        if(isset($_POST['prop_featured'])){
            $prop_featured                  =    sanitize_text_field( $_POST['prop_featured']); ;
            if($prop_featured=='true'){
                 $prop_featured_check    =' checked="checked" ';
            }else{
                 $prop_featured_check   =' ';
            }  
        }

        $prop_category                  =   get_term( $prop_category, 'property_category');
        $prop_category_selected         =   $prop_category->term_id;
        $prop_action_category           =   get_term( $prop_action_category, 'property_purpose');     
        $prop_action_category_selected  =   $prop_action_category->term_id;
        
        // save custom fields
     
        $i=0;
		
		if($custom_fields){
			for($i=0;$i<count($custom_fields);$i++){
				$nvr_propcustom = $custom_fields[$i]; 
				$nvr_cusslug = nvr_gen_slug($nvr_propcustom);
				$nvr_textpropcustomfields[] = array(
					'name' 	=> $nvr_propcustom,
					'id' 	=> $nvr_initial.'_custom_'.$nvr_cusslug,
					'val' 	=> sanitize_text_field( $_POST[$nvr_initial.'_custom_'.$nvr_cusslug])
				);
				$custom_fields_array[$nvr_cusslug]= sanitize_text_field( $_POST[$nvr_initial.'_custom_'.$nvr_cusslug]);
				
			}
		}
      
            
            
        if($submit_title==''){
            $has_errors=true;
            $errors[]=__('Please submit a title for your property',THE_LANG);
        }
        
        if($submit_description==''){
            $has_errors=true;
            $errors[]=__('*Please submit a description for your property',THE_LANG);
        }
        
        if ($_POST['attachid']==''){
            $has_errors=true;
            $errors[]=__('*Please submit an image for your property',THE_LANG); 
        }
        
        
        
        if($property_address==''){
            $has_errors=true;
            $errors[]=__('*Please submit an address for your property',THE_LANG);
        }

        if($has_errors){
            foreach($errors as $key=>$value){
                $show_err.=$value.'</br>';
            }
            
        }else{
            $paid_submission_status = esc_html ( nvr_get_option( $nvr_shortname.'_paid_submission','') );
            $new_status             = 'pending';
            
            $admin_submission_status= esc_html ( nvr_get_option( $nvr_shortname.'_admin_submission','') );
            if($admin_submission_status=='no' && $paid_submission_status!='per listing'){
               $new_status='publish';  
            }
            
            $post = array(
                'post_title'	=> $submit_title,
                'post_content'	=> $submit_description,
                'post_status'	=> $new_status, 
                'post_type'     => 'propertys' ,
                'post_author'   => $current_user->ID 
            );
            $post_id =  wp_insert_post($post );  
          
            if( $paid_submission_status == 'membership'){ // update pack status
                update_listing_no($current_user->ID);                
                if($prop_featured=='true'){
                    update_featured_listing_no($current_user->ID); 
                }
               
            }
           
        }
        
      

        
        
        ////////////////////////////////////////////////////////////////////////
        // Upload images
        ////////////////////////////////////////////////////////////////////////
        if($post_id) {

                
            $attchs=explode(',',$_POST['attachid']);
            $last_id='';
            foreach($attchs as $att_id){
                if( !is_numeric($att_id) ){
                 
                }else{
                    if($last_id==''){
                        $last_id=  $att_id;  
                    }
                    wp_update_post( array(
                                'ID' => $att_id,
                                'post_parent' => $post_id
                            ));
                        
                    
                }
            }
            if( is_numeric($_POST['attachthumb']) && $_POST['attachthumb']!=''  ){
                set_post_thumbnail( $post_id, $_POST['attachthumb'] ); 
            }else{
                set_post_thumbnail( $post_id, $last_id );                
            }
            
            
            if( isset($prop_category->name) ){
                 wp_set_object_terms($post_id,$prop_category->name,'property_category'); 
            }  
            if ( isset ($prop_action_category->name) ){
                 wp_set_object_terms($post_id,$prop_action_category->name,'property_purpose'); 
            }  
            if( isset($property_city) ){
                   wp_set_object_terms($post_id,$property_city,'property_city'); 
            }
      
            update_post_meta($post_id, $nvr_initial.'_address', $property_address);
            update_post_meta($post_id, $nvr_initial.'_county', $property_county);
            update_post_meta($post_id, $nvr_initial.'_state', $property_state);
            update_post_meta($post_id, $nvr_initial.'_zipcode', $property_zip);
            update_post_meta($post_id, $nvr_initial.'_country', $country_selected);
            update_post_meta($post_id, $nvr_initial.'_size', $property_size);
            update_post_meta($post_id, $nvr_initial.'_lot_size', $property_lot_size);  
            update_post_meta($post_id, $nvr_initial.'_rooms', $property_rooms);
            update_post_meta($post_id, $nvr_initial.'_bathrooms', $property_bathrooms);
            update_post_meta($post_id, $nvr_initial.'_status', $prop_stat);
            update_post_meta($post_id, $nvr_initial.'_price', $property_price);
              
            update_post_meta($post_id, $nvr_initial.'price_label', $property_label);

            update_post_meta($post_id, $nvr_initial.'_latitude', $property_latitude);
            update_post_meta($post_id, $nvr_initial.'_longitude', $property_longitude);
            update_post_meta($post_id, $nvr_initial.'_featured', $prop_featured);
            update_post_meta($post_id, 'pay_status', 'not paid');
            
            if('yes' ==  esc_html ( nvr_get_option( $nvr_shortname.'_user_agent','') )){
                $user_id_agent            =   get_the_author_meta( 'user_agent_id' , $current_user->ID  );
                update_post_meta($post_id, $nvr_initial.'_agent', $user_id_agent);                
            }
           
            // save custom fields
            $i=0;
			if($custom_fields){
				for($i=0;$i<count($custom_fields);$i++){
					$nvr_propcustom = $custom_fields[$i]; 
					$nvr_cusslug = nvr_gen_slug($nvr_propcustom);
					$nvr_textpropcustomfields[] = array(
						'name' 	=> $nvr_propcustom,
						'id' 	=> $nvr_initial.'_custom_'.$nvr_cusslug,
						'val' 	=> ''
					);
					$value_custom    =   esc_html(sanitize_text_field( $_POST[$nvr_initial.'_custom_'.$nvr_cusslug] ) );
                    update_post_meta($post_id, $nvr_initial.'_custom_'.$nvr_cusslug, $value_custom);
					$custom_fields_array[$nvr_cusslug]= $value_custom;
					
				}
			}
            
            foreach($feature_list_array as $key => $value){
                $feature_value  =   sanitize_text_field( $_POST[$nvr_initial.'_amenities'][$value] );
                if($feature_value==1){
					$moving_array[] =  $value;
				}
            }
			$nvr_amenities = implode(",",$moving_array);
			update_post_meta($post_id, $nvr_initial.'_amenities', $nvr_amenities);
   
            // get user dashboard link
            $redirect = nvr_dashboard_link('profile');
  
            $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
            $message  = __('Hi there,',THE_LANG) . "\r\n\r\n";
            $message .= sprintf( __("A user has submited a new property on  %s! You should go check it out.This is the property title: %s",THE_LANG), get_option('blogname'),$submit_title) . "\r\n\r\n";
 
            wp_mail(get_option('admin_email'),
		    sprintf(__('[%s] New Listing Submission',THE_LANG), get_option('blogname')),
                    $message,
                    $headers);
            
            wp_reset_query();
            wp_redirect( $redirect);
            exit;
        }
        
        }//end if user can submit  
} // end post




///////////////////////////////////////////////////////////////////////////////////////////
/////// Edit Part Code
///////////////////////////////////////////////////////////////////////////////////////////


if( 'POST' == $_SERVER['REQUEST_METHOD'] && $_POST['action']=='edit' ) {
    
        if ( !isset($_POST['new_property']) || !wp_verify_nonce($_POST['new_property'],'submit_new_property') ){
           exit('Sorry, your not submiting from site');
        } 
        
        $has_errors                     =   false;
        $show_err                       =   '';
        $edited                         =   0;
        $edit_id                        =   intval( $_POST['edit_id'] );
        $post                           =   get_post( $edit_id ); 
        $author_id                      =   $post->post_author ;
        if($current_user->ID !=  $author_id){
            exit('you don\'t have the rights to edit');
        }
        
        $images_todelete                =   sanitize_text_field( $_POST['images_todelete'] );
        $images_delete_arr              =   explode(',',$images_todelete);
        foreach ($images_delete_arr as $key=>$value){
             $img                       =   get_post( $value ); 
             $author_id                 =   $img->post_author ;
             if($current_user->ID !=  $author_id){
                exit('you don\'t have the rights to delete images');
             }else{
                  wp_delete_post( $value );   
             }
                      
        }
        
        if( !isset($_POST['prop_category']) ) {
            $prop_category=0;           
        }else{
            $prop_category  =   intval($_POST['prop_category']);
        }
    
        if( !isset($_POST['prop_action_category']) ) {
            $prop_action_category=0;           
        }else{
            $prop_action_category  =   sanitize_text_field($_POST['prop_action_category']);
        }
        
        if( !isset($_POST['property_city']) ) {
            $property_city=0;           
        }else{
            $property_city  =   sanitize_text_field($_POST['property_city']);
        } 
        
        
        $submit_title                   =   sanitize_text_field( $_POST['title'] ); 
        $submit_description             =   wp_filter_nohtml_kses( $_POST['description']);
        //$prop_category                  =   intval($_POST['prop_category']);//
        $property_address               =   sanitize_text_field( $_POST['property_address']);
        $property_county                =   sanitize_text_field( $_POST['property_county']);
        $property_state                 =   sanitize_text_field( $_POST['property_state']);
        $property_zip                   =   sanitize_text_field( $_POST['property_zip']);
        $country_selected               =   sanitize_text_field( $_POST['property_country']);     
        $prop_stat                      =   sanitize_text_field( $_POST['property_status']);
        $property_status                =   '';
        
        foreach ($status_values_array as $key=>$value) {
            $value = trim($value);
            $value_wpml=$value;
            $slug_status=sanitize_title($value);

            $property_status.='<option value="' . $value . '"';
            if ($value == $prop_stat) {
                $property_status.='selected="selected"';
            }
            $property_status.='>' . $value_wpml . '</option>';
        }

        $property_price                 =   sanitize_text_field( $_POST['property_price']);
		  
        if( isset($_POST['property_label']) ){
            $property_label                 =   sanitize_text_field( $_POST['property_label']);    
        }else{
            $property_label='';
        }
        $property_size                  =   sanitize_text_field( $_POST['property_size']);  
        $property_lot_size              =   sanitize_text_field( $_POST['property_lot_size']);  
        $property_rooms                 =   sanitize_text_field( $_POST['property_rooms']); 
        $property_bathrooms             =   sanitize_text_field( $_POST['property_bathrooms']); 

        $property_latitude              =   sanitize_text_field( $_POST[ $nvr_initial.'_latitude']); 
        $property_longitude             =   sanitize_text_field( $_POST[ $nvr_initial.'_longitude']); 
        
        $prop_featured                  =   sanitize_text_field( $_POST['prop_featured'] );
        if($prop_featured=="true"){
           $prop_featured_check    =' checked="checked" ';
        }else{
            $prop_featured_check   =' ';
        }
		
        $prop_category                  =   get_term( $prop_category, 'property_category');
        $prop_action_category           =   get_term( $prop_action_category, 'property_purpose');     

      
     
        
        if($submit_title==''){
            $has_errors=true;
            $errors[]=__('Please submit a title for your property',THE_LANG);
        }
        
        if($submit_description==''){
            $has_errors=true;
            $errors[]=__('*Please submit a description for your property',THE_LANG);
        }
        
      
         if ($_POST['attachid']==''){
            $has_errors=true;
            $errors[]=__('*Please submit an image for your property',THE_LANG); 
        }
        
        if($property_address==''){
            $has_errors=true;
            $errors[]=__('*Please submit an address for your property',THE_LANG);
        }
         
     
       if($has_errors){
            foreach($errors as $key=>$value){
                $show_err.=$value.'</br>';
            }
            
        }else{
            $new_status='pending';
            $admin_submission_status = esc_html ( nvr_get_option( $nvr_shortname.'_admin_submission','') );
            $paid_submission_status  = esc_html ( nvr_get_option( $nvr_shortname.'_paid_submission','') );
              
            if($admin_submission_status=='no' ){
               $new_status=get_post_status($edit_id);  
            }
            
            $post = array(
                    'ID'            => $edit_id,
                    'post_title'    => $submit_title,
                    'post_content'  => $submit_description,
                    'post_type'     => 'propertys',
                    'post_status'   => $new_status
            );

            $post_id =  wp_update_post($post ); 

            $edited=1;
        }
        
      
        


        if( $edited==1) {
           
            
            $attchs=explode(',',$_POST['attachid']);
            $last_id='';
         
            // check for deleted images
            $arguments = array(
                'numberposts'   => -1,
                'post_type'     => 'attachment',
                'post_parent'   => $edit_id,
                'post_status'   => null,
                'orderby'       => 'menu_order',
                'order'         => 'ASC'
            );
            $post_attachments = get_posts($arguments);
            $new_thumb=0;
            $curent_thumb = get_post_thumbnail_id($edit_id);
            foreach ($post_attachments as $attachment){
                if ( !in_array ($attachment->ID,$attchs) ){
                    wp_delete_post($attachment->ID);
                    if( $curent_thumb == $attachment->ID ){
                        $new_thumb=1;
                    }
                }
            }
            
            // check for deleted images
                   

             
            foreach($attchs as $att_id){
                if( !is_numeric($att_id) ){
                 
                }else{
                    if($last_id==''){
                        $last_id=  $att_id;  
                    }
                    wp_update_post( array(
                                'ID' => $att_id,
                                'post_parent' => $post_id
                            ));
                        
                    
                }
            }
           
            
            if( is_numeric($_POST['attachthumb']) && $_POST['attachthumb']!=''  ){
                set_post_thumbnail( $post_id, $_POST['attachthumb'] ); 
            } 
            
            
           
            
            if($new_thumb==1 || !has_post_thumbnail($post_id) || $_POST['attachthumb']==''){
                set_post_thumbnail( $post_id, $last_id ); 
            }
           
            if( isset($prop_category->name) ){
                 wp_set_object_terms($post_id,$prop_category->name,'property_category'); 
            }  
            if ( isset ($prop_action_category->name) ){
                 wp_set_object_terms($post_id,$prop_action_category->name,'property_purpose'); 
            }  
            if( isset($property_city) ){
                 wp_set_object_terms($post_id,$property_city,'property_city'); 
            }  

            update_post_meta($post_id, $nvr_initial.'_address', $property_address);
            update_post_meta($post_id, $nvr_initial.'_county', $property_county);
            update_post_meta($post_id, $nvr_initial.'_state', $property_state);
            update_post_meta($post_id, $nvr_initial.'_zipcode', $property_zip);
            update_post_meta($post_id, $nvr_initial.'_country', $country_selected);
            update_post_meta($post_id, $nvr_initial.'_size', $property_size);
            update_post_meta($post_id, $nvr_initial.'_lot_size', $property_lot_size);  
            update_post_meta($post_id, $nvr_initial.'_rooms', $property_rooms);  
            update_post_meta($post_id, $nvr_initial.'_bathrooms', $property_bathrooms);
            update_post_meta($post_id, $nvr_initial.'_status', $prop_stat);
            update_post_meta($post_id, $nvr_initial.'_price', $property_price);
            update_post_meta($post_id, $nvr_initial.'_price_label', $property_label);           
            update_post_meta($post_id, $nvr_initial.'_latitude', $property_latitude);
            update_post_meta($post_id, $nvr_initial.'_longitude', $property_longitude);
            update_post_meta($post_id, $nvr_initial.'_featured', $prop_featured);
         
            foreach($feature_list_array as $key => $value){
                $feature_value  =   sanitize_text_field( $_POST[$nvr_initial.'_amenities'][$value] );
                if($feature_value==1){
					$moving_array[] =  $value;
				}
            }
			
			$moving_array=array();

			if(isset($_POST[$nvr_initial.'_amenities']) && is_array($_POST[$nvr_initial.'_amenities']) && count($_POST[$nvr_initial.'_amenities'])>0){
				$moving_array = array_values($_POST[$nvr_initial.'_amenities']);
				$valuestring = implode(",",$moving_array);
			}
			
			$nvr_amenities = implode(",",$moving_array);
			update_post_meta($post_id, $nvr_initial.'_amenities', $nvr_amenities);
        
    
            // save custom fields
            $i=0;
			if($custom_fields){
				for($i=0;$i<count($custom_fields);$i++){
					$nvr_propcustom = $custom_fields[$i]; 
					$nvr_cusslug = nvr_gen_slug($nvr_propcustom);
					$nvr_textpropcustomfields[] = array(
						'name' 	=> $nvr_propcustom,
						'id' 	=> $nvr_initial.'_custom_'.$nvr_cusslug,
						'val' 	=> ''
					);
					$value_custom    =   esc_html(sanitize_text_field( $_POST[$nvr_initial.'_custom_'.$nvr_cusslug] ) );
                    update_post_meta($post_id, $nvr_initial.'_custom_'.$nvr_cusslug, $value_custom);
					$custom_fields_array[$nvr_cusslug]= $value_custom;
					
				}
			}
        
            // get user dashboard link
            $redirect = nvr_dashboard_link('list');
            wp_reset_query();
            $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
            $message  = __('Hi there,',THE_LANG) . "\r\n\r\n";
            $message .= sprintf( __("A user has edited one of his listings! You should go check it out.",THE_LANG), get_option('blogname')) . "\r\n\r\n";
            $message .= __('The property name is : ',THE_LANG).$submit_title;
            @wp_mail(get_option('admin_email'),
		    sprintf(__('[%s] Listing Edited',THE_LANG), get_option('blogname')),
                    $message,
                    $headers);
            
            
           wp_redirect( $redirect);
           exit;
        }// end if edited
    
}




get_header();


///////////////////////////////////////////////////////////////////////////////////////////
/////// Html Form Code below
///////////////////////////////////////////////////////////////////////////////////////////
?> 	
	<!-- begin content--> 
    <div id="post" class="noborder twelve columns alpha noshadow"> 
        <div class="inside_post inside_no_border submit_area">
            <div class="twelve columns">
                <div class="dashboard-menu">
                    <a href="<?php echo nvr_dashboard_link('profile'); ?>" class="dashboard-menu-link"><i class="fa fa-user"></i> <?php _e('My Profile'); ?></a>
                    <a href="<?php echo nvr_dashboard_link('add'); ?>" class="dashboard-menu-link active"><i class="fa fa-home"></i> <?php _e('Add New Property'); ?></a>
                    <a href="<?php echo nvr_dashboard_link('list'); ?>" class="dashboard-menu-link"><i class="fa fa-bars"></i> <?php _e('Property Lists'); ?></a>
                    <div class="clearfix"></div>
                </div>
            </div>
			<?php 
            
			get_currentuserinfo();
            
            $userID                 =   $current_user->ID;
            $user_login             =   $current_user->user_login;
            $user_pack              =   get_the_author_meta( 'package_id' , $userID );
            $user_registered        =   get_the_author_meta( 'user_registered' , $userID );
            $user_package_activation=   get_the_author_meta( 'package_activation' , $userID );
            $images                 =   '';
            $counter                =   0;
            $unit                   =   esc_html( nvr_get_option( $nvr_shortname.'_measure_unit', '') );
            $attachid               =   '';
            $thumbid                =   '';
            
            if ($action=='edit'){
                $arguments = array(
                      'numberposts' => -1,
                      'post_type' => 'attachment',
                      'post_parent' => $edit_id,
                      'post_status' => null,
                      'exclude' => get_post_thumbnail_id(),
                      'orderby' => 'menu_order',
                      'order' => 'ASC'
                  );
                $post_attachments = get_posts($arguments);
                $post_thumbnail_id = $thumbid = get_post_thumbnail_id( $edit_id );
             
               
                foreach ($post_attachments as $attachment) {
                    $preview =  wp_get_attachment_image_src($attachment->ID, 'thumbnail');    
                    $images .=  '<div class="uploaded_images" data-imageid="'.$attachment->ID.'"><img src="'.$preview[0].'" alt="thumb" /><i class="fa fa-trash-o"></i>';
                    if($post_thumbnail_id == $attachment->ID){
                        $images .='<i class="fa thumber fa-star"></i>';
                    }
                    $images .='</div>';
                    $attachid.= ','.$attachment->ID;
                }
            }
           
            
            $remaining_listings=get_remain_listing_user($userID,$user_pack);

            if($remaining_listings=== -1){
               $remaining_listings=11;
            }
            $paid_submission_status = esc_html ( nvr_get_option( $nvr_shortname.'_paid_submission','') );
            
            if( !isset( $_GET['listing_edit'] ) && $paid_submission_status == 'membership' && $remaining_listings != -1 && $remaining_listings < 1 ) {
                print '<div class="user_profile_div"><h4>'.__('Your current package doesn\'t let you publish more properties! You need to upgrade your membership.',THE_LANG ).'</h4></div>';
            }else{
                
            ?>
            
            
            
            
            
            
            <form id="new_post" name="new_post" method="post" action="" enctype="multipart/form-data" class="row add-estate">
                 
                   <?php
                   
                   if(  $paid_submission_status == 'yes' ){
                     print '<br>'.__('This is a paid submission.The listing will be live after payment is received.',THE_LANG);  
                   }
                    
                   ?>
                    </span>
                   <?php
                   if($show_err){
                       print '<div class="notes_err" >'.$show_err.'</div>';
                   }
                   ?>
                        
                
                
                 <div class="eight columns alpha nomargin">

                        <div class="submit_container row">
                            <h3 class="submit_container_header twelve columns"><?php _e('Property Description & Price',THE_LANG);?></h3>
                            <p class="twelve columns">
                               <label for="title"><?php _e('*Title (mandatory)',THE_LANG); ?> </label>
                               <input type="text" class="textbox" id="title" value="<?php print $submit_title; ?>" size="20" name="title" />
                            </p>
            
                            <p class="twelve columns">
                               <label for="description"><?php _e('*Description (mandatory)',THE_LANG);?></label>
                               <textarea id="description" class="bigtextbox" tabindex="3" name="description" cols="50" rows="6"><?php print $submit_description; ?></textarea>
                            </p>
                            <?php $curency_price = esc_html( nvr_get_option( $nvr_shortname.'_currency_symbol', '') ); ?>
                             <p class="one_half columns">
                               <label for="property_price"> <?php _e('Price in ',THE_LANG);print $curency_price.' '; _e('(only numbers)',THE_LANG); ?>  </label>
                               <input type="text" id="property_price" class="textbox" size="40" name="property_price" value="<?php print $property_price;?>">
                             </p>
                            <p class="one_half columns">
                               <label for="property_label"><?php _e('After Price Label (for example "per month")',THE_LANG);?></label>
                               <input type="text" id="property_label" class="textbox" size="40" name="property_label" value="<?php print $property_label;?>">
                            </p>   
                            <div class="clearfix"></div>
                        </div> 

                        <div class="submit_container row">
                            <h3 class="submit_container_header twelve columns"><?php _e('Listing Images',THE_LANG);?></h3>
                            <!-- 
                            <input id="fileupload" type="file" name="files[]" data-url="<?php // print 'wp-content/themes/wpestate/libs/php-uploads/'?>" multiple>
                            -->
                              <div id="upload-container" class="twelve columns">                 
                                    <div id="aaiu-upload-container">                 
                                     
                                        <div id="aaiu-upload-imagelist">
                                            <ul id="aaiu-ul-list" class="aaiu-upload-list"></ul>
                                        </div>
                                        
                                        <div id="imagelist">
                                        <?php 
                                            if($images!=''){
                                                print $images;
                                            }
                                        ?>  
                                        	
                                        </div>
                                        <div class="clearfix"></div>
                                        <a id="aaiu-uploader" class="aaiu_button button" href="#"><?php _e('*Select Images (mandatory)',THE_LANG);?></a>
                                        <input type="hidden" name="attachid" id="attachid" value="<?php echo $attachid;?>">
                                        <input type="hidden" name="attachthumb" id="attachthumb" value="<?php echo $thumbid;?>">
                                    </div>
                                    <span class="upload_explain"><?php _e('*click to set the featured image',THE_LANG);?></span>
                                </div>
                        </div>  

                        <div class="submit_container row" >
                            <h3 class="submit_container_header twelve columns"><?php _e('Listing Location',THE_LANG);?></h3>
                            <p class="one_half columns">
                                <label for="property_address"><?php _e('*Address (mandatory) ',THE_LANG);?></label>
                                <textarea type="text" id="property_address" class="bigtextbox" size="40" name="property_address" rows="3" cols="42"><?php print $property_address; ?></textarea>
                            </p>
                            
                            <p class="one_half columns">
                                <label for="property_country"><?php _e('Country ',THE_LANG); ?></label>
                                <?php
								$nvr_countries = nvr_country_list();
								$nvr_optionout = '<option value="">'.__('Select Country', THE_LANG).'</option>';
								for($i=0;$i<count($nvr_countries);$i++){
									$nvr_country = $nvr_countries[$i];
									$nvr_selected = ($nvr_country==$country_selected)? 'selected="selected"' : '';
									$nvr_optionout .= '<option value="'.esc_attr( $nvr_country ).'" '.$nvr_selected.'>'.$nvr_country.'</option>';
								}
								?>
                                <select name="property_country" class="textbox" id="property_country">
                                	<?php echo $nvr_optionout; ?>
                                </select>
                            </p>
                            
                            <p class="one_half columns">
                                <label for="property_state"><?php _e('State ',THE_LANG);?></label>
                                <input type="text" id="property_state" size="40" class="textbox" name="property_state" value="<?php print $property_state;?>">
                            </p>
                      
                                <div class="advanced_city_div one_half columns">
                                <label for="property_city"><?php _e('City',THE_LANG);?></label>
                         
                                    <?php 
                                        $args = array(
                                            'hide_empty'    => false  
                                        ); 
            
                                        $select_city='';
                                        $taxonomy = 'property_city';
                                        $tax_terms = get_terms($taxonomy,$args);
            
                                        $selected_option='';
                                        $selected= get_term_by('id', $property_city, $taxonomy);
                                        if($selected!=''){
                                        print 'selected option '.    $selected_option=$selected->name;
                                        } 
            
                                        foreach ($tax_terms as $tax_term) {
                                           $select_city.= '<option value="' . $tax_term->name . '"';
                                            if($property_city==$tax_term->name ){
                                                      $select_city.= ' selected="selected" ';
                                                }
                                           $select_city.=  ' >' . $tax_term->name . '</option>';
                                        }
                                    ?>
                             
                                    <select id="property_city_submit" name="property_city" class="textbox" >
                                         <option value="all"><?php _e('All Cities',THE_LANG); ?></option>
                                        <?php echo $select_city ;?>
                                    </select>
                                </div>
                                
                                 <div class="clearfix"></div>

                            <p class="one_half columns">
                                <label for="property_zip"><?php _e('Zip ',THE_LANG);?></label>
                                <input type="text" id="property_zip" size="40" class="textbox" name="property_zip" value="<?php print $property_zip;?>">
                            </p>
                            
                            <p class="one_half columns">
                                <label for="property_county"><?php _e('County ',THE_LANG);?></label>
                                <input type="text" id="property_county" class="textbox" size="40" name="property_county" value="<?php print $property_county;?>">
                            </p>
                            
                            <div class="clearfix"></div>
                            
                            <div class="twelve columns">
                                <div id="gMapContainer"></div>   
                            </div>  
                            
                            <p class="one_half columns">            
                                 <label for="<?php echo $nvr_initial; ?>_latitude"><?php _e('Latitude (for Google Maps)',THE_LANG); ?></label>
                                 <input type="text" id="<?php echo $nvr_initial; ?>_latitude" class="textbox" size="40" name="<?php echo $nvr_initial; ?>_latitude" value="<?php print $property_latitude; ?>">
                            </p>
                            
                            <p class="one_half columns">    
                                 <label for="<?php echo $nvr_initial; ?>_longitude"><?php _e('Longitude (for Google Maps)',THE_LANG);?></label>
                                 <input type="text" id="<?php echo $nvr_initial; ?>_longitude" class="textbox" size="40" name="<?php echo $nvr_initial; ?>_longitude" value="<?php print $property_longitude;?>">
                            </p>
                            <div class="clearfix"></div>
                        </div>    

                        <div class="submit_container nomargin row">
                            <h3 class="submit_container_header twelve columns"><?php _e('Listing Details',THE_LANG);?></h3>
                            <p class="one_half columns">
                                <label for="property_size"><?php _e('Size in square',THE_LANG);print ' '.$unit;?></label>
                                <input type="text" id="property_size" class="textbox" size="40" name="property_size" value="<?php print $property_size;?>">
                            </p>
            
                            <p class="one_half columns">
                                <label for="property_lot_size"> <?php  _e('Lot Size in square',THE_LANG);print ' '.$unit;?> </label>
                                <input type="text" id="property_lot_size" class="textbox" size="40" name="property_lot_size" value="<?php print $property_lot_size;?>">
                            </p>
            
                        
            
                            <p class="one_half columns">
                                <label for="property_rooms"><?php _e('Rooms',THE_LANG);?></label>
                                <input type="text" id="property_rooms" class="textbox" size="40" name="property_rooms" value="<?php print $property_rooms;?>">
                            </p>
            
                            <p class="one_half columns">
                                <label for="property_bedrooms"><?php _e('Bathrooms',THE_LANG);?></label>
                                <input type="text" id="property_bathrooms" size="40" class="textbox" name="property_bathrooms" value="<?php print $property_bathrooms;?>">
                            </p>

                             <!-- Add custom details -->
                             <?php
                             $i=0;
                             if($custom_fields){
								for($i=0;$i<count($custom_fields);$i++){
									$nvr_propcustom = $custom_fields[$i]; 
									$nvr_cusslug = nvr_gen_slug($nvr_propcustom);
									$nvr_textpropcustomfields[] = array(
										'name' 	=> $nvr_propcustom,
										'id' 	=> $nvr_initial.'_custom_'.$nvr_cusslug,
										'val' 	=> ''
									);

                                   	echo '<p class="one_half columns">';
									echo '<label for="'.$nvr_cusslug.'">'.$nvr_propcustom.'</label>';
                                   	echo '<input type="text" class="textbox" id="'.$nvr_initial.'_custom_'.$nvr_cusslug.'" size="40" name="'.$nvr_initial.'_custom_'.$nvr_cusslug.'" value="'.$custom_fields_array[$nvr_cusslug].'">';
									echo '</p>';
								}
							}
                            ?>                             
                        </div>  
                                
                 </div><!-- end nine columns-->
                 
                 <div class="four columns omega nomargin">
                     
                     
                        <?php
                                      
                        if( $paid_submission_status == 'membership'){
                           print'
                           <h3 class="submit_container_header web">'.__('Membership',THE_LANG).'</h3>
                           <div class="submit_container web">';   
                           get_pack_data_for_user($userID,$user_pack,$user_registered,$user_package_activation);
                           print'</div>'; // end submit container
                        }
                        if( $paid_submission_status == 'per listing'){
                            $price_submission               =   floatval( nvr_get_option( $nvr_shortname.'_price_submission','') );
                            $price_featured_submission      =   floatval( nvr_get_option( $nvr_shortname.'_price_featured_submission','') );
                            $submission_curency_status      =   esc_html( nvr_get_option( $nvr_shortname.'_submission_curency','') );
                           print'
                           <h3 class="submit_container_header">'.__('Paid submission',THE_LANG).'</h3>
                           <div class="submit_container">';
                            print '<p class="full_form-nob">'.__( 'This is a paid submission.',THE_LANG).'</p>';
                            print '<p class="full_form-nob">'.__( 'Price: ',THE_LANG).'<span class="submit-price">'.$price_submission.' '.$submission_curency_status.'</span></p>';
                            print '<p class="full_form-nob">'.__( 'Featured (extra): ',THE_LANG).'<span class="submit-price">'.$price_featured_submission.' '.$submission_curency_status.'</span></p>';
                            print'</div>'; // end submit container
                         }
       

                     if ( ( $paid_submission_status == 'membership' && get_remain_featured_listing_user($userID)>0 ) && $action!='edit'){ ?>  
                    
                        <div class="submit_container_header web"><?php _e('Featured submission',THE_LANG);?></div>
                        <div class="submit_container web">            
                                <p class="meta-options full_form-nob"> 
                                   
                                   <input type="hidden"    name="prop_featured" value="">
                                   <input type="checkbox"  id="prop_featured"  name="prop_featured"  value="true" <?php print $prop_featured_check;?> >                           
                                   <label for="prop_featured" id="prop_featured_label"><?php _e('Make this property featured?',THE_LANG);?></label>
                                </p> 
                        </div>

                     <?php 
					 }elseif( $paid_submission_status == 'no' ){
                     	echo '<input type="hidden"  id="prop_featured"  name="prop_featured"  value="0" > ';
                     }else{
                     	echo '<input type="hidden"  id="prop_featured"  name="prop_featured"  value="'.$prop_featured.'"  '.$prop_featured_check.' > ';
                     } 
					 ?> 
                     
                     <h3 class="submit_container_header"><?php _e('Select Categories',THE_LANG);?></h3>
                     <div class="submit_container"> 
                         <p class="full_form"><label for="prop_category"><?php _e('Category',THE_LANG);?></label>
                        <?php 
                            $args=array(
                                  'class'       => 'textbox select-submit2',
                                  'hide_empty'  => false,
                                  'selected'    => $prop_category_selected,
                                  'name'        => 'prop_category',
                                  'id'          => 'prop_category_submit',
                                  'taxonomy'    => 'property_category');
                            wp_dropdown_categories( $args ); ?>
                        </p>
            
                         <p class="full_form"><label for="prop_action_category"> <?php _e('Listed In ',THE_LANG); $prop_action_category;?></label>
                            <?php 
                             $args=array(
                                  'class'       => 'textbox select-submit2',
                                  'hide_empty'  => false,
                                  'selected'    => $prop_action_category_selected,
                                  'name'        => 'prop_action_category',
                                  'id'          => 'prop_action_category_submit',
                                  'taxonomy'    => 'property_purpose');
            
                               wp_dropdown_categories( $args );  ?>
                        </p>       
                     </div>

                     
                     <h3 class="submit_container_header"><?php _e('Select Property Status',THE_LANG);?></h3>
                     <div class="submit_container">            
                        <p class="full_form">
                            <select id="property_status" name="property_status" class="textbox select-submit">
                                <option value="normal"><?php _e('normal',THE_LANG);?></option>
                                <?php print $property_status; ?>
                            </select>
                        </p>
                     </div>
                     
                     
                     
                     
                     
                     
                     
                     
                     <h3 class="submit_container_header"><?php _e('Amenities and Features',THE_LANG);?></h3>
                     <div class="submit_container ">  
                        <?php
                        foreach($feature_list_array as $key => $value){
                            $value_label=$value;
                            $post_var_name=  str_replace(' ','_', trim($value) );
							$checked = '';
							if(is_array($moving_array) ){                      
								 if( in_array($value,$moving_array) ){
									 $checked = 'checked="checked"';
								 }
							}
							
                            echo '<p class="full_form featurescol">
                                   <input type="checkbox"   id="'.$nvr_initial.'_amenities_'.$post_var_name.'" name="'.$nvr_initial.'_amenities[\''.$value.'\']" value="'.$value.'" '.$checked.' />';
                            echo '<label for="'.$nvr_initial.'_amenities_'.$post_var_name.'">'.$value_label.'</label></p>';  
                        }
                        ?>
                     </div>
                 </div><!-- end three columns-->
                 
            
            
                <input type="hidden" name="action" value="<?php print $action;?>">
                <?php
                if($action=='edit'){ ?>
                       <button type="submit" id="form_submit_1" class="btn vernil small"><?php _e('Save Changes', THE_LANG) ?></button>   
                <?php    
                }else{
                ?>
                       <button type="submit" id="form_submit_1" class="btn vernil small"><?php _e('Add Property', THE_LANG) ?></button>       
                <?php
                }
                ?>
                     
                   
                <input type="hidden" name="edit_id" value="<?php print $edit_id;?>">
                <input type="hidden" name="images_todelete" id="images_todelete" value="">
                <?php wp_nonce_field('submit_new_property','new_property'); ?>
            </form>
            <?php } // end check pack rights ?>
        </div> <!-- end inside post-->
    </div>
    <!-- end content-->
    <div class="clearfix"></div><!-- clear float --> 
    
<?php get_footer(); ?>
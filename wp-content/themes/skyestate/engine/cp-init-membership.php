<?php
// register the custom post type
add_action( 'init', 'create_membership_type' );
function create_membership_type() {
	register_post_type( 'membership_package',
		array(
			'labels' => array(
				'name'          => __( 'Membership Packages',THE_LANG),
				'singular_name' => __( 'Membership Packages',THE_LANG),
				'add_new'       => __('Add New Membership Package',THE_LANG),
                'add_new_item'          =>  __('Add Membership Packages',THE_LANG),
                'edit'                  =>  __('Edit Membership Packages' ,THE_LANG),
                'edit_item'             =>  __('Edit Membership Package',THE_LANG),
                'new_item'              =>  __('New Membership Packages',THE_LANG),
                'view'                  =>  __('View Membership Packages',THE_LANG),
                'view_item'             =>  __('View Membership Packages',THE_LANG),
                'search_items'          =>  __('Search Membership Packages',THE_LANG),
                'not_found'             =>  __('No Membership Packages found',THE_LANG),
                'not_found_in_trash'    =>  __('No Membership Packages found',THE_LANG),
                'parent'                =>  __('Parent Membership Package',THE_LANG)
			),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'package'),
		'supports' => array('title'),
		'can_export' => true
		)
	);
}

////////////////////////////////////////////////////////////////////////////////
/// Get a list of all visible packages
////////////////////////////////////////////////////////////////////////////////
function get_all_packs(){
    $args = array(
                 'post_type' => 'membership_package',
                 'meta_query' => array(
                    array(
			'key' => 'pack_visible',
			'value' => 'true',
			'compare' => '='
                    )
                     
                 )
                  
         );
        $pack_selection = new WP_Query($args);
    
        while ($pack_selection->have_posts()): $pack_selection->the_post();
            $return_string.='<option value="'.$post->ID.'">'.get_the_title().'</option>';
        endwhile;
        wp_reset_query();
        return $return_string;
}



////////////////////////////////////////////////////////////////////////////////
/// Get a package details from user top profile
////////////////////////////////////////////////////////////////////////////////
function get_pack_data_for_user_top($userID,$user_pack,$user_registered,$user_package_activation){
	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
	echo '<div class="pack_description">';
		echo '<div class="row">';
		if ($user_pack!=''){
			$title              = get_the_title($user_pack);
			$pack_time          = get_post_meta($user_pack, 'pack_time', true);
			$pack_list          = get_post_meta($user_pack, 'pack_listings', true);
			$pack_featured      = get_post_meta($user_pack, 'pack_featured_listings', true);
			$pack_price         = get_post_meta($user_pack, 'pack_price', true);
			$unlimited_lists    = ($pack_list<0)? 1 : 0;
			$date               = strtotime ( get_user_meta($userID, 'package_activation',true) );
			$biling_period      = get_post_meta($user_pack, 'biling_period', true);
			$billing_freq       = get_post_meta($user_pack, 'billing_freq', true);  
		
			
			$seconds=0;
			switch ($biling_period){
			   case 'day':
				   $seconds=60*60*24;
				   break;
			   case 'week':
				   $seconds=60*60*24*7;
				   break;
			   case 'month':
				   $seconds=60*60*24*30;
				   break;    
			   case 'year':
				   $seconds=60*60*24*365;
				   break;    
		   }
		   
		   $time_frame      =   $seconds*$billing_freq;
		   $expired_date    =   $date+$time_frame;
		   $expired_date    =   date('Y-m-d',$expired_date); 
	
			echo '<div class="pack_description_unit two columns"><span class="pack_desc_title">'.__('Your Package',THE_LANG).'</span>'.$title.' </div> ';
			
			if($unlimited_lists==1){
				echo '<div class="pack_description_unit two columns"><span class="pack_desc_title">'.__('Listings Included',THE_LANG).'</span>';
				_e('  unlimited listings',THE_LANG);
				echo '</div>';
				
				echo '<div class="pack_description_unit two columns"><span class="pack_desc_title">'.__('Listings remaining',THE_LANG).'</span>';
				_e('  unlimited listings',THE_LANG);
				echo '</div>';
			}else{
				echo '<div class="pack_description_unit two columns"><span class="pack_desc_title">'.__('Listings Included',THE_LANG).'</span>';
				echo $pack_list;
				echo '</div>';
				
				echo '<div class="pack_description_unit two columns"><span class="pack_desc_title">'.__('Listings remaining',THE_LANG).'</span>';
				echo '<span id="normal_list_no">'.get_remain_listing_user($userID,$user_pack).'</span>';
				echo '</div>';
			}
			
			echo '<div class="pack_description_unit two columns"><span class="pack_desc_title">'.__('Featured Included',THE_LANG).'</span>';
			echo '<span id="normal_list_no">'.$pack_featured.'</span>';
			echo '</div>';
			
			echo '<div class="pack_description_unit two columns"><span class="pack_desc_title">'.__('Featured Remaining',THE_LANG).'</span>';
			echo '<span id="featured_list_no">'.get_remain_featured_listing_user($userID).'</span>';
			echo '</div>';
			
			echo '<div class="pack_description_unit two columns"><span class="pack_desc_title">'.__('Ends On',THE_LANG).'</span>';
			echo $expired_date;
			echo '</div>';
			
		 
		}else{
	
			$free_mem_list      =   esc_html( nvr_get_option( $nvr_shortname.'_free_mem_list','') );
			$free_feat_list     =   esc_html( nvr_get_option( $nvr_shortname.'_free_feat_list','') );
			$free_mem_list_unl  =   ($free_mem_list)<0? 1 : 0;
			
			echo '<div class="pack_description_unit two columns"><span class="pack_desc_title">'.__('Your Package',THE_LANG).'</span>'.__('Free Membership',THE_LANG).'</div>';
			
			echo '<div class="pack_description_unit two columns"><span class="pack_desc_title">'.__('Listings Included',THE_LANG).'</span>';
			if($free_mem_list_unl==1){
				_e('  unlimited listings',THE_LANG);
			}else{
				echo $free_mem_list;
			}
			echo '</div>';
			 
			echo '<div class="pack_description_unit two columns"><span class="pack_desc_title">'.__('Listings remaining',THE_LANG).'</span>';
			 if($free_mem_list_unl==1){
				  echo '<span id="normal_list_no">'.__('  unlimited listings',THE_LANG).'</span>';
			 }
			 else{                    
				  echo '<span id="normal_list_no">'.get_remain_listing_user($userID,$user_pack).'</span>';
			 }
		   
			echo '</div>';
		 
			echo '<div class="pack_description_unit two columns"><span class="pack_desc_title">'.__('Featured Included',THE_LANG).'</span>';
			echo '<span id="normal_list_no">'.$free_feat_list.'</span>';
			echo '</div>';
			
			echo '<div class="pack_description_unit two columns"><span class="pack_desc_title">'.__('Featured Remaining',THE_LANG).'</span>';
			echo '<span id="featured_list_no">'.get_remain_featured_listing_user($userID).'</span>';
			echo '</div>';
			
			echo '<div class="pack_description_unit two columns"><span class="pack_desc_title">'.__('Ends On',THE_LANG).'</span>';
			echo '-';
			echo '</div>';
			
		}
		echo '</div>';
	echo '</div>';
}



////////////////////////////////////////////////////////////////////////////////
/// Get a package details from user
////////////////////////////////////////////////////////////////////////////////
function get_pack_data_for_user($userID,$user_pack,$user_registered,$user_package_activation){
			
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
	if ($user_pack!=''){
		$title              = get_the_title($user_pack);
		$pack_time          = get_post_meta($user_pack, 'pack_time', true);
		$pack_list          = get_post_meta($user_pack, 'pack_listings', true);
		$pack_featured      = get_post_meta($user_pack, 'pack_featured_listings', true);
		$pack_price         = get_post_meta($user_pack, 'pack_price', true);

		$unlimited_lists    = ($pack_list<0)? 1 : 0;

		echo '<strong>'.__('Your Package: ',THE_LANG).$title.'</strong></br> ';
		echo '<p class="full_form-nob">';
		if($unlimited_lists==1){
			_e('  Unlimited listings',THE_LANG);
		}else{
			echo $pack_list.__(' Listings',THE_LANG);
			echo ' - '.get_remain_listing_user($userID,$user_pack).__(' remaining ',THE_LANG).'</p>';
		}

		echo ' <p class="full_form-nob"> <span id="normal_list_no">'.$pack_featured.__(' Featured listings',THE_LANG).'</span>';
		echo ' - <span id="featured_list_no">'.get_remain_featured_listing_user($userID).'</span>'.__(' remaining',THE_LANG).' </p>';


	}else{

		$free_mem_list      =   esc_html( nvr_get_option( $nvr_shortname.'_free_mem_list','') );
		$free_feat_list     =   esc_html( nvr_get_option( $nvr_shortname.'_free_feat_list','') );
		$free_mem_list_unl  =   ($free_mem_list<0)? 1 : 0;
		echo '<strong>'.__('Your Package: Free Membership  ',THE_LANG).'</strong></br>';
		echo '<p class="full_form-nob">';
		if($free_mem_list_unl==1){
			 _e('unlimited listings',THE_LANG);
		}else{
			 echo $free_mem_list.__(' Listings',THE_LANG);
			 echo ' - <span id="normal_list_no">'.get_remain_listing_user($userID,$user_pack).'</span>'.__(' remaining',THE_LANG).'</p>';

		}
		echo '<p class="full_form-nob">';
		echo $free_feat_list.__(' Featured listings',THE_LANG);
		echo ' - <span id="featured_list_no">'.get_remain_featured_listing_user($userID).'</span>'.__('  remaining',THE_LANG).' </p>';
	}
    
}



function get_remain_days_user($userID,$user_pack,$user_registered,$user_package_activation){   
   	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
    
	if ($user_pack!=''){
        $pack_time  = get_post_meta($user_pack, 'pack_time', true);
        $now        = time();
    
        $user_date  = strtotime($user_package_activation);
        $datediff   = $now - $user_date;
        if( floor($datediff/(60*60*24)) > $pack_time){
            return 0;
        }else{
            return floor($pack_time-$datediff/(60*60*24));
        }
        
        
    }else{
        $free_mem_days      = esc_html( nvr_get_option( $nvr_shortname.'_free_mem_days','') );
        $free_mem_days_unl  = ($free_mem_days<0)? 1 : 0;
        if($free_mem_days_unl==1){
            return;
        }else{
             $now = time();
             $user_date = strtotime($user_registered);
             $datediff = $now - $user_date;
             if(  floor($datediff/(60*60*24)) >$free_mem_days){
                 return 0;
             }else{
                return floor($free_mem_days-$datediff/(60*60*24));
             }
        }   
    }
}


function get_remain_listing_user($userID,$user_pack){
		
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
      if ( $user_pack !='' ){
        $current_listings   = get_current_user_listings($userID);
        $pack_listings      = get_post_meta($user_pack, 'pack_listings', true);
       
         return $current_listings;
      }else{
        $free_mem_list      = esc_html( nvr_get_option( $nvr_shortname.'_free_mem_list','') );
        $free_mem_list_unl  = ($free_mem_list<0)? 1 : 0;
        if($free_mem_list_unl==1){
              return -1;
        }else{
            $current_listings=get_current_user_listings($userID);
            return $current_listings;
        }
      }
}


///////////////////////////////////////////////////////////////////////////////////////////
// return no of featuerd listings available for current pack
///////////////////////////////////////////////////////////////////////////////////////////

function get_remain_featured_listing_user($userID){
    $count  =   get_the_author_meta( 'package_featured_listings' , $userID );
    return $count;
}


///////////////////////////////////////////////////////////////////////////////////////////
// return no of listings available for current pack
///////////////////////////////////////////////////////////////////////////////////////////
function get_current_user_listings($userID){
    $count  =   get_the_author_meta( 'package_listings' , $userID );
    return $count;
}

///////////////////////////////////////////////////////////////////////////////////////////
// update listing no
///////////////////////////////////////////////////////////////////////////////////////////
function update_listing_no($userID){
    $current  =   get_the_author_meta( 'package_listings' , $userID );
    if($current==''){
        //do nothing
    }else if($current==-1){ // if unlimited
        //do noting
    }else if($current-1>=0){
        update_user_meta( $userID, 'package_listings', $current-1) ;
    }else if( $current==0 ){
         update_user_meta( $userID, 'package_listings', 0) ;
    }
}

///////////////////////////////////////////////////////////////////////////////////////////
// update featured listing no
///////////////////////////////////////////////////////////////////////////////////////////
function update_featured_listing_no($userID){
    $current  =   get_the_author_meta( 'package_featured_listings' , $userID );
    
    if($current-1>=0){
        update_user_meta( $userID, 'package_featured_listings', $current-1) ;
    }else{
          update_user_meta( $userID, 'package_featured_listings', 0) ;
    }
}

///////////////////////////////////////////////////////////////////////////////////////////
// update old users that don;t have membership details
///////////////////////////////////////////////////////////////////////////////////////////
function update_old_users($userID){
	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
    $paid_submission_status    = esc_html ( nvr_get_option( $nvr_shortname.'_paid_submission','') );
    if($paid_submission_status == 'membership' ){

        $curent_list   =   get_user_meta( $userID, 'package_listings', true) ;
        $cur_feat_list =   get_user_meta( $userID, 'package_featured_listings', true) ;
        
            if($curent_list=='' || $cur_feat_list=='' ){
                 $package_listings           = esc_html( nvr_get_option( $nvr_shortname.'_free_mem_list','') );
                 $featured_package_listings  = esc_html( nvr_get_option( $nvr_shortname.'_free_feat_list','') );
                   if($package_listings==''){
                       $package_listings=0;
                   }
                   if($featured_package_listings==''){
                      $featured_package_listings=0;
                   }

                 update_user_meta( $userID, 'package_listings', $package_listings) ;
                 update_user_meta( $userID, 'package_featured_listings', $featured_package_listings) ;

               $time = time(); 
               $date = date('Y-m-d H:i:s',$time);
               update_user_meta( $userID, 'package_activation', $date);
            }
     
    }// end if memebeship
    
}

///////////////////////////////////////////////////////////////////////////////////////////
// update user profile on register 
///////////////////////////////////////////////////////////////////////////////////////////



function nvr_update_profile($userID){
    $nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
	if(1==1){ // if membership is on
        
		$package_listings = esc_html( nvr_get_option( $nvr_shortname.'_free_mem_list','') );
		$unlimited_listings = ($package_listings<0)? 1 : 0;
		
        if( $unlimited_listings==1 ){
            $package_listings =-1;
            $featured_package_listings  = esc_html( nvr_get_option( $nvr_shortname.'_free_feat_list','') );
        }else{
            $package_listings           = esc_html( nvr_get_option( $nvr_shortname.'_free_mem_list','') );
            $featured_package_listings  = esc_html( nvr_get_option( $nvr_shortname.'_free_feat_list','') );
            
            if($package_listings==''){
                $package_listings=0;
            }
            if($featured_package_listings==''){
                $featured_package_listings=0;
            }
        }
        update_user_meta( $userID, 'package_listings', $package_listings) ;
        update_user_meta( $userID, 'package_featured_listings', $featured_package_listings) ;
        $time = time(); 
        $date = date('Y-m-d H:i:s',$time);
        update_user_meta( $userID, 'package_activation', $date);
        //package_id no id since the pack is free
   
    }
     
}

///////////////////////////////////////////////////////////////////////////////////////////
// update user profile on register 
///////////////////////////////////////////////////////////////////////////////////////////
function nvr_display_packages(){
    global $post;
    $args = array(
                 'post_type' => 'membership_package',
                  'meta_query' => array(
                                     array(
                                         'key' => 'pack_visible',
                                         'value' => 'true',
                                         'compare' => '=',
                                     )
                                  )
    );
    $pack_selection = new WP_Query($args);

    $return='<select name="pack_select" id="pack_select" class="select-submit2"><option value="">'.__('Select package',THE_LANG).'</option>';
    while($pack_selection->have_posts() ){
        
        $pack_selection->the_post();
        $title=get_the_title();
        $return.='<option value="'.$post->ID.'" data-price="'.get_post_meta(get_the_id(),'pack_price',true).'" data-pick="'.sanitize_title($title).'">'.$title.'</option>';
    }
    $return.='</select>';
    
    echo $return;
    
}



////////////////////////////////////////////////////////////////////////////////
/// Ajax  Package Paypal function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_ajax_paypal_pack_generation', 'ajax_paypal_pack_generation' );  
add_action( 'wp_ajax_ajax_paypal_pack_generation', 'ajax_paypal_pack_generation' );  
   
function ajax_paypal_pack_generation(){
    
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
    $packName=$_POST['packName'];
    $pack_id=$_POST['packId'];
    if(!is_numeric($pack_id)){
        exit();
    }
    
    
    $is_pack = get_posts('post_type=membership_package&p='.$pack_id);
    
    
    if( !empty ( $is_pack ) ) {
            global $current_user;
            get_currentuserinfo(); 
            $pack_price                     =   get_post_meta($pack_id, 'pack_price', true);
			$pack_price						=	number_format($pack_price,2);
            $submission_curency_status      =   esc_html( nvr_get_option( $nvr_shortname.'_submission_currency','') );
            $paypal_status                  =   esc_html( nvr_get_option( $nvr_shortname.'_paypal_api','') );
          
            $host                           =   'https://api.sandbox.paypal.com';
            if($paypal_status=='live'){
                $host   =   'https://api.paypal.com';
            }
            
            $url = $host.'/v1/oauth2/token'; 
            $postArgs = 'grant_type=client_credentials';
            $token = nvr_access_token($url,$postArgs);
            $url = $host.'/v1/payments/payment';
            

           $dash_profile_link = nvr_dashboard_link('profile');


            $payment = array(
				'intent' => 'sale',
				"redirect_urls"=>array(
					"return_url"=>$dash_profile_link,
					"cancel_url"=>$dash_profile_link
					),
				'payer' => array("payment_method"=>"paypal"),

       		);

            
			$payment['transactions'][0] = array(
				'amount' => array(
					'total' => $pack_price,
					'currency' => $submission_curency_status,
					'details' => array(
						'subtotal' => $pack_price,
						'tax' => '0',
						'shipping' => '0'
						)
					),
				'description' => $packName.' '.__('membership payment on ',THE_LANG).get_bloginfo('url')
			);

			$payment['transactions'][0]['item_list']['items'][] = array(
				'quantity' => '1',
				'name' => __('Membership Payment',THE_LANG),
				'price' => $pack_price,
				'currency' => $submission_curency_status,
				'sku' => $packName.' '.__('Membership Payment',THE_LANG),
			);
                   
                    
			$json = json_encode($payment); 
			$json_resp = nvr_post_call($url, $json,$token);
			foreach ($json_resp['links'] as $link) {
				if($link['rel'] == 'execute'){
					$payment_execute_url = $link['href'];
					$payment_execute_method = $link['method'];
				} elseif($link['rel'] == 'approval_url'){
					$payment_approval_url = $link['href'];
					$payment_approval_method = $link['method'];
				}
			} 
			
			$executor['paypal_execute']     =   $payment_execute_url;
			$executor['paypal_token']       =   $token;
			$executor['pack_id']            =   $pack_id;
			$save_data[$current_user->ID ]  =   $executor;
			update_option('paypal_pack_transfer',$save_data);
			echo $payment_approval_url;
       }
       die();
}
////////////////////////////////////////////////////////////////////////////////
/// Ajax  Package Paypal function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_ajax_paypal_pack_recuring_generation', 'ajax_paypal_pack_recuring_generation' );  
add_action( 'wp_ajax_ajax_paypal_pack_recuring_generation', 'ajax_paypal_pack_recuring_generation' );  
   
function ajax_paypal_pack_recuring_generation(){
   
   $nvr_shortname = THE_SHORTNAME;
   $nvr_initial = THE_INITIAL;
   
    $packName=$_POST['packName'];
    $pack_id=$_POST['packId'];
    if(!is_numeric($pack_id)){
        exit();
    }
    
    $is_pack = get_posts('post_type=membership_package&p='.$pack_id);
    if( !empty ( $is_pack ) ) {
        require( THE_TEMPLATEPATH.'engine/plugins/paypal/paypalfunctions.php' );
        global $current_user;

        get_currentuserinfo(); 
        $pack_price                     =   get_post_meta($pack_id, 'pack_price', true);
		$pack_price 					=	number_format($pack_price,2);
        $billing_period                 =   get_post_meta($pack_id, 'billing_period', true);
        $billing_freq                   =   intval(get_post_meta($pack_id, 'billing_freq', true));
        $pack_name                      =   get_the_title($pack_id);
        $submission_curency_status      =   esc_html( nvr_get_option( $nvr_shortname.'_submission_currency','') );
        $paypal_status                  =   esc_html( nvr_get_option( $nvr_shortname.'_paypal_api','') );
        $paymentType                    =   "Sale";
        
       	$dash_profile_link = nvr_dashboard_link('profile');
     
        $obj=new paypal_recurring;
        $obj->environment               =   esc_html( nvr_get_option( $nvr_shortname.'_paypal_api','') );
        $obj->paymentType               =   urlencode('Sale');
        $obj->productdesc               =   urlencode($pack_name.__(' package on ',THE_LANG).get_bloginfo('name') );
        $time                           =   time(); 
        $date                           =   date('Y-m-d H:i:s',$time); 
        $obj->startDate                 =   urlencode($date);
        $obj->billingPeriod             =   urlencode($billing_period);         
        $obj->billingFreq               =   urlencode($billing_freq);                
        $obj->paymentAmount             =   urlencode($pack_price);
        $obj->currencyID                =   urlencode($submission_curency_status);  
        $paypal_api_username            =   ( nvr_get_option( $nvr_shortname.'_paypal_api_username','') );
        $paypal_api_password            =   ( nvr_get_option( $nvr_shortname.'_paypal_api_password','') );
        $paypal_api_signature           =   ( nvr_get_option( $nvr_shortname.'_paypal_api_signature','') );    
        $obj->API_UserName              =   urlencode( $paypal_api_username );
        $obj->API_Password              =   urlencode( $paypal_api_password );
        $obj->API_Signature             =   urlencode( $paypal_api_signature );
        $obj->API_Endpoint              =   "https://api-3t.paypal.com/nvp";
        $obj->returnURL                 =   urlencode($dash_profile_link);
        $obj->cancelURL                 =   urlencode($dash_profile_link);
        $executor['paypal_execute']     =   '';
        $executor['paypal_token']       =   '';
        $executor['pack_id']            =   $pack_id;
        $executor['recursive']          =   1;
        $executor['date']               =   $date;
        $save_data[$current_user->ID ]  =   $executor;
        update_option('paypal_pack_transfer',$save_data);
         
        $obj->setExpressCheckout();
		
    }
}
/////////////////////////////////////////////////////////////////////////////////////
/// downgrade to pack
/////////////////////////////////////////////////////////////////////////////////////
function downgrade_to_pack( $user_id, $pack_id ){
    
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
    $future_listings                  =   get_post_meta($pack_id, 'pack_listings', true);
    $future_featured_listings         =   get_post_meta($pack_id, 'pack_featured_listings', true);
    update_user_meta( $user_id, 'package_listings', $future_listings) ;
    update_user_meta( $user_id, 'package_featured_listings', $future_featured_listings);
    
    $args = array(
               'post_type' => 'propertys',
               'author'    => $user_id,
               'post_status'   => 'any' 
        ); 
    
    $query = new WP_Query( $args ); 
    global $post;
    while( $query->have_posts()){
		$query->the_post();
	
		$prop = array(
				'ID'            => $post->ID,
				'post_type'     => 'propertys',
				'post_status'   => 'expired'
		);
	   
		wp_update_post($prop ); 
		update_post_meta($post->ID, $nvr_initial.'_featured', 'false');
    }
    wp_reset_query();
    
    $user = get_user_by('id',$user_id); 
    $user_email=$user->user_email;
            
    $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
    $message  = __('Account Downgraded,',THE_LANG) . "\r\n\r\n";
    $message .= sprintf( __("Hello, You downgraded your subscription on  %s. Because your listings number was greater than what the actual package offers, we set the status of all your listings to \"expired\". You will need to choose which listings you want live and send them again for approval. Thank you!",THE_LANG), get_option('blogname')) . "\r\n\r\n";

    wp_mail($user_email,
            sprintf(__('[%s] Account Downgraded',THE_LANG), get_option('blogname')),
            $message,
            $headers);
    
    
    
}


/////////////////////////////////////////////////////////////////////////////////////
/// downgrade to free
/////////////////////////////////////////////////////////////////////////////////////

function downgrade_to_free($user_id){
    global $post;
	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
    $free_pack_listings        = esc_html( nvr_get_option( $nvr_shortname.'_free_mem_list','') );
    $free_pack_feat_listings   = esc_html( nvr_get_option( $nvr_shortname.'_free_feat_list','') );

    update_user_meta( $user_id, 'package_id', '') ;
    update_user_meta( $user_id, 'package_listings', $free_pack_listings) ;
    update_user_meta( $user_id, 'package_featured_listings', $free_pack_feat_listings); 
    
     $args = array(
               'post_type' => 'propertys',
               'author'    => $user_id,
               'post_status'   => 'any' 
        );
    
    $query = new WP_Query( $args );    
    while( $query->have_posts()){
            $query->the_post();
        
            $prop = array(
                    'ID'            => $post->ID,
                    'post_type'     => 'propertys',
                    'post_status'   => 'expired'
            );
           
            wp_update_post($prop ); 
            update_post_meta($post->ID, $nvr_initial.'_featured', 'false');
      }
    wp_reset_query();
    
    $user = get_user_by('id',$user_id); 
    $user_email=$user->user_email;
            
    $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
    $message  = __('Membership Cancelled,',THE_LANG) . "\r\n\r\n";
    $message .= sprintf( __("Your subscription on %s was cancelled because it expired or the recurring payment from Paypal was not processed. All your listings are no longer visible for our visitors but remain in your account. Thank you. ",THE_LANG), get_option('blogname')) . "\r\n\r\n";

    wp_mail($user_email,
            sprintf(__('[%s] Membership Cancelled',THE_LANG), get_option('blogname')),
            $message,
            $headers);
    
 }
 
 

   
   
   
/////////////////////////////////////////////////////////////////////////////////////
/// upgrade user
/////////////////////////////////////////////////////////////////////////////////////
function upgrade_user_membership($user_id,$pack_id,$type,$paypal_tax_id){
  
    $available_listings                  =   get_post_meta($pack_id, 'pack_listings', true);
    $featured_available_listings         =   get_post_meta($pack_id, 'pack_featured_listings', true);
    $pack_unlimited_list                 =   ($available_listings<0)? 1 : 0;
    
    
    $current_used_listings               =   get_user_meta($user_id, 'package_listings',true);
    $curent_used_featured_listings       =   get_user_meta($user_id, 'package_featured_listings',true);  
    $current_pack=get_user_meta($user_id, 'package_id',true);
   
    
    $user_curent_listings               =   get_user_curent_listings_no_exp ( $user_id );
    $user_curent_future_listings        =   get_user_curent_future_listings_no_exp( $user_id );
    

    if( check_downgrade_situation($user_id,$pack_id) ){
        $new_listings           =   $available_listings;
        $new_featured_listings  =   $featured_available_listings;
    }else{
        $new_listings            =  $available_listings - $user_curent_listings ;
        $new_featured_listings   =  $featured_available_listings-  $user_curent_future_listings ;       
    }         
    
    
    // in case of downgrade
    if($new_listings<0){
        $new_listings=0;
    }
    
    if($new_featured_listings<0){
        $new_featured_listings=0;
    }


    if ($pack_unlimited_list==1){
        $new_listings=-1;
    }
        
   
    update_user_meta( $user_id, 'package_listings', $new_listings) ;
    update_user_meta( $user_id, 'package_featured_listings', $new_featured_listings);  
        
        
    $time = time(); 
    $date = date('Y-m-d H:i:s',$time); 
    update_user_meta( $user_id, 'package_activation', $date);
    update_user_meta( $user_id, 'package_id', $pack_id);
        
    
    $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
    $message  = __('Hi there,',THE_LANG) . "\r\n\r\n";
    $message .= sprintf( __("Your new membership on  %s is activated! You should go check it out.",THE_LANG), get_option('blogname')) . "\r\n\r\n";

    $user = get_user_by('id',$user_id); 
    $user_email=$user->user_email;
    wp_mail($user_email,
            sprintf(__('[%s] Membership Activated',THE_LANG), get_option('blogname')),
            $message,
            $headers);
    
    $billing_for='Package';
    insert_invoice($billing_for,$type,$pack_id,$date,$user_id,0,0,$paypal_tax_id);
}



/////////////////////////////////////////////////////////////////////////////////////
/// check for downgrade
/////////////////////////////////////////////////////////////////////////////////////


function  check_downgrade_situation($user_id,$new_pack_id){
    
    $future_listings                  =   get_post_meta($new_pack_id, 'pack_listings', true);
    $future_featured_listings         =   get_post_meta($new_pack_id, 'pack_featured_listings', true);
    $unlimited_future                 =   ($future_listings<0)? 1 : 0;
    $curent_list                      =   get_user_meta( $user_id, 'package_listings', true) ;
   
    if($unlimited_future==1){
        return false;
    }
  
    if ($curent_list == -1 && $unlimited_future!=1 ){ // if is unlimited and go to non unlimited pack     
        return true;
    }
    
    if ( (get_user_curent_listings($user_id) > $future_listings ) || ( get_user_curent_future_listings($user_id) > $future_featured_listings ) ){
        return true;
    }else{
        return false;
    }
    
    
    
}



/////////////////////////////////////////////////////////////////////////////////////
/// get the number of listings
/////////////////////////////////////////////////////////////////////////////////////
function get_user_curent_listings($userid) {
    $args = array(
        'post_type'     => 'propertys',
        'post_status'   => 'any',  
        'author'        =>$userid,
        
    );
    $posts = new WP_Query( $args );
    return $posts->found_posts;
    wp_reset_query();
    

}

function get_user_curent_listings_no_exp($userid) {
    $args = array(
        'post_type'     => 'propertys',
        'post_status' => array( 'pending', 'publish' ),  
        'author'        =>$userid,
        
    );
    $posts = new WP_Query( $args );
    return $posts->found_posts;
    wp_reset_query();
    

}


/////////////////////////////////////////////////////////////////////////////////////
/// get the number of featured listings
/////////////////////////////////////////////////////////////////////////////////////

function get_user_curent_future_listings($user_id){
    
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
    $args = array(
        'post_type'     => 'propertys',
        'post_status'   =>'any',  
        'author'        =>$user_id,
        'meta_query' => array(   
                            array(
                                'key'   => $nvr_initial.'_featured',
                                'value' => 'true',
                                'meta_compare '=>'='
                            )
                        )
    );
    $posts = new WP_Query( $args );
    return $posts->found_posts;
    wp_reset_query();
    
}

function get_user_curent_future_listings_no_exp($user_id){
    
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
    $args = array(
        'post_type'     => 'propertys',
        'post_status' => array( 'pending', 'publish' ),
        'author'        =>$user_id,
        'meta_query' => array(   
                            array(
                                'key'   => $nvr_initial.'_featured',
                                'value' => 'true',
                                'meta_compare '=>'='
                            )
                        )
    );
    $posts = new WP_Query( $args );
    return $posts->found_posts;
    wp_reset_query();
    
}






/////////////////////////////////////////////////////////////////////////////////////
/// update user with paypal profile id
/////////////////////////////////////////////////////////////////////////////////////
function update_user_recuring_profile( $profile_id,$user_id ){
      $profile_id=  str_replace('-', 'xxx', $profile_id);
      $profile_id=  str_replace('%2d', 'xxx', $profile_id);
  
      update_user_meta( $user_id, 'profile_id', $profile_id);  
    
}


////////////////////////////////////////////////////////////////////////////////
/// Ajax  Package Paypal function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_nvr_ajax_make_prop_featured', 'nvr_ajax_make_prop_featured');  
add_action( 'wp_ajax_nvr_ajax_make_prop_featured', 'nvr_ajax_make_prop_featured' );
function  nvr_ajax_make_prop_featured(){
	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
    $prop_id=intval($_POST['propid']);
    global $current_user;
    get_currentuserinfo();
    $userID =   $current_user->ID;
    $post   =   get_post($prop_id); 
     
    if( $post->post_author != $userID){
        exit('get out of my cloud');
    }else{
        if(get_remain_featured_listing_user($userID) >0 ){
           update_featured_listing_no($userID); 
           update_post_meta($prop_id, $nvr_initial.'_featured', 'true');
           echo 'done';
        }else{
            echo 'no places';
        }
      
    }    
    die();
    
    
}
       
////////////////////////////////////////////////////////////////////////////////
/// Check user status durin cron
////////////////////////////////////////////////////////////////////////////////       

function check_user_membership_status_function(){
  
    $blogusers = get_users('role=subscriber');
    foreach ($blogusers as $user) {
        $user_id=$user->ID;
        $pack_id= get_user_meta ( $user_id, 'package_id', true);
        

        if( $pack_id !='' ){ // if the pack is ! free
            $date =  strtotime ( get_user_meta($user_id, 'package_activation',true) );
            
            $biling_period  =   get_post_meta($pack_id, 'billing_period', true);
            $billing_freq   =   get_post_meta($pack_id, 'billing_freq', true);  
            
            $seconds=0;
            switch ($biling_period){
               case 'Day':
                   $seconds=60*60*24;
                   break;
               case 'Week':
                   $seconds=60*60*24*7;
                   break;
               case 'Month':
                   $seconds=60*60*24*30;
                   break;    
               case 'Year':
                   $seconds=60*60*24*365;
                   break;    
           }
           $time_frame=$seconds*$billing_freq;
            
           $now=time();
           
           if( $now >$date+$time_frame ){ // if this moment is bigger than pack activation + billing period
                 downgrade_to_free($user_id);                
           }
    
        } // end if if pack !- free
        
    }// end foreach
    
    
    
    
}
?>
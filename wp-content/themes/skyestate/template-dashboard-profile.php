<?php
/**
 * Template Name: Dashboard Profile
 *
 * A custom page template for showing dashboard profile page.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0.5
 */
 
global $current_user;

$nvr_shortname = THE_SHORTNAME;
$nvr_initial = THE_INITIAL;

$dash_profile_link = nvr_dashboard_link('profile');


if (isset($_GET['token']) ){
    $token               =   sanitize_text_field ( $_GET['token'] );
    $token_recursive     =   sanitize_text_field ( $_GET['token'] );
   
       
    // get transfer data
    $save_data=get_option('paypal_pack_transfer');
    $payment_execute_url    =   $save_data[$current_user->ID ]['paypal_execute'];
    $token                  =   $save_data[$current_user->ID ]['paypal_token'];
    $pack_id                =   $save_data[$current_user->ID ]['pack_id'];
    $recursive              =   0;
    if (isset ( $save_data[$current_user->ID ]['recursive']) ){
        $recursive              =   $save_data[$current_user->ID ]['recursive']; 
    }
   
    if($recursive!=1){
        if( isset($_GET['PayerID']) ){
            $payerId             =   sanitize_text_field ( $_GET['PayerID'] );  
        
            $payment_execute = array(
                           'payer_id' => $payerId
                          );
            $json = json_encode($payment_execute);
            $json_resp = nvr_post_call($payment_execute_url, $json,$token);

            $save_data[$current_user->ID ]=array();
            update_option ('paypal_pack_transfer',$save_data); 

             if($json_resp['state']=='approved' ){

                if( check_downgrade_situation($current_user->ID,$pack_id) ){

                    downgrade_to_pack( $current_user->ID, $pack_id );
                    upgrade_user_membership($current_user->ID,$pack_id,1,'');
                }else{
                   upgrade_user_membership($current_user->ID,$pack_id,1,'');
                }
               wp_redirect( $dash_profile_link ); 
            }
        }
    }else{ 
        require( THE_TEMPLATEPATH.'engine/plugins/paypal/paypalfunctions.php');   
        $billing_period                 =   get_post_meta($pack_id, 'billing_period', true);
        $billing_freq                   =   intval(get_post_meta($pack_id, 'billing_freq', true));
		
		$billing_period                 =   get_post_meta($pack_id, 'billing_period', true);
        $billing_freq                   =   intval(get_post_meta($pack_id, 'billing_freq', true));
        
        $obj=new paypal_recurring;
        $obj->environment       =   esc_html( nvr_get_option( $nvr_shortname.'_paypal_api','') );
        $obj->paymentType       =   urlencode('Sale');          // or 'Sale' or 'Order'
        $paypal_api_username    =   esc_html( nvr_get_option( $nvr_shortname.'_paypal_api_username','') );
        $paypal_api_password    =   esc_html( nvr_get_option( $nvr_shortname.'_paypal_api_password','') );
        $paypal_api_signature   =   esc_html( nvr_get_option( $nvr_shortname.'_paypal_api_signature','') );    
        $obj->API_UserName      =   urlencode( $paypal_api_username );
        $obj->API_Password      =   urlencode( $paypal_api_password );
        $obj->API_Signature     =   urlencode( $paypal_api_signature );
        $obj->API_Endpoint      =   "https://api-3t.paypal.com/nvp";
        $obj->paymentType       =   urlencode('Sale');  
        $obj->returnURL         =   urlencode($dash_profile_link);
        $obj->cancelURL         =   urlencode($dash_profile_link);
        $obj->paymentAmount     =   get_post_meta($pack_id, 'pack_price', true);
        $obj->currencyID        =   nvr_get_option( $nvr_shortname.'_submission_curency','');
        $date                   =   $save_data[$current_user->ID ]['date'];
        $obj->startDate         =   urlencode($date);
        $obj->billingPeriod     =   urlencode($billing_period);         
        $obj->billingFreq       =   urlencode($billing_freq); 
        $pack_name              =   get_the_title($pack_id);
        $obj->productdesc       =   urlencode($pack_name.__(' package on ', THE_LANG ).get_bloginfo('name') );
        $obj->user_id           =   $current_user->ID;
        $obj->pack_id           =   $pack_id;
        
       if ( $obj->getExpressCheckout($token_recursive) ){
        
             if( check_downgrade_situation($current_user->ID,$pack_id) ){
                 downgrade_to_pack( $current_user->ID, $pack_id );
                 upgrade_user_membership($current_user->ID,$pack_id,2,'');
             }else{
                upgrade_user_membership($current_user->ID,$pack_id,2,'');
             }
            //wp_redirect( $dash_profile_link );  
        }
        
    }
                             
}

$processor_link =nvr_processor_link('paypal');
get_header(); ?>

	<?php
	get_currentuserinfo();
	
	$nvr_uid               =   $current_user->ID;
	$nvr_ulogin            =   $current_user->user_login;
	
	$nvr_firstname         =   get_the_author_meta( 'first_name' , $nvr_uid );
	$nvr_lastname          =   get_the_author_meta( 'last_name' , $nvr_uid );
	$nvr_email             =   get_the_author_meta( 'user_email' , $nvr_uid );
	$nvr_phone             =   get_the_author_meta( 'phone' , $nvr_uid );
	$nvr_mobile            =   get_the_author_meta( 'mobile' , $nvr_uid );
	
	$description           =   get_the_author_meta( 'description' , $nvr_uid );
	$nvr_skype             =   get_the_author_meta( 'skype' , $nvr_uid );
	$nvr_facebook          =   get_the_author_meta( 'facebook' , $nvr_uid );
	$nvr_twitter           =   get_the_author_meta( 'twitter' , $nvr_uid );
	$nvr_pinterest    	   =   get_the_author_meta( 'pinterest' , $nvr_uid );
	$nvr_linkedin          =   get_the_author_meta( 'linkedin' , $nvr_uid );
	$nvr_youtube           =   get_the_author_meta( 'youtube' , $nvr_uid );
	$nvr_instagram    	   =   get_the_author_meta( 'instagram' , $nvr_uid );
	$nvr_title             =   get_the_author_meta( 'title' , $nvr_uid );
	$nvr_custom_picture    =   get_the_author_meta( 'custom_picture' , $nvr_uid );
	$nvr_pack              =   get_the_author_meta( 'package_id' , $nvr_uid );
	$nvr_registered        =   get_the_author_meta( 'user_registered' , $nvr_uid );
	$nvr_package_activation=   get_the_author_meta( 'package_activation' , $nvr_uid );
	
   	$nvr_pid = nvr_get_postid();
	$nvr_custom = nvr_get_customdata($nvr_pid);

    ?>
    
    <div class="user_profile_div row"> 
    	<div class="twelve columns">
            <div class="dashboard-menu">
                <a href="<?php echo nvr_dashboard_link('profile'); ?>" class="dashboard-menu-link active"><i class="fa fa-user"></i> <?php _e('My Profile'); ?></a>
                <a href="<?php echo nvr_dashboard_link('add'); ?>" class="dashboard-menu-link"><i class="fa fa-home"></i> <?php _e('Add New Property'); ?></a>
                <a href="<?php echo nvr_dashboard_link('list'); ?>" class="dashboard-menu-link"><i class="fa fa-bars"></i> <?php _e('Property Lists'); ?></a>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="twelve columns">
            <h3><?php _e('Welcome back, ',THE_LANG); echo $nvr_ulogin.'!';?></h3>
            <?php 
                $is_membership=0;
                $membership_class='twelve';
                $paid_submission_status = esc_html ( nvr_get_option($nvr_shortname . '_paid_submission','') );  
                if ($paid_submission_status == 'membership'){
                    get_pack_data_for_user_top($nvr_uid,$nvr_pack,$nvr_registered,$nvr_package_activation); 
                     $is_membership=1;
                     $membership_class='nine';
                } 
            ?>  
        </div>
        <div class="<?php echo esc_attr( $membership_class );?> columns alpha nomargin">
        	<div id="profile_message"></div>    
                
            <div class="add-estate profile-page row">  
                <div class="profile_div twelve columns" id="profile-div">
                	<?php
					$agentimagestyle = '';
					if($nvr_custom_picture){
						$agentimagestyle = 'background-image: url('. esc_attr( $nvr_custom_picture ) .');';
					}
					?>
                    <div class="featured_agent_image" id="profile-image" style=" <?php echo esc_attr($agentimagestyle); ?>">
                        <input type="hidden" id="profile-image_id" name="" value="">
                    </div>  

                    <div id="upload-container">                 
                        <div id="aaiu-upload-container">                 
                            <a id="aaiu-uploader" class="aaiu_button button" href="#"><?php _e('Upload Profile Image',THE_LANG);?></a>
                           
                            <div id="aaiu-upload-imagelist">
                                <ul id="aaiu-ul-list" class="aaiu-upload-list"></ul>
                            </div>
                        </div>  
                    </div>
                    <span class="upload_explain"><?php _e('*recommended size: at least 160 px tall &amp; wide',THE_LANG);?></span>
                </div>

                <p class="one_half columns">
                    <label for="firstname"><?php _e('First Name',THE_LANG);?></label>
                    <input type="text" class="textbox" id="firstname" value="<?php echo $nvr_firstname;?>"  name="firstname">
                </p>

                <p class="one_half columns">
                    <label for="secondname"><?php _e('Last Name',THE_LANG);?></label>
                    <input type="text" class="textbox" id="secondname" value="<?php echo $nvr_lastname;?>"  name="secondname">
                </p>

                <p class="one_half columns">
                    <label for="useremail"><?php _e('Email',THE_LANG);?></label>
                    <input type="text" class="textbox" id="useremail" value="<?php echo $nvr_email;?>"  name="useremail">
                </p>

                <p class="one_half columns">
                    <label for="userphone"><?php _e('Phone',THE_LANG);?></label>
                    <input type="text" class="textbox" id="userphone" value="<?php echo $nvr_phone;?>"  name="userphone">
                </p>

                <p class="one_half columns">
                    <label for="usermobile"><?php _e('Mobile',THE_LANG);?></label>
                    <input type="text" class="textbox" id="usermobile" value="<?php echo $nvr_mobile;?>"  name="usermobile">
                </p>

                
                <p class="one_half columns">
                    <label for="userskype"><?php _e('Skype',THE_LANG);?></label>
                    <input type="text" class="textbox" id="userskype" value="<?php echo $nvr_skype;?>"  name="userskype">
                </p>
                
                <p class="one_half columns">
                    <label for="userfacebook"><?php _e('Facebook',THE_LANG);?></label>
                    <input type="text" class="textbox" id="userfacebook" value="<?php echo $nvr_facebook;?>"  name="userfacebook">
                </p>
                
                <p class="one_half columns">
                    <label for="usertwitter"><?php _e('Twitter',THE_LANG);?></label>
                    <input type="text" class="textbox" id="usertwitter" value="<?php echo $nvr_twitter;?>"  name="usertwitter">
                </p>
                
                <p class="one_half columns">
                    <label for="userpinterest"><?php _e('Pinterest',THE_LANG);?></label>
                    <input type="text" class="textbox" id="userpinterest" value="<?php echo $nvr_pinterest;?>"  name="userpinterest">
                </p>
                
                <p class="one_half columns">
                    <label for="userlinkedin"><?php _e('Linkedin',THE_LANG);?></label>
                    <input type="text" class="textbox" id="userlinkedin" value="<?php echo $nvr_linkedin;?>"  name="userlinkedin">
                </p>
                
                <p class="one_half columns">
                    <label for="useryoutube"><?php _e('Youtube',THE_LANG);?></label>
                    <input type="text" class="textbox" id="useryoutube" value="<?php echo $nvr_youtube;?>"  name="useryoutube">
                </p>
                
                <p class="one_half columns">
                    <label for="userinstagram"><?php _e('Instagram',THE_LANG);?></label>
                    <input type="text" class="textbox" id="userinstagram" value="<?php echo $nvr_instagram;?>"  name="userinstagram">
                </p>

                <p class="one_half columns">
                    <label for="usertitle"><?php _e('Title/Position',THE_LANG);?></label>
                    <input type="text" class="textbox" id="usertitle" value="<?php echo $nvr_title;?>"  name="usertitle">
                </p>
                
                <p class="twelve columns">
                    <label for="about_me"><?php _e('About Me',THE_LANG);?></label>
                    <textarea id="about_me" class="bigtextbox" name="about_me"><?php echo $description;?></textarea>
                </p>
                
            
                <?php   wp_nonce_field( 'nvr_profile_nonce', 'security-profile' );   ?>
                <div class="clearfix"></div>
                <p class="twelve columns">
                    <button type="submit" id="update_profile" class="btn vernil small"><?php _e('Update profile',THE_LANG);?></button>
                </p>
            </div>
    
            <div class="add-estate row profile-page add-pass">  
                <h3><?php _e('Change Password',THE_LANG);?> </h3>
                <div id="profile_pass">
                </div>    
                <p class="one_third columns">
                    <label for="oldpass"><?php _e('Old Password',THE_LANG);?></label>
                    <input  id="oldpass" class="textbox" value=""  name="oldpass" type="password">
                </p>
                
                <p class="one_third columns">
                    <label for="newpass"><?php _e('New Password ',THE_LANG);?></label>
                    <input  id="newpass" class="textbox" value=""  name="newpass" type="password">
                </p>
                <p class="one_third columns">
                    <label for="renewpass"><?php _e('Confirm New Password',THE_LANG);?></label>
                    <input id="renewpass" class="textbox" value=""  name="renewpass"type="password">
                </p>
                
                 <?php   wp_nonce_field( 'nvr_pass_nonce', 'security-pass' );   ?>
                <p class="twelve columns">
                    <button type="submit" id="change_pass" class="button"><?php _e('Reset Password',THE_LANG);?></button>
                </p>
            </div>
        </div>
        <?php 
        if ( $is_membership==1){
            $is_stripe_live  = nvr_get_option( $nvr_shortname.'_enable_stripe', false);
            $is_paypal_live = nvr_get_option( $nvr_shortname.'_enable_paypal', false);
                  
            echo'<div class="three columns omega nomargin">';   
            echo '<div class="submit_container_header">'.__('Select your Package',THE_LANG).'</div>'; ?>
            <div class="submit_container">
                   <form action="" id="package_pick">
                      <?php nvr_display_packages(); ?></br>
                      <input type="checkbox" name="pack_recuring" id="pack_recuring" value="1" /> 
                      <label for="pack_recurring"><?php _e('make payment recurring',THE_LANG)?></label>
                      <?php if ($is_paypal_live=='1') {?>
                        <div id="pick_pack"></div>
                      <?php } ?>
                  </form>  
                
                <?php 
                if ( $is_stripe_live=='1'){
                 
                require_once( THE_TEMPLATEPATH.'engine/plugins/stripe/lib/Stripe.php');
                $stripe_secret_key              =   esc_html( nvr_get_option( $nvr_shortname.'_stripe_secret_key','') );
                $stripe_publishable_key         =   esc_html( nvr_get_option( $nvr_shortname.'_stripe_publishable_key','') );

                $stripe = array(
                  "secret_key"      => $stripe_secret_key,
                  "publishable_key" => $stripe_publishable_key
                );
                $pay_ammout=9999;
                $pack_id='11';
                Stripe::setApiKey($stripe['secret_key']);
                $processor_link 			= nvr_processor_link('stripe');
				
                $submission_curency_status  =   esc_html( nvr_get_option( $nvr_shortname.'_submission_currency','') );
                
                
                
                echo ' 
                <form action="'.$processor_link.'" method="post" id="stripe_form">
                    '.nvr_get_stripe_buttons($stripe['publishable_key'],$user_email,$submission_curency_status).'
                   
                    <input type="hidden" id="pack_id" name="pack_id" value="'.$pack_id.'">
                    <input type="hidden" name="userID" value="'.$nvr_uid.'">
                    <input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$pay_ammout.'">
                </form>';
                }
                
                
                ?>
                
           </div>
           
            <div class="submit_container_header"><?php _e('Packages Available',THE_LANG);?></div>
            <div class="submit_container nopad">
                
                <?php
                $currency  =   esc_html( nvr_get_option( $nvr_shortname.'_submission_curency', '') );
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
                
                while($pack_selection->have_posts() ){
                    $pack_selection->the_post();
                    $postid             = $post->ID;
					$nvr_custom 		= nvr_get_customdata($postid);
					$pack_list          = isset($nvr_custom['pack_listings'][0])? $nvr_custom['pack_listings'][0] : '';
                    $pack_featured      = isset($nvr_custom['pack_featured_listings'][0])? $nvr_custom['pack_featured_listings'][0] : '';
                    $pack_price         = isset($nvr_custom['pack_price'][0])? $nvr_custom['pack_price'][0] : '';
                    $unlimited_lists    = ($pack_list<0)? 1 : 0;
                    $billing_period      = isset($nvr_custom['billing_period'][0])? $nvr_custom['billing_period'][0] : '';
                    $billing_freq       = isset($nvr_custom['billing_freq'][0])? $nvr_custom['billing_freq'][0] : '';
                    $pack_time          = isset($nvr_custom['pack_time'][0])? $nvr_custom['pack_time'][0] : '';
                    $unlimited_listings = ($pack_list<0)? 1 : 0;

                    echo'<div class="pack-listing">';
                    echo'<h3>'.get_the_title().' - <span class="submit-price">'.$pack_price.' '.$currency.'</span> / '.$billing_freq.' '.nvr_show_bill_period($billing_period).' </h3>';
                    if($unlimited_listings==1){
                        echo'<span class="submit-featured">'.__('Unlimited',THE_LANG).'</span> '.__('listings of which',THE_LANG).' </br>';
                    }else{
                        echo'<span class="submit-featured">'.$pack_list.'</span> '.__('Listings of which',THE_LANG).' </br>';    
                    }
                  
                    echo'<span class="submit-featured">'.$pack_featured.'</span>  '.__('Featured',THE_LANG).'   
                    </div>';
       
                }
				wp_reset_query();
                ?>
                
            </div>
          </div><!-- end three col-->
        <?php    
        }
        
        ?>
        
        
    </div>
    <div class="clearfix"></div><!-- clear float --> 
    
<?php get_footer(); ?>
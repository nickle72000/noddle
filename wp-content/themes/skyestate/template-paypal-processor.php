<?php
/**
 * Template Name: Paypal Processor
 *
 * A custom page template for showing paypal processor page.
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

 $paid_submission_status    = esc_html ( nvr_get_option( $nvr_shortname.'_paid_submission','') );
 if($paid_submission_status !='per listing'){
  //   exit('member');
 }

$paypal_status  =   esc_html( nvr_get_option( $nvr_shortname.'_paypal_api','') );
$host           =   'https://api.sandbox.paypal.com';
if($paypal_status == 'live'){
    $host='https://api.paypal.com';
}

global $current_user;
get_currentuserinfo();     
$processor_link                 =   get_procesor_link();
$clientId                       =   esc_html( nvr_get_option( $nvr_shortname.'_paypal_client_id','') );
$clientSecret                   =   esc_html( nvr_get_option( $nvr_shortname.'_paypal_client_secret','') );
$price_submission               =   floatval( nvr_get_option( $nvr_shortname.'_price_submission','') );
$price_submission               =   number_format($price_submission, 2, '.', '');
$submission_curency_status      =   esc_html( nvr_get_option( $nvr_shortname.'_submission_curency','') );
$headers = 'From: My Name <myname@example.com>' . "\r\n";
$attachments ='';

if (isset($_GET['token']) && isset($_GET['PayerID']) ){
        $token     =   sanitize_text_field ( $_GET['token'] );
        $payerId   =   sanitize_text_field ( $_GET['PayerID'] );

        // get transfer data
        $save_data=get_option('paypal_transfer');
        $payment_execute_url    =   $save_data[$current_user->ID ]['paypal_execute'];
        $token                  =   $save_data[$current_user->ID ]['paypal_token'];
        $listing_id             =   $save_data[$current_user->ID ]['listing_id'];
        $is_featured            =   $save_data[$current_user->ID ]['is_featured'];
        $is_upgrade             =   $save_data[$current_user->ID ]['is_upgrade'];
        $is_booking             =   $save_data[$current_user->ID ]['is_booking'];
        
        $invoice_id             =   $save_data[$current_user->ID ]['invoice_id'];
        $booking_id             =   $save_data[$current_user->ID ]['booking_id'];
        
        
        
        
        $payment_execute = array(
                        'payer_id' => $payerId
                       );
        $json = json_encode($payment_execute);
        $json_resp = make_post_call($payment_execute_url, $json,$token);
        $save_data[$current_user->ID ]=array();
        update_option ('paypal_transfer',$save_data);
        
        // update prop listing status
        if($json_resp['state']=='approved'){
            $time = time(); 
            $date = date('Y-m-d H:i:s',$time);
    
            
            if($is_booking==1){
                ////////////////////////////////////////////////////////////////////////
                /// booking payment
                ////////////////////////////////////////////////////////////////////////
               
                
                // confirm booking
                update_post_meta($booking_id, 'booking_status', 'confirmed');

                $curent_listng_id   =   get_post_meta($booking_id,'booking_id',true);
                $reservation_array  =   get_booking_dates($curent_listng_id);


                update_post_meta($curent_listng_id, 'booking_dates', $reservation_array); 

                // set invoice to paid
                $total_price        =   get_post_meta($invoice_id, 'item_price', true);
                $book_down          =   floatval( nvr_get_option( $nvr_shortname.'_book_down','') );
                if($book_down==''){
                   $book_down=10;
                }

                $total_price=$depozit            =   round($total_price/100*$book_down);
    
                update_post_meta($invoice_id, 'invoice_status', 'confirmed');
                update_post_meta($invoice_id, 'depozit_paid', ($depozit) );



                /////////////////////////////////////////////////////////////////////////////
                // send confirmation emails
                /////////////////////////////////////////////////////////////////////////////

                nvr_send_booking_email("bookingconfirmeduser",$user_email);

                $receiver_id    =   wpsestate_get_author($invoice_id);
                $receiver_email =   get_the_author_meta('user_email', $receiver_id); 
                $receiver_name  =   get_the_author_meta('user_login', $receiver_id); 
                nvr_send_booking_email("bookingconfirmed",$receiver_email);


                // add messages to inbox
                $userID         =   $current_user->ID;
                $username       =   $current_user->user_login;
                $subject=__('Booking Confirmation',THE_LANG);
                $description=__('A booking was confirmed',THE_LANG);
                nvr_add_to_inbox($userID,$receiver_name,$userID,$subject,$description);

                $subject=__('Booking Confirmed',THE_LANG);
                $description=__('A booking was confirmed',THE_LANG);
                nvr_add_to_inbox($receiver_id,$username,$receiver_id,$subject,$description);





                ////redirect catre bookng list
                $redirect= nvr_get_reservations_link();
                wp_redirect($redirect);
                exit();

                
                
            }else if($is_upgrade==1){
                ////////////////////////////////////////////////////////////////////////
                /// upgrade to featured
                ////////////////////////////////////////////////////////////////////////
                update_post_meta($listing_id, $nvr_shortname'_featured', 'true');
                insert_invoice('Upgrade to Featured','One Time',$listing_id,$date,$current_user->ID,0,1,'' );
                nvr_email_to_admin(1);
            }else{
                ////////////////////////////////////////////////////////////////////////
                /// submission payment
                ////////////////////////////////////////////////////////////////////////
                update_post_meta($listing_id, 'pay_status', 'paid');
                
                // if admin does not need to approve - make post status as publish
                $admin_submission_status = esc_html ( nvr_get_option( $nvr_shortname.'_admin_submission','') );
                $paid_submission_status  = esc_html ( nvr_get_option( $nvr_shortname.'_paid_submission','') );
              
                if($admin_submission_status=='no'  && $paid_submission_status=='per listing' ){
                      
                    $post = array(
                        'ID'            => $listing_id,
                        'post_status'   => 'publish'
                        );
                     $post_id =  wp_update_post($post ); 
                }
                // end make post publish
                
                
                if($is_featured==1){
                    update_post_meta($listing_id, $nvr_shortname.'_featured', 'true');
                    insert_invoice('Publish Listing with Featured','One Time',$listing_id,$date,$current_user->ID,1,0,'' );
                }else{
                    insert_invoice('Listing','One Time',$listing_id,$date,$current_user->ID,0,0,'' );
                }
                 nvr_email_to_admin(0);
                 //print 'facem update';
            }
            
        }
       
       
        $redirect = nvr_dashboard_link('list');
        wp_redirect($redirect);
        
    
    
    
}
$token = '';





/*
*/
//////////////////////////////////////////////////////////////////////////////////////
/// Process messages from Paypal IPN
//////////////////////////////////////////////////////////////////////////////////////
define('DEBUG',0);

$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
        $keyval = explode ('=', $keyval);
        if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
   $get_magic_quotes_exists = true;
} 

foreach ($myPost as $key => $value) {        
   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
        $value = urlencode(stripslashes($value)); 
   } else {
        $value = urlencode($value);
   }
   $req .= "&$key=$value";
}
 

// Step 2: POST IPN data back to PayPal to validate
$paypal_status  =   esc_html( nvr_get_option( $nvr_shortname.'_paypal_api','') );
if($paypal_status == 'live'){
     $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
} else {
     $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
}  
   
print $paypal_url;
   
$ch = curl_init($paypal_url);
if ($ch == FALSE) {
        return FALSE;
}

curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

if(DEBUG == true) {
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
}



// Set TCP timeout to 30 seconds
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));



$res = curl_exec($ch);

if (curl_errno($ch) != 0) // cURL error
        {
        if(DEBUG == true) {        
               // error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
        }
        curl_close($ch);
        exit;

} else {
        // Log the entire HTTP response if debug is switched on.
        if(DEBUG == true) {
                // Split response headers and payload
                list($headers, $res) = explode("\r\n\r\n", $res, 2);
        }
        curl_close($ch);
       
}



// Inspect IPN validation result and act accordingly
//$res= "VERIFIED";

if (strcmp ($res, "VERIFIED") == 0) {


        $payment_status         =   $_POST['payment_status'];
        $txn_id                 =   $_POST['txn_id'];
        $txn_type               =   $_POST['txn_type'];   
        $paypal_rec_email       =   esc_html( nvr_get_option( $nvr_shortname.'_paypal_rec_email','') );
        $receiver_email         =   $_POST['receiver_email'];
        $payer_id               =   $_POST['payer_id'];
  
        $payer_email            =   $_POST['payer_email'];
        $amount                 =   $_POST['amount'];
        $recurring_payment_id   =   $_POST['recurring_payment_id'];
        $user_id                =   retrive_user_by_profile($recurring_payment_id) ; 
        $pack_id                =   get_user_meta($user_id, 'package_id',true);
        $price                  =   get_post_meta($pack_id, 'pack_price', true);
        
            $mailm='';
            foreach ($_POST as $key => $value){
                  $mailm.='['.$key.']='.$value.'</br>';
            }  
            
        
        
        if( $payment_status=='Completed' ){
           
             if($receiver_email!=$paypal_rec_email){
                exit();
            }
                      
            if(retrive_invoice_by_taxid($txn_id)){// payment already processd
                exit();
            }
            
           if( $user_id==0 ){ // no user with such profile id
                exit();
           }
           
           if( $amount != $price){ // received payment diffrent than pack value
                exit(); 
           }
           
           upgrade_user_membership($user_id,$pack_id,2,$txn_id);
         
        }else{ // payment not completed
           
            if($txn_type!='recurring_payment_profile_created'){
                  downgrade_to_free($user_id);
            }
          
          }
 
} else if (strcmp ($res, "INVALID") == 0) {
      exit('invalid');
     
}
 



function retrive_user_by_profile($recurring_payment_id){   
    $recurring_payment_id=  str_replace('-', 'xxx', $recurring_payment_id);
    $arg=array(
        'role'         => 'subscriber',
        'meta_key'     => 'profile_id',
	'meta_value'   => $recurring_payment_id,
        'meta_compare' => '='
        );
    print_r($arg);
    $userid=0;
    $blogusers = get_users($arg);
    foreach ($blogusers as $user) {
       $userid=$user->ID;
    }
    return $userid;
}



function retrive_invoice_by_taxid($tax_id){
    $args = array(
	'post_type' => 'novaro_invoice',
	'meta_query' => array(
		array(
			'key' => 'txn_id',
			'value' => $tax_id,
			'compare' => '='
		)
	)
    );
  
    $query = new WP_Query( $args );
    
    if( $query->have_posts() ){
        return true;
    }else{
        return false;
    }

}
?>
<?php
/**
 * Template Name: Dashboard List
 *
 * A custom page template for showing dashboard list of property page.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0.5
 */

get_header(); ?>

	<?php
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
	
   	if ( !is_user_logged_in() ) {   
		 wp_redirect(  home_url() );
	} 

global $current_user;
get_currentuserinfo();
$userID                         =   $current_user->ID;
$user_login                     =   $current_user->user_login;
$user_pack                      =   get_the_author_meta( 'package_id' , $userID );
$user_registered                =   get_the_author_meta( 'user_registered' , $userID );
$user_package_activation        =   get_the_author_meta( 'package_activation' , $userID );   
$paid_submission_status         =   esc_html ( nvr_get_option( $nvr_shortname.'_paid_submission','') );
$price_submission               =   floatval( nvr_get_option( $nvr_shortname.'_price_submission','') );
$submission_curency_status      =   esc_html( nvr_get_option( $nvr_shortname.'_submission_curency','') );
$price_featured_submission  	=   floatval( nvr_get_option( $nvr_shortname.'_price_featured_submission','') );
$currency_title             	=   nvr_get_option( $nvr_shortname . '_currency_symbol');
$where_currency             	=   nvr_get_option( $nvr_shortname . '_currency_place');

$is_paypal_live 				= 	nvr_get_option( $nvr_shortname.'_enable_paypal','');
$is_stripe_live 				= 	nvr_get_option( $nvr_shortname.'_enable_stripe','');

$stripe_secret_key              =   esc_html( nvr_get_option( $nvr_shortname.'_stripe_secret_key','') );
$stripe_publishable_key         =   esc_html( nvr_get_option( $nvr_shortname.'_stripe_publishable_key','') );


$edit_link = nvr_dashboard_link('add');

$processor_link = nvr_processor_link('paypal');


if( isset( $_GET['delete_id'] ) ) {
    if( !is_numeric($_GET['delete_id'] ) ){
          exit('you don\'t have the right to delete this');
    }else{
        $delete_id=$_GET['delete_id'];
        $the_post= get_post( $delete_id); 
		
		if($the_post){
			if( $current_user->ID != $the_post->post_author ) {
				exit('you don\'t have the right to delete this');;
			}else{
				// delete attchaments
				$arguments = array(
					'numberposts' => -1,
					'post_type' => 'attachment',
					'post_parent' => $delete_id,
					'post_status' => null,
					'exclude' => get_post_thumbnail_id(),
					'orderby' => 'menu_order',
					'order' => 'ASC'
				);
				$post_attachments = get_posts($arguments);
				
				foreach ($post_attachments as $attachment) {
				   wp_delete_post($attachment->ID);                      
				 }
			   
				wp_delete_post( $delete_id ); 
			 
			}
		}
        
    }
       
}  
?>
<div class="row">
    <div class="twelve columns">
        <div class="dashboard-menu">
            <a href="<?php echo nvr_dashboard_link('profile'); ?>" class="dashboard-menu-link"><i class="fa fa-user"></i> <?php _e('My Profile'); ?></a>
            <a href="<?php echo nvr_dashboard_link('add'); ?>" class="dashboard-menu-link"><i class="fa fa-home"></i> <?php _e('Add New Property'); ?></a>
            <a href="<?php echo nvr_dashboard_link('list'); ?>" class="dashboard-menu-link active"><i class="fa fa-bars"></i> <?php _e('Property Lists'); ?></a>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<?php
$paid_submission_status= esc_html ( nvr_get_option( $nvr_shortname.'_paid_submission','') );
if( $paid_submission_status == 'membership'){
	get_pack_data_for_user_top($userID,$user_pack,$user_registered,$user_package_activation);
}
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
		'post_type'         => 'propertys',
		'author'            =>  $current_user->ID,
		'paged'             => $paged,
		'post_status'       => array( 'any' )
	);


$prop_selection = new WP_Query($args);
if( !$prop_selection->have_posts() ){
	echo '<h4>'.__('You don\'t have any properties yet!',THE_LANG).'</h4>';
}
?>

<div class="dashboard-listing-container">
<?php
while ($prop_selection->have_posts()): $prop_selection->the_post(); 
	
	
	$post_id                    =   get_the_ID();
	$preview                    =   wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'property-image');
	$edit_link                  =   add_query_arg( 'listing_edit', $post_id, $edit_link ) ;
	$post_status                =   get_post_status($post_id);
	$property_address           =   esc_html ( get_post_meta($post_id, $nvr_initial.'_address', true) );
	$property_city              =   get_the_term_list($post_id, 'property_city', '', ', ', '') ;
	$property_category          =   get_the_term_list($post_id, 'property_category', '', ', ', '');
	$property_action_category   =   get_the_term_list($post_id, 'property_purpose', '', ', ', '');
	$price_label                =   esc_html ( get_post_meta($post_id, $nvr_initial.'_price_label', true) );
	$price                      =   intval( get_post_meta($post->ID, $nvr_initial.'_price', true) );
	$currency                   =   $submission_curency_status;
	$status                     =   '';
	$link                       =   '';
	$pay_status                 =   '';
	$is_pay_status              =   '';
	
	if ($price != 0) {
	   $price = number_format($price);
	   
	   if ($where_currency == 'before') {
		   $price_title =   $currency_title . ' ' . $price;
		   $price       =   $currency . ' ' . $price;
	   } else {
		   $price_title = $price . ' ' . $currency_title;
		   $price       = $price . ' ' . $currency;
		 
	   }
	}else{
		$price='';
		$price_title='';
	}
	
	if($post_status=='expired'){ 
		$status='<span class="tag-waiting">'.__('Expired',THE_LANG).'</span>';
	}else if($post_status=='publish'){ 
		$link=get_permalink();
		$status='<span class="tag-published">'.__('Published',THE_LANG).'</span>';
	}else{
		$link = $edit_link;
		$status='<span class="tag-waiting">'.__('Waiting for approval',THE_LANG).'</span>';
	}
	
	
	if ($paid_submission_status=='per listing'){
		$pay_status    = get_post_meta(get_the_ID(), 'pay_status', true);
		if($pay_status=='paid'){
			$is_pay_status.='<span class="tag-paid">'.__('Paid',THE_LANG).'</span>';
		}
		if($pay_status=='not paid'){
			$is_pay_status.='<span class="tag-notpaid">'.__('Not Paid',THE_LANG).'</span>';
		}
	}
	$featured  = get_post_meta($post->ID, $nvr_initial.'_featured', true);
	?>

	<div class="property-listing">
	   <div class="prop_listing_image">
		   <?php
			if($featured=='true'){
					echo '<div class="featured_div_admin">'.__('Featured',THE_LANG).'</div>';
			}
		   ?>
		   <a href="<?php echo $link; ?>"><img  src="<?php  echo $preview[0]; ?>"  alt="slider-thumb" /></a>
	   </div>
		
	
		<div class="prop-info">
			<h3 class="listing_title">
				<a href="<?php echo $link; ?>"><?php the_title(); ?></a> 
				<?php echo ' -  <span class="price_label"> '. $price_title.' '.$price_label.'</span>';?>
			   
			</h3>
			
			<div class="user_dashboard_listed">
				<?php _e('Listed in',THE_LANG);?>  
				<?php echo $property_action_category; ?> 
				<?php if( $property_action_category!='') {
						echo ' '.__('and',THE_LANG).' ';
						} 
					  echo $property_category;?>                     
			</div>    
			
			<div class="user_dashboard_listed">
				<?php _e('City',THE_LANG);?>            
				<?php echo $property_city;?>     
			</div>
			
			<div class="user_dashboard_actions">
				<?php echo $status.$is_pay_status;?>      
			</div>
			
			<div class="user_dashboard_user_actions web">
				 <a href="<?php  echo $edit_link;?>"><?php _e('Edit',THE_LANG);?></a> |
				 <a onclick="return confirm(' <?php echo __('Are you sure you wish to delete ',THE_LANG).get_the_title(); ?>?')" href="<?php echo add_query_arg( 'delete_id', $post_id, $_SERVER['REQUEST_URI'] );?>"><?php _e('Delete',THE_LANG);?></a>  
			 </div>
		 
		</div>

		<div class="info-container">
		   <?php 
		   $pay_status    = get_post_meta($post_id, 'pay_status', true);
		   
			
		  if( $post_status == 'expired' ){ 
			 echo'<div class="listing_submit">
					<span class="resend_pending" data-listingid="'.$post_id.'">'.__('Resend for approval',THE_LANG).'</span>
					</div>';
			  
		  }else{
			 
			   if($paid_submission_status=='membership'){
					if ( $featured=='true'){
						 echo '<span class="featured_prop">Property is featured</span>';       
					}
					else{
						echo '<a class="make_featured" href="#" data-postid="'.$post_id.'">'.__('Make this property featured?', THE_LANG).'</a><br />';
						echo '<span class="featured_exp">'.__('*You can featured this property by clicking the button.',THE_LANG).' </span>';
					}
	
			   }
				
			   if($paid_submission_status=='per listing'){
		   
					if($pay_status!='paid' ){
						echo' <h3 class="listing_title">'.__('Price Info',THE_LANG).'</h3>
							   <div class="listing_submit">
							   '.__('Price',THE_LANG).': <span class="submit-price submit-price-no">'.$price_submission.'</span><span class="submit-price"> '.$currency.'</span></br>
							   <input type="checkbox" class="extra_featured" name="extra_featured" style="display:block;" value="1" >
							   '.__('Featured listing',THE_LANG).': <span class="submit-price submit-price-featured">'.$price_featured_submission.'</span><span class="submit-price"> '.$currency.'</span> </br>
							   '.__('Total',THE_LANG).': <span class="submit-price submit-price-total">'.$price_submission.'</span> <span class="submit-price">'.$currency.'</span> ';  
					   
						if ( $is_paypal_live=='1'){
							echo  '<div class="listing_submit_normal" data-listingid="'.$post_id.'"></div>';
						}
						
						if ( $is_stripe_live=='1'){
						 
							require_once(THE_TEMPLATEPATH.'engine/plugins/stripe/lib/Stripe.php');
	
							$stripe = array(
							  "secret_key"      => $stripe_secret_key,
							  "publishable_key" => $stripe_publishable_key
							);
							
							Stripe::setApiKey($stripe['secret_key']);
							$processor_link = nvr_processor_link('stripe');
							global $current_user;
							get_currentuserinfo();
							$userID                 =   $current_user->ID ;
							$user_email             =   $current_user->user_email ;
							
							$price_submission_total =   $price_submission+$price_featured_submission;
							$price_submission_total =   $price_submission_total*100;
							$price_submission_amount=   $price_submission*100;
							echo ' 
							<form action="'.$processor_link.'" method="post" id="stripe_form_simple">
								<div class="stripe_simple">
									<script src="https://checkout.stripe.com/checkout.js" 
									class="stripe-button"
									data-key="'. $stripe_publishable_key.'"
									data-amount="'.$price_submission.'" 
									data-email="'.$user_email.'"
									data-currency="'.$submission_curency_status.'"
									data-label="'.__('Pay with Credit Card',THE_LANG).'"
									data-description="'.__('Submission Payment',THE_LANG).'">
									</script>
								</div>
								<input type="hidden" id="propid" name="propid" value="'.$post_id.'">
								<input type="hidden" id="submission_pay" name="submission_pay" value="1">
								<input type="hidden" name="userID" value="'.$userID.'">
								<input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$price_submission_amount.'">
							</form>
							
							<form action="'.$processor_link.'" method="post" id="stripe_form_featured">
								<div class="stripe_simple">
									<script src="https://checkout.stripe.com/checkout.js" 
									class="stripe-button"
									data-key="'. $stripe_publishable_key.'"
									data-amount="'.$price_submission_total.'" 
									data-email="'.$user_email.'"
									data-currency="'.$submission_curency_status.'"
									data-label="'.__('Pay with Credit Card',THE_LANG).'"
									data-description="'.__('Submission & Featured Payment',THE_LANG).'">
									</script>
								</div>
								<input type="hidden" id="propid" name="propid" value="'.$post_id.'">
								<input type="hidden" id="submission_pay" name="submission_pay" value="1">
								<input type="hidden" id="featured_pay" name="featured_pay" value="1">
								<input type="hidden" name="userID" value="'.$userID.'">
								<input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$price_submission_total.'">
							</form>
	
	
							';
					
						}
						
					   
						echo'</div>'; 
					}else{
						 if ( intval(get_post_meta($post_id, 'prop_featured', true))==1){
							echo '<span class="featured_prop">'.__('Property is featured',THE_LANG).'</span>';  
						 }else{
	
							if ( $is_paypal_live=='1'){
								echo'<span class="listing_upgrade" data-listingid="'.$post_id.'">'.__('Upgrade to Featured (PayPal)',THE_LANG).'</span>'; 
							}
							
							if ( $is_stripe_live=='1'){
								require_once(get_template_directory().'/libs/stripe/lib/Stripe.php');
	
								$stripe = array(
								  "secret_key"      => $stripe_secret_key,
								  "publishable_key" => $stripe_publishable_key
								);
	
								Stripe::setApiKey($stripe['secret_key']);
								$processor_link = nvr_processor_link('stripe');
								global $current_user;
								get_currentuserinfo();
								$userID                 =   $current_user->ID ;
								$user_email             =   $current_user->user_email ;
	
								$price_featured_submission  =   $price_featured_submission*100;
								
								echo '  
								<form action="'.$processor_link.'" method="post" >
								<div class="stripe_simple upgrade_stripe">
									<script src="https://checkout.stripe.com/checkout.js" 
									class="stripe-button"
									data-key="'. $stripe_publishable_key.'"
									data-amount="'.$price_featured_submission.'" 
									data-email="'.$user_email.'"
									data-currency="'.$submission_curency_status.'"
									data-panel-label="'.__('Upgrade to Featured',THE_LANG).'"
									data-label="'.__('Upgrade to Featured',THE_LANG).'"
									data-description="'.__(' Featured Payment',THE_LANG).'">
							 
									</script>
								</div>
								<input type="hidden" id="propid" name="propid" value="'.$post_id.'">
								<input type="hidden" id="submission_pay" name="submission_pay" value="1">
								<input type="hidden" id="is_upgrade" name="is_upgrade" value="1">
								<input type="hidden" name="userID" value="'.$userID.'">
								<input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$price_featured_submission.'">
								</form>';
							}
						 } 
					 }
			  }
		   
		  }
		  
		   ?>
		
		</div>
        <div class="clearfix"></div>
	 </div>
<?php			
	
endwhile;           
?>      
<div class="clearfix"></div>
</div>
<?php echo nvr_pagination($prop_selection->max_num_pages); ?>
<div class="clearfix"></div><!-- clear float --> 
    
<?php get_footer(); ?>
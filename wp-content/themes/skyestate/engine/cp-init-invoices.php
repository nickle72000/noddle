<?php
// register the custom post type
add_action( 'init', 'create_invoice_type' );
function create_invoice_type() {
register_post_type( 'novaro_invoice',
		array(
			'labels' => array(
				'name'          => __( 'Invoices',THE_LANG),
				'singular_name' => __( 'Invoices',THE_LANG),
				'add_new'       => __('Add New Invoice',THE_LANG),
                'add_new_item'          =>  __('Add Invoice',THE_LANG),
                'edit'                  =>  __('Edit Invoice' ,THE_LANG),
                'edit_item'             =>  __('Edit Invoice',THE_LANG),
                'new_item'              =>  __('New Invoice',THE_LANG),
                'view'                  =>  __('View Invoices',THE_LANG),
                'view_item'             =>  __('View Invoices',THE_LANG),
                'search_items'          =>  __('Search Invoices',THE_LANG),
                'not_found'             =>  __('No Invoices found',THE_LANG),
                'not_found_in_trash'    =>  __('No Invoices found',THE_LANG),
                'parent'                =>  __('Parent Invoice',THE_LANG)
			),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'invoice'),
		'supports' => array('title'),
		'can_export' => true
		)
	);
}

/////////////////////////////////////////////////////////////////////////////////////
/// populate the invoice list with extra columns
/////////////////////////////////////////////////////////////////////////////////////

add_filter( 'manage_edit-novaro_invoice_columns', 'novaro_invoice_my_columns' );

function novaro_invoice_my_columns( $columns ) {
    $slice=array_slice($columns,2,2);
    unset( $columns['comments'] );
    unset( $slice['comments'] );
    $splice=array_splice($columns, 2);   
    $columns['invoice_price']   = __('Price',THE_LANG);
    $columns['invoice_for']     = __('Billing For',THE_LANG);
    $columns['invoice_type']    = __('Invoice Type',THE_LANG);
    $columns['invoice_user']    = __('Purchased by User',THE_LANG);
    return  array_merge($columns,array_reverse($slice));
}



add_action( 'manage_posts_custom_column', 'novaro_invoice_populate_columns' );
function novaro_invoice_populate_columns( $column ) {
     $the_id=get_the_ID();
     if ( 'invoice_price' == $column ) {
        echo get_post_meta($the_id, 'item_price', true);
    } 
    
    if ( 'invoice_for' == $column ) {
         echo get_post_meta($the_id, 'invoice_type', true);
    } 
    
    if ( 'invoice_type' == $column ) {
        echo get_post_meta($the_id, 'biling_type', true);
    }
    
    if ( 'invoice_user' == $column ) {
         $user_id= get_post_meta($the_id, 'buyer_id', true);
         $user_info = get_userdata($user_id);
         echo $user_info->user_login;
    }
   
}




add_filter( 'manage_edit-novaro_invoice_sortable_columns', 'novaro_invoice_sort_me' );
function novaro_invoice_sort_me( $columns ) {
    $columns['invoice_price']   = 'invoice_price';
    $columns['invoice_user']    = 'invoice_user';
    $columns['invoice_for']     = 'invoice_for';
    $columns['invoice_type']    = 'invoice_type';
    return $columns;
}






/////////////////////////////////////////////////////////////////////////////////////
/// insert invoice 
/////////////////////////////////////////////////////////////////////////////////////

 function insert_invoice($billing_for,$type,$pack_id,$date,$user_id,$is_featured,$is_upgrade,$paypal_tax_id){
     
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
    
	 $post = array(
                'post_title'	=> 'Invoice ',
                'post_status'	=> 'publish', 
                'post_type'     => 'novaro_invoice'
            );
     $post_id =  wp_insert_post($post ); 

     
     if($type==2){
         $type='Recurring';
     }else{
         $type='One Time';
     }
     
     $price_submission               =   floatval( nvr_get_option( $nvr_shortname.'_price_submission','') );
     $price_featured_submission      =   floatval( nvr_get_option( $nvr_shortname.'_price_featured_submission','') );
    
     if($billing_for=='Package'){
         $price= get_post_meta($pack_id, 'pack_price', true);
     }else{
         if($is_upgrade==1){
              $price=$price_featured_submission;
         }else{
             if($is_featured==1){
                 $price=$price_featured_submission+$price_submission;
             }else{
                  $price=$price_submission;
             }
         }
        
         
     }
     
     update_post_meta($post_id, 'invoice_type', $billing_for);   
     update_post_meta($post_id, 'biling_type', $type);
     update_post_meta($post_id, 'item_id', $pack_id);
     update_post_meta($post_id, 'item_price',$price);
     update_post_meta($post_id, 'purchase_date', $date);
     update_post_meta($post_id, 'buyer_id', $user_id);
     update_post_meta($post_id, 'txn_id', $paypal_tax_id);
     $my_post = array(
        'ID'           => $post_id,
        'post_title'	=> 'Invoice '.$post_id,
     );
    
     wp_update_post( $my_post );
    
}






if(!function_exists('nvr_invoice_renting_details')){
     function nvr_invoice_renting_details($post){
        global $post;
		$nvr_shortname = THE_SHORTNAME;
		$nvr_initial = THE_INITIAL;
		
        $details                    =   get_post_meta($post->ID, 'renting_details', true);
        $currency                   =   esc_html  ( nvr_get_option( $nvr_shortname.'_submission_curency', '') );
        $where_currency             =   esc_html  ( get_option( $nvr_shortname.'_where_currency_symbol', '') );
        $invoice_status             =   esc_html  ( get_post_meta ( $post->ID, 'invoice_status', true) );
        $depozit_paid               =   floatval   ( get_post_meta ( $post->ID, 'depozit_paid', true) );
        
        
        
        $price = floatval( get_post_meta($post->ID, 'item_price', true) );
        if ($price != 0) {
           $price = number_format($price,2,'.',',');

           if ($where_currency == 'before') {
               $price = $currency . ' ' . $price;
               $depozit_paid= $currency . ' ' . $depozit_paid;
           } else {
                $price = $price . ' ' . $currency;
                $depozit_paid = $depozit_paid . ' ' . $currency;
           }
        }else{
            $price='';
        }
        
       
         
         
        if(is_array($details)){
            foreach($details as $detail){
                print '<div style="padding:5px 0px;"><span style="width:130px;float:left;">'.$detail[0].':</span>'.$detail[1].'</div>';
            }
        }
        
        print '<div><span style="width:130px;float:left;"><strong>'.__('Total',THE_LANG).':</strong></span><strong>'.$price.'</strong></div>'; 
     
        if($invoice_status==='confirmed'){
            print '<div class="invoice_wrapper"><span class="invoiced_paid">'.__('Invoice Paid. Ammount paid: ',THE_LANG).$depozit_paid.'</span></div>';
        }else{
            print '<div class="invoice_wrapper"><span class="invoiced_issued">'.__('Invoice Issued.Not Paid ',THE_LANG).'</span></div>';
        }
        
        
    }

}
?>
<?php
if(!function_exists('nvr_pagination')){
	function nvr_pagination($pages = '', $range = 2){  
		$showitems = ($range * 2)+1;  
		global $paged;
		if(empty($paged)) $paged = 1;
	
		if($pages == '')
		{
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if(!$pages)
			{
				$pages = 1;
			}
		}   
	
		if(1 != $pages)
		{
			echo "<div class='pagination wp-pagenavi'>";
			echo "<a href='".get_pagenum_link($paged - 1)."'>&laquo;</a>";
	
			for ($i=1; $i <= $pages; $i++)
			{
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
				{
					echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
				}
			}
	
			$prev_page= get_pagenum_link($paged + 1);
			if ( ($paged +1) > $pages){
			   $prev_page= get_pagenum_link($paged );
			}else{
				$prev_page= get_pagenum_link($paged + 1);
			}
	
	
			echo "<a href='".$prev_page."'>&raquo; </a>";  
			echo "</div>\n";
		}
	}
}
if(!function_exists('nvr_placeholder_img_src')){
	function nvr_placeholder_img_src() {
		return apply_filters( 'nvr_placeholder_img_src', THE_STYLEURI . 'images/placeholder.png' );
	}
}

if(!function_exists('nvr_json_encode')){
	function nvr_json_encode($value){
		return json_encode($value);
	}
}

if(!function_exists('nvr_filter_title')){
	function nvr_filter_title( $where, &$wp_query ){
		global $wpdb;
		if ( $filter_title = $wp_query->get( 'filter_title' ) ) {
			$where .= ' AND '.$wpdb->posts.'.post_title LIKE \'%'.$wpdb->esc_like( trim( $filter_title ) ).'%\'';
		}
		return $where;
	}
	add_filter( 'posts_where', 'nvr_filter_title', 10, 2 );
}
if(!function_exists('nvr_get_sidebar_position')){
	function nvr_get_sidebar_position($nvr_postid = ''){
		$nvr_shortname = THE_SHORTNAME;
		$nvr_initial = THE_INITIAL;
		
		$nvr_pid = nvr_get_postid();
		$nvr_custom = nvr_get_customdata($nvr_pid);
		
		if($nvr_postid){
			$nvr_custom = nvr_get_customdata($nvr_postid);
		}
		
		$nvr_pagelayoutall = array('one-col','two-col-left','two-col-right');
		
		$nvr_sidebarposition = nvr_get_option( $nvr_shortname . '_sidebar_position' ,'two-col-left'); 
		$nvr_pagelayout = ($nvr_sidebarposition!="")? $nvr_sidebarposition : 'two-col-left';
		if(isset( $nvr_custom['_'.$nvr_initial.'_layout'][0] ) && $nvr_custom['_'.$nvr_initial.'_layout'][0]!='default'){
			$nvr_pagelayout = $nvr_custom['_'.$nvr_initial.'_layout'][0];
		}
		
		if(isset($_GET['sidebar_layout']) && in_array($_GET['sidebar_layout'],$nvr_pagelayoutall)){
			$nvr_pagelayout = esc_html($_GET['sidebar_layout']);
		}
		return $nvr_pagelayout;
	}
}

if(!function_exists('nvr_pf_get_image')){
	function nvr_pf_get_image($nvr_imgsize, $nvr_postid=""){
	
		global $post;
		$nvr_initial = THE_INITIAL;
		$nvr_shortname = THE_SHORTNAME;
		
		if($nvr_postid==""){
			$nvr_postid = get_the_ID();
		}
	
		$nvr_custom = get_post_custom( $nvr_postid );
		$nvr_cf_thumb = (isset($nvr_custom["custom_thumb"][0]))? $nvr_custom["custom_thumb"][0] : "";
		$nvr_cf_externallink = (isset($nvr_custom["external_link"][0]))? $nvr_custom["external_link"][0] : "";
		$nvr_cf_imagegallery	= (isset($nvr_custom[$nvr_initial."_imagesgallery"][0]))? $nvr_custom[$nvr_initial."_imagesgallery"][0] : "";
		
		if(isset($nvr_custom["lightbox_img"])){
			$nvr_checklightbox = $nvr_custom["lightbox_img"] ; 
			$nvr_cf_lightbox = array();
			for($i=0;$i<count($nvr_checklightbox);$i++){
				if($nvr_checklightbox[$i]){
					$nvr_cf_lightbox[] = $nvr_checklightbox[$i];
				}
			}
			if(!count($nvr_cf_lightbox)){
				$nvr_cf_lightbox = "";
			}
		}else{
			$nvr_cf_lightbox = "";
		}
		
		if($nvr_cf_imagegallery!=''){
			$nvr_attachments = $nvr_cf_imagegallery;
			$nvr_attachmentids = explode(",",$nvr_attachments);
			$nvr_qryposts = array(
				'include' => $nvr_attachmentids,
				'post_status' => 'any',
				'post_type' => 'attachment'
			);
			
			$nvr_attachments = get_posts( $nvr_qryposts );
		}else{
			$nvr_qrychildren = array(
				'post_parent' => $nvr_postid ,
				'post_status' => null,
				'post_type' => 'attachment',
				'order_by' => 'menu_order',
				'order' => 'ASC',
				'post_mime_type' => 'image'
			);
		
			$nvr_attachments = get_children( $nvr_qrychildren );
		}
		
		$nvr_cf_thumb2 = array();
		$nvr_cf_full2 = "";
		$z = 1;
		foreach ( $nvr_attachments as $nvr_att_id => $nvr_attachment ) {
			$nvr_att_id = $nvr_attachment->ID;
			$nvr_getimage = wp_get_attachment_image_src($nvr_att_id, $nvr_imgsize, true);
			$nvr_portfolioimage = $nvr_getimage[0];
			$nvr_alttext = get_post_meta( $nvr_attachment->ID , '_wp_attachment_image_alt', true);
			$nvr_image_title = $nvr_attachment->post_title;
			$nvr_caption = $nvr_attachment->post_excerpt;
			$nvr_description = $nvr_attachment->post_content;
			$nvr_cf_thumb2[] ='<img src="'.esc_url( $nvr_portfolioimage ).'" alt="'.esc_attr( $nvr_alttext ).'" title="'. esc_attr( $nvr_image_title ) .'" class="scale-with-grid" />';
			
			$nvr_getfullimage = wp_get_attachment_image_src($nvr_att_id, 'full', true);
			$nvr_fullimage = $nvr_getfullimage[0];
			
			if($z==1){
				$nvr_fullimageurl = $nvr_fullimage;
				$nvr_fullimagetitle = $nvr_image_title;
				$nvr_fullimagealt = $nvr_alttext;
			}elseif($nvr_att_id == get_post_thumbnail_id( $nvr_postid ) ){
				$nvr_cf_full2 ='<a data-rel="prettyPhoto['.esc_attr( $post->post_name ).']" href="'.esc_url( $nvr_fullimageurl ).'" title="'. esc_attr( $nvr_fullimagetitle ) .'" class="hidden"></a>'.$nvr_cf_full2;
				$nvr_fullimageurl = $nvr_fullimage;
				$nvr_fullimagetitle = $nvr_image_title;
				$nvr_fullimagealt = $nvr_alttext;
			}else{
				$nvr_cf_full2 .='<a data-rel="prettyPhoto['.esc_attr( $post->post_name ).']" href="'.esc_url( $nvr_fullimage ).'" title="'. esc_attr( $nvr_image_title ) .'" class="hidden"></a>';
			}
			$z++;
		}
		
		if($nvr_cf_thumb!=""){
			$nvr_cf_thumb = '<img src="' . esc_url( $nvr_cf_thumb ) . '" alt="'. esc_attr( get_the_title($nvr_postid) ) .'"  class="scale-with-grid" />';
		}elseif( has_post_thumbnail( $nvr_postid ) ){
			$nvr_cf_thumb = get_the_post_thumbnail($nvr_postid, $nvr_imgsize, array('class' => 'scale-with-grid'));
		}elseif( isset( $nvr_cf_thumb2[0] ) ){
			$nvr_cf_thumb = $nvr_cf_thumb2[0];
		}else{
			$nvr_cf_thumb = '<span class="nvr-noimage"></span>';
		}
		
		
		if($nvr_cf_externallink!=""){
			$nvr_golink = $nvr_cf_externallink;
			$nvr_rollover = "gotolink";
			$nvr_atext = __('More',THE_LANG);
			$nvr_cf_full2 = '';
		}else{
			$nvr_golink = get_permalink();
			$nvr_rollover = "gotopost";
			$nvr_atext = __('More',THE_LANG);
		}
		
		$nvr_bigimageurl = $nvr_bigimagetitle = $nvr_rel = '';
		if( is_array($nvr_cf_lightbox) ){
			$nvr_bigimageurl = $nvr_cf_lightbox[0];
			$nvr_bigimagetitle = get_the_title();
			$nvr_rel = ' data-rel="prettyPhoto['.$post->post_name.']"';
			$nvr_cf_lightboxoutput = '';
			for($i=1;$i<count($nvr_cf_lightbox);$i++){
				$nvr_cf_lightboxoutput .='<a data-rel="prettyPhoto['.esc_attr( $post->post_name ).']" href="'.esc_url( $nvr_cf_lightbox[$i] ).'" title="'. esc_attr( get_the_title($nvr_postid) ) .'" class="hidden"></a>';
			}
			$nvr_cf_full2 = $nvr_cf_lightboxoutput;
		}else{
			if( isset($nvr_fullimageurl)){
				$nvr_bigimageurl = $nvr_fullimageurl; 
				$nvr_bigimagetitle = $nvr_fullimagetitle;
				$nvr_rel = ' data-rel="prettyPhoto['.esc_attr( $post->post_name ).']"';
			}
		}
		
		$nvr_return = array(
			'nvr_bigimageurl' 	=> $nvr_bigimageurl,
			'nvr_bigimagetitle'	=> $nvr_bigimagetitle,
			'nvr_rel'			=> $nvr_rel,
			'nvr_cf_full2'		=> $nvr_cf_full2,
			'nvr_golink'		=> $nvr_golink,
			'nvr_rollover'		=> $nvr_rollover,
			'nvr_atext'			=> $nvr_atext,
			'nvr_cf_thumb'		=> $nvr_cf_thumb
		);
		return $nvr_return;
	}
}

if(!function_exists('nvr_pf_get_box')){
	function nvr_pf_get_box( $nvr_imgsize, $nvr_postid="",$nvr_class="", $nvr_limitchar = 250 ){
	
		$nvr_output = "";
		global $post;
		
		if($nvr_postid==""){
			$nvr_postid = get_the_ID();
		}
		$nvr_taxonomy_slug = 'portfoliocat';
		
		$nvr_get_image = nvr_pf_get_image($nvr_imgsize, $nvr_postid );
		extract($nvr_get_image);
		
		$nvr_output  .='<li class="'.esc_attr( $nvr_class ).'">';
			$nvr_output  .='<div class="nvr-pf-box">';
				$nvr_output  .='<div class="nvr-pf-img">';
					
					$nvr_output .='<a class="image '.esc_attr( $nvr_rollover ).'" href="'.esc_url( $nvr_golink ).'" title="'.esc_attr( get_the_title($nvr_postid) ).'"></a>';
					if($nvr_bigimageurl!=''){
						$nvr_output .='<a class="image zoom" href="'. esc_url( $nvr_bigimageurl ) .'" '.$nvr_rel.' title="'.esc_attr( $nvr_bigimagetitle ).'"></a>';
					}
					
					$nvr_output  .=$nvr_cf_thumb;
					$nvr_output  .=$nvr_cf_full2;
				$nvr_output  .='</div>';
		
				$nvr_excerpt = nvr_string_limit_char( get_the_excerpt(), $nvr_limitchar );
				$nvr_output  .='<div class="nvr-pf-text">';
				
					$nvr_output  .='<h2 class="nvr-pf-title"><a href="'.esc_url( get_permalink($nvr_postid) ).'" title="'.esc_attr( get_the_title($nvr_postid) ).'">'.get_the_title($nvr_postid).'</a></h2>';
					 // get the terms related to post
					$nvr_terms = get_the_terms( $nvr_postid, $nvr_taxonomy_slug );
					$nvr_termarr = array();
					if ( !empty( $nvr_terms ) ) {
					  foreach ( $nvr_terms as $nvr_term ) {
						$nvr_termarr[] = '<a href="'. esc_url( get_term_link( $nvr_term->slug, $nvr_taxonomy_slug ) ).'">'. $nvr_term->name ."</a>";
					  }
					  
					  $nvr_output .= '<div class="nvr-pf-cat">'.implode(", ", $nvr_termarr).'</div>';
					}
					$nvr_output .= '<div class="nvr-pf-separator"></div>';
					$nvr_output .= '<div class="nvr-pf-content">'.$nvr_excerpt.'</div>';
					
				$nvr_output  .='</div>';
				$nvr_output  .='<div class="nvr-pf-clear"></div>';
			$nvr_output  .='</div>';
		$nvr_output  .='</li>';
		
		return $nvr_output; 
	}
}

if( !function_exists('nvr_section_builder') ){
	function nvr_section_builder($nvr_sectionbuilders){

		if(isset($nvr_sectionbuilders) && is_array($nvr_sectionbuilders) && count($nvr_sectionbuilders)>0){ 
			$i = 1;
			foreach($nvr_sectionbuilders as $nvr_sectionbuilder){ 
				
				$nvr_sectionbgcolor = (isset($nvr_sectionbuilder['backgroundcolor']))? $nvr_sectionbuilder['backgroundcolor'] : '';
				$nvr_sectionbgimage = (isset($nvr_sectionbuilder['backgroundimage']))? $nvr_sectionbuilder['backgroundimage'] : '';
				$nvr_sectionclass   = (isset($nvr_sectionbuilder['customclass']))? $nvr_sectionbuilder['customclass'] : '';
				$nvr_sectioncontent = (isset($nvr_sectionbuilder['content']))? $nvr_sectionbuilder['content'] : '';
				
				if($nvr_sectioncontent==''){ continue; }
				
				$nvr_sectionstyle = '';
				if($nvr_sectionbgcolor!='' || $nvr_sectionbgimage!=''){
						if($nvr_sectionbgcolor!=''){
							$nvr_sectionstyle .= 'background-color:'.$nvr_sectionbgcolor.'; ';
						}
						if($nvr_sectionbgimage!=''){
							$nvr_sectionstyle .= 'background-image:url('.esc_url( $nvr_sectionbgimage ).'); ';
						}
						$nvr_sectionstyle .= 'background-repeat:no-repeat';
						$nvr_sectionstyle .= 'background-position:center';
					$nvr_sectionstyle = 'style="'.esc_attr( $nvr_sectionstyle ).'"';
				}
			?>
				<section id="outersection_<?php echo esc_attr( $i ); ?>" class="outersection <?php echo esc_attr( $nvr_sectionclass ); ?>" <?php echo $nvr_sectionstyle; ?>>
					<div class="container">
						<section id="innersection_<?php echo esc_attr( $i ); ?>" class="row innersection">
							<div class="sectioncontent twelve columns">
								<?php echo do_shortcode($nvr_sectioncontent); ?>
								<div class="clearfix"></div>
							</div>
							<div class="clearfix"></div>
						</section>
					</div>
				</section>
			<?php 
				$i++;
			}//end foreach 
		}
	}
}

/*Template for comments and pingbacks. */
if ( ! function_exists( 'nvr_comment' ) ) :
function nvr_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="con-comment">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 65 ); ?>
		</div><!-- .comment-author .vcard -->


		<div class="comment-body">
			<?php  printf( __( '<cite class="fn">%s</cite>', THE_LANG ), sprintf( '%s Says:', get_comment_author_link() ) ); ?>
            <span class="time">
            <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
            <?php
                /* translators: 1: date, 2: time */
                printf( __( '%1$s %2$s', THE_LANG ), get_comment_date(),  get_comment_time() ); ?></a>
                <?php edit_comment_link( __( '(Edit)', THE_LANG ), ' ' );?>
            </span>
            
            <div class="clear"></div>
			<div class="commenttext">
			<?php comment_text(); ?>
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em><?php _e( 'Your comment is awaiting moderation.', THE_LANG ); ?></em>
			<?php endif; ?>
			</div>
            <span class="reply"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ,'reply_text' => 'Reply') ) ); ?></span>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
			echo '<li class="post pingback">';
				echo '<p>'. __( 'Pingback:', THE_LANG ).' ';
					comment_author_link();
					edit_comment_link( __('(Edit)', THE_LANG), ' ' );
				echo '</p>';
				
			break;
	endswitch;
}
endif;

if ( ! function_exists( 'nvr_share_button_output' ) ) :

function nvr_share_button_output() {
	if(function_exists('ssba_buttons')){
	echo '<div class="sharebutton-container">';
		echo do_shortcode('[ssba]');
	echo '</div>';
	}
}
add_action('nvr_share_button','nvr_share_button_output',1);
endif;


/*** PROPERTY CUSTOM FUNCTIONS ***/
if(!function_exists('nvr_get_propsearch_page')){
	function nvr_get_propsearch_page(){
		$nvr_searchpages = get_pages(array(
			'hierarchical' => '0',
			'meta_key' => '_wp_page_template',
			'meta_value' => 'template-property-search-result.php'
		));
		
		if( $nvr_searchpages ){
			$nvr_adv_submit = get_permalink( $nvr_searchpages[0]->ID );
			$nvr_page_id = $nvr_searchpages[0]->ID;
		}else{
			$nvr_adv_submit = home_url('/');
			$nvr_page_id = get_the_ID();
		}
		$nvr_return = array(
			'submiturl' => $nvr_adv_submit,
			'pageid'	=> $nvr_page_id
		);
		return $nvr_return;
	}
}

if(!function_exists('nvr_prop_get_image')){
	function nvr_prop_get_image($nvr_imgsize, $nvr_postid=""){
	
		global $post;
		if($nvr_postid==""){
			$nvr_postid = get_the_ID();
		}
	
		$nvr_custom = get_post_custom( $nvr_postid );
		$nvr_cf_thumb = (isset($nvr_custom["custom_thumb"][0]))? $nvr_custom["custom_thumb"][0] : "";
		$nvr_cf_externallink = (isset($nvr_custom["external_link"][0]))? $nvr_custom["external_link"][0] : "";
		
		
		/*get recent-portfolio-post-thumbnail*/
		$nvr_qrychildren = array(
			'post_parent' => $nvr_postid ,
			'post_status' => null,
			'post_type' => 'attachment',
			'order_by' => 'menu_order',
			'order' => 'ASC',
			'post_mime_type' => 'image'
		);
	
		$nvr_attachments = get_children( $nvr_qrychildren );
		
		$nvr_cf_thumb2 = array();
		$nvr_cf_full2 = "";
		$z = 1;
		foreach ( $nvr_attachments as $nvr_att_id => $nvr_attachment ) {
			$nvr_getimage = wp_get_attachment_image_src($nvr_att_id, $nvr_imgsize, true);
			$nvr_portfolioimage = $nvr_getimage[0];
			$nvr_alttext = get_post_meta( $nvr_attachment->ID , '_wp_attachment_image_alt', true);
			$nvr_image_title = $nvr_attachment->post_title;
			$nvr_caption = $nvr_attachment->post_excerpt;
			$nvr_description = $nvr_attachment->post_content;
			$nvr_cf_thumb2[] ='<img src="'.esc_url( $nvr_portfolioimage ).'" alt="'.esc_attr( $nvr_alttext ).'" title="'. esc_attr( $nvr_image_title ) .'" class="scale-with-grid" />';
			
			$nvr_getfullimage = wp_get_attachment_image_src($nvr_att_id, 'full', true);
			$nvr_fullimage = $nvr_getfullimage[0];
			
			if($z==1){
				$nvr_fullimageurl = $nvr_fullimage;
				$nvr_fullimagetitle = $nvr_image_title;
				$nvr_fullimagealt = $nvr_alttext;
			}elseif($nvr_att_id == get_post_thumbnail_id( $nvr_postid ) ){
				$nvr_cf_full2 ='<a data-rel="prettyPhoto['.esc_attr( $post->post_name ).']" href="'.esc_url( $nvr_fullimageurl ).'" title="'. esc_attr( $nvr_fullimagetitle ) .'" class="hidden"></a>'.$nvr_cf_full2;
				$nvr_fullimageurl = $nvr_fullimage;
				$nvr_fullimagetitle = $nvr_image_title;
				$nvr_fullimagealt = $nvr_alttext;
			}else{
				$nvr_cf_full2 .='<a data-rel="prettyPhoto['.esc_attr( $post->post_name ).']" href="'.esc_url( $nvr_fullimage ).'" title="'. esc_attr( $nvr_image_title ) .'" class="hidden"></a>';
			}
			$z++;
		}
		
		if($nvr_cf_thumb!=""){
			$nvr_cf_thumb = '<img src="' . esc_url( $nvr_cf_thumb ) . '" alt="'. esc_attr( get_the_title($nvr_postid) ) .'"  class="scale-with-grid" />';
		}elseif( has_post_thumbnail( $nvr_postid ) ){
			$nvr_cf_thumb = get_the_post_thumbnail($nvr_postid, $nvr_imgsize, array('class' => 'scale-with-grid'));
		}elseif( isset( $nvr_cf_thumb2[0] ) ){
			$nvr_cf_thumb = $nvr_cf_thumb2[0];
		}else{
			$nvr_cf_thumb = '<span class="nvr-noimage"></span>';
		}
		
		
		if($nvr_cf_externallink!=""){
			$nvr_golink = $nvr_cf_externallink;
			$nvr_rollover = "gotolink";
			$nvr_atext = __('More',THE_LANG);
			$nvr_cf_full2 = '';
		}else{
			$nvr_golink = get_permalink();
			$nvr_rollover = "gotopost";
			$nvr_atext = __('More',THE_LANG);
		}
		
		$nvr_bigimageurl = $nvr_bigimagetitle = $nvr_rel = '';
		if( isset($nvr_fullimageurl)){
			$nvr_bigimageurl = $nvr_fullimageurl;
			$nvr_bigimagetitle = $nvr_fullimagetitle;
			$nvr_rel = ' data-rel="prettyPhoto['.esc_attr( $post->post_name ).']"';
		}
		
		$nvr_return = array(
			'nvr_bigimageurl' 	=> $nvr_bigimageurl,
			'nvr_bigimagetitle'	=> $nvr_bigimagetitle,
			'nvr_rel'			=> $nvr_rel,
			'nvr_cf_full2'		=> $nvr_cf_full2,
			'nvr_golink'		=> $nvr_golink,
			'nvr_rollover'		=> $nvr_rollover,
			'nvr_atext'			=> $nvr_atext,
			'nvr_cf_thumb'		=> $nvr_cf_thumb
		);
		return $nvr_return;
	}
}

if(!function_exists('nvr_prop_get_box')){
	function nvr_prop_get_box( $nvr_imgsize, $nvr_postid="",$nvr_class="", $nvr_unit = '', $nvr_cursymbol = '', $nvr_curplace = '' ){
		
		$nvr_initial = THE_INITIAL;
		$nvr_output = "";
		global $post;
		
		if($nvr_postid==""){
			$nvr_postid = get_the_ID();
		}
		
		$nvr_custom = nvr_get_customdata($nvr_postid);
		$nvr_price = (isset($nvr_custom[$nvr_initial."_price"][0]))? $nvr_custom[$nvr_initial."_price"][0] : '';
		$nvr_plabel = (isset($nvr_custom[$nvr_initial."_price_label"][0]))? $nvr_custom[$nvr_initial."_price_label"][0] : '';
		$nvr_bed = (isset($nvr_custom[$nvr_initial."_room"][0]))? $nvr_custom[$nvr_initial."_room"][0] : '';
		$nvr_bath = (isset($nvr_custom[$nvr_initial."_bathroom"][0]))? $nvr_custom[$nvr_initial."_bathroom"][0] : '';
		$nvr_size = (isset($nvr_custom[$nvr_initial."_size"][0]))? $nvr_custom[$nvr_initial."_size"][0] : '';
		
		$nvr_address = (isset($nvr_custom[$nvr_initial."_address"][0]))? $nvr_custom[$nvr_initial."_address"][0] : '';
		$nvr_state = (isset($nvr_custom[$nvr_initial."_state"][0]))? $nvr_custom[$nvr_initial."_state"][0] : '';
		$nvr_country = (isset($nvr_custom[$nvr_initial."_country"][0]))? $nvr_custom[$nvr_initial."_country"][0] : '';
			
		$nvr_prop_cat = 'property_category';
		$nvr_categories = get_the_terms( $nvr_postid, $nvr_prop_cat );
		$nvr_categoryarr = array();
		if ( !empty( $nvr_categories ) ) {
			foreach ( $nvr_categories as $nvr_category ) {
				$nvr_categoryarr[] = $nvr_category->name;
			}
		  
			$nvr_type = implode(', ', $nvr_categoryarr);
		}else{
			$nvr_type = '';
		}
		
		$nvr_get_image = nvr_prop_get_image($nvr_imgsize, $nvr_postid );
		extract($nvr_get_image);
		
		$nvr_prop_purpose = 'property_purpose';
		$nvr_upper_meta = '';
		$nvr_purposes = get_the_terms( $nvr_postid, $nvr_prop_purpose );
		$nvr_purposearr = array();
		if ( !empty( $nvr_purposes ) ) {
			foreach ( $nvr_purposes as $nvr_purpose ) {
				$nvr_purposearr[] = $nvr_purpose->name;
			}
		  
			$nvr_upper_meta .= '<span class="meta-purpose">'.implode(", ", $nvr_purposearr).'</span>';
		}
		
		$nvr_prop_city = 'property_city';
		$nvr_cities = get_the_terms( $nvr_postid, $nvr_prop_city );
		$nvr_cityarr = array();
		if ( !empty( $nvr_cities ) ) {
			foreach ( $nvr_cities as $nvr_city ) {
				$nvr_cityarr[] = $nvr_city->name;
			}
		}
		
		$nvr_city = implode(', ', $nvr_cityarr);
		
		if(!empty($nvr_price)){
			$nvr_upper_meta .= '<span class="meta-price">'.nvr_show_price($nvr_price, $nvr_cursymbol, $nvr_curplace).' '.$nvr_plabel.'</span>';
		}
		
		$nvr_output  .='<li class="prop-item-container '.esc_attr( $nvr_class ).'">';
			$nvr_output  .='<div class="nvr-prop-box">';
				$nvr_output  .='<div class="nvr-prop-img">';

					if($nvr_upper_meta!=''){
						$nvr_output .= '<div class="nvr-upper-meta">'.$nvr_upper_meta.'</div>';
					}
					
					$nvr_output .='<a class="image '.esc_attr( $nvr_rollover ).'" href="'.esc_url( $nvr_golink ).'" title="'.esc_attr( get_the_title($nvr_postid) ).'"></a>';
					if($nvr_bigimageurl!=''){
						$nvr_output .='<a class="image zoom" href="'. esc_url( $nvr_bigimageurl ) .'" '.$nvr_rel.' title="'.esc_attr( $nvr_bigimagetitle ).'"></a>';
					}
					
					$nvr_output  .=$nvr_cf_thumb;
					$nvr_output  .=$nvr_cf_full2;
				$nvr_output  .='</div>';
		
				$nvr_output  .='<div class="nvr-prop-text">';
				
					$nvr_output	.='<h2 class="nvr-prop-title"><a href="'.esc_url( get_permalink($nvr_postid) ).'" title="'.esc_attr( get_the_title($nvr_postid) ).'">'.get_the_title($nvr_postid).'</a></h2>';
					$nvr_output_addr = nvr_string_limit_char($nvr_address.' '.$nvr_city.' '.$nvr_state.' '.$nvr_country, 40);
					$nvr_output	.= '<div class="nvr-prop-address"><i class="fa fa-map-marker"></i> '.$nvr_output_addr.'</div>';
					$nvr_output  .='<div class="clearfix"></div>';
				$nvr_output  .='</div>';
				// get the terms related to post
				$nvr_output_meta = '';
				if ( !empty( $nvr_type ) ) {
					$nvr_output_meta .= '<span class="nvr-prop-type"><i class="fa fa-building"></i>&nbsp; '. $nvr_type .'</span>';
				}
				if ( !empty( $nvr_size ) ) {
					$nvr_output_meta .= '<span class="nvr-prop-size"><i class="fa fa-expand"></i>&nbsp; '.$nvr_size.' '. $nvr_unit .'</span>';
				}
				if ( !empty( $nvr_bed ) ) {
					$nvr_output_meta .= '<span class="nvr-prop-bed"><i class="fa fa-inbox"></i>&nbsp; '.$nvr_bed.' '.__('Bed', THE_LANG).'</span>';
				}
				if ( !empty( $nvr_bath ) ) {
					$nvr_output_meta .= '<span class="nvr-prop-bath"><i class="fa fa-tint"></i>&nbsp; '.$nvr_bath.' '.__('Bath', THE_LANG).'</span>';
				}
				
				if(!empty($nvr_output_meta)){
					$nvr_output .= '<div class="nvr-prop-meta">';
						$nvr_output .= $nvr_output_meta;
					$nvr_output .= '<div>';
				}
				$nvr_output  .='<div class="clearfix"></div>';
			$nvr_output  .='</div>';
		$nvr_output  .='</li>';
		
		return $nvr_output; 
	}
}

if(!function_exists('nvr_show_price')){
	function nvr_show_price($nvr_price=0, $nvr_cursymbol='', $nvr_curplace='' ){
		$nvr_shortname = THE_SHORTNAME;
		
		$nvr_numformat = number_format($nvr_price,0,'.',',');
		if($nvr_cursymbol==''){
			$nvr_cursymbol = nvr_get_option( $nvr_shortname . '_currency_symbol');
		}
		if($nvr_curplace==''){
			$nvr_curplace = nvr_get_option( $nvr_shortname . '_currency_place');
		}
		
		if($nvr_curplace=='before'){
			$nvr_return = $nvr_cursymbol . $nvr_numformat;
		}else{
			$nvr_return = $nvr_numformat . $nvr_cursymbol;
		}
		return $nvr_return;
	}
}

if(!function_exists('nvr_property_mapquery')){
	function nvr_property_mapquery($nvr_queryfor='', $nvr_postids=''){
		$nvr_initial = THE_INITIAL;
		$nvr_shortname = THE_SHORTNAME;
		
		$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		$nvr_paged = $paged;
		
		$nvr_query_args = array(
			'post_type'         =>  'propertys'
		);
		
		if(isset($nvr_paged)){
			$nvr_query_args['paged'] = $nvr_paged;
		}
		
		
		if($nvr_queryfor=='specific' && $nvr_postids!=''){
			if(is_array($nvr_postids)){
				$nvr_query_args['post__in'] = $nvr_postids;
			}else{
				$nvr_arrids = explode(",",$nvr_postids);
				$nvr_query_args['post__in'] = $nvr_arrids;
			}
		}elseif($nvr_queryfor=='property' || is_singular('propertys')){
			$postid = get_the_ID();
			$nvr_query_args['p'] = $postid;
			$nvr_query_args['nopaging'] = 'true';
		}elseif($nvr_queryfor=='agent' || is_singular('peoplepost')){
			$peopleinfo = get_post(get_the_ID());
			if(!$peopleinfo){
				$nvr_query_args['p'] = 0;
			}else{
				$peopleslug = $peopleinfo->post_name;
				$nvr_compare_meta['key']        = $nvr_initial.'_agent';
				$nvr_compare_meta['value']      = $peopleslug;
				$nvr_meta_query[]				= $nvr_compare_meta;
				
				$nvr_query_args['meta_query'] = $nvr_meta_query;
				$nvr_query_args['nopaging'] = 'true';
			}
		}elseif($nvr_queryfor=='search' || is_page_template('template-property-search-result.php')){
			
			$nvr_taxquery = $nvr_metaquery = array();
			
			$filter_purpose = isset($_REQUEST['adv_filter_purpose'])? $_REQUEST['adv_filter_purpose'] : '';
			if($filter_purpose!=''){
				
				$nvr_purposeval = sanitize_title($filter_purpose);
				$nvr_purposearr = array($nvr_purposeval);
				
				$nvr_taxquery[] = array(
					'taxonomy' 	=> 'property_purpose',
					'field'		=> 'slug',
					'terms'		=> $nvr_purposearr
				);
			}
			
			$filter_type = isset($_REQUEST['adv_filter_type'])? $_REQUEST['adv_filter_type'] : '';
			if($filter_type!=''){
				
				$nvr_typeval = sanitize_title($filter_type);
				$nvr_typearr = array($nvr_typeval);
				
				$nvr_taxquery[] = array(
					'taxonomy' 	=> 'property_category',
					'field'		=> 'slug',
					'terms'		=> $nvr_typearr
				);
			}
			
			$filter_city = isset($_REQUEST['adv_filter_city'])? $_REQUEST['adv_filter_city'] : '';
			if($filter_city!=''){
				
				$nvr_cityval = sanitize_title($filter_city);
				$nvr_cityarr = array($nvr_cityval);
				
				$nvr_taxquery[] = array(
					'taxonomy' 	=> 'property_city',
					'field'		=> 'slug',
					'terms'		=> $nvr_cityarr
				);
			}
			
			$filter_keywords = isset($_REQUEST['adv_filter_keywords'])? $_REQUEST['adv_filter_keywords'] : '';
			if($filter_keywords!=''){
				
				$nvr_keywordsval = sanitize_text_field($filter_keywords);
				
				$nvr_query_args['filter_title'] = $nvr_keywordsval;
			}
			
			
			
			$filter_status = isset($_REQUEST['adv_filter_status'])? $_REQUEST['adv_filter_status'] : '';
			if($filter_status!=''){
				
				$nvr_statusval = sanitize_text_field($filter_status);
				
				$nvr_metaquery[] = array(
					'key'		=> $nvr_initial.'_status',
					'value'		=> $nvr_statusval
				);
			}
			
			$filter_numroom = isset($_REQUEST['adv_filter_numroom'])? $_REQUEST['adv_filter_numroom'] : '';
			if(is_numeric($filter_numroom) && $filter_numroom>=0){
				
				$nvr_numroom = $filter_numroom;
				
				$nvr_metaquery[] = array(
					'key'		=> $nvr_initial.'_room',
					'value'		=> $nvr_numroom,
					'type'		=> 'DECIMAL',
					'compare'	=> '='
				);
			}
			
			$filter_numbath = isset($_REQUEST['adv_filter_numbath'])? $_REQUEST['adv_filter_numbath'] : '';
			if($filter_numbath>=0){
				
				$nvr_filter_bath = str_replace(",",".", $filter_numbath);
				
				if(is_numeric($nvr_filter_bath)){
				
					$nvr_metaquery[] = array(
						'key'		=> $nvr_initial.'_bathroom',
						'value'		=> $nvr_filter_bath,
						'type'		=> 'DECIMAL',
						'compare'	=> '='
					);
				}
			}
			
			$filter_ammenities = isset($_REQUEST['adv_filter_ammenity'])? $_REQUEST['adv_filter_ammenity'] : '';
			if($filter_ammenities!=''){

				if(count($filter_ammenities)>0){
					$ammenityquery = array( 'relation' => 'AND');
					foreach($filter_ammenities as $ammenity){
						$ammenityquery[] = array(
							'key'		=> $nvr_initial.'_amenities',
							'value'		=> $ammenity,
							'compare'	=> 'like'
						);
					}
					$nvr_metaquery[] = $ammenityquery;
				}
			}
			
			$nvr_pricemin = $nvr_pricemax = -1;
			
			$filter_price_min = isset($_REQUEST['adv_filter_price_min'])? $_REQUEST['adv_filter_price_min'] : '';
			if(is_numeric($filter_price_min)){
				$nvr_pricemin = $filter_price_min;
			}
			
			$filter_price_max = isset($_REQUEST['adv_filter_price_max'])? $_REQUEST['adv_filter_price_max'] : '';
			if(is_numeric($filter_price_max )){
				$nvr_pricemax = $filter_price_max;
			}
			
			if($nvr_pricemin>=0 && $nvr_pricemax>=0){
				$nvr_metaquery[] = array(
					'key'		=> $nvr_initial.'_price',
					'value'		=> array($nvr_pricemin, $nvr_pricemax),
					'type'		=> 'numeric',
					'compare'	=> 'BETWEEN'
				);
			}
			
			if(count($nvr_taxquery)){
				$nvr_query_args['tax_query'] = $nvr_taxquery;
			}
			
			if(count($nvr_metaquery)){
				$nvr_query_args['meta_query'] = $nvr_metaquery;
			}
			
		}else{
			$nvr_pid = get_the_ID();
			$nvr_custom = nvr_get_customdata($nvr_pid);
			$nvr_cf_numpins = (isset($nvr_custom["slider_numpins"][0]))? $nvr_custom["slider_numpins"][0] : get_option('posts_per_page');
			if(!is_numeric($nvr_cf_numpins)){
				$nvr_cf_numpins = 12;
			}
			$nvr_compare_meta['key']        = $nvr_initial.'_latitude';
			$nvr_compare_meta['value']      = '';
			$nvr_compare_meta['type']       = 'CHAR';
			$nvr_compare_meta['compare']    = '!=';
			$nvr_meta_query[]				= $nvr_compare_meta;
		
			$nvr_compare_meta['key']        = $nvr_initial.'_longitude';
			$nvr_compare_meta['value']      = '';
			$nvr_compare_meta['type']       = 'CHAR';
			$nvr_compare_meta['compare']    = '!=';
			$nvr_meta_query[]   			= $nvr_compare_meta;
			
			$nvr_query_args['meta_query'] = $nvr_meta_query;
			$nvr_query_args['posts_per_page'] = $nvr_cf_numpins;
		}
		
		return $nvr_query_args;
	}
}

if(!function_exists('nvr_property_getdata')){
	function nvr_property_getdata($nvr_postid='', $nvr_unit='', $nvr_cursymbol='', $nvr_curplace='', $nvr_option=''){
		
		if($nvr_postid==''){return false;}
		
		$nvr_initial = THE_INITIAL;
		$nvr_shortname = THE_SHORTNAME;
		
		if($nvr_option==''){
			$nvr_option = get_option('property_category_thumbnail_id');
		}
		
		$pid = $nvr_postid;
				
		$nvr_custom = nvr_get_customdata($pid);
		$nvr_lat = (isset($nvr_custom[$nvr_initial."_latitude"][0]))? $nvr_custom[$nvr_initial."_latitude"][0] : '';
		$nvr_long = (isset($nvr_custom[$nvr_initial."_longitude"][0]))? $nvr_custom[$nvr_initial."_longitude"][0] : '';
		
		$nvr_price = (isset($nvr_custom[$nvr_initial."_price"][0]))? $nvr_custom[$nvr_initial."_price"][0] : '';
		$nvr_pricelabel = (isset($nvr_custom[$nvr_initial."_price_label"][0]))? $nvr_custom[$nvr_initial."_price_label"][0] : '';
		
		$nvr_bed = (isset($nvr_custom[$nvr_initial."_room"][0]))? $nvr_custom[$nvr_initial."_room"][0] : '';
		$nvr_bath = (isset($nvr_custom[$nvr_initial."_bathroom"][0]))? $nvr_custom[$nvr_initial."_bathroom"][0] : '';
		$nvr_size = (isset($nvr_custom[$nvr_initial."_size"][0]))? $nvr_custom[$nvr_initial."_size"][0] : '';
		
		$nvr_address = (isset($nvr_custom[$nvr_initial."_address"][0]))? $nvr_custom[$nvr_initial."_address"][0] : '';
		$nvr_state = (isset($nvr_custom[$nvr_initial."_state"][0]))? $nvr_custom[$nvr_initial."_state"][0] : '';
		$nvr_country = (isset($nvr_custom[$nvr_initial."_country"][0]))? $nvr_custom[$nvr_initial."_country"][0] : '';

		$prop_slug 		= array();
		$prop_category	= array();
		$prop_purpose	= array();
		$prop_cats		= get_the_terms($pid,'property_category');
		$prop_purp		= get_the_terms($pid,'property_purpose');
		$prop_city		= get_the_terms($pid,'property_city');
		
		if($prop_cats && !is_wp_error($prop_cats)){
			
			$the_category = $the_catid = '';
			$i=0;
			foreach($prop_cats as $prop_cat){
				$prop_category[] = $prop_cat->slug;
				$slug = $prop_cat->slug;
				$i++;
				if($i==1){
					$the_category = $slug;
					$the_catid = $prop_cat->term_id;
				}
			}
			
		}else{
			$the_category = '';
		}
		
		if($prop_purp && !is_wp_error($prop_purp)){
			
			$the_purpose = '';
			$i=0;
			foreach($prop_purp as $purpose){
				$prop_purpose[] = $purpose->slug;
				$slug = $purpose->slug;
				$i++;
				if($i==1){
					$the_purpose = $slug;
				}
			}
			
		}else{
			$the_purpose = '';
		}
		
		if($prop_city && !is_wp_error($prop_city)){
			
			$the_city = '';
			$i=0;
			foreach($prop_city as $city){
				$prop_city[] = $city->slug;
				$slug = $city->slug;
				$i++;
				$the_city = $slug;
			}
			
		}else{
			$the_city = '';
		}
		
		$nvr_pin = '';
		
		$nvr_thumbnail_id = ( $nvr_option && isset( $nvr_option[$the_catid] ) ) ? $nvr_option[$the_catid] : '';

		if ($nvr_thumbnail_id){
			$nvr_pin = wp_get_attachment_thumb_url( $nvr_thumbnail_id );
		}
		$nvr_price = nvr_show_price($nvr_price,$nvr_cursymbol,$nvr_curplace);
		
		$prop_markers=array();

		$prop_markers['title']	= urlencode( get_the_title() );
		$prop_markers['lat']    = $nvr_lat;
		$prop_markers['long']	= $nvr_long;
		$prop_markers['thumb']	= get_the_post_thumbnail($pid, 'property-image');
		$prop_markers['price']	= $nvr_price.' '.$nvr_pricelabel;
		$prop_markers['cat'] 	= $the_category;
		$prop_markers['purpose']= $the_purpose;
		$prop_markers['pin']	= $nvr_pin;
		$prop_markers['link']	= get_permalink();
		$prop_markers['id']		= $pid;
		$prop_markers['bed']	= $nvr_bed;
		$prop_markers['bath']	= $nvr_bath;
		$prop_markers['size']	= $nvr_size.' '.$nvr_unit;
		$prop_markers['address']= $nvr_address;
		$prop_markers['city']	= $the_city;
		$prop_markers['state']	= $nvr_state;
		$prop_markers['country']= $nvr_country;
		
		return $prop_markers;
	}
}
if(!function_exists('nvr_property_Latlng')){
	function nvr_property_latlng($nvr_query=''){
	
		$nvr_initial = THE_INITIAL;
		$nvr_shortname = THE_SHORTNAME;
		
		$nvr_unit = nvr_get_option($nvr_shortname.'_measurement_unit');
		$nvr_cursymbol = nvr_get_option( $nvr_shortname . '_currency_symbol');
		$nvr_curplace = nvr_get_option( $nvr_shortname . '_currency_place');
		
		$nvr_cache = false;
		$nvr_markers = array();
		$nvr_place_markers = $nvr_markers = array();
		
		if($nvr_query==''){
			$nvr_query_args = nvr_property_mapquery();
		}else{
			$nvr_query_args = $nvr_query;
		}
		
		if($nvr_cache=='yes'){
			if(!get_transient('cache_property_list')) { 
	
				$property_lists = new WP_Query($nvr_query_args);
				set_transient('cache_property_list', $property_lists, HOUR_IN_SECONDS * 2);
				
			}else{
			
				$property_lists = get_transient('cache_property_list');
				
			}
			wp_reset_query(); 
		}
		else{  

			$property_lists = new WP_Query($nvr_query_args);
			wp_reset_query(); 
			
		}
		
		$nvr_option = get_option('property_category_thumbnail_id');
		
		$counter=0;
		if($property_lists->have_posts()){
			while($property_lists->have_posts()): $property_lists->the_post();
				
				$counter++;
				$pid = get_the_ID();
				$prop_markers = nvr_property_getdata($pid, $nvr_unit, $nvr_cursymbol, $nvr_curplace, $nvr_option);
				
				$nvr_markers[] = $prop_markers;
			endwhile;
		}else{
			$nvr_markers = false;
		}
        wp_reset_query();

		return $nvr_markers;
	}
}

/*** WOOCOMMERCE CUSTOM FUNCTIONS ***/
if(!function_exists('nvr_productbox') && function_exists('is_woocommerce')){
	function nvr_productbox($postobj){
		
		global $post, $product, $woocommerce;
		
		setup_postdata($postobj);
		
		$licontent = "";
		ob_start();
		woocommerce_get_template_part( 'content', 'product' );
		$licontent .= ob_get_contents();
		ob_end_clean();
		
		
		return $licontent;
	}
}
if(!function_exists('nvr_productquery')){
	function nvr_productquery($number=12, $type, $cat=''){
		global $woocommerce;
		
		if(!function_exists('is_woocommerce')){ return false; }
		
		if(!is_numeric($number) || $number < 1){
			$number = 12;
		}
		
		if($type == 'featured'){
			/**********QUERY FOR FEATURED PRODUCT**********/
			$query_args = array('posts_per_page' => $number, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product' );
			
			$query_args['meta_query'] = array();
		
			$query_args['meta_query'][] = array(
				'key' => '_featured',
				'value' => 'yes'
			);
			$query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
			$query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
			/**********END QUERY FOR FEATURED PRODUCT**********/
		}elseif($type == 'top-rated'){
			/**********QUERY FOR TOP-RATED PRODUCT**********/
			add_filter( 'posts_clauses',  array( $woocommerce->query, 'order_by_rating_post_clauses' ) );
		
			$query_args = array('posts_per_page' => $number, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product' );
		
			$query_args['meta_query'] = array();
		
			$query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
			$query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
			/**********END QUERY FOR TOP-RATED PRODUCT**********/
		}elseif($type == 'best-selling'){
			/**********QUERY FOR BEST SELLING PRODUCT**********/
			$query_args = array(
				'posts_per_page' => $number,
				'post_status' 	 => 'publish',
				'post_type' 	 => 'product',
				'meta_key' 		 => 'total_sales',
				'orderby' 		 => 'meta_value_num',
				'no_found_rows'  => 1,
			);
		
			$query_args['meta_query'] = array();
		
			if ( isset( $instance['hide_free'] ) && 1 == $instance['hide_free'] ) {
				$query_args['meta_query'][] = array(
					'key'     => '_price',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'DECIMAL',
				);
			}
		
			$query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
			$query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
			/**********END QUERY FOR BEST SELLING PRODUCT**********/
		}elseif($type == 'latest'){
			$query_args = array('posts_per_page' => $number, 'no_found_rows' => 1, 'orderby' => 'date', 'order' => 'DESC', 'post_status' => 'publish', 'post_type' => 'product' );
		}
		
		if($cat){
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => $cat
				)
			);
		}
		
		return $query_args;
	}
}

/*********QUICK VIEW PRODUCT**********/
add_action("wp_ajax_nvr_quickviewproduct", "nvr_quickviewproduct");
add_action("wp_ajax_nopriv_nvr_quickviewproduct", "nvr_quickviewproduct");
function nvr_quickviewproduct(){
	
	if( !wp_verify_nonce( $_REQUEST['nonce'], "nvr_quickviewproduct_nonce")) {
    	exit("No naughty business please");
	}
	
	$nvr_productid = (isset($_REQUEST["post_id"]) && $_REQUEST["post_id"]>0)? $_REQUEST["post_id"] : 0;
	
	$nvr_query_args = array(
		'post_type'	=> 'product',
		'p'			=> $nvr_productid
	);
	$nvr_outputraw = $nvr_output = '';
	$nvr_productquery = new WP_Query($nvr_query_args);
	if($nvr_productquery->have_posts()){ 

		while ($nvr_productquery->have_posts()){ $nvr_productquery->the_post(); setup_postdata($nvr_productquery->post);
			global $product;
			ob_start();
			woocommerce_get_template_part( 'content', 'quickview-product' );
			$nvr_outputraw = ob_get_contents();
			ob_end_clean();
		}
	}// end if ($nvr_productquery->have_posts())
	$nvr_output = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $nvr_outputraw);
	echo $nvr_output;
	
	die();
}

/*********GMAPS CHANGE PIN**********/
add_action("wp_ajax_nvr_changepinmap", "nvr_changepinmap");
add_action("wp_ajax_nopriv_nvr_changepinmap", "nvr_changepinmap");
function nvr_changepinmap(){
	
	if( !wp_verify_nonce( $_REQUEST['adv_filter_nonce'], "nvr_propadvancefilter_nonce")) {
    	exit("No naughty business please");
	}
	
	$nvr_initial = THE_INITIAL;
	$nvr_shortname = THE_SHORTNAME;
	
	$nvr_unit = nvr_get_option($nvr_shortname.'_measurement_unit');
	$nvr_cursymbol = nvr_get_option( $nvr_shortname . '_currency_symbol');
	$nvr_curplace = nvr_get_option( $nvr_shortname . '_currency_place');
	$nvr_option = get_option('property_category_thumbnail_id');
	$nvr_imgsize = "property-image";
		
	$nvr_query_args = nvr_property_mapquery('search');
	
	$nvr_cache = false;
	$nvr_markers = $nvr_senddata = array();
	
	if($nvr_cache=='yes'){
		if(!get_transient('cache_property_list')) { 

			$property_lists = new WP_Query($nvr_query_args);
			set_transient('cache_property_list', $property_lists, HOUR_IN_SECONDS * 2);
			
		}else{
		
			$property_lists = get_transient('cache_property_list');
			
		}
		wp_reset_query(); 
	}
	else{  

		$property_lists = new WP_Query($nvr_query_args);
		wp_reset_query(); 
		
	}
	
	$nvr_propbox = '';
	$counter=0;
	if($property_lists->have_posts()){
		while($property_lists->have_posts()): $property_lists->the_post();
			
			$counter++;
			$pid = get_the_ID();
			$prop_markers	= nvr_property_getdata($pid, $nvr_unit, $nvr_cursymbol, $nvr_curplace, $nvr_option);
			$nvr_propbox	.= nvr_prop_get_box( $nvr_imgsize, get_the_ID(), 'element columns', $nvr_unit, $nvr_cursymbol, $nvr_curplace );
			$nvr_markers[] = $prop_markers;
		endwhile;
	}else{
		$nvr_markers = false;
	}
	$nvr_senddata['markers'] = $nvr_markers;
	$nvr_senddata['propbox'] = $nvr_propbox;
	wp_reset_query();
	wp_send_json($nvr_senddata);
	die();
}

/*********CONTACT AGENT**********/
add_action("wp_ajax_nvr_propsingle_contactagent", "nvr_propsingle_contactagent");
add_action("wp_ajax_nopriv_nvr_propsingle_contactagent", "nvr_propsingle_contactagent");
function nvr_propsingle_contactagent(){
	
	if( !wp_verify_nonce( $_REQUEST['contact-nonce'], "nvr_propdetailcontactagent_nonce")) {
    	exit("No naughty business please");
	}
	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
	$nvr_propid = (isset($_REQUEST["propid"]) && $_REQUEST["propid"]>0)? $_REQUEST["propid"] : 0;
	
	if ( isset($_POST['name']) ) {
	   if( trim($_POST['name']) =='' || trim($_POST['name']) ==__('Your Name',THE_LANG) ){
		   echo json_encode(array('sent'=>false, 'response'=>__('The name field is empty !', THE_LANG) ));         
		   exit(); 
	   }else {
		   $name = esc_html( trim($_POST['name']) );
	   }          
	} 

	//Check email
	if ( isset($_POST['email']) || trim($_POST['name']) ==__('Your Email',THE_LANG) ) {
		  if( trim($_POST['email']) ==''){
				echo json_encode(array('sent'=>false, 'response'=>__('The email field is empty',THE_LANG ) ) );      
				exit(); 
		  } else if( filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) === false) {
				echo json_encode(array('sent'=>false, 'response'=>__('The email doesn\'t look right !',THE_LANG) ) ); 
				exit();
		  } else {
				$email = esc_html( trim($_POST['email']) );
		  }
	}

	
	
	$phone = esc_html( trim($_POST['phone']) );
	$subject =__('Contact form from ',THE_LANG) . home_url() ;

	//Check comments 
	if ( isset($_POST['comment']) ) {
		  if( trim($_POST['comment']) =='' || trim($_POST['comment']) ==__('Your Message',THE_LANG)){
			echo json_encode(array('sent'=>false, 'response'=>__('Your message is empty !',THE_LANG) ) ); 
			exit();
		  }else {
			$comment = esc_html( trim ($_POST['comment'] ) );
		  }
	} 

	$message='';
	
	if(isset($_POST['agentemail'] )){
		if( is_email ( $_POST['agentemail'] ) ){
			$receiver_email = $_POST['agentemail'] ;
		}
	}
   
	
	$propid=intval($_POST['propid']);
	if($propid!=0){
		$permalink = get_permalink(  $propid );
	}else{
		$permalink = 'contact page';
	}
	
	$message .= __('Client Name', THE_LANG).": " . $name . "\n\n ".__('Email', THE_LANG).": " . $email . " \n\n ".__('Phone', THE_LANG).": " . $phone . " \n\n ".__('Subject',THE_LANG).":" . $subject . " \n\n".__('Message', THE_LANG).":\n " . $comment;
	$message .="\n\n ".__('Message sent from ', THE_LANG).$permalink;
	$email_headers = "From: " . $email . " \r\n Reply-To:" . $email;
	$headers = 'From:   <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n".
					'Reply-To:'.$email . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
	
	$mail = wp_mail($receiver_email, $subject, $message, $headers);

	echo json_encode(array('sent'=>true, 'response'=>__('The message was sent !',THE_LANG) ) ); 
	
	die();
}

/*********LOGIN FORM**********/
add_action( 'wp_ajax_nopriv_nvr_ajax_login', 'nvr_ajax_login' );  
add_action( 'wp_ajax_nvr_ajax_login', 'nvr_ajax_login' );  
   
function nvr_ajax_login(){
     
	if( !wp_verify_nonce( $_REQUEST['security-login'], "nvr_login_nonce")) {
		exit("No naughty business please");
	}
	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
	$login_user  =  esc_html ( $_POST['login_user'] ) ;
	$login_pwd   =  esc_html ( $_POST['login_pwd'] ) ;
	$ispop       =  intval($_POST['ispop']);
   
	if ($login_user=='' || $login_pwd==''){      
	  echo json_encode(array('loggedin'=>false, 'message'=>__('Username and/or Password field is empty!', THE_LANG )));   
	  exit();
	}
	$redirectlink			= nvr_dashboard_link('profile');
	$info                   = array();
	$info['user_login']     = $login_user;
	$info['user_password']  = $login_pwd;
	$info['remember']       = true;
	$user_signon            = wp_signon( $info, false );
  

	 if ( is_wp_error($user_signon) ){
		 echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password!', THE_LANG )));       
	} else {
		 echo json_encode(array('loggedin'=>true,'redirect'=>$redirectlink,'ispop'=>$ispop,'newuser'=>$user_signon->ID, 'message'=>__('Login successful, redirecting...', THE_LANG )));
	}
	die(); 
              
}

/*********REGISTER FORM**********/
add_action( 'wp_ajax_nopriv_nvr_ajax_register', 'nvr_ajax_register' );  
add_action( 'wp_ajax_nvr_ajax_register', 'nvr_ajax_register' );

function nvr_ajax_register(){
		
	if( !wp_verify_nonce( $_REQUEST['security-register'], "nvr_register_nonce")) {
		exit("No naughty business please");
	}
	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;

	$user_email  =   trim( $_POST['user_email_register'] ) ;
	$user_name   =   trim( $_POST['user_login_register'] ) ;
   
	if (preg_match("/^[0-9A-Za-z_]+$/", $user_name) == 0) {
		 _e('Invalid username( *do not use special characters or spaces ) ', THE_LANG );
		die();
	}
	
   

	
	if ($user_email=='' || $user_name==''){
	  _e('Username and/or Email field is empty!', THE_LANG );
	  die();
	}
	
	if(filter_var($user_email,FILTER_VALIDATE_EMAIL) === false) {
		 _e('The email doesn\'t look right !', THE_LANG );
		die();
	}
	
	$domain = substr(strrchr($user_email, "@"), 1);
	if( !checkdnsrr ($domain) ){
		_e('The email\'s domain doesn\'t look right.', THE_LANG );
		die();
	}
	
	
	$user_id     =   username_exists( $user_name );
	if ($user_id){
		_e('Username already exists.  Please choose a new one.', THE_LANG );
		die();
	 }
	
	 
	 
	 
	if ( !$user_id and email_exists($user_email) == false ) {
		$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
   
		$user_id  = wp_create_user( $user_name, $random_password, $user_email );
	 
		 if ( is_wp_error($user_id) ){
				print_r($user_id);
		 }else{ 
			_e('An email with the generated password was sent!', THE_LANG );
			if('1' ==  esc_html ( nvr_get_option( $nvr_shortname.'_user_agent','') )){
				nvr_register_as_agent($user_name,$user_id);
			}
			$freelisting = nvr_get_option($nvr_shortname.'_free_mem_list');
			$freefeatlist = nvr_get_option($nvr_shortname.'_free_feat_list');
			
			update_user_meta( $user_id, 'package_listings', $freelisting) ;
			update_user_meta( $user_id, 'package_featured_listings', $freefeatlist) ;
		 }
		 
	} else {
	   _e('Email already exists.  Please choose a new one.', THE_LANG );
	}


	die(); 
              
}

function nvr_register_as_agent($user_name,$user_id){
    $post = array(
      'post_title'	=> $user_name,
      'post_status'	=> 'publish', 
      'post_type'       => 'peoplepost' ,
    );
	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
    
    $post_id =  wp_insert_post($post );  
    update_post_meta($post_id, 'user_meda_id', $user_id);
    update_user_meta( $user_id, 'user_agent_id', $post_id) ;
}

function nvr_update_user_agent( $agent_id,$firstname ,$secondname ,$useremail,$userphone,$userskype,$userfacebook,$usertwitter,$userpinterest,$userlinkedin,$useryoutube,$userinstagram,$usertitle,$upload_picture,$about_me,$profile_image_id) {
     
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
	 if($firstname!=='' || $secondname!='' ){
          $post = array(
                    'ID'            => $agent_id,
                    'post_title'    => $firstname.' '.$secondname,
                    'post_content'  => $about_me,
            );
           $post_id =  wp_update_post($post );  
      }
    
            
      update_post_meta($agent_id, '_'.$nvr_initial.'_people_email',  $useremail);
      update_post_meta($agent_id, '_'.$nvr_initial.'_people_phone',  $userphone);
      update_post_meta($agent_id, '_'.$nvr_initial.'_people_skype',  $userskype);
	  update_post_meta($agent_id, '_'.$nvr_initial.'_people_facebook',  $userfacebook);
      update_post_meta($agent_id, '_'.$nvr_initial.'_people_twitter',  $usertwitter);
      update_post_meta($agent_id, '_'.$nvr_initial.'_people_pinterest',  $userpinterest);
	  update_post_meta($agent_id, '_'.$nvr_initial.'_people_linkedin',  $userlinkedin);
      update_post_meta($agent_id, '_'.$nvr_initial.'_people_youtube',  $useryoutube);
      update_post_meta($agent_id, '_'.$nvr_initial.'_people_userinstagram',  $userinstagram);
      update_post_meta($agent_id, '_'.$nvr_initial.'_people_info',  $usertitle);
   
      set_post_thumbnail( $agent_id, $profile_image_id );
}

function nvr_show_bill_period($biling_period){
	if($biling_period=='day'){
		return  __('days',THE_LANG);
	}
	else if($biling_period=='week'){
	   return  __('weeks',THE_LANG);
	}
	else if($biling_period=='month'){
		return  __('months',THE_LANG);
	}
	else if($biling_period=='year'){
		return  __('year',THE_LANG);
	}

}

add_action( 'wp_ajax_nopriv_nvr_ajax_update_profile', 'nvr_ajax_update_profile' );  
add_action( 'wp_ajax_nvr_ajax_update_profile', 'nvr_ajax_update_profile' );  


function nvr_ajax_update_profile(){
	global $current_user;
	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
	get_currentuserinfo();
	$userID         =   $current_user->ID;
	
	if( !wp_verify_nonce( $_REQUEST['security-profile'], "nvr_profile_nonce")) {
		exit("No naughty business please");
	}

	$firstname      =   esc_html( $_POST['firstname'] ) ;
	$secondname     =   esc_html( $_POST['secondname'] ) ;
	$useremail      =   esc_html( $_POST['useremail'] ) ;
	$userphone      =   esc_html( $_POST['userphone'] ) ;
	$userskype      =   esc_html( $_POST['userskype'] ) ;
	$userfacebook   =   esc_html( $_POST['userfacebook'] ) ;
	$usertwitter    =   esc_html( $_POST['usertwitter'] ) ;
	$userpinterest  =   esc_html( $_POST['userpinterest'] ) ;
	$userlinkedin   =   esc_html( $_POST['userlinkedin'] ) ;
	$useryoutube    =   esc_html( $_POST['useryoutube'] ) ;
	$userinstagram  =   esc_html( $_POST['userinstagram'] ) ;
	$usertitle      =   esc_html( $_POST['usertitle'] ) ;
	$upload_picture =   esc_html( $_POST['upload_picture'] );
	$profile_image_id =  esc_html( $_POST['profile_image_id'] );
	$mobile         =   esc_html( $_POST['mobile'] );
	$about_me       =   esc_html( $_POST['aboutme']);
   // $profile_image_url=  esc_html( $_POST['profile_image_url']);
	
	
	update_user_meta( $userID, 'first_name', $firstname ) ;
	update_user_meta( $userID, 'last_name',  $secondname) ;
	update_user_meta( $userID, 'phone' , $userphone) ;
	update_user_meta( $userID, 'mobile' , $mobile) ;
	update_user_meta( $userID, 'description' , $about_me) ;
	update_user_meta( $userID, 'skype' , $userskype) ;
	update_user_meta( $userID, 'facebook' , $userfacebook) ;
	update_user_meta( $userID, 'twitter' , $usertwitter) ;
	update_user_meta( $userID, 'pinterest' , $userpinterest) ;
	update_user_meta( $userID, 'linkedin' , $userlinkedin) ;
	update_user_meta( $userID, 'youtube' , $useryoutube) ;
	update_user_meta( $userID, 'instagram' , $userinstagram) ;
	update_user_meta( $userID, 'title', $usertitle) ;
	
	update_user_meta($userID, 'custom_picture', $upload_picture);
	  
	  
	$agent_id=get_user_meta( $userID, 'user_agent_id',true);
	if('1' ==  esc_html ( nvr_get_option( $nvr_shortname.'_user_agent','') )){
		nvr_update_user_agent($agent_id, $firstname ,$secondname ,$useremail,$userphone,$userskype,$userfacebook,$usertwitter,$userpinterest,$userlinkedin,$useryoutube,$userinstagram,$usertitle,$upload_picture,$about_me,$profile_image_id) ;
	}
	if($current_user->user_email != $useremail ) {
		$user_id=email_exists( $useremail ) ;
		if ( $user_id){
			_e('The email was not saved because it is used by another user.</br>', THE_LANG);
		} else{
		   $args = array(
				  'ID'         => $userID,
				  'user_email' => $useremail
			  ); 
			 wp_update_user( $args );
		} 
	}

	_e('Profile updated' , THE_LANG);
	die(); 
}

////////////////////////////////////////////////////////////////////////////////
/// Ajax  Forgot Pass function
////////////////////////////////////////////////////////////////////////////////
   add_action( 'wp_ajax_nopriv_nvr_ajax_forgot_pass', 'nvr_ajax_forgot_pass' );  
   add_action( 'wp_ajax_nvr_ajax_forgot_pass', 'nvr_ajax_forgot_pass' );  
   

   
function nvr_ajax_forgot_pass(){
    global $wpdb;
	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
   
    //    check_ajax_referer( 'login_ajax_nonce', 'security-forgot' );
        $post_id        =   ( $_POST['postid'] ) ;
        $forgot_email   =   ( $_POST['forgot_email'] ) ;
 
       
        if ($forgot_email==''){      
          echo _e('Email field is empty!',THE_LANG);   
          exit();
        }
       
        

        //We shall SQL escape the input
        $user_input = trim($forgot_email);
 
        if ( strpos($user_input, '@') ) {
                $user_data = get_user_by( 'email', $user_input );
                if(empty($user_data) || isset( $user_data->caps['administrator'] ) ) {
                    echo'Invalid E-mail address!';
                    exit();
                }
                            
        }
        else {
            $user_data = get_user_by( 'login', $user_input );
            if( empty($user_data) || isset( $user_data->caps['administrator'] ) ) {
               echo'Invalid Username!';
               exit();
            }
        }
        	$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;

 
        $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
        if(empty($key)) {
                //generate reset key
                $key = wp_generate_password(20, false);
                $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
        }
 
        //emailing password change request details to the user
        $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
        $message = __('Someone requested that the password be reset for the following account:',THE_LANG) . "\r\n\r\n";
        $message .= get_option('siteurl') . "\r\n\r\n";
        $message .= sprintf(__('Username: %s',THE_LANG), $user_login) . "\r\n\r\n";
        $message .= __('If this was a mistake, just ignore this email and nothing will happen.',THE_LANG) . "\r\n\r\n";
        $message .= __('To reset your password, visit the following address:',THE_LANG) . "\r\n\r\n";
        $message .= tg_validate_url($post_id) . "action=reset_pwd&key=$key&login=" . rawurlencode($user_login) . "\r\n";
        if ( $message && !wp_mail($user_email, __('Password Reset Request',THE_LANG), $message,  $headers) ) {
                echo "<div class='error'>".__('Email failed to send for some unknown reason.',THE_LANG)."</div>";
                exit();
        }
        else {
            echo '<div>'.__('We have just sent you an email with Password reset instructions.',THE_LANG).'</div>';
        }
        die(); 
              
}


function tg_validate_url($post_id) {

	$page_url = esc_url(get_permalink( $post_id));
	$urlget = strpos($page_url, "?");
	if ($urlget === false) {
		$concate = "?";
	} else {
		$concate = "&";
	}
	return $page_url.$concate;
}

function nvr_dashboard_link($nvr_page=''){
	
	$nvr_page_array = array('profile','list','add','favorite');
	
	if(in_array($nvr_page,$nvr_page_array)){
    	$pages = get_pages(array(
			'hierarchical' => '0',
            'meta_key' => '_wp_page_template',
            'meta_value' => 'template-dashboard-'.$nvr_page.'.php'
        ));
	}else{
		$pages = false;
	}
    
    if( $pages ){
        $dash_link = get_permalink( $pages[0]->ID);
    }else{
        $dash_link=home_url();
    }  
    
    return $dash_link;
}

function nvr_processor_link($nvr_page=''){
	
	$nvr_page_array = array('paypal','stripe');
	
	if(in_array($nvr_page,$nvr_page_array)){
    	$pages = get_pages(array(
			'hierarchical' => '0',
            'meta_key' => '_wp_page_template',
            'meta_value' => 'template-'.$nvr_page.'-processor.php'
        ));
	}else{
		$pages = false;
	}
    
    if( $pages ){
        $dash_link = get_permalink( $pages[0]->ID);
    }else{
        $dash_link=home_url();
    }  
   	
    return $dash_link;
}

function nvr_access_token($url, $postdata) {
	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
	$clientId = esc_html( nvr_get_option( $nvr_shortname.'_paypal_client_id','') );
    $clientSecret = esc_html( nvr_get_option( $nvr_shortname.'_paypal_client_secret','') );
       
	$curl = curl_init($url); 
	curl_setopt($curl, CURLOPT_POST, true); 
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
	curl_setopt($curl, CURLOPT_HEADER, false); 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata); 
#	curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
	$response = curl_exec( $curl );
	if (empty($response)) {
	    // some kind of an error happened
	    die(curl_error($curl));
	    curl_close($curl); // close cURL handler
	} else {
	    $info = curl_getinfo($curl);
		//echo "Time took: " . $info['total_time']*1000 . "ms\n";
	    curl_close($curl); // close cURL handler
		if($info['http_code'] != 200 && $info['http_code'] != 201 ) {
			echo "Received error: " . $info['http_code']. "\n";
			echo "Raw response:".$response."\n";
			die();
	    }
	}

	// Convert the result from JSON format to a PHP array 
	$jsonResponse = json_decode( $response );
	return $jsonResponse->access_token;
}

function nvr_post_call($url, $postdata,$token) {
	//global $token;
	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
	$curl = curl_init($url); 
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer '.$token,
				'Accept: application/json',
				'Content-Type: application/json'
				));
	
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata); 
	#curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
	$response = curl_exec( $curl );
	if (empty($response)) {
	    // some kind of an error happened
	    die(curl_error($curl));
	    curl_close($curl); // close cURL handler
	} else {
	    $info = curl_getinfo($curl);
		//echo "Time took: " . $info['total_time']*1000 . "ms\n";
	    curl_close($curl); // close cURL handler
		
		if($info['http_code'] != 200 && $info['http_code'] != 201 ) {
			//echo "Received error !xx: " . $info['http_code']. "\n";
			//echo "Raw response:".$response."\n";
                        $dash_profile_link = nvr_dashboard_link('profile');
                        wp_redirect( $dash_profile_link ); 
			die();
	    }
	}

	// Convert the result from JSON format to a PHP array 
	$jsonResponse = json_decode($response, TRUE);
	return $jsonResponse;
}

function nvr_get_stripe_buttons($stripe_pub_key,$user_email,$submission_curency_status){
    wp_reset_query();
    $buttons='';
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
        $i=0;        
        while($pack_selection->have_posts() ){
             $pack_selection->the_post();
                    $postid             = get_the_ID();
          
                    $pack_price         = get_post_meta($postid, 'pack_price', true)*100;
                    $title=get_the_title();
                    if($i==0){
                        $visible_stripe=" visible_stripe ";
                    }else{
                        $visible_stripe ='';
                    }
                    $i++;
                    $buttons.='
                    <div class="stripe_buttons '.$visible_stripe.' " id="'.  sanitize_title($title).'">
                        <script src="https://checkout.stripe.com/checkout.js" id="stripe_script"
                        class="stripe-button"
                        data-key="'. $stripe_pub_key.'"
                        data-amount="'.$pack_price.'" 
                        data-email="'.$user_email.'"
                        data-currency="'.$submission_curency_status.'"
                        data-label="'.__('Pay with Credit Card',THE_LANG).'"
                        data-description="'.$title.' '.__('Package Payment',THE_LANG).'">
                        </script>
                    </div>';         
        }
        wp_reset_query();
    return $buttons;
}

add_action( 'wp_ajax_nopriv_nvr_ajax_update_pass', 'nvr_ajax_update_pass' );  
add_action( 'wp_ajax_nvr_ajax_update_pass', 'nvr_ajax_update_pass' );  


function nvr_ajax_update_pass(){
	
	if( !wp_verify_nonce( $_REQUEST['security-pass'], "nvr_pass_nonce")) {
		exit("No naughty business please");
	}
	
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
	global $current_user;
	get_currentuserinfo();
	$userID         =   $current_user->ID;    
	$oldpass        =   esc_html( $_POST['oldpass'] ) ;
	$newpass        =   esc_html( $_POST['newpass'] ) ;
	$renewpass      =   esc_html( $_POST['renewpass'] ) ;
	
	if($newpass=='' || $renewpass=='' ){
		_e('The new password is blank', THE_LANG);
		die();
	}
   
	if($newpass != $renewpass){
		_e('Passwords do not match', THE_LANG);
		die();
	}
	
	$user = get_user_by( 'id', $userID );
	if ( $user && wp_check_password( $oldpass, $user->data->user_pass, $user->ID) ){
		 wp_set_password( $newpass, $user->ID );
		 _e('Password Updated',THE_LANG);
	}
 
	die();         
}

function nvr_send_booking_email($email_type,$receiver_email){
    
    if ($email_type == 'bookingconfirmeduser'){
           $subject = __('Booking Confirmed '.get_site_url(),THE_LANG); 
           $message = __('Your booking made on '.get_site_url().' was confirmed! You can see all your bookings by logging in your account and visiting "My Bookings" page.',THE_LANG);
    }
    if ($email_type == 'bookingconfirmed'){
           $subject = __('Booking Confirmed on '.get_site_url(),THE_LANG); 
           $message = __('Somebody confirmed a booking on '.get_site_url().'! You should go and check it out!Please remember that the confirmation is made based on the payment confirmation of a non-refundable % fee of the total invoice cost, processed through '.get_site_url().' and sent to website administrator. ',THE_LANG);
    }
    else if ($email_type == 'inbox'){
           $subject = __('New Message on '.get_site_url(),THE_LANG); 
           $message = __('You have a new message on '.get_site_url().'! You should go and check it out!',THE_LANG);
    }
    else if ($email_type == 'newbook'){
           $subject = __('New Booking Request on '.get_site_url(),THE_LANG); 
           $message = __('You have received a new booking request on '.get_site_url().'!  Go to your account in “Bookings” page to see the request, issue the invoice or reject it!',THE_LANG);
    }
    else if ($email_type == 'newinvoice'){
           $subject = __('New Invoice on '.get_site_url(),THE_LANG); 
           $message = __('An invoice was generated for your booking request on '.get_site_url().'!  A deposit will be required for booking to be confirmed. For more details check out your account, "My Reservations" page.',THE_LANG);
    }
    else if ($email_type == 'deletebooking'){
           $subject = __('Booking Request Rejected on '.get_site_url(),THE_LANG); 
           $message = __('One of your booking requests sent on  '.get_site_url().' was rejected by the owner. The rejected property is automatically removed from your account.',THE_LANG);
    }
    else if ($email_type == 'deletebookinguser'){
           $subject = __('Booking Request Cancelled on '.get_site_url(),THE_LANG); 
           $message = __('One of the unconfirmed booking requests you received on '.get_site_url().'  was cancelled! The request is automatically deleted from your account!',THE_LANG);
    }
    
    


    
    
    $email_headers = "From: <noreply@".$_SERVER['HTTP_HOST']."> \r\n Reply-To:<noreply@".$_SERVER['HTTP_HOST'].">";      
    $headers = 'From: noreply  <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n".
                    'Reply-To: <noreply@'.$_SERVER['HTTP_HOST'].'>\r\n" '.
                    'X-Mailer: PHP/' . phpversion();

    $mail = wp_mail($receiver_email, $subject, $message, $headers);
    
}

function nvr_add_to_inbox($userID,$from,$to,$subject,$description){
        $post = array(
            'post_title'	=> $subject,
            'post_content'	=> $description,
            'post_status'	=> 'publish', 
            'post_type'         => 'novaro_message' ,
            'post_author'       => $userID
        );
        $post_id =  wp_insert_post($post );  
        update_post_meta($post_id, 'mess_status', 'new' );
        update_post_meta($post_id, 'message_from_user', $from );
        update_post_meta($post_id, 'message_to_user', $to );
        
        update_post_meta($post_id, 'message_status', 'unread');
        update_post_meta($post_id, 'delete_source', 0);
        update_post_meta($post_id, 'delete_destination', 0);       
}

function nvr_email_to_admin($onlyfeatured){
    
    
        $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
        $message  = __('Hi there,',THE_LANG) . "\r\n\r\n";
     
        if($onlyfeatured==1){
            $title= sprintf(__('[%s] New Feature Upgrade ',THE_LANG), get_option('blogname'));
            $message .= sprintf( __("You have a new featured submission on  %s! You should go check it out.",THE_LANG), get_option('blogname')) . "\r\n\r\n";

        }else{
             $title= sprintf(__('[%s] New Paid Submission',THE_LANG), get_option('blogname'));
             $message .= sprintf( __("You have a new paid submission on  %s! You should go check it out.",THE_LANG), get_option('blogname')) . "\r\n\r\n";

        }
        
        
        wp_mail(get_option('admin_email'),
                $title ,
                $message,
                $headers);
    
}

add_action('wp_ajax_me_upload', 'me_upload');
add_action('wp_ajax_aaiu_delete', 'me_delete_file');

add_action('wp_ajax_nopriv_me_upload',  'me_upload');
add_action('wp_ajax_nopriv_aaiu_delete', 'me_delete_file');

function me_delete_file()
{
    $attach_id = $_POST['attach_id'];
    wp_delete_attachment($attach_id, true);
    exit;
}

function me_upload(){
   // check_ajax_referer('aaiu_allow', 'nonce');

    $file = array(
        'name' => $_FILES['aaiu_upload_file']['name'],
        'type' => $_FILES['aaiu_upload_file']['type'],
        'tmp_name' => $_FILES['aaiu_upload_file']['tmp_name'],
        'error' => $_FILES['aaiu_upload_file']['error'],
        'size' => $_FILES['aaiu_upload_file']['size']
    );
    $file = fileupload_process($file);
       

}  
    
    
    
function fileupload_process($file){
	$attachment = handle_file($file);
	
	if (is_array($attachment)) {
		$html = getHTML($attachment);
	
		$response = array(
			'success' => true,
			'html' => $html,
			'attach'=> $attachment['id']
		);
	
		echo json_encode($response);
		exit;
	}
	
	$response = array('success' => false);
	echo json_encode($response);
	exit;
}
    
    
    
    
    
    
    
function handle_file($upload_data){

        $return = false;
        $uploaded_file = wp_handle_upload($upload_data, array('test_form' => false));

        if (isset($uploaded_file['file'])) {
            $file_loc = $uploaded_file['file'];
            $file_name = basename($upload_data['name']);
            $file_type = wp_check_filetype($file_name);

            $attachment = array(
                'post_mime_type' => $file_type['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                'post_content' => '',
                'post_status' => 'inherit'
            );

            $attach_id = wp_insert_attachment($attachment, $file_loc);
            $attach_data = wp_generate_attachment_metadata($attach_id, $file_loc);
            wp_update_attachment_metadata($attach_id, $attach_data);

            $return = array('data' => $attach_data, 'id' => $attach_id);

            return $return;
        }

        return $return;
}
    
    
function getHTML($attachment){

	$attach_id = $attachment['id'];
	$file = explode('/', $attachment['data']['file']);
	$file = array_slice($file, 0, count($file) - 1);
	$path = implode('/', $file);
	$image = $attachment['data']['sizes']['thumbnail']['file'];
	$post = get_post($attach_id);
	$dir = wp_upload_dir();
	$path = $dir['baseurl'] . '/' . $path;
	
	$html = '';
	// $html .= '<li class="aaiu-uploaded-files">';
	//$html .= sprintf('<img src="%s" name="' . $post->post_title . '" />', $path . '/' . $image);
	//$html .= sprintf('<br /><a href="#" class="action-delete" data-upload_id="%d">%s</a></span>', $attach_id, __('Delete'));
	//$html .= sprintf('<input type="hidden" name="aaiu_image_id[]" value="%d" />', $attach_id);
	//$html .= '</li>';
	global $current_user;
	get_currentuserinfo();
	$userID         =   $current_user->ID;
	
	//if( is_page_template('user_dashboard_profile.php') ){
	  //  update_user_meta($userID, 'custom_picture', $path.'/'.$image);
	// }
	
	
	$html .= $path.'/'.$image; 
	return $html;
}
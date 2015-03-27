<?php
//custom meta field
$nvr_prefix = 'nvr_';
$nvr_pid = nvr_get_postid();    
$nvr_custom = nvr_get_customdata($nvr_pid);
$nvr_cf_pagetitle = (isset($nvr_custom[$nvr_prefix."page-title"][0]))? $nvr_custom[$nvr_prefix."page-title"][0] : "";

if(is_singular('portofolio') || is_attachment()){

	echo '<h1 class="pagetitle"><span>'.get_the_title().'</span></h1>';
	
}elseif( function_exists('is_woocommerce') && is_woocommerce() ){
	echo '<h1 class="pagetitle"><span>';
		woocommerce_page_title();
	echo '</span></h1>';
}elseif(is_single()){
	
	echo '<h1 class="pagetitle"><span>'.get_the_title().'</span></h1>';
	
}elseif(is_archive()){
	echo ' <h1 class="pagetitle"><span>';
	if ( is_day() ) :
	printf( __( 'Daily Archives <span>%s</span>', THE_LANG ), get_the_date() );
	elseif ( is_month() ) :
	printf( __( 'Monthly Archives <span>%s</span>', THE_LANG ), get_the_date('F Y') );
	elseif ( is_year() ) :
	printf( __( 'Yearly Archives <span>%s</span>', THE_LANG ), get_the_date('Y') );
	elseif ( is_author()) :
	printf( __( 'Author Archives %s', THE_LANG ), "<a class='url fn n' href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "' title='" . esc_attr( get_the_author() ) . "' rel='me'>" . get_the_author() . "</a>" );
	else :
	printf( __( '%s', THE_LANG ), '<span>' . single_cat_title( '', false ) . '</span>' );
	endif;
	echo '</span> </h1>';
	
}elseif(is_search()){
	echo ' <h1 class="pagetitle"><span>';
	printf( __( 'Search Results for %s', THE_LANG ), '<span>' . get_search_query() . '</span>' );
	echo '</span> </h1>';
	
}elseif(is_404()){
	echo ' <h1 class="pagetitle"><span>';
	_e( '404 Page', THE_LANG );
	echo '</span> </h1>';
	
}elseif( is_home() ){
	$nvr_postspage = get_option('page_for_posts');
	$nvr_poststitle = get_the_title($nvr_postspage);
	
	echo ' <h1 class="pagetitle"><span>';
	echo ($nvr_postspage)? $nvr_poststitle : __('Blog', THE_LANG );
	echo '</span> </h1>';
	
}else{

 if (have_posts()) : while (have_posts()) : the_post();
	$nvr_titleoutput='';
	
	if($nvr_cf_pagetitle == ""){
		$nvr_titleoutput.='<h1 class="pagetitle"><span>'.get_the_title().'</span></h1>';
	}else{
		$nvr_titleoutput.='<h1 class="pagetitle"><span>'.$nvr_cf_pagetitle.'</span></h1>';
	}
	
	echo $nvr_titleoutput;
endwhile; endif; wp_reset_query();

}
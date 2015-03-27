<?php
/**
 * Template Name: Blog
 *
 * A custom page template for portfolio page.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */

get_header(); ?>

	<?php
	$nvr_shortname = THE_SHORTNAME;
	$nvr_initial = THE_INITIAL;
	
	
   	$nvr_pid = nvr_get_postid();
	$nvr_custom = nvr_get_customdata($nvr_pid);
    $nvr_cat = (isset($nvr_custom["blog_category"][0]))? $nvr_custom["blog_category"][0] : "";
	$nvr_bloglayout = (isset($nvr_custom["blog_layout"][0]))? $nvr_custom["blog_layout"][0] : "";
	$nvr_bloginfscrolls = (isset($nvr_custom["blog_infscrolls"][0]))? $nvr_custom["blog_infscrolls"][0] : "";
	if($nvr_bloginfscrolls=='true' && $nvr_bloglayout!='classic'){
		$nvr_bloginfscrolls = '1';
	}else{
		$nvr_bloginfscrolls = '0';
	}
	
    global $post, $more, $nvr_blogparam;
    $more = 0;
	
	$nvr_blogparam = array(
		'bloglayout' => $nvr_bloglayout,
		'infscrolls' => $nvr_bloginfscrolls
	);
	
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$nvr_paged = $paged;
	
	$nvr_argquery = array(
        'post_type' => 'post'
    );
	
	if(isset($nvr_paged)){
		$nvr_argquery['paged'] = $nvr_paged;
	}
	if($nvr_cat!=''){
		$nvr_argquery['category_name'] = $nvr_cat;
    }
    query_posts($nvr_argquery); 
	
    /* Run the loop to output the posts.
    * If you want to overload this in a child theme then include a file
    * called loop-index.php and that will be used instead.
    */
    get_template_part( 'loop', 'index' );
    ?>
    <div class="clearfix"></div><!-- clear float --> 
    
<?php get_footer(); ?>
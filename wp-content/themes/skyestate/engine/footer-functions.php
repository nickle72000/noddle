<?php 
/* get website title */
if(!function_exists("nvr_footer_text")){
	function nvr_footer_text(){
		
		$nvr_shortname = THE_SHORTNAME;
		
		$nvr_foot= stripslashes(nvr_get_option( $nvr_shortname . '_footer'));
		if($nvr_foot==""){
		
			_e('Copyright', THE_LANG ); echo ' &copy;';
			global $wpdb;
			$sqlfrom = esc_sql( $wpdb->posts );
			$sqlyear = esc_sql( 1970 );
			$nvr_post_datetimes = $wpdb->get_results(
				$wpdb->prepare("
				SELECT YEAR(min(post_date_gmt)) AS firstyear, YEAR(max(post_date_gmt)) AS lastyear 
				FROM $sqlfrom 
				WHERE post_date_gmt > %d", 
				$sqlyear)
			);
			if ($nvr_post_datetimes) {
				$nvr_firstpost_year = $nvr_post_datetimes[0]->firstyear;
				$nvr_lastpost_year = $nvr_post_datetimes[0]->lastyear;
	
				$nvr_copyright = $nvr_firstpost_year;
				if($nvr_firstpost_year != $nvr_lastpost_year) {
					$nvr_copyright .= '-'. $nvr_lastpost_year;
				}
				$nvr_copyright .= ' ';
	
				echo $nvr_copyright;
				echo '<a href="'.esc_url( home_url( '/') ).'">'.get_bloginfo('name') .'.</a>';
			}
			?> 
			<?php _e('Designed by', THE_LANG ); ?> 
            <a href="<?php echo esc_url( __( 'http://www.novarostudio.com', THE_LANG) ); ?>" title="<?php echo esc_attr( __('Novaro Studio', THE_LANG) ); ?>"><?php _e('Novaro Studio', THE_LANG); ?></a>
        <?php 
		}else{
        	echo $nvr_foot;
        }
		
	}/* end nvr_footer_text() */
}

if(!function_exists('nvr_output_footertext')){
	function nvr_output_footertext(){
		echo '<div class="copyright">';
			nvr_footer_text();
		echo '</div>';
	}
	add_action('nvr_output_footerarea','nvr_output_footertext',5);
	
}

if(!function_exists('nvr_tracking_code')){
	function nvr_tracking_code(){
		$nvr_shortname = THE_SHORTNAME;
		$nvr_trackingcode = stripslashes(nvr_get_option( $nvr_shortname . '_trackingcode'));
		if($nvr_trackingcode!=""){
			echo '<script type="text/javascript">';
			echo esc_js( $nvr_trackingcode );
			echo '</script>';
		}
	}
	add_action('nvr_wp_footer','nvr_tracking_code',8);
	
}

if(!function_exists("nvr_wp_footer")){
	function nvr_wp_footer(){
		do_action("nvr_wp_footer");
	}
	add_action('wp_head', 'nvr_wp_footer', 100);
}
<?php
	/* Shortcode */
	add_shortcode('content_title', 'nvr_content_title');
	
	/* -----------------------------------------------------------------
		Content Title
	----------------------------------------------------------------- */
	function nvr_content_title($atts, $content = null) {
		extract(shortcode_atts(array(
		), $atts));

		$nvr_output = '<h2 class="contenttitle"><span>'.$content.'</span></h2>';
		return do_shortcode($nvr_output);
	}
?>
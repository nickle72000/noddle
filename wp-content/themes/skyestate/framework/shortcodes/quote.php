<?php
	/* Pullquote &amp; Blockquote */
	add_shortcode( 'pullquote', 'nvr_pullquote' );
	add_shortcode( 'blockquote', 'nvr_blockquote' );
	
	/* -----------------------------------------------------------------
		Pullquote
	----------------------------------------------------------------- */
	function nvr_pullquote($atts, $content = null) {
		extract(shortcode_atts(array(
					"position" => 'left'
		), $atts));
		
		$nvr_output = '<span class="pullquote-'.esc_attr( $position ).'">'.$content.'</span>';
			
		return do_shortcode($nvr_output);
	}
	
	
 	/* -----------------------------------------------------------------
		Blockquote
	----------------------------------------------------------------- */
	function nvr_blockquote($atts, $content = null) {
		
		$nvr_output = '<blockquote>'.$content.'</blockquote>';
		return do_shortcode($nvr_output);
	}

?>
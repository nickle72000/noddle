<?php
	/* Highlight Shortcode */
	add_shortcode( 'highlight', 'nvr_highlight' );
	
	/* -----------------------------------------------------------------
		Highlight
	----------------------------------------------------------------- */
	function nvr_highlight($atts, $content = null) {
		extract(shortcode_atts(array(
					"type" => ''
		), $atts));
		
		if($type=="" || $type=="light"){
			$nvr_output = '<span class="highlight1">'.$content.'</span>';
		}
		if($type=="dark"){
			$nvr_output = '<span class="highlight2">'.$content.'</span>';
		}	
		return do_shortcode($nvr_output);
	}
?>
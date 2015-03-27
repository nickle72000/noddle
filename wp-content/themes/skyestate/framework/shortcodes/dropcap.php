<?php
	/* Dropcap Shortcode */
	add_shortcode( 'dropcap', 'nvr_dropcap' );
	
	/* -----------------------------------------------------------------
		Dropcaps
	----------------------------------------------------------------- */
	function nvr_dropcap($atts, $content = null) {
		extract(shortcode_atts(array(
					"type" => ''
		), $atts));
		
		if($type=="circle"){
			$nvr_output = '<span class="dropcap2">'.$content.'</span>';
		}elseif($type=="square"){
			$nvr_output = '<span class="dropcap3">'.$content.'</span>';
		}else{
			$nvr_output = '<span class="dropcap1">'.$content.'</span>';
		}		
		return do_shortcode($nvr_output);
	}

?>
<?php
	/* Toggle Shortcode */
	add_shortcode('toggles', 'nvr_toggles');
	add_shortcode('toggle', 'nvr_toggle');
	
	/* -----------------------------------------------------------------
		Toggle
	----------------------------------------------------------------- */
	function nvr_toggle($atts, $content = null) {
		
		extract(shortcode_atts(array(
			'title' => 'Unnamed',
			'image' => '',
			'icon'	=> '',
			'class' => ''
		), $atts));
		
		$nvr_imageoutput = '';
		if($image!=''){
			$nvr_imageoutput = '<img src="'.esc_url( $image ).'" alt="" />';
		}
		
		$nvr_iconoutput = '';
		if($icon!=''){
			$nvr_iconoutput = '<i class="fa '.esc_attr( $icon ).' colortext"></i> ';
		}
		
		$nvr_output = '
				<h2 class="trigger '.esc_attr( $class ).'"><span>'.$nvr_iconoutput.$nvr_imageoutput.$title.'</span></h2>
				<div class="toggle_container '.esc_attr( $class ).'">
					<div class="block">'.$content.'</div>
				</div>';
			
		return do_shortcode($nvr_output);
		
	}
	
	
	/* -----------------------------------------------------------------
		Toggles container
	----------------------------------------------------------------- */
	function nvr_toggles($atts, $content = null) {
		$nvr_output = '<div id="toggle">'.$content.'</div>';
		return do_shortcode($nvr_output);
		
	}
?>
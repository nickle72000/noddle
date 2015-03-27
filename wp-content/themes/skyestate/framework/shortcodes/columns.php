<?php 
	/* Columns Shortcode */
	add_shortcode('row', 'nvr_row');
	add_shortcode('one_half', 'nvr_one_half');
	add_shortcode('one_third', 'nvr_one_third');
	add_shortcode('one_fourth', 'nvr_one_fourth');
	add_shortcode('one_fifth', 'nvr_one_fifth');
	add_shortcode('one_sixth', 'nvr_one_sixth');
	
	add_shortcode('two_third', 'nvr_two_third');
	add_shortcode('two_fourth', 'nvr_two_fourth');
	add_shortcode('two_fifth', 'nvr_two_fifth');
	add_shortcode('two_sixth', 'nvr_two_sixth');
	
	
	add_shortcode('three_fourth', 'nvr_three_fourth');
	add_shortcode('three_fifth', 'nvr_three_fifth');
	add_shortcode('three_sixth', 'nvr_three_sixth');
	
	add_shortcode('four_fifth', 'nvr_four_fifth');
	add_shortcode('four_sixth', 'nvr_four_sixth');
	
	add_shortcode('five_sixth', 'nvr_five_sixth');
	
	
	
	/* -----------------------------------------------------------------
		Columns shortcodes
	----------------------------------------------------------------- */
	function nvr_row($atts, $content = null) {
		extract(shortcode_atts(array(
					"class" => ''
		), $atts));
		
		$nvr_output = '<div class="row '.esc_attr( $class ).'">' . $content . '</div>';
		
		return do_shortcode($nvr_output);
		
	}
	
	
	function nvr_one_half($atts, $content = null) {
		extract(shortcode_atts(array(
					"class" => ''
		), $atts));
		
		$nvr_output = '<div class="six columns '.esc_attr( $class ).'">' . $content . '</div>';
		
		return do_shortcode($nvr_output);
		
	}
	

	function nvr_one_third($atts, $content = null) {
		extract(shortcode_atts(array(
					"class" => ''
		), $atts));
		
		$nvr_output = '<div class="four columns '.esc_attr( $class ).'">' . $content . '</div>';
		
		return do_shortcode($nvr_output);
		
	}
	
	
	function nvr_one_fourth($atts, $content = null) {
		extract(shortcode_atts(array(
					"class" => ''
		), $atts));
		
		$nvr_output = '<div class="three columns '.esc_attr( $class ).'">' . $content . '</div>';
		
		return do_shortcode($nvr_output);
		
	}
	
	function nvr_one_sixth($atts, $content = null) {
		extract(shortcode_atts(array(
					"class" => ''
		), $atts));
		
		$nvr_output = '<div class="two columns '.esc_attr( $class ).'">' . $content . '</div>';
		
		return do_shortcode($nvr_output);
		
	}
	
	function nvr_two_third($atts, $content = null) {
		extract(shortcode_atts(array(
					"class" => ''
		), $atts));
		
		$nvr_output = '<div class="eight columns '.esc_attr( $class ).'">' . $content . '</div>';
		
		return do_shortcode($nvr_output);
		
	}
	
	function nvr_two_fourth($atts, $content = null) {
		extract(shortcode_atts(array(
					"class" => ''
		), $atts));
		
		$nvr_output = '<div class="six columns '.esc_attr( $class ).'">' . $content . '</div>';
		
		return do_shortcode($nvr_output);
		
	}
	
	function nvr_two_sixth($atts, $content = null) {
		extract(shortcode_atts(array(
					"class" => ''
		), $atts));
		
		$nvr_output = '<div class="four columns '.esc_attr( $class ).'">' . $content . '</div>';
		
		return do_shortcode($nvr_output);
		
	}
	
	function nvr_three_fourth($atts, $content = null) {
		extract(shortcode_atts(array(
					"class" => ''
		), $atts));
		
		$nvr_output = '<div class="nine columns '.esc_attr( $class ).'">' . $content . '</div>';
		
		return do_shortcode($nvr_output);
		
	}
	
	function nvr_three_sixth($atts, $content = null) {
		extract(shortcode_atts(array(
					"class" => ''
		), $atts));
		
		$nvr_output = '<div class="six columns '.esc_attr( $class ).'">' . $content . '</div>';
		
		return do_shortcode($nvr_output);
		
	}
	
	
	function nvr_four_sixth($atts, $content = null) {
		extract(shortcode_atts(array(
					"class" => ''
		), $atts));
		
		$nvr_output = '<div class="eight columns '.esc_attr( $class ).'">' . $content . '</div>';
		
		return do_shortcode($nvr_output);
		
	}
	
	function nvr_five_sixth($atts, $content = null) {
		extract(shortcode_atts(array(
					"class" => ''
		), $atts));
		
		$nvr_output = '<div class="ten columns '.esc_attr( $class ).'">' . $content . '</div>';
		
		return do_shortcode($nvr_output);
		
	}
?>
<?php
	/* Shortcode */
	add_shortcode('pre', 'nvr_pre');
	add_shortcode('code', 'nvr_code');
	
	/* -----------------------------------------------------------------
		Pre
	----------------------------------------------------------------- */
	function nvr_pre($atts, $content) {
	
		$nvr_return_html = '<pre>'.strip_tags($content).'</pre>';
		
		return $nvr_return_html;
	}
	
	/* -----------------------------------------------------------------
		Code
	----------------------------------------------------------------- */
	function nvr_code($atts, $content) {
		
		$content = str_replace("[", '&#91;', $content);
		$content = str_replace("]", '&#93;', $content);
		$nvr_return_html = '<code>'.strip_tags($content).'</code>';
		
		return $nvr_return_html;
	}

?>
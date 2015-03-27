<?php

	/* Tab */
	add_shortcode('tabs', 'nvr_tab');
	
	/* -----------------------------------------------------------------
		Tab
	----------------------------------------------------------------- */
	function nvr_tab($atts, $content = null, $code) {
		extract(shortcode_atts(array(
			'style' => false
		), $atts));
		
		if (!preg_match_all("/(.?)\[(tab)\b(.*?)(?:(\/))?\](?:(.+?)\[\/tab\])?(.?)/s", $content, $count)) {
			return do_shortcode($content);
		} else {
			for($i = 0; $i < count($count[0]); $i++) {
				$count[3][$i] = shortcode_parse_atts($count[3][$i]);
			}
			$nvr_output = '<ul class="tabs">';
			
			for($i = 0; $i < count($count[0]); $i++) {
				$nvr_output .= '<li><a href=".tab'.$i.'">' . $count[3][$i]['title'] . '</a></li>';
			}
			$nvr_output .= '</ul>';
			$nvr_output .= '<div class="tab-body">';
			for($i = 0; $i < count($count[0]); $i++) {
				$nvr_output .= '<div class="tab-content tab'.$i.'">' . do_shortcode(trim($count[5][$i])) . '</div>';
			}
			$nvr_output .= '</div>';
			
			return '<div class="tabcontainer">' . $nvr_output . '</div>';
		}
	}

?>
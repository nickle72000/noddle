<?php
$output = $el_class = $bg_image = $bg_color = $bg_image_repeat = $font_color = $padding = $margin_bottom = $css = '';
extract(shortcode_atts(array(
    'el_class'        => '',
	'row_type' => 'row',
	'type' => '',
	'anchor' => '',
	'video' => '',
	'video_overlay' => '',
	'video_webm' => '',
	'video_mp4' => '',
	'video_ogv' => '',
	'video_image' => '',
    'bg_image'        => '',
    'bg_color'        => '',
    'bg_image_repeat' => '',
    'font_color'      => '',
    'padding'         => '',
	'margin_top'   	  => '',
    'margin_bottom'   => '',
    'css' => ''
), $atts));

wp_enqueue_style( 'js_composer_front' );
wp_enqueue_script( 'wpb_composer_front_js' );
wp_enqueue_style('js_composer_custom_css');

$el_class = $this->getExtraClass($el_class);

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_row wpb_row '. ( $this->settings('base')==='vc_row_inner' ? 'vc_inner ' : '' ) . get_row_css_class() . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

$style = novaro_row_buildStyle($bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom, $margin_top);
$strypestyle = '';

$anchor_id = "";
if($anchor != ""){
	$anchor_id = ' data-section_id="#'.$anchor.'" id="'.$anchor.'"';
}

if($row_type=='section'){
	$style = novaro_row_buildStyle('','','',$font_color, $padding);
	$strypestyle = novaro_row_buildStyle($bg_image, $bg_color, $bg_image_repeat, '', '', $margin_bottom, $margin_top);
}

$output .= '<div class="'.$css_class.'"'.$style.'>';
$output .= wpb_js_remove_wpautop($content);
$output .= '</div>'.$this->endBlockComment('row');

$voutput = '';
if($video == "show_video"){
	$v_image = wp_get_attachment_url($video_image);
	$voutput .= '<div class="mobile-video-image" style="background-image: url('.$v_image.')"></div><div class="video-overlay';
		if($video_overlay == "show_video_overlay"){
			$voutput .= ' active';
		}
		$voutput .= '"></div><div class="video-wrap">
			
			<video class="video" width="1920" height="800" poster="'.$v_image.'" controls="controls" preload="auto" loop="true" autoplay="true" muted="true">';
					if(!empty($video_webm)) { $voutput .= '<source type="video/webm" src="'.$video_webm.'">'; }
					if(!empty($video_mp4)) { $voutput .= '<source type="video/mp4" src="'.$video_mp4.'">'; }
					if(!empty($video_ogv)) { $voutput .= '<source type="video/ogg" src="'. $video_ogv.'">'; }
				 $voutput .='<object width="320" height="240" type="application/x-shockwave-flash" data="'.get_template_directory_uri().'/js/flashmediaelement.swf">
							<param name="movie" value="'.get_template_directory_uri().'/js/flashmediaelement.swf" />
							<param name="flashvars" value="controls=true&file='.$video_mp4.'" />
							<img src="'.$v_image.'" width="1920" height="800" title="No video playback capabilities" alt="Video thumb" />
					</object>
			</video>		
	</div>';
}

if($row_type=='section'){
	if($el_class){
		$el_class = $el_class.'-container';
	}
	if($type=='fullwidth'){
		$addclass = 'fullwidth';
	}else{
		$addclass = '';
	}
	$output = '<div '.$anchor_id.' class="stripecontainer '.$el_class.' '.$addclass.'"'.$strypestyle.'>'.$voutput.'<div class="stripewrapper">'.$output.'</div></div>';
}

echo $output;
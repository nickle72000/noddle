<?php
/**
 * The Sidebar containing the post widget areas.
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */

global $post;

$nvr_pid = nvr_get_postid();
$nvr_custom = nvr_get_customdata($nvr_pid);
$nvr_initial = THE_INITIAL;
$nvr_shortname = THE_SHORTNAME;

$nvr_defaultsidebar = $nvr_shortname . "-sidebar";
$nvr_chosensidebar = (isset($nvr_custom['_'.$nvr_initial.'_sidebar'][0]) && !is_search())? $nvr_custom['_'.$nvr_initial.'_sidebar'][0] : $nvr_defaultsidebar;
?>
<div class="widget-area">
	<?php if ( ! dynamic_sidebar( $nvr_chosensidebar ) ) : ?><?php endif; // end general widget area ?>
</div>
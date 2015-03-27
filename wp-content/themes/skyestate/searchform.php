<?php
/**
 * The template for displaying search forms in the template
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */
?>
<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
<div class="searcharea">
    <input type="text" name="s" id="s" value="<?php esc_attr_e('Enter the keyword...', THE_LANG );?>" onfocus="if (this.value == '<?php echo esc_js( __('Enter the keyword...',THE_LANG ) ) ;?>')this.value = '';" onblur="if (this.value == '')this.value = '<?php echo esc_js( __('Enter the keyword...', THE_LANG ) );?>';" />
</div>
</form>
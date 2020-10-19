<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();
?>

<button data-opinionstage-content-launch class="button" type="button">
<img src="<?php echo plugins_url('admin/images/os-icon.png', plugin_dir_path( __FILE__ )) ?>" 
		style="position: relative; left: -3px; top: -2px; padding: 0"
>
	Add a Poll, Survey, Quiz or Form
</button>

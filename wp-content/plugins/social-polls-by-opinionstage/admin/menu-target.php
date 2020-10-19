<?php
add_action('admin_footer', 'OpinionStage_addMenuTargetLink');

function OpinionStage_addMenuTargetLink(){
	if ( isset($os_options['item_count']) && $os_options['item_count'] > 0) { ?>
		<script type="text/javascript">			
			jQuery(document).ready(function(){
				jQuery("li.toplevel_page_opinionstage-view-my-items ul li:nth-last-child(1) a,li.toplevel_page_opinionstage-settings ul li:nth-last-child(1) a").attr('target', '_blank');
			});
		</script>
	<?php }else{ ?>
		<script type="text/javascript">			
			jQuery(document).ready(function(){
				jQuery("li.toplevel_page_opinionstage-getting-started ul li:nth-last-child(1) a,li.toplevel_page_opinionstage-settings ul li:nth-last-child(1) a").attr('target', '_blank');
			});
		</script>
	<?php }
}
?>
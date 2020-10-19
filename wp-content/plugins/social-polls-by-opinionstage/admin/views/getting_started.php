<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();
?>
<div id="opinionstage-content">
	<div class="opinionstage-header-wrapper">
			<?php if ( !$os_client_logged_in ) {?>
			<div class="opinionstage-logo-wrapper">
				<div class="opinionstage-logo"></div>
			</div>
			<?php } else { ?>
			<div class="opinionstage-logo-wrapper">
				<div class="opinionstage-logo"></div>
				<div class="opinionstage-connectivity-status"><?php echo($os_options["email"]); ?>
					<form method="POST" action="<?php echo get_admin_url(null, 'admin.php?page='.OPINIONSTAGE_DISCONNECT_PAGE)?>" class="opinionstage-connect-form">
						<button class="opinionstage-disconnect" type="submit">Disconnect</button>
					</form>
				</div>
			</div>
			<?php } ?>
	</div>
	<?php if( $os_client_logged_in ){  ?>
		<div class="gettingStartedSection">
			<div class="gettingStartedContainer">
			<div class="opinionstage-status-content-connected">
				<div class='opinionstage-status-title opinionstage-resources-title'>Getting Started Resources</div>
			</div>
			</div>
			<div class="gettingBlockContainer">
				<?php echo opinionstage_help_links('GETTING STARTED <br/>VIDEO TUTORIAL', 'https://help.opinionstage.com/wordpress-plugin/how-to-use-the-wordpress-plugin', 'gettingTemplateTutorial help-link'); ?>
				<?php echo opinionstage_link('TEMPLATES & <br/> EXAMPLES', 'dashboard/content/templates', 'gettingTemplateGallery help-link'); ?>				
			</div>
		</div>
	<?php }else{ ?>
		<div class="gettingStartedSection">
			<div class="gettingStartedContainer" style="height: 240px;">
			<div class="opinionstage-status-content-connected">
				<div class='opinionstage-status-title opinionstage-connect-title'>Connect Wordpress with<br/>Opinion Stage to get started</div>
				<form action="<?php echo OPINIONSTAGE_LOGIN_PATH ?>" method="get" class="opinionstage-connect-form">
				<input type="hidden" name="utm_source" value="<?php echo OPINIONSTAGE_UTM_SOURCE ?>">
					<input type="hidden" name="utm_campaign" value="<?php echo OPINIONSTAGE_UTM_CAMPAIGN ?>">
					<input type="hidden" name="utm_medium" value="<?php echo OPINIONSTAGE_UTM_CONNECT_MEDIUM ?>">
					<input type="hidden" name="o" value="<?php echo OPINIONSTAGE_WIDGET_API_KEY ?>">
					<input type="hidden" name="callback" value="<?php echo opinionstage_callback_url()?>">
					<input id="os-email" type="email" name="email" placeholder="Your email" data-os-email-input required>
					<button class="opinionstage-connect-btn opinionstage-getting-btn opinionstage-blue-btn" type="submit" id="os-start-login" data-os-login>CONNECT</button>
				</form>
			</div>
			</div>
			<div class="gettingBlockContainer">
				<?php echo opinionstage_help_links('GETTING STARTED <br/>VIDEO TUTORIAL', 'https://help.opinionstage.com/wordpress-plugin/how-to-use-the-wordpress-plugin', 'gettingTemplateTutorial help-link'); ?>	
				<?php echo opinionstage_link('TEMPLATES & <br/> EXAMPLES', 'dashboard/content/templates', 'gettingTemplateGallery help-link'); ?>				
			</div>
			<div class="gettingBlockContainer">
				<?php echo opinionstage_help_links('HELP CENTER', 'https://help.opinionstage.com', 'gettingTemplateTutorial help-link','padding: 29px 0;'); ?>
				<?php echo opinionstage_link('LIVE CHAT HELP', 'live-chat/', 'help-center-os help-link'); ?>		
			</div>
		</div>
		<script type="text/javascript">
			var aElems = document.getElementsByTagName('a');

			for (var i = 0, len = aElems.length; i < len; i++) {
			    aElems[i].onclick = function(e) {
			    	e.preventDefault();
			    	var href = jQuery(this).attr('href');
			    	var target = jQuery(this).attr('target');

			    	if(e.target.classList.contains('help-link')){
			    		if(target !== undefined) {
					        window.open(href, target);
					    }
					    else {
						    window.location.href = href;
						}
			    	}else{
			    		swal({
				            title: "Leave without connecting?",
				            text: "To use this plugin you need to first connect WordPress with Opinion Stage.",
				            icon: "warning",
				            buttons: ["Cancel", "Leave"],
				        })
				        .then((willDelete) => {
					        if (willDelete) {
					        	if(target !== undefined) {
					        		window.open(href, target);
					        	}
					        	else {
						        	window.location.href = href;
						        }
					        }
					        else {
					        	jQuery('#os-email').focus();
					        }
				        });
			    	}
			    };
			}
		</script>
	<?php } ?>
</div>

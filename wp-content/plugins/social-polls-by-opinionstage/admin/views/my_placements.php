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
			<div class="opinionstage-status-content">
				<div class='opinionstage-status-title'><b class="opinionstage-title">Connect WordPress with Opinion Stage to get started</b></div>
				<form action="<?php echo OPINIONSTAGE_LOGIN_PATH ?>" method="get" class="opinionstage-connect-form">
					<input type="hidden" name="utm_source" value="<?php echo OPINIONSTAGE_UTM_SOURCE ?>">
					<input type="hidden" name="utm_campaign" value="<?php echo OPINIONSTAGE_UTM_CAMPAIGN ?>">
					<input type="hidden" name="utm_medium" value="<?php echo OPINIONSTAGE_UTM_CONNECT_MEDIUM ?>">
					<input type="hidden" name="o" value="<?php echo OPINIONSTAGE_WIDGET_API_KEY ?>">
					<input type="hidden" name="callback" value="<?php echo opinionstage_callback_url()?>">
					<input id="os-email" type="email" name="email" placeholder="Your email" data-os-email-input required>
					<button class="opinionstage-connect-btn opinionstage-blue-btn" type="submit" id="os-start-login" data-os-login>CONNECT</button>
				</form>
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
	<div class="opinionstage-dashboard">
		<div class="opinionstage-placement-dashboard">
			<div id="opinionstage-section-placements" class="opinionstage-dashboard-section <?php echo( $os_client_logged_in ? '' : 'opinionstage-disabled-section' ) ?>">
				<div class="opinionstage-section-header">
					<div class="opinionstage-section-title">Placements</div>
					<a href="https://help.opinionstage.com/wordpress-plugin/how-to-add-items-to-your-site-using-placements" style="float: right;" target="_blank">Need help working with Placements?</a>
				</div>
				<div class="opinionstage-section-content-wrapper">
					<div class="opinionstage-section-content">
						<div class="opinionstage-section-raw">
							<div class="opinionstage-section-cell opinionstage-toggle-cell">
								<div class="opinionstage-onoffswitch <?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>">
									<input type="checkbox" name="fly-out-switch" class="opinionstage-onoffswitch-checkbox"
												<?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>
												id="fly-out-switch"
												<?php echo($os_client_logged_in && $os_options['fly_out_active'] == 'true' ? 'checked' : '') ?>
									>
									<label class="opinionstage-onoffswitch-label" for="fly-out-switch">
										<div class="opinionstage-onoffswitch-inner"></div>
										<div class="opinionstage-onoffswitch-switch"></div>
									</label>
								</div>
							</div>
							<div class="opinionstage-section-cell opinionstage-description-cell">
								<div class="title">Popup</div>
								<div class="example">Add a content popup to your site</div>
							</div>
							<div class="opinionstage-section-cell opinionstage-btns-cell">
								<?php if ( $os_client_logged_in ) {?>
								<a href="<?php echo opinionstage_flyout_edit_url('content'); ?>" class='opinionstage-blue-bordered-btn opinionstage-edit-content' target="_blank">SELECT ITEM</a>
								<a href="<?php echo opinionstage_flyout_edit_url('settings'); ?>" class='opinionstage-blue-bordered-btn opinionstage-edit-settings' target="_blank">
									<div class="os-icon-plugin icon-os-common-settings"></div>
								</a>
								<?php } else { ?>
								<a class='opinionstage-blue-bordered-btn opinionstage-edit-content disabled'>SELECT ITEM</a>
								<a class='opinionstage-blue-bordered-btn opinionstage-edit-settings disabled'>
									<div class="os-icon-plugin icon-os-common-settings"></div>
								</a>
								<?php } ?>
							</div>
						</div>
						<div class="opinionstage-section-raw">
							<div class="opinionstage-section-cell opinionstage-toggle-cell">
								<div class="opinionstage-onoffswitch <?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>">
									<input type="checkbox" name="article-placement-switch" class="opinionstage-onoffswitch-checkbox"
												<?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>
												id="article-placement-switch"
												<?php echo( $os_client_logged_in && $os_options['article_placement_active'] == 'true' ? 'checked' : '') ?>
									>
									<label class="opinionstage-onoffswitch-label" for="article-placement-switch">
										<div class="opinionstage-onoffswitch-inner"></div>
										<div class="opinionstage-onoffswitch-switch"></div>
									</label>
								</div>
							</div>
							<div class="opinionstage-section-cell opinionstage-description-cell">
								<div class="title">Article</div>
								<div class="example">Add a content section to all posts</div>
							</div>
							<div class="opinionstage-section-cell opinionstage-btns-cell">
								<?php if ( $os_client_logged_in ) {?>
								<a href="<?php echo opinionstage_article_placement_edit_url('content'); ?>" class='opinionstage-blue-bordered-btn opinionstage-edit-content' target="_blank">SELECT ITEM</a>
								<a href="<?php echo opinionstage_article_placement_edit_url('settings'); ?>" class='opinionstage-blue-bordered-btn opinionstage-edit-settings' target="_blank">
									<div class="os-icon-plugin icon-os-common-settings"></div>
								</a>
								<?php } else { ?>
								<a class='opinionstage-blue-bordered-btn opinionstage-edit-content disabled'>SELECT ITEM</a>
								<a class='opinionstage-blue-bordered-btn opinionstage-edit-settings disabled'>
									<div class="os-icon-plugin icon-os-common-settings"></div>
								</a>
								<?php } ?>
							</div>
						</div>
						<div class="opinionstage-section-raw">
							<div class="opinionstage-section-cell opinionstage-toggle-cell">
								<div class="opinionstage-onoffswitch <?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>">
									<input type="checkbox" name="sidebar-placement-switch" class="opinionstage-onoffswitch-checkbox"
												<?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>
												id="sidebar-placement-switch"
												<?php echo($os_client_logged_in && $os_options['sidebar_placement_active'] == 'true' ? 'checked' : '') ?>
									>
									<label class="opinionstage-onoffswitch-label" for="sidebar-placement-switch">
										<div class="opinionstage-onoffswitch-inner"></div>
										<div class="opinionstage-onoffswitch-switch"></div>
									</label>
								</div>
							</div>
							<div class="opinionstage-section-cell opinionstage-description-cell">
								<div class="title">Sidebar Widget</div>
								<div class="example">
									<?php if ( !$os_client_logged_in ) {?>
									Add content to your sidebar
									<?php } else { ?>
									<div class="os-long-text">
										<a href="<?php echo $url = get_admin_url('', '', 'admin') . 'widgets.php' ?>">Add widget to your sidebar</a>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="opinionstage-section-cell opinionstage-btns-cell">
								<?php if ( $os_client_logged_in ) {?>
								<a href="<?php echo opinionstage_sidebar_placement_edit_url('content'); ?>" class='opinionstage-blue-bordered-btn opinionstage-edit-content' target="_blank">SELECT ITEM</a>
								<a href="<?php echo opinionstage_sidebar_placement_edit_url('settings'); ?>" class='opinionstage-blue-bordered-btn opinionstage-edit-settings' target="_blank">
									<div class="os-icon-plugin icon-os-common-settings"></div>
								</a>
								<?php } else { ?>
								<a class='opinionstage-blue-bordered-btn opinionstage-edit-content disabled'>SELECT ITEM</a>
								<a class='opinionstage-blue-bordered-btn opinionstage-edit-settings disabled'>
									<div class="os-icon-plugin icon-os-common-settings"></div>
								</a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>

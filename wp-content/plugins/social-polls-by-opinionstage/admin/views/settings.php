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
				<div class='opinionstage-status-title'><b class="opinionstage-title" style="font-size: 20px;">Connect WordPress with Opinion Stage to get started</b></div>
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
		<div class="opinionstage-dashboard-left">
			<div id="opinionstage-section-create" class="opinionstage-dashboard-section">
				<div class="opinionstage-section-header">
					<div class="opinionstage-section-title">Create</div>
					<?php echo opinionstage_help_links('Need help adding items to your site?', 'https://help.opinionstage.com/wordpress-plugin/how-to-add-items-to-your-wordpress-site', 'opinionstage-need-help-link'); ?>
				</div>
				<div class="opinionstage-section-content" style="position: relative;">
					<div class="opinionstage-section-raw">
						<div class="opinionstage-section-cell opinionstage-icon-cell">
							<div class="os-icon-plugin"><img src="<?php echo plugins_url( 'images/poll.png', dirname(__FILE__) ); ?>" ></div>
						</div>
						<div class="opinionstage-section-cell opinionstage-description-cell">
							<div class="title">Poll</div>
							<div class="example">Get opinions, run contests & competitions</div>
						</div>
						<div class="opinionstage-section-cell opinionstage-btn-cell">
							<?php echo opinionstage_create_poll_link('opinionstage-blue-btn opinionstage-create-btn os_create_new_btn template'); ?>
							<?php echo opinionstage_template_poll_link('opinionstage-blue-bordered-btn opinionstage-create-btn os_use_template_btn template'); ?>
						</div>
					</div>
					<div class="opinionstage-section-raw">
						<div class="opinionstage-section-cell opinionstage-icon-cell">
							<div class="os-icon-plugin"><img src="<?php echo plugins_url( 'images/personality.png', dirname(__FILE__) ); ?>" ></div>
						</div>
						<div class="opinionstage-section-cell opinionstage-description-cell">
							<div class="title">Personality Quiz</div>
							<div class="example">Create a personality test or a product/service selector</div>
						</div>
						<div class="opinionstage-section-cell opinionstage-btn-cell">
							<?php echo opinionstage_create_personality_link('opinionstage-blue-btn opinionstage-create-btn os_create_new_btn template'); ?>
							<?php echo opinionstage_template_personality_quiz_link('opinionstage-blue-bordered-btn opinionstage-create-btn os_use_template_btn template') ?>
						</div>
					</div>
					<div class="opinionstage-section-raw">
						<div class="opinionstage-section-cell opinionstage-icon-cell">
							<div class="os-icon-plugin"><img src="<?php echo plugins_url( 'images/trivia.png', dirname(__FILE__) ); ?>" ></div>
						</div>
						<div class="opinionstage-section-cell opinionstage-description-cell">
							<div class="title">Trivia Quiz</div>
							<div class="example">Create a knowledge test or assessment</div>
						</div>
						<div class="opinionstage-section-cell opinionstage-btn-cell">
							<?php echo opinionstage_create_trivia_link('opinionstage-blue-btn opinionstage-create-btn os_create_new_btn template'); ?>
							<?php echo opinionstage_template_trivia_link('opinionstage-blue-bordered-btn opinionstage-create-btn os_use_template_btn template'); ?>
						</div>
					</div>
					<div class="opinionstage-section-raw">
						<div class="opinionstage-section-cell opinionstage-icon-cell">
							<div class="os-icon-plugin"><img src="<?php echo plugins_url( 'images/survey.png', dirname(__FILE__) ); ?>" ></div>
						</div>
						<div class="opinionstage-section-cell opinionstage-description-cell">
							<div class="title">Survey</div>
							<div class="example">Gather feedback from your users</div>
						</div>
						<div class="opinionstage-section-cell opinionstage-btn-cell">
							<?php echo opinionstage_create_survey_link('opinionstage-blue-btn opinionstage-create-btn os_create_new_btn template'); ?>
							<?php echo opinionstage_template_survey_link('opinionstage-blue-bordered-btn opinionstage-create-btn os_use_template_btn template'); ?>
						</div>
					</div>
					<div class="opinionstage-section-raw">
						<div class="opinionstage-section-cell opinionstage-icon-cell">
							<div class="os-icon-plugin"><img src="<?php echo plugins_url( 'images/form.png', dirname(__FILE__) ); ?>" ></div>
						</div>
						<div class="opinionstage-section-cell opinionstage-description-cell">
							<div class="title">Form</div>
							<div class="example">Gather information from your users</div>
						</div>
						<div class="opinionstage-section-cell opinionstage-btn-cell">
							<?php echo opinionstage_create_form_link('opinionstage-blue-btn opinionstage-create-btn os_create_new_btn template'); ?>
							<?php echo opinionstage_template_form_link('opinionstage-blue-bordered-btn opinionstage-create-btn os_use_template_btn template'); ?>
						</div>
					</div>
					<div class="opinionstage-section-raw">
						<div class="opinionstage-section-cell opinionstage-icon-cell">
							<div class="os-icon-plugin"><img src="<?php echo plugins_url( 'images/slideshow.png', dirname(__FILE__) ); ?>" ></div>
						</div>
						<div class="opinionstage-section-cell opinionstage-description-cell">
							<div class="title">Slideshow</div>
							<div class="example">Group items in an interactive display</div>
						</div>
						<div class="opinionstage-section-cell opinionstage-btn-cell">
							<?php echo opinionstage_create_slideshow_link( 'opinionstage-blue-btn opinionstage-create-btn os_create_new_btn template' ); ?>
							<?php echo opinionstage_template_slideshow_link('opinionstage-blue-bordered-btn opinionstage-create-btn os_use_template_btn template') ?>
						</div>
					</div>
					<?php 
						if ( !$os_client_logged_in ) {
							echo '<div id="overlay"></div>';
						}
					?>
				</div>
			</div>
		</div>				
	</div>
</div>

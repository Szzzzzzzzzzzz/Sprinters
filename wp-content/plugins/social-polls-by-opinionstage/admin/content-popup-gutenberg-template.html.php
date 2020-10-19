<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

require_once( plugin_dir_path( __FILE__ ).'../includes/opinionstage-client-session.php' );

$opinionstage_user_logged_in = opinionstage_user_logged_in();

function opinionstage_create_new_href() {
	return OPINIONSTAGE_SERVER_BASE.'/dashboard/content';
}

// Note: all html put here (not moved to js build system) in order to preserve ability to use Wordpress translate APIs
?>
<style type="text/css">
		.progress_message{
			display: none;
		}
		.content__image {
		    background-image: url(<?php echo plugins_url('', dirname(__FILE__) ) . '/admin/images/form-not-found.png' ?>);
		    background-repeat: no-repeat;
    		background-size: cover;
		}
</style>	
<template data-opinionstage-content-popup-template>
	<div class='opinionstage-content-popup-contents' data-opinionstage-content-popup data-opinionstage-client-logged-in="<?php echo $opinionstage_user_logged_in ?>">
		<header class='header'>
			<div class='header__container'>
				<div class='header__logo'>
					<a href='<?php echo OPINIONSTAGE_SERVER_BASE ?>' target='_blank'>
						<img src='<?php echo plugins_url('admin/images/os-logo-header.png', plugin_dir_path( __FILE__ )) ?>'>
					</a>
				</div>
				<div class='header__action'>
					<div class='btn-close' @click="closePopup">X</div>
				</div>
			</div>
		</header>
		<section>
			<popup-content
				:show-client-content="showClientContent"
				:client-is-logged-in="isClientLoggedIn"
				:modal-is-opened="isModalOpened"
				@insert-shortcode="insertShortcode"
				client-widgets-url="<?php echo OPINIONSTAGE_CONTENT_POPUP_CLIENT_WIDGETS_API ?>"
				shared-widgets-url="<?php echo OPINIONSTAGE_CONTENT_POPUP_SHARED_WIDGETS_API ?>"
				client-widgets-has-new-url="<?php echo OPINIONSTAGE_CONTENT_POPUP_CLIENT_WIDGETS_API_RECENT_UPDATE ?>"
				access-key="<?php echo opinionstage_user_access_token() ?>"
				plugin-version="<?php echo OPINIONSTAGE_WIDGET_VERSION ?>"
			>
			</popup-content>
		</section>
	</div>
</template>

<template id="opinionstage-widget-list">
<div class='page-content'>
	<div class='content-actions'>
		<div class="popup-header-title">My Items</div>
		<div class="header-right-container">
		<div class="header-right-inner-container">
		<div class='filter'>
			<div class="dropdown dropdown_items">
				<button class="dropbtn gutenberg_dropdown" id="dropbtn"><span>All ITEMS</span></button>
				<div class="dropdown-content">
					<div class='filter__itm'
							@click="selectWidgetType('all')"
							:class="{ active: selectedWidgetType === 'all' }"
					>all Items</div>
					<div class='filter__itm'
							@click="selectWidgetType('poll')"
							:class="{ active: selectedWidgetType === 'poll' }"
					>poll</div>
					<div class='filter__itm'
							@click="selectWidgetType('set')"
							:class="{ active: selectedWidgetType === 'set' }"
					>multi poll set</div>
					<div class='filter__itm'
							@click="selectWidgetType('survey')"
							:class="{ active: selectedWidgetType === 'survey' }"
					>survey</div>
					<div class='filter__itm'
							@click="selectWidgetType('slideshow')"
							:class="{ active: selectedWidgetType === 'slideshow' }"
					>slideshow</div>
					<div class='filter__itm'
							@click="selectWidgetType('trivia')"
							:class="{ active: selectedWidgetType === 'trivia' }"
					>trivia quiz</div>
					<div class='filter__itm'
							@click="selectWidgetType('outcome')"
							:class="{ active: selectedWidgetType === 'outcome' }"
					>personality quiz</div>
					<div class='filter__itm'
							@click="selectWidgetType('list')"
							:class="{ active: selectedWidgetType === 'list' }"
					>list</div>
					<div class='filter__itm'
							@click="selectWidgetType('form')"
							:class="{ active: selectedWidgetType === 'form' }"
					>form</div>
					<div class='filter__itm'
							@click="selectWidgetType('story')"
							:class="{ active: selectedWidgetType === 'story' }"
					>story article</div>
				</div>
			</div>
		</div>
		<div class='search' style="width: 180px;">
			<input
				class='std-search'
				placeholder='Search...'
				type='text'
				v-model='widgetTitleSearch'
				:class='{ hidden: !showSearch }'
			>
		</div>
		</div>
		<a href="<?php echo admin_url( 'admin.php?page=opinionstage-settings' ); ?>" target='_blank' class="popup-header-item-create-btn">CREATE</a>
		</div>
	</div>
	<span class="progress_message" style="font-size: 16px; text-align: center;">Loading...</span>
	<div class='content__list'>
		<div v-if='hasData'>
			<div class='content__itm' v-for="widget in widgets">
				<a target="_blank" :href='widget.landingPageUrl'>
				<div class='content__image'>
					<img :src='widget.imageUrl'>
					<div class='content__label'>{{ widget.type }}</div>
				</div>
				</a>
				<p class='content__info'><a target="_blank" :href='widget.editUrl'>{{ widget.title }}</a></p>
				<div class='content__links'>
					<button class='popup-content-btn content__links-itm'
					@click="insertIntoGutenberg(widget)">insert</button>
					<div class="dropdown dropdown-popup-action">
						<div class="popup-action popup-content-btn"></div>
						<div class="content-item-menu">
							<div class="top-arrow-box"><div class="top-arrow"></div></div>
							<div class="popup-action-dropdown dropdown-content">
								<a class='content__links-itm' target="_blank" :href='widget.landingPageUrl'>view</a>
								<a class='content__links-itm' target="_blank" :href='widget.editUrl' v-show="!widget.template">edit</a>
								<a class='content__links-itm' target="_blank" :href='widget.statsUrl' v-show="!widget.template">Results</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class='content__loading' v-if='dataLoading'>
				loading...
			</div>
			<div v-else>
				<button
					class='btn-show-more'
					v-if='!noMoreData'
					@click='showMore'
				>Click for more</button>
			</div>
		</div>
		<div v-else>
			No items found
		</div>
	</div>
</div>
</template>

<template id="opinionstage-popup-content">
	<div v-if="showClientContent">
		<div v-if="clientIsLoggedIn">
			<div v-if="newWidgetsAvailable" class="notification-container">
				<notification
					:widget-type='searchCriteria.type'
					@reload='reloadData'
					@hide='startWidgetUpdatesChecker'
				>
			</div>
			<widget-list
				:widgets='widgets'
				:data-loading='dataLoading'
				:show-search='true'
				:no-more-data='noMoreData'
				@insert-shortcode="insertShortcode"
				@widgets-search-update='reloadData'
				@load-more-widgets='appendData'
			>
		</div>
		<div class='page-content' v-else>
			<div class="bordered-container">
				<h1 class='main-title'>
					<b>Connect WordPress</b>
					<span>with</span>
					<b>Opinion Stage</b>
					<span>to get started</span>
				</h1>
				<form action="<?php echo OPINIONSTAGE_LOGIN_PATH ?>" method="get" class="opinionstage-connect-form">
					<input type="hidden" name="utm_source" value="<?php echo OPINIONSTAGE_UTM_SOURCE ?>">
					<input type="hidden" name="utm_campaign" value="<?php echo OPINIONSTAGE_UTM_CAMPAIGN ?>">
					<input type="hidden" name="utm_medium" value="<?php echo OPINIONSTAGE_UTM_MEDIUM ?>">
					<input type="hidden" name="o" value="<?php echo OPINIONSTAGE_WIDGET_API_KEY ?>">
					<input type="hidden" name="callback" value="<?php echo opinionstage_custom_content_popup_callback_url() ?>">
					<input id="os-email" type="email" name="email" placeholder="Enter Your Email" data-os-email-input class="opinionstage-conect-input">
					<button class="opinionstage-blue-btn" type="submit" id="os-start-login" data-os-login>CONNECT</button>
				</form>
			</div>
		</div>
	</div>
	<div v-else>
		<widget-list
			:widgets='widgets'
			:data-loading='dataLoading'
			:show-search='false'
			:no-more-data='noMoreData'
			@insert-shortcode='insertShortcode'
			@widgets-search-update='reloadData'
			@load-more-widgets='appendData'
		>
	</div>
</template>

<template id="opinionstage-notification">
	<div class="opinionstage-section-notification">
		<p class="opinionstage-section-notification__title">
			Your content has been updated, please click the button to update your view
		</p>
		<div class="opinionstage-section-notification__controls">
			<button class='btn-blue' @click="reload">Update view</button>
		</div>
	</div>
</template>
	<script>
		jQuery(document).ready(function ($) {	
		   	$('.filter__itm').on('click', null, function(e) {
			   	var text = $(this).text();
		   		$("button#dropbtn span").text(text);

		   		$('.dropdown_items .dropdown-content').attr('style', 'display: none !important');
		   		$(".dropdown_items #dropbtn").hover(function() {
				  $('.dropdown_items .dropdown-content').css("display","block");
				});
			});
			
			jQuery(document).on('click', null, function(e) {
				$('.dropdown_items .dropdown-content').attr('style', 'display: none !important');
			});

			$('div#show-templates').on('click', null, function(e) {
				var inputs = $(".filter__itm");
               	for(var i = 0; i < inputs.length; i++){
                   	if($(inputs[i]).text() == 'story article'){
                    	$(inputs[i]).hide();
                    	break; 
                    }
                }
			});
		});
	</script>

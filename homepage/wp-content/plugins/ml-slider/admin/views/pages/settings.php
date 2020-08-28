<?php if (!defined('ABSPATH')) die('No direct access.'); ?>

<div
	id="metaslider-ui"
	class="metaslider metaslider-ui block min-w-0 p-0 bg-white"
	style="min-height:calc(100vh - 32px)">
<metaslider-toolbar inline-template>
<div id="ms-toolbar"
	class="flex-col bg-white h-16 shadow-sm sticky z-999">
	<div class="h-full px-6">
		<div class="flex items-center h-full -mx-4">
			<a href="<?php echo admin_url('admin.php?page=metaslider') ?>" class="flex items-center h-full py-2 px-4">
				<img style="height:2.3rem;width:2.3rem" width=40 height=40 class="mr-2 rtl:mr-0 rtl:ml-2" src="<?php echo METASLIDER_ADMIN_URL ?>images/metaslider_logo_large.png" alt="MetaSlider">
				<span class="text-2xl font-sans font-thin text-orange leading-none">
					<span class="font-normal">Meta</span>Slider
					<span class="block font-semibold text-sm font-mono text-gray tracking-tight">
						v<?php echo metaslider_pro_is_active() ?  metaslider_pro_version() . '<span class="ml-1">Premium</span>' : $this->version; ?>
					</span>
				</span>
			</a>
		</div>
	</div>
</div>
</metaslider-toolbar>
<div v-if="isIE11" class="flex justify-center bg-gray-lighter">
	<?php include METASLIDER_PATH."admin/views/pages/parts/ie-warning.php"; ?>
</div>
<div v-else>
<metaslider inline-template>
<metaslider-settings-page inline-template>
<div
	id="metaslider-settings-page"
	class="absolute bg-gray-light border-b border-gray-light flex h-full inset-0 min-w-0 p-0 w-full">
	<div class="flex md:flex-shrink-0">
		<div class="flex flex-col md:w-48 border-r border-gray-lightest pt-5 pb-4 bg-white">
			<div class="-mt-1 h-0 flex-1 flex flex-col overflow-y-auto">
				<nav class="flex-1 px-2 py-1 bg-white">
					<a
						href="#"
						@click.prevent="loadPage('general')"
						@keydown.space.prevent="loadPage('general')"
						@keyup.enter.prevent="loadPage('general')"
						class="group flex items-center justify-between px-2 py-2 text-sm leading-5 font-medium text-gray-darker rounded-md hover:text-gray-darkest hover:bg-gray-light focus:outline-none transition ease-in-out duration-150"
						:class="{'text-gray-darker bg-gray-light': 'general' === component}">
						<span class="hidden md:flex"><?php _e('Settings', 'ml-slider'); ?></span>
						<font-awesome-icon
							icon='cog'
							class='md:mr-2 h-4 w-4 group-hover:text-gray-darkest group-focus:text-gray-darkest transition ease-in-out duration-150'
							:class="{'text-gray-darker': 'general' === component, 'md:text-white': 'general' !== component}"/>
						</font-awesome-icon>
					</a>
					<a
						href="#"
						@click.prevent="loadPage('import')"
						@keydown.space.prevent="loadPage('import')"
						@keyup.enter.prevent="loadPage('import')"
						class="mt-1 group flex items-center justify-between px-2 py-2 text-sm leading-5 font-medium text-gray-darker rounded-md hover:text-gray-darkest hover:bg-gray-light transition ease-in-out duration-150"
						:class="{'text-gray-darker bg-gray-light': 'import' === component}">
						<span class="hidden md:flex"><?php _e('Import', 'ml-slider'); ?></span>
						<font-awesome-icon
							icon='sign-in-alt'
							class='md:mr-2 h-4 w-4 group-hover:text-gray-darkest group-focus:text-gray-darkest transition ease-in-out duration-150'
							:class="{'text-gray-darker': 'import' === component, 'md:text-white': 'import' !== component}"/>
						</font-awesome-icon>
					</a>
					<a
						href="#"
						@click.prevent="loadPage('export')"
						@keydown.space.prevent="loadPage('export')"
						@keyup.enter.prevent="loadPage('export')"
						class="mt-1 group flex items-center justify-between px-2 py-2 text-sm leading-5 font-medium text-gray-darker rounded-md hover:text-gray-darkest hover:bg-gray-light transition ease-in-out duration-150"
						:class="{'text-gray-darker bg-gray-light': 'export' === component}">
						<span class="hidden md:flex"><?php _e('Export', 'ml-slider'); ?></span>
						<font-awesome-icon
							icon='sign-out-alt'
							class='md:mr-2 h-4 w-4 group-hover:text-gray-darkest group-focus:text-gray-darkest transition ease-in-out duration-150'
							:class="{'text-gray-darker': 'export' === component, 'md:text-white': 'export' !== component}"/>
						</font-awesome-icon>
					</a>
					<?php do_action('metaslider_settings_page_nav_items'); ?>
				</nav>
			</div>
		</div>
	</div>
	<div class="flex flex-col w-0 flex-1 overflow-hidden">
		<main class="flex-1 relative z-0 overflow-y-auto py-6 focus:outline-none" tabindex="0">
			<div class="max-w-7xl w-full px-4 md:px-6 md:px-8">
				<transition name="settings-fade" mode="out-in">
					<?php $slideshowCount = wp_count_posts('ml-slider'); ?>
					<component :needs-slideshows="<?php echo $slideshowCount->publish < 1 ? 'true' : 'false'; ?>" :is="$options.components[component]"></component>
				</transition>
			</div>
		</main>
	</div>
</div>
</metaslider-settings-page>
</metaslider>
</div>
</div>

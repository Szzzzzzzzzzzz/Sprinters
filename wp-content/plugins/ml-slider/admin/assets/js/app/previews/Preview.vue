<template>
	<div
		id="preview-component"
		:class="{ 'ms-has-error': errorMessage.length }"
		class="h-full z-max relative">
		<sweet-modal
			:ref="'preview'"
			:class="{'control-light': lightsOn}"
			:overlay-theme="overlayTheme"
			:modal-theme="overlayTheme"
			:blocking="true"
			:pulse-on-block="false"
			hide-close-button>
			<div
				slot="box-action"
				class="ms-preview-toolbar">
				<h2 class="ms-preview-title bg-orange text-white flex items-center h-full m-0 pl-6 pr-8 relative overflow-hidden uppercase text-sm font-medium">
					<span>{{ __('Preview', 'ml-slider') }}</span>
				</h2>
				<div class="ms-preview-tools">
					<i
						:title="__('Toggle overlay type', 'ml-slider') + ' (L)'"
						class="lightbulb tipsy-tooltip-bottom"
						@click="toggleLights">
						<font-awesome-icon
							icon="lightbulb"/>
					</i>
					<i
						:title="__('Toggle full width', 'ml-slider') + ' (F)'"
						:class="{'active': showFullwidth}"
						class="tipsy-tooltip-bottom"
						@click="toggleFullwidth">
						<font-awesome-icon
							:icon="showFullwidth ? 'compress' : 'expand'"/>
					</i>
				</div>
				<i
					:title="__('Exit preview', 'ml-slider') + ' (ESC)'"
					class="tipsy-tooltip mr-6 rtl:ml-6 rtl:mr-0"
					@click="closePreview">
					<font-awesome-icon
						icon="times"/>
				</i>
			</div>
			<font-awesome-icon
				v-if="!iframeLoaded && !errorMessage.length"
				transform="grow-4"
				icon="spinner"
				class="fa-spin"/>
			<p
				v-if="errorMessage.length"
				class="ms-error"
				v-text="errorMessage"/>
			<iframe
				v-if="'' !== html"
				:class="{'ms-invisible':!iframeLoaded}"
				:id="'iframe-' + _uid"
				:srcdoc="html"
				frameborder="0"
				@load="setupIframe"
			/>
			<p
				v-if="notFullySupported"
				class="ms-feature-not-supported">
				{{ __('This feature is not fully supported in this browser.', 'ml-slider') }}
			</p>
		</sweet-modal>
	</div>
</template>

<script>
// TODO Maybe we dont need to save first if on a theme view
// green checkmark

import { EventManager } from '../utils'
import { Axios } from '../api'
import './components'
import srcDoc from 'srcdoc-polyfill'
import hotkeys from 'hotkeys-js';
import { mapGetters } from 'vuex'

export default {
	props: {},
	data() {
		return {
			html: '',
			slideshowId: '',
			theme_id: '',
			iframeLoaded: false,
			errorMessage: '',
			previewIframe: {},
			overlayTheme: 'dark',
			showFullwidth: false,
			notFullySupported: !('srcdoc' in document.createElement('iframe')),
			resizeEvent: {}
		}
	},
	computed: {
		lightsOn() {
			// TODO: save the state in the settings behind the scenes
			return 'dark' !== this.overlayTheme
		},
		maxWidth() {
			// TODO: refactor when settings object is implimented in vue store
			let width = parseInt(document.getElementsByName('settings[width]')[0].value, 10)

			// This accounts the 40px padding on each side.
			return (!this.showFullwidth && width) ? width + 'px' : '100%'
		},
		...mapGetters({
			current: 'slideshows/getCurrent'
		})
	},
	mounted() {
		// Note, the slideshow should be saved BEFORE this event is fired
		EventManager.$on('metaslider/open-preview', ({ slideshowId, themeId }) => {
			this.openPreview(slideshowId, themeId)
		})

		hotkeys('alt+p', () => this.handleOpeningPreviewByKeyboard())
	},
	methods: {
		hasSlides() {
			return document.querySelector('tr.slide:not(.ms-deleted)')
		},
		openPreview(slideshowId = null, themeId = null) {
			// If no images are found, offer to import some.
			if (!this.hasSlides()) {
				EventManager.$emit('import-notice', themeId || this.current.theme.folder)
				return false
			}

			// Add events for keyboard controls
			document.addEventListener('keyup', this.handleKeyups)

			// Reset to not show fullwidth whenever loaded.
			this.showFullwidth = false

			// Open the specific preview
			this.$refs['preview'].open()

			// Fetch the iframe
			this.fetchIframe(slideshowId, themeId)

		},
		closePreview() {
			this.$refs['preview'].close()
			this.html = ''
			this.iframeLoaded = false
			this.errorMessage = ''
			document.removeEventListener('keyup', this.handleKeyups)
		},
		fetchIframe(slideshowId = null, themeId = null) {
			this.errorMessage = ''
			Axios.get('slideshow/preview', {
				params: {
					action: 'ms_get_preview',
					theme_slug: themeId || this.current.theme.folder, // Used for pro themes
					slideshow_id: slideshowId || this.current.id,
					theme_id: themeId || this.current.theme.folder
				}
			}).then(response => {
				this.html = response.data.data

				// polyfill for ie11
				this.$nextTick(() => {
					srcDoc.set(document.getElementById('iframe-' + this._uid))

					// ! Somehow this is an IE11 fix. I'm guessing it forces Vuejs to compare
					// ! the dom to the virtual dom and force the update. Oh well, it works
					if (this.notFullySupported) console.log(document.getElementById('iframe-' + this._uid))
				})
				this.notifySuccess('metaslider/preview-loaded', 'Preview loaded')
			}).catch(error => {
				this.iframeLoaded = true
				this.errorMessage = this.getErrorMessage(error.response)
				this.notifyError('metaslider/preview-error', error)
			})
		},
		setupIframe(event) {
			this.previewIframe = {
				window: event.target.contentWindow,
				document: event.target.contentDocument,
				container: event.target.contentDocument.getElementById('preview-container'),
				slideshow: event.target.contentDocument.querySelector('.metaslider')
			}

			// Add events for keyboard controls for when focus is inside the iframe
			this.previewIframe.document.addEventListener('keyup', this.handleKeyups)

			// Set the slideshow to 100% width
			this.previewIframe.slideshow.style.width = '100%'

			// Add a way to fake a resize event inside the iframe, and trigger it
			if ('function' !== typeof window.Event) { // IE 11 polyfill
				this.resizeEvent = this.previewIframe.window.document.createEvent('UIEvents')
				this.resizeEvent.initUIEvent('resize', true, false, window, 0)
			} else {
				this.resizeEvent = new Event('resize')
			}

			// If the slideshow is a carousel make full width
			if (document.getElementsByName('settings[carouselMode]')[0].checked) {
				this.toggleFullwidth()
			}

			this.previewIframe.window.dispatchEvent(this.resizeEvent)
			this.iframeLoaded = true
		},
		toggleFullwidth() {
			this.showFullwidth = !this.showFullwidth

			// Set the container and slideshow to full width
			this.previewIframe.container.style.maxWidth = this.maxWidth
			this.previewIframe.slideshow.style.maxWidth = this.maxWidth

			// trigger a resize in the iframe to let the slider recalculate itself
			this.previewIframe.window.dispatchEvent(this.resizeEvent)
		},
		toggleLights() {
			this.overlayTheme = 'dark' === this.overlayTheme ? 'light' : 'dark'
		},
		handleKeyups(event) {
			70 === event.keyCode && this.toggleFullwidth() // F
			76 === event.keyCode && this.toggleLights() // L
			27 === event.keyCode && this.closePreview() // ESC
		},
		handleOpeningPreviewByKeyboard() {

			if (this.$parent.saving) return false

			if (document.getElementById('preview-component').length) {
				return false
			}

			// This will also offer to import slides if none exist
			EventManager.$emit('metaslider/preview')
		}
	}
}
</script>

<style lang="scss">
	@import '../assets/styles/globals.scss';
	div#preview-component {
		float: left;
		> .sweet-modal-overlay {
			background: #FFF;
			&.theme-dark {
				background: $wp-black;
			}
			.sweet-modal {
				background: transparent;
				box-shadow: none;
				min-width: 100%;
				padding: 0;
				.sweet-content,
				.sweet-content-content,
				iframe {
					width: 100%;
					height:100%;
				}
				.fa-spinner {
					position: absolute;
					left: 50%;
					top: 50%;
					margin: -10px 0 0 -10px;
				}
			}
		}
		.ms-preview-toolbar {
			height: 32px;
			position: fixed;
			left: 0;
			top: 0;
			background-color: $wp-gray-light;
			padding: 0;
			width: 100%;
			display: flex;
			align-items: center;
			justify-content: space-between;
		}
		.ms-preview-title::after {
			content: ' ';
			display: block;
			height: 50px;
			width: 50px;
			background: transparent;
			position: absolute;
			right: 0;
			top: 50%;
			transform-origin: top right;
			transform: translateX(26px) rotate(45deg);
			border-top: 20px solid $wp-gray-light;
			border-right: 20px solid $wp-gray-light;
			z-index: 1;
		}
		.ms-preview-toolbar i {
			color: $wp-black;
			transition: all 0.2s ease-in-out;
			cursor: pointer;
			font-size: 1.4em;
			margin-left: 0.25rem;
			line-height: 27px;
			display: inline-block;
			&:hover {
				color: $wp-gray;
				transition: all 0.1s ease-in-out;
			}
		}
		.ms-preview-tools {
			display: flex;
			justify-content: center;
			align-items: center;
			position: absolute;
			height: 32px;
			top: 0;
			left: 50%;
			margin-left: -22px;
			i {
				padding: 0 6px;
				border-radius: 3px;
				&:hover {
					background: $wp-gray;
					color: white;
				}
				&.active {
					color: white;
					background: $wp-black;
				}
			}
		}
		.control-light .ms-preview-toolbar i.lightbulb {
			color: white;
			background: $wp-black;
		}
		&.ms-has-error {
			.sweet-content-content {
				justify-content: center;
				.ms-error {
					font-size: 20px;
				}
			}

		}
	}
</style>

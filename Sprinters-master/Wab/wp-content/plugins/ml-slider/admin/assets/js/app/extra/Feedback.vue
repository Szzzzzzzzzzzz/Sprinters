<template>
	<a
		v-show="show"
		:href="feedbackUrl"
		:class="'ms-feedback-button-' + styleType"
		target="_blank"
		class="ms-feedback"
		@click.prevent="openPre()">Feedback</a>
</template>

<script>
// Very basic for now but could be expanded to include extra data,
// or handle sending the message itself.
import Vue from 'vue'
import Swal from 'sweetalert2'
import '@fortawesome/vue-fontawesome'
import '../assets/icons'
export default {
	props: {
		feedbackUrl: {
			type: String,
			default: ''
		},
		supportUrl: {
			type: String,
			default: 'https://wordpress.org/support/plugin/ml-slider'
		},
		show: {
			type: Boolean,
			default: false
		},
		styleType: {
			type: String,
			default: 'regular'
		}
	},
	data() {
		return {}
	},
	computed: {},
	mounted() {},
	methods: {
		openPre() {
			Swal.fire({
				title: this.__('Feedback', 'ml-slider'),
				icon: 'info',
				iconHtml: '<div class="dashicons dashicons-megaphone" style="transform: scale(3.5);"></div>',
				confirmButtonText: this.__('Take me there', 'ml-slider'),
				customClass: 'shadow-lg',
				html: '<p class="text-base">' + this.__('Got ideas? We are looking forward to hearing your feedback and suggestions!', 'ml-slider') + '</p>',
				footer: this.sprintf(
					this.__('<p>If this is a request for support, <a %1s href="%2s">visit here instead %3s</a></p>', 'ml-slider'),
					'target="_blank"',
					this.supportUrl,
					'<font-awesome-icon icon="external-link-alt"/>'
				)
			}).then(result => {
				result.value && window.open(this.feedbackUrl, '_blank')
			})

			// These force the components in the modal to render
			/* eslint no-new: "off" */
			new Vue({ el: Swal.getFooter() })
		}
	}
}
</script>

<style lang="scss">
@import '../assets/styles/globals.scss';
.ms-feedback {
    text-decoration: none;
	background: $brand;
	font-size: 16px;
    color: #fff;
	padding: 10px 15px 8px;
	line-height: 1;
	text-transform: uppercase;
	&:hover, &:active, &:focus {
		background: darken($brand, 5%);
		color: #fff;
		box-shadow: none;
	}
}

</style>

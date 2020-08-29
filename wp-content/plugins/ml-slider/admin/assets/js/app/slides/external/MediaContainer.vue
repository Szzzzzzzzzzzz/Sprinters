<template>
	<div style="height:100%">
		<div class="media-toolbar">
			<div class="media-toolbar-secondary">
				<label
					for="media-attachment-filters"
					class="screen-reader-text">{{ __('Filter by type', 'ml-slider') }}</label>
				<select
					v-if="$parent.filters.length"
					class="attachment-filters"
					style="min-width:150px">
					<option value="all">{{ __('All media items', 'ml-slider') }}</option>
					<option
						v-for="(filter, value) in $parent.filters"
						:key="value"
						:value="value"
					>{{ filter }}</option>
				</select>
				<span class="spinner"/>
			</div>
			<div class="media-toolbar-primary search-form">
				<!-- TODO: make search optional -->
				<label
					for="media-search-input"
					class="screen-reader-text">{{ __('Search Unsplash API', 'ml-slider') }}</label>
				<input
					id="search-unsplash"
					v-model="$parent.searchTerm"
					:placeholder="__('Search unsplash.com...', 'ml-slider')"
					type="search"
					class="search"
					@focus="notifyInfo('metaslider/unsplash-search-focused', 'Unsplash search was focused')"
					@keyup="searchByTerm"
					@search="searchByTerm">
			</div>
		</div>

		<div
			v-if="loading && !$parent.errorMessage"
			class="attachments ui-sortable ui-sortable-disabled">
			<span class="ms-full-loading">
				<font-awesome-icon
					icon="spinner"
					class="fa-2x"
					spin/>
			</span>
		</div>
		<div
			v-if="$parent.errorMessage"
			class="external-api-error">{{ $parent.errorMessage }}
		</div>
		<ul
			v-if="!loading && !$parent.errorMessage"
			tabindex="-1"
			class="attachments ui-sortable ui-sortable-disabled">
			<slot name="media-list"/>
			<li
				v-if="$parent.canLoadMore"
				class="attachment ms-load-more-api">
				<div class="attachment-preview">
					<span
						v-if="$parent.loadingMore"
						class="ms-loading-more-images">
						<font-awesome-icon
							icon="spinner"
							class="fa-2x"
							spin/>
					</span>
					<button
						v-else
						class="ms-load-more-button"
						@click="$parent.loadMore">
						<font-awesome-icon icon="plus"/>
						<span>{{ __('Load more', 'ml-slider') }}</span>
					</button>
				</div>
			</li>
		</ul>

		<div class="media-sidebar">
			<div class="ms-api-sidebar">
				<div class="ms-api-photo-details">
					<slot name="sidebar"/>
				</div>
				<div class="ms-api-copyright">
					<slot name="copyright"/>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { Helpers as _ } from '../../utils'

export default {
	// name: 'MediaContainer',
	props: {},
	data() {
		return {}
	},
	computed: {
		loading() {
			return this.$parent.loadingFresh
		}
	},
	mounted() {

		// Calculte the column width in case WP skips over it
		let columns = document.querySelector('.media-frame-content').dataset.columns
		if (!columns) {
			let width = document.querySelector('.attachments-browser .attachments').offsetWidth / 150
			document.querySelector('.media-frame-content').dataset.columns = Math.round(width)
		}

		// add an eve
	},
	methods: {
		searchByTerm: _.debounce(function() {
			this.$parent.searchByTerm()
		}, 500)
	}
}
</script>

<style lang="scss">
@import '../../assets/styles/globals.scss';
@import '../../assets/styles/mixins.scss';
.ms-load-more-api {
	.ms-load-more-button {
		display: flex;
		align-items: center;
		justify-content: center;
		position: absolute;
		top: 0;
		left: 0;
		flex-direction: column;
		width: 100%;
		height: 100%;
		border: 0;
		padding: 0;
		font-size: 1.1em;
		cursor: pointer;
		&:hover {
			background: transparent;
		}
	}
}
.ms-loading-more-images {
	position: absolute;
	top: 0;
	left: 0;
	background: white;
	display: flex;
	width: 100%;
	height: 100%;
	justify-content: center;
	align-items: center;
}
.external-api-error {
	padding: 1rem;
    color: $red;
    font-size: 1.2em;
}
.ms-api-sidebar {
	height: 100%;
	display: flex;
    flex-direction: column;
	justify-content: space-between;
	.details {
		a {
			box-shadow: none;
		}
	}
	.ms-api-copyright p {
		margin-bottom: 0;
		color: #aaa;
		a {
			color: #aaa;
			&:hover, &:active, &:focus {
				color: $wp-links;
			}
		}
	}
}
.ms-full-loading {
	display: flex;
	width: 100%;
	height: 100%;
	justify-content: center;
	align-items: center;
}
</style>

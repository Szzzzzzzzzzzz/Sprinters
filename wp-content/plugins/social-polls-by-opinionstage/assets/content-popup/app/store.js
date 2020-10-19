import Vue from 'vue'
import Vuex from 'vuex'
import trim from 'lodash.trim'
import isEmpty from 'lodash.isempty'
import join from 'lodash.join'

import JsonApi from '../lib/jsonapi.js'

Vue.use(Vuex)

function dispatchWidgetData (apiJsonData) {
  return apiJsonData.data.map( (rawWidget) => {
    return {
      id: rawWidget.id,
      type: rawWidget.attributes['type'],
      title: rawWidget.attributes['title'],
      imageUrl: rawWidget.attributes['image-url'],
      updatedAt: rawWidget.attributes['updated-at'],
      landingPageUrl: rawWidget.attributes['landing-page-url'],
      editUrl: rawWidget.attributes['edit-url'],
      statsUrl: rawWidget.attributes['stats-url'],
      shortcode: rawWidget.attributes['shortcode'],
    }
  })
}

function dispatchNextPage (apiJsonData) {
  return apiJsonData.meta.nextPage
}

function withFiltering(url, { type, title, page, perPage }) {
  const urlParams = []

  if ( type ) {
    urlParams.push( `type=${type}` )
  }

  if ( !isEmpty(title) ) {
    const trimmed = trim(title)
    if ( !isEmpty(trimmed) ) {
      urlParams.push( `title_like=${trimmed}` )
    }
  }

  if ( page ) {
    urlParams.push( `page=${page}` )
  }

  if ( perPage ) {
    urlParams.push( `per_page=${perPage}` )
  }

  if ( isEmpty(urlParams) ) {
    return url
  } else {
    return url + '?' + join(urlParams, '&')
  }
}

export default new Vuex.Store({
  state: {
    widgets: [],
    nextPageNumber: null,
  },

  mutations: {
    loadWidgets (state, {widgetsData}) {
      state.widgets.push( dispatchWidgetData(widgetsData) )
      state.nextPageNumber = dispatchNextPage(widgetsData)
    },

    loadTemplateWidgets (state, {widgetsData}) {
      state.widgets.push( dispatchWidgetData(widgetsData).map( widget => {
        widget.template = true
        return widget
      }) )
      state.nextPageNumber = dispatchNextPage(widgetsData)
    },

    clearWidgets (state) {
      state.widgets = []
      state.nextPageNumber = null
    },
  },

  actions: {
    loadClientWidgets ({ dispatch }, { widgetsUrl, pluginVersion, accessToken, filtering }) {
      return dispatch('load', {
        commitType: 'loadWidgets',
        widgetsUrl,
        pluginVersion,
        accessToken,
        filtering,
      })
    },

    loadTemplateWidgets ({ dispatch }, { widgetsUrl, pluginVersion, filtering }) {
      return dispatch('load', {
        commitType: 'loadTemplateWidgets',
        widgetsUrl,
        pluginVersion,
        filtering,
      })
    },

    load ({ commit }, { commitType, widgetsUrl, filtering, pluginVersion, accessToken }) {
      const url = withFiltering(widgetsUrl, filtering)

      return JsonApi.get(url, pluginVersion, accessToken)
        .then( (apiJson) => {
          commit({
            type: commitType,
            widgetsData: apiJson,
          })
        })
        .catch( (error) => {
          console.error( "[social-polls-by-opinionstage][content-popup] can't load widgets:", error.statusText )
        })
    },
  },
})

import Vue from 'vue'
import debounce from 'lodash.debounce'

export default Vue.component('widget-list', {
  props: [
    'widgets',
    'dataLoading',
    'noMoreData',
    'showSearch',
  ],

  template: '#opinionstage-widget-list',

  data () {
    return {
      selectedWidgetType: 'all',
      widgetTitleSearch: '',
      showMoreBtn: true,
      hasData: true,
    }
  },

  mounted () {
    widgetsSearchUpdate.call(this)
  },

  watch: {
    widgetTitleSearch: debounce(function () {
      widgetsSearchUpdate.call(this)
    }, 500),

    widgets () {
      this.hasData = this.dataLoading || this.widgets.length > 0
    },
  },

  methods: {
    select (widget) {
      this.$emit('widget-selected', widget)
    },

    selectWidgetType (type) {
      this.selectedWidgetType = type
      this.widgetTitleSearch = ''

      widgetsSearchUpdate.call(this)
    },


    showMore () {
      this.$emit('load-more-widgets')
    },
  },
})

function widgetsSearchUpdate () {
  this.$emit('widgets-search-update', {
    widgetType: this.selectedWidgetType,
    widgetTitle: this.widgetTitleSearch
  })
}

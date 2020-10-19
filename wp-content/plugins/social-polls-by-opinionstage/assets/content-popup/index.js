import Modal from './lib/modal.js'
import ContentPopupContent from './app/index.js'

if ( window.OpinionStage && typeof(OpinionStage.contentPopup) !== 'undefined' ) {
  console.warn('[OpinionStage] content-popup APIs was already included')
}

/*
 * Content popup API for internal plugin use:
 *   OpinionStage.contentPopup.open({
 *     onWidgetSelect: function (widget) {
 *       // insert shortcode (widget.shortcode) or
 *       // call gutenberg APIs
 *     }
 *   })
 */
;(function (OS, $) {
  class ContentPopup {
    constructor () {
      // vuejs vm instance
      this.app = undefined

      // tingle.js popup instance
      this.modal = undefined
    }

    open ({ onWidgetSelect }) {
      if ( typeof(onWidgetSelect) !== 'function' ) {
        throw new Error('onWidgetSelect must be a function')
      }

      $(() => {
        if ( !this.app ) { createModal.call(this) }

        this.app.widgetSelectCb = onWidgetSelect
        this.modal.open()
      })
    }
  }

  function createModal () {
    const self = this

    self.modal = new Modal({
      content: $('[data-opinionstage-content-popup-template]').html(),

      onCreate (modal) {
        self.app = new ContentPopupContent(modal)
      },

      onClose (modal) {
        self.app.isModalOpened = false
      },

      onOpen (modal) {
        self.app.isModalOpened = true
      },
    })
  }

  OS.contentPopup = new ContentPopup()
})(window.OpinionStage = window.OpinionStage || {}, jQuery)

// this is part is specific only to classic WordPress editor
jQuery(function($) {
  if ( window.location.href.indexOf('modal_is_open') > -1 ) {
    OpinionStage.contentPopup.open({
      onWidgetSelect
    })

    modal.open()
  }

  $('body').on('click', '[data-opinionstage-content-launch]', function (event) {
    event.preventDefault()

    OpinionStage.contentPopup.open({
      onWidgetSelect
    })
  })

  function onWidgetSelect (widget) {
    wp.media.editor.insert(widget.shortcode)
  }
})

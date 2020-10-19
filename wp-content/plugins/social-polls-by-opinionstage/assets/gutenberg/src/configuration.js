// https://developer.wordpress.org/block-editor/developers/block-api/block-attributes/
export const attributes = {
  widgetType: {
    type:      'string',
    source:    'attribute',
    attribute: 'data-type',
    selector:  'div[data-type]',
  },

  embedUrl: {
    source:    'attribute',
    attribute: 'data-test-url',
    selector:  'div[data-test-url]',
  },

  lockEmbed: {
    source:    'attribute',
    attribute: 'data-lock-embed',
    selector:  'div[data-lock-embed]',
  },

  buttonText: {
    source:    'attribute',
    attribute: 'data-button-text',
    selector:  'div[data-button-text]',
  },

  insertItemImage: {
    source:    'attribute',
    attribute: 'data-image-url',
    selector:  'div[data-image-url]',
  },

  insertItemOsTitle: {
    source:    'attribute',
    attribute: 'data-title-url',
    selector:  'div[data-title-url]',
  },

  insertItemOsView: {
    source:    'attribute',
    attribute: 'data-view-url',
    selector:  'div[data-view-url]',
  },

  insertItemOsEdit: {
    source:    'attribute',
    attribute: 'data-edit-url',
    selector:  'div[data-edit-url]',
  },

  insertItemOsStatistics: {
    source:    'attribute',
    attribute: 'data-statistics-url',
    selector:  'div[data-statistics-url]',
  },
}

export const category = 'opinion-stage'

export const supports = {
  // Removes support for an HTML mode.
  html: false,
}

// block widget types (values of widgetType attribute):
export const WIDGET_POLL             = 'poll'
export const WIDGET_PERSONALITY_QUIZ = 'personality'
export const WIDGET_TRIVIA_QUIZ      = 'trivia'
export const WIDGET_SURVEY           = 'survey'
export const WIDGET_SLIDESHOW        = 'slideshow'
export const WIDGET_FORM             = 'form'

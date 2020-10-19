// values for widgetType attribute:
import {
  WIDGET_POLL,
  WIDGET_PERSONALITY_QUIZ,
  WIDGET_TRIVIA_QUIZ,
  WIDGET_SURVEY,
  WIDGET_SLIDESHOW,
  WIDGET_FORM,
} from './configuration.js'

export default function save({ attributes }) {
  const {
    widgetType,
    embedUrl,
    lockEmbed,
    buttonText,
    insertItemImage,
    insertItemOsTitle,
    insertItemOsView,
    insertItemOsEdit,
    insertItemOsStatistics,
  } = attributes

  return (
    <div class={ unusedWrapperClassFromWidgetType(widgetType) }
      data-type={ widgetType }
      data-image-url={ insertItemImage }
      data-title-url={ insertItemOsTitle }
      data-view-url={ insertItemOsView }
      data-statistics-url={ insertItemOsStatistics }
      data-edit-url={ insertItemOsEdit }
      data-test-url={ embedUrl }
      data-lock-embed={ lockEmbed }
      data-button-text={ buttonText }
    >
      [os-widget path="{ embedUrl }"]
      <span></span>
    </div>
  )
}

// kept as backwards compatibility:
function unusedWrapperClassFromWidgetType (widgetType) {
  // case when widget is not inserted yet:
  if ( !widgetType ) { return null }

  switch ( widgetType ) {
  case WIDGET_POLL:
    return 'os-poll-wrapper'
    break
  case WIDGET_SURVEY:
    return 'os-survey-wrapper'
    break
  case WIDGET_TRIVIA_QUIZ:
    return 'os-trivia-wrapper'
    break
  case WIDGET_PERSONALITY_QUIZ:
    return 'os-personality-wrapper'
    break
  case WIDGET_FORM:
    return 'os-form-wrapper'
    break
  case WIDGET_SLIDESHOW:
    return 'os-slideshow-wrapper'
    break
  default:
    console.warn('unknown widget type:', widgetType)
  }
}

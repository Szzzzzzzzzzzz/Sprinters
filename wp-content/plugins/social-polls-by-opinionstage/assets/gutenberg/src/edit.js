import { __ } from '@wordpress/i18n'
import { createBlock } from '@wordpress/blocks'

import './editor.scss'
// values for widgetType attribute:
import {
  WIDGET_POLL,
  WIDGET_PERSONALITY_QUIZ,
  WIDGET_TRIVIA_QUIZ,
  WIDGET_SURVEY,
  WIDGET_SLIDESHOW,
  WIDGET_FORM,
} from './configuration.js'

export default function Edit ({ name, className, attributes, setAttributes, /*isSelected,*/ clientId }) {
  let {
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

  if ( OPINIONSTAGE_GUTENBERG_DATA.userLoggedIn === 'false' ) {
    return (
      <div className={ className }>
        <div class="os-widget-wrapper components-placeholder">
          <p class="components-heading"><img src={OPINIONSTAGE_GUTENBERG_DATA.brandLogoUrl} alt=""/></p>
          <p class="components-heading">Please connect WordPress to Opinion Stage to start adding widgets</p>
          <a href={OPINIONSTAGE_GUTENBERG_DATA.loginPageUrl} class="components-button is-button is-default is-block is-primary">Connect</a>
        </div>
      </div>
    )
  }

  const currentWidgetType = widgetTypeFromBlockName(name)
  const currentWidgetTitle = widgetTitleFromType(currentWidgetType)

  const placeWidget = function (widget) {
    const newAttributes = {
      widgetType:             currentWidgetType,
      lockEmbed:              true,
      buttonText:             'Change',
      embedUrl:               widget.landingPageUrl.replace(/^https?:\/\/[^/]+\//,'/'),
      insertItemImage:        widget.imageUrl,
      insertItemOsTitle:      widget.title,
      insertItemOsView:       widget.landingPageUrl,
      insertItemOsEdit:       widget.editUrl,
      insertItemOsStatistics: widget.statsUrl,
    }

    if ( backendWidgetTypeToBlockWidgetType(widget.type) === currentWidgetType ) {
      setAttributes(newAttributes)
    } else {
      // on widget type change we also want to change block,
      // in order to accommodate widget type for better UX.
      // https://wordpress.stackexchange.com/questions/305932/gutenberg-remove-add-blocks-with-custom-script
      const replacementBlock = createBlock(blockName(currentWidgetType))
      replacementBlock.attributes = newAttributes

      wp.data.dispatch('core/block-editor').replaceBlock(
        clientId,
        replacementBlock
      )
    }
  }

  const selectWidget = _event => {
    OpinionStage.contentPopup.open({ onWidgetSelect: placeWidget })
  }

  const changeWidget = _event => {
    OpinionStage.contentPopup.open({ onWidgetSelect: widget => {
      setAttributes({
        widgetType: '',
        embedUrl: '',
        buttonText:'Embed',
        lockEmbed: false,
        insertItemImage: false,
        insertItemOsTitle: false,
        insertItemOsView: false,
        insertItemOsEdit: false,
        insertItemOsStatistics: false,
      })
      placeWidget(widget)
    }})
  }

  let createNewWidgetUrl = `${OPINIONSTAGE_GUTENBERG_DATA.createNewWidgetUrl}&w_type=${backendWidgetTypeForNewWidget(currentWidgetType)}`

  let contentViewEditStatOs = (
    <div class="os-widget-wrapper components-placeholder">
      <p class="components-heading"><img src={OPINIONSTAGE_GUTENBERG_DATA.brandLogoUrl} alt=""/></p>
      <button class="components-button is-button is-default is-block is-primary" onClick={selectWidget} >Select a {currentWidgetTitle}</button>
      <a href={createNewWidgetUrl} target="_blank" class="components-button is-button is-default is-block is-primary">Create a new {currentWidgetTitle}</a>
    </div>
  )

  if ( embedUrl && embedUrl !== '' ) {
    if ( buttonText === 'Change' ) {
      contentViewEditStatOs = (
        <div class="os-widget-wrapper components-placeholder">
          <p class="components-heading"><img src={OPINIONSTAGE_GUTENBERG_DATA.brandLogoUrl} alt=""/></p>
          <div class="components-preview__block" >
            <div class="components-preview__leftBlockImage">
              <img src={insertItemImage} alt={insertItemOsTitle} class="image" />
              <div class="overlay">
                <div class="text">
                  <a href={insertItemOsView} target="_blank"> View </a>
                  <a href={insertItemOsEdit} target="_blank"> Edit </a>
                  <a href={insertItemOsStatistics} target="_blank"> Statistics </a>
                  <input type="button" value={buttonText} class="components-button is-button is-default is-large left-align" onClick={changeWidget}/>
                </div>
              </div>
            </div>
            <div class="components-preview__rightBlockContent">
              <div class="components-placeholder__label">{currentWidgetTitle}: {insertItemOsTitle}</div>
            </div>
          </div>
        </div>
      )
    }
  } else {
    setAttributes({ buttonText: 'Embed'})
  }

  return (
    <div className={ className }>
      {contentViewEditStatOs}
    </div>
  )
}

function widgetTypeFromBlockName (blockName) {
  switch ( blockName ) {
  case 'opinion-stage/block-os-poll':        return WIDGET_POLL
    break
  case 'opinion-stage/block-os-survey':      return WIDGET_SURVEY
    break
  case 'opinion-stage/block-os-trivia':      return WIDGET_TRIVIA_QUIZ
    break
  case 'opinion-stage/block-os-personality': return WIDGET_PERSONALITY_QUIZ
    break
  case 'opinion-stage/block-os-form':        return WIDGET_FORM
    break
  case 'opinion-stage/block-os-slideshow':   return WIDGET_SLIDESHOW
    break
  default:
    console.warn('unknown block name:', blockName)
  }
}

// opposite to widgetTypeFromBlockName
function blockName (widgetType) {
  switch ( widgetType ) {
  case WIDGET_POLL:             return 'opinion-stage/block-os-poll'
    break
  case WIDGET_SURVEY:           return 'opinion-stage/block-os-survey'
    break
  case WIDGET_TRIVIA_QUIZ:      return 'opinion-stage/block-os-trivia'
    break
  case WIDGET_PERSONALITY_QUIZ: return 'opinion-stage/block-os-personality'
    break
  case WIDGET_FORM:             return 'opinion-stage/block-os-form'
    break
  case WIDGET_SLIDESHOW:        return 'opinion-stage/block-os-slideshow'
    break
  default:
    console.warn('unknown block widget type:', widgetType)
  }
}

function widgetTitleFromType (widgetType) {
  switch ( widgetType ) {
  case WIDGET_POLL:
    return __('Poll')
    break
  case WIDGET_SURVEY:
    return __('Survey')
    break
  case WIDGET_TRIVIA_QUIZ:
    return __('Trivia Quiz')
    break
  case WIDGET_PERSONALITY_QUIZ:
    return __('Personality Quiz')
    break
  case WIDGET_FORM:
    return __('Form')
    break
  case WIDGET_SLIDESHOW:
    return __('Slideshow')
    break
  }
}

function backendWidgetTypeForNewWidget (widgetType) {
  switch ( widgetType ) {
  case WIDGET_POLL:
    return 'poll'
    break
  case WIDGET_SURVEY:
    return 'survey'
    break
  case WIDGET_TRIVIA_QUIZ:
    return 'quiz'
    break
  case WIDGET_PERSONALITY_QUIZ:
    return 'outcome'
    break
  case WIDGET_FORM:
    return 'contact_form'
    break
  case WIDGET_SLIDESHOW:
    return 'slideshow'
    break
  }
}

// backend API endpoint returns these widget types:
function backendWidgetTypeToBlockWidgetType (backendType) {
  switch ( backendType ) {
  case 'poll':
    return WIDGET_POLL
    break
  case 'survey':
    return WIDGET_SURVEY
    break
  case 'trivia':
    return WIDGET_TRIVIA_QUIZ
    break
  case 'personality':
    return WIDGET_PERSONALITY_QUIZ
    break
  case 'form':
    return WIDGET_FORM
    break
  case 'slideshow':
    return WIDGET_SLIDESHOW
    break
  }
}

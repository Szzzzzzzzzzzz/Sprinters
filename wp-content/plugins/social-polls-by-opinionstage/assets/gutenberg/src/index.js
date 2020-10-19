import { registerBlockType } from '@wordpress/blocks'
import { __ } from '@wordpress/i18n'

import Edit from './edit'
import save from './save'
import { attributes, category, supports } from './configuration'

registerBlockType('opinion-stage/block-os-poll', {
  title: __( 'Poll', 'social-polls-by-opinionstage' ),
  icon: 'chart-bar',
  description: __('Embed an Opinion Stage Poll', 'social-polls-by-opinionstage'),
  category,
  keywords: [
    __('poll', 'social-polls-by-opinionstage'),
    __('social poll', 'social-polls-by-opinionstage'),
  ],

  supports,
  attributes,

  edit: Edit,
  save,
})

registerBlockType('opinion-stage/block-os-survey', {
  title: __( 'Survey' ),
  icon: 'list-view',
  description: __('Embed an Opinion Stage Survey', 'social-polls-by-opinionstage'),
  category,
  keywords: [
    __('survey', 'social-polls-by-opinionstage'),
  ],

  supports,
  attributes,

  edit: Edit,
  save,
})

registerBlockType('opinion-stage/block-os-trivia', {
  title: __( 'Trivia Quiz' ),
  icon: 'yes',
  description: __('Embed an Opinion Stage Trivia Quiz', 'social-polls-by-opinionstage'),
  category,
  keywords: [
    __('quiz', 'social-polls-by-opinionstage'),
    __('trivia', 'social-polls-by-opinionstage'),
  ],

  supports,
  attributes,

  edit: Edit,
  save,
})

registerBlockType('opinion-stage/block-os-personality', {
  title: __( 'Personality Quiz' ),
  icon: 'smiley',
  description: __('Embed an Opinion Stage Personality Quiz', 'social-polls-by-opinionstage'),
  category,
  keywords: [
    __('personality', 'social-polls-by-opinionstage'),
    __('quiz', 'social-polls-by-opinionstage'),
    __('outcome', 'social-polls-by-opinionstage'),
  ],

  supports,
  attributes,

  edit: Edit,
  save,
})

registerBlockType('opinion-stage/block-os-form', {
  title: __( 'Form' ),
  icon: 'editor-justify',
  description: __('Embed an Opinion Stage Form', 'social-polls-by-opinionstage'),
  category,
  keywords: [
    __('form', 'social-polls-by-opinionstage'),
    __('contact form', 'social-polls-by-opinionstage'),
  ],

  supports,
  attributes,

  edit: Edit,
  save,
})

registerBlockType('opinion-stage/block-os-slideshow', {
  title: __( 'Slideshow' ),
  icon: 'playlist-video',
  description: __('Embed an Opinion Stage Slideshow', 'social-polls-by-opinionstage'),
  category,
  keywords: [
    __('slideshow', 'social-polls-by-opinionstage'),
  ],

  supports,
  attributes,

  edit: Edit,
  save,
})

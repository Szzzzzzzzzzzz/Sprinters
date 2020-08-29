/**
 * Load all the components. needed for the app
 */

import Vue from 'vue'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import SweetModal from '../../tmp/sweet-modal-vue/src/plugin.js'
import '../assets/icons'

Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.use(SweetModal)

export default { FontAwesomeIcon, SweetModal }

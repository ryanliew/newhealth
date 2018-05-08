import translations from './vue-translations.js';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.Vue = require('vue');

/**
 * Initialize translation functions
 * @type {[type]}
 */
var Lang = require('lang.js');

window.lang = new Lang();

lang.setLocale('en'); // Set up language switcher here

lang.setMessages(translations);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

require('./filters');


// Base components
Vue.component('flash', require('./components/Flash.vue'));
Vue.component('confirmation', require('./components/Confirmation.vue'));
Vue.component('loader', require('./components/Loader.vue'));
//Vue.component('navigation', require('./components/Navigation.vue'));
Vue.component('modal', require('./components/Modal.vue'));
Vue.component('table-view', require('./components/TableView.vue'));
Vue.component('text-input', require('./components/TextInput.vue'));
Vue.component('textarea-input', require('./components/TextareaInput.vue'));
Vue.component('checkbox-input', require('./components/CheckboxInput.vue'));
Vue.component('selector-input', require('./components/SelectorInput.vue'));
Vue.component('image-input', require('./components/ImageInput.vue'));

Vue.component('company-registration', require('./components/CompanyRegistration.vue'));
Vue.component('referrer', require('./components/Referrer.vue'));

const app = new Vue({
    el: '#app'
});

import VueRouter from 'vue-router';
import App from './layout/App.vue';
import Dashboard from './pages/Dashboard.vue';
import LoginPage from './pages/LoginPage.vue';
import ProfilePage from './pages/ProfilePage.vue';
import translations from './vue-translations.js';
import VueNoty from 'vuejs-noty';
require('./bootstrap');

Vue.use(VueRouter);
Vue.use(VueNoty);
/**
 * Initialize translation functions
 * @type {[type]}
 */
var Lang = require('lang.js');

window.lang = new Lang();

lang.setLocale('en'); // Set up language switcher here

lang.setMessages(translations);

Vue.mixin({
	created() {
		window.events.$on("lang", function(){
			this.$forceUpdate();
		}.bind(this));
	}
});
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.user = '';
window.intended = '';
require('./filters');

const routes = [
	{ path: '/', component: ProfilePage }, 
	{ path: '/dashboard', component:Dashboard },
	{ path: '/login', component: LoginPage },
	{ path: '/profile', component: ProfilePage }
];

const router = new VueRouter({ routes });

router.beforeEach((to, from, next) => {
	if(to.fullPath !== '/login' && !user) {
		// To counter facebook login
		if(to.fullPath == "_=_")
		{
			to.fullPath = "";
		}
		axios.get('/api/profile')
			.then(response => { user = response.data; next(); })
			//.catch(error => { intended = to.fullPath; window.location.href="/register"; });
					
	} else {
		next();
	}
});

// Base components
Vue.component('flash', require('./base/Flash.vue'));
Vue.component('confirmation', require('./base/Confirmation.vue'));
Vue.component('loader', require('./base/Loader.vue'));
//Vue.component('navigation', require('./base/Navigation.vue'));
Vue.component('modal', require('./base/Modal.vue'));
Vue.component('table-view', require('./base/TableView.vue'));
Vue.component('text-input', require('./base/TextInput.vue'));
Vue.component('textarea-input', require('./base/TextareaInput.vue'));
Vue.component('checkbox-input', require('./base/CheckboxInput.vue'));
Vue.component('selector-input', require('./base/SelectorInput.vue'));
Vue.component('image-input', require('./base/ImageInput.vue'));

// Project components
Vue.component('side-nav', require('./components/SideNavigation.vue'));

window.app = new Vue({
	router,
    el: '#app',
    render: f => f(App)
});

import VueRouter from 'vue-router';
import App from './layout/App.vue';
import Dashboard from './pages/Dashboard.vue';
import LoginPage from './pages/LoginPage.vue';
import translations from './vue-translations.js';

require('./bootstrap');

window.Vue = require('vue');

Vue.use(VueRouter);
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

window.user = '';
window.intended = '';
require('./filters');

const routes = [
	{ path: '/', component: Dashboard }, 
	{ path: '/dashboard', component:Dashboard },
	{ path: '/login', component: LoginPage }
];

const router = new VueRouter({ routes });

router.beforeEach((to, from, next) => {
	if(to.fullPath !== '/login' && !user) {

		intended = '/';
		
		axios.get('/api/profile')
			.then(response => { user = response.data; next(); })
			.catch(error => { intended = to.fullPath; router.push('/login'); });
					
	} else {
		next();
	}
});


const app = new Vue({
	router,
    el: '#app',
    render: f => f(App)
});

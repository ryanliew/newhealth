import VueRouter from 'vue-router';
import VueNoty from 'vuejs-noty';
import VueEvents from 'vue-events';

import App from './layout/App.vue';
import Dashboard from './pages/Dashboard.vue';
import LoginPage from './pages/LoginPage.vue';
import ProfilePage from './pages/ProfilePage.vue';
import PurchasePage from './pages/PurchasePage.vue';
import PackagePage from './pages/PackagePage.vue';
import UserPage from './pages/UserPage.vue';
import GenoPage from './pages/GenoPage.vue';
import TransactionPage from './pages/TransactionPage.vue';
import PayoutReportPage from './pages/PayoutReportPage.vue';
import PostPage from './pages/PostPage.vue';
import MarketingMaterialPage from './pages/MarketingMaterialPage.vue';
import TreeManagementPage from './pages/TreeManagementPage.vue';
import ReportsPage from './pages/ReportsPage.vue';
import ResellAccountPage from './pages/ResellAccountPage.vue';
import RedemptionItemPage from './pages/RedemptionItemPage.vue';
import RedemptionPage from './pages/RedemptionPage.vue';
import OrganizationPage from './pages/OrganizationPage.vue';
import translations from './vue-translations.js';

import { MdButton, MdContent, MdTabs, MdRadio, MdField } from 'vue-material/dist/components'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'

require('./bootstrap');

Vue.use(VueRouter);
Vue.use(VueNoty);
Vue.use(VueEvents);
Vue.use(MdButton);
Vue.use(MdContent);
Vue.use(MdTabs);
Vue.use(MdRadio);
Vue.use(MdField);
/**
 * Initialize translation functions
 * @type {[type]}
 */
var Lang = require('lang.js');

window.lang = new Lang();
var defaultlang = 'en';

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

var defaultlang = getParameterByName('lang');

lang.setLocale(defaultlang ? defaultlang : 'en'); // Set up language switcher here

lang.setMessages(translations);

Vue.mixin({
	data() {
		return {
			lang: 'en'
		};
	},

	created() {
		window.events.$on("lang", function(){
			this.lang = lang;
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
	{ path: '/_=_', redirect: '/'}, // To counter facebook login
	{ path: '/', redirect: '/dashboard' }, 
	{ path: '/dashboard', component:Dashboard },
	{ path: '/profile', component: ProfilePage },
	{ path: '/purchases', component: PurchasePage },
	{ path: '/users', component: UserPage },
	{ path: '/organization', component: OrganizationPage },
	{ path: '/packages', component: PackagePage },
	{ path: '/transactions', component: TransactionPage },
	{ path: '/payouts', component: PayoutReportPage },
	{ path: '/news', component: PostPage },
	{ path: '/materials', component: MarketingMaterialPage },
	{ path: '/trees', component: TreeManagementPage },
	{ path: '/reports', component: ReportsPage },
	{ path: '/resell', component: ResellAccountPage },
	{ path: '/items', component: RedemptionItemPage },
	{ path: '/redemptions', component: RedemptionPage }
];

const router = new VueRouter({ routes, linkActiveClass: 'opened' });

router.beforeEach((to, from, next) => {
	if(to.fullPath !== '/login' && !user) {
	
		axios.get('/api/profile')
			.then(response => { 
				user = response.data; 
				window.events.$emit('authenticate', user);
				if(user.country_id == 48){ 
					lang.setLocale('zh'); 
					window.events.$emit('defaultzh'); 
				} 
				if(!user.has_purchase && !user.is_admin) {
					router.push({path: 'purchases', query: { new: 1 }})
				}
				next(); 
			});
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
Vue.component('radio-group-input', require('./base/RadioGroupInput.vue'));

// Actions components
Vue.component('PurchasesActions', require('./actions/PurchasesActions.vue'));
Vue.component('UsersActions', require('./actions/UsersActions.vue'));
Vue.component('PackagesActions', require('./actions/PackagesActions.vue'));
Vue.component('PayoutsActions', require('./actions/PayoutsActions.vue'));
Vue.component('PostsActions', require('./actions/PostsActions.vue'));
Vue.component('RedemptionActions', require('./actions/RedemptionActions.vue'));

// Project components
Vue.component('side-nav', require('./components/SideNavigation.vue'));

// Custom table column
Vue.component('TablePriceSwitcher', require('./components/TablePriceSwitcher.vue'));
Vue.component('TransactionDescription', require('./components/TransactionDescription.vue'));
Vue.component('PostTitleSwitcher', require('./components/PostTitleSwitcher.vue'));

window.app = new Vue({
	router,
    el: '#app',
    render: f => f(App)
});

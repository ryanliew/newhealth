<template>
	<div>
		<div class="mobile-menu-left-overlay"></div>
		<nav class="side-menu side-menu-compact">
		    <ul class="side-menu-list">
		        <router-link :to="item.route" tag="li" :class="item.color" v-for="(item, index) in menu" :key="index" v-if="adminViewOnly(item) && advisorViewOnly(item) && !item.link">
		        	<a>
						<i class="font-icon" :class="item.icon"></i>
		                <span class="lbl">{{ 'nav.' + item.title | trans }}</span>
		            </a>
		        </router-link>
		        <li v-for="(item, index) in menu" :key="index" v-if="item.link && adminViewOnly(item) && advisorViewOnly(item)" :class="item.color">
		        	<a :href="item.route">
		        		<i class="font-icon" :class="item.icon"></i>
		        		<span class="lbl">{{ 'nav.' + item.title | trans }}</span>
		        	</a>
		        </li>
		    </ul>
		</nav><!--.side-menu-->
	</div>	
</template>

<script>
	export default {
		props: [''],
		data() {
			return {
				menu:[ 
					{color:'brown' , icon:' font-icon font-icon-speed' , title: 'dashboard', route: 'dashboard', opened: true},
					{color:'brown' , icon:' glyphicon glyphicon-user' , title: 'profile', route: 'profile', opened: false},
					{color:'brown' , icon:' glyphicon glyphicon-barcode' , title: 'purchases', route: 'purchases', opened: false, advisor: true},
					{color:'brown' , icon:' fa fa-sitemap' , title: 'organization', route: 'organization', opened: false, advisor: true},
					{color:'brown' , icon:' fa fa-money' , title: 'transactions', route: 'transactions', opened: false, advisor: true},
					{color:'brown', icon:' fa fa-newspaper-o', title: 'news', route: 'news', opened: false},
					{color:'brown', icon:' fa fa-file-pdf-o', title: 'materials', route: 'materials', opened: false, advisor: true},
					{color:'brown', icon:' fa fa-tree', title: 'trees', route: 'trees', opened: false},
					{color:'blue' , icon:' fa fa-users' , title: 'users', route: 'users', opened: false, admin: true},
					{color:'blue' , icon:' fa fa-gift' , title: 'packages', route: 'packages', opened: false, admin: true},
					{color:'blue' , icon:' fa fa-bank' , title: 'payouts', route: 'payouts', opened: false, admin: true},
					{color:'blue', icon:' fa fa-pie-chart', title: 'reports', route: 'reports', opened: false, admin: true}
				],
				user: ''
			};
		},

		mounted() {
			axios.get('/api/profile')
				.then(response => { this.user = response.data; })
		},

		methods: {
			getMenuItemClass(item) {
				let itemClass = item.opened ? 'opened' : '';
				return item.color;
			},

			triggerMenu(item) {
				this.menu.forEach(function(mItem){
					mItem.opened = false;
				});

				item.opened = true;
			},

			adminViewOnly(item) {
				if(item.admin) {
					return this.user.is_admin;
				}

				return true;
			},

			advisorViewOnly(item){
				if(item.advisor) {
					return this.user.is_admin || this.user.is_advisor;
				}

				return true;
			}
		}
	}
</script>
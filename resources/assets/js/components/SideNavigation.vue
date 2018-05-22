<template>
	<div>
		<div class="mobile-menu-left-overlay"></div>
		<nav class="side-menu side-menu-compact">
		    <ul class="side-menu-list">
		        <router-link :to="item.route" tag="li" :class="item.color" v-for="(item, index) in menu" :key="index" v-if="adminViewOnly(item)">
		        	<a>
						<i class="font-icon" :class="item.icon"></i>
		                <span class="lbl">{{ 'nav.' + item.title | trans }}</span>
		            </a>
		        </router-link>
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
					{color:'brown' , icon:' glyphicon glyphicon-user' , title: 'profile', route: 'profile', opened: false},
					{color:'brown' , icon:' glyphicon glyphicon-barcode' , title: 'purchases', route: 'purchases', opened: false},
					{color:'blue' , icon:' glyphicon glyphicon-user' , title: 'users', route: 'users', opened: false, admin: true}
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
			}
		}
	}
</script>
<template>
	<div>
		<div class="mobile-menu-left-overlay"></div>
		<nav class="side-menu side-menu-compact">
		    <ul class="side-menu-list">
		        <li class="brown" :class="getMenuItemClass(item)" v-for="item in menu">
		            <a :href="item.route" @click="triggerMenu(item)">
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
					{color:'brown' , icon:' glyphicon glyphicon-user' , title: 'profile', route: '#', opened: true}
				]
			};
		},

		methods: {
			getMenuItemClass(item) {
				return item.opened ? 'opened' : '';
			},

			triggerMenu(item) {
				this.menu.forEach(function(mItem){
					mItem.opened = false;
				});

				item.opened = true;
			}
		}	
	}
</script>
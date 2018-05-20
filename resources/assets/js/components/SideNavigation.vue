<template>
	<div>
		<div class="mobile-menu-left-overlay"></div>
		<nav class="side-menu side-menu-compact">
		    <ul class="side-menu-list">
		        <li :class="getMenuItemClass(item)" v-for="item in menu">
		        	<router-link :to="item.route" @click.native="triggerMenu(item)">
						<i class="font-icon" :class="item.icon"></i>
		                <span class="lbl">{{ 'nav.' + item.title | trans }}</span>
		        	</router-link>
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
					{color:'brown' , icon:' glyphicon glyphicon-user' , title: 'profile', route: 'profile', opened: true},
					//{color:'brown' , icon:' glyphicon glyphicon-barcode' , title: 'purchases', route: 'purchases', opened: false}
				]
			};
		},

		methods: {
			getMenuItemClass(item) {
				let itemClass = item.opened ? 'opened' : '';
				return itemClass + " " + item.color;
			},

			triggerMenu(item) {
				console.log(item);
				this.menu.forEach(function(mItem){
					mItem.opened = false;
				});

				item.opened = true;
			}
		}	
	}
</script>
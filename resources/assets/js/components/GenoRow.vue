<template>
	<ul>
		<li :class="getClass(user)" v-for="user in users">
			<a>
				{{ user.name }} <span class="badge badge-primary" v-if="getClass(user) == 'self'">{{ 'tree.self' |trans }}</span><br>
				{{ user.referral_code }}
				<br>
				<span class="badge badge-success">{{ user.tree_count }} {{ 'auth.tree' | trans_choice(user.tree_count) }}</span>
				<br>
				<span class="badge badge-info">{{ 'user.level_' + user.user_level | trans }}</span>

			</a>
			<geno-row v-if="user.children.length > 0" :users="user.children">
			</geno-row>
		</li>
	</ul>
</template>

<script>
	export default {
		name: "geno-row",
		props: ['users'],
		data() {
			return {
				
			};
		},

		methods: {
			getClass(user) {
				if(user.id == window.user.id) {
					return 'self';
				}

				return '';
			}
		}	
	}
</script>
<template>
	<ul>
		<li :class="getClass(user)" v-for="user in users">
			<a>
				<span v-if="isPurchase">
					{{ 'purchase.' + user.name |trans }} 
				</span>
				<span v-else>
					{{ user.name }} 
				</span>
				<span class="badge badge-primary" v-if="getClass(user) == 'self'">{{ 'tree.self' |trans }}</span><br>
				{{ user.referral_code }}
				<br v-if="user.tree_count">
				<span v-if="user.tree_count" class="badge badge-success">{{ user.tree_count }} {{ 'auth.tree' | trans_choice(user.tree_count) }}</span>
				<br v-if="user.user_level">
				<span v-if="user.user_level" class="badge badge-info">{{ 'user.level_' + user.user_level | trans }}</span>

			</a>
			<geno-row v-if="user.children.length > 0" :users="user.children" :isPurchase="isPurchase">
			</geno-row>
		</li>
	</ul>
</template>

<script>
	export default {
		name: "geno-row",
		props: ['users', 'isPurchase'],
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
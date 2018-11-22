<template>
	<ul>
		<li :class="getClass(user)" v-for="user in users">
			<a style="min-width: 100px;" @click="clicked(user)">
				<span v-if="user.user">{{ user.user.name }}</span>
				<br v-if="user.user">
				<span v-if="isPurchase">
					{{ 'purchase.' + user.name |trans }} 
				</span>
				<span v-else>
					{{ user.name }} 
				</span>
				<!-- <span class="badge badge-primary" v-if="getClass(user) == 'self'">{{ 'tree.self' |trans }}</span><br> -->
				{{ user.referral_code }}
				<br v-if="user.selectedPackage">
				<span v-if="user.selectedPackage" class="badge badge-success">{{ user.selectedPackage }}</span>
				<br v-if="user.account_level">
				<span v-if="user.account_level" class="badge badge-info">{{ 'tree.level_' + user.account_level | trans }}</span>

			</a>
			<geno-row v-if="user.children.length > 0" :users="user.children" :isPurchase="isPurchase" @clicked="clicked">
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
				if(user.user_id == window.user.id) {
					return 'self';
				}

				return '';
			},

			clicked(user) {
				this.$emit('clicked', {name: user.name});
			}
		}	
	}
</script>
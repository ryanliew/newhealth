<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<table-view ref="purchases"
						:fields="fields"
						title="Purchases"
						:url="url"
						:searchables="searchables"
						v-if="!isPurchasing"
						addNew="Make new purchase">
			</table-view>

			<purchase v-if="isPurchasing"></purchase>
		</transition>
	</div>
</template>

<script>
	import Purchase from "../components/Purchase.vue";
	export default {
		props: [''],

		components: { Purchase },

		data() {
			return {
				fields: [
					{ name: 'created_at', title: 'Purchase date' },
					{ name: 'total_amount', title: 'Total payable'},
					{ name: 'status', title: 'Status'}
				],
				searchables: "status,total_amount",
				url: "/api/user/" + window.user.id + "/purchases",
				isPurchasing: false
			};
		},

		mounted() {
			window.events.$on('add_new', function(){
				this.addNewPurchase();
			}.bind(this));
		},

		methods: {
			addNewPurchase() {
				this.isPurchasing = true;
			}
		}	
	}
</script>
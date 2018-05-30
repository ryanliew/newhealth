<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<table-view ref="purchases"
						:fields="fields"
						:title="$options.filters.trans('purchase.purchases')"
						:url="url"
						:searchables="searchables"
						v-if="!isPurchasing"
						:dateFilterable="true"
						dateFilterKey="purchases.created_at"
						addNew="purchase.make_new_purchase">
			</table-view>

			<purchase :cancelable="cancelable" :selectedPurchase="selectedPurchase" v-if="isPurchasing" @back="back"></purchase>
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
					{ name: 'user_name', title: this.tableTitle('purchase.made_by'), sortField: 'users.name'},
					{ name: 'created_at', title: this.tableTitle('purchase.purchase_date'), sortField: 'purchases.created_at', callback: 'date' },
					{ name: '__component:table-price-switcher', title: this.tableTitle('purchase.total_payable'), sortField: 'purchases.total_price' },
					{ name: 'status', title: this.tableTitle('purchase.status'), sortField: 'purchases.status', callback: 'purchaseStatusLabel'},
					{ name: '__component:purchases-actions', title: this.tableTitle('table.actions')}
				],
				searchables: "purchases.status,users.name",
				isPurchasing: false,
				selectedPurchase: '',
				cancelable: true
			};
		},

		mounted() {
			window.events.$on('add_new', function(){
				this.addNewPurchase();
			}.bind(this));

			this.$events.on('view', data => this.view(data));
			
			if(this.getParameterByName('new') == '1') {
				this.cancelable = false;
				this.isPurchasing = true;
			}
			
		},

		methods: {
			getParameterByName(name, url) {
			    if (!url) url = window.location.href;
			    name = name.replace(/[\[\]]/g, "\\$&");
			    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
			        results = regex.exec(url);
			    if (!results) return null;
			    if (!results[2]) return '';
			    return decodeURIComponent(results[2].replace(/\+/g, " "));
			},

			addNewPurchase() {
				this.isPurchasing = true;
			},

			back() {
				this.isPurchasing = false;
				this.selectedPurchase = '';
			},

			tableTitle(value) {
				return this.$options.filters.trans(value);
			},

			view(purchase) {
				this.selectedPurchase = purchase;
				this.isPurchasing = true;
			}
		},

		watch: {
			lang: function(newlang, oldLang) {
				this.fields = [
					{ name: 'user_name', title: this.tableTitle('purchase.made_by'), sortField: 'users.name'},
					{ name: 'created_at', title: this.tableTitle('purchase.purchase_date'), sortField: 'purchases.created_at', callback: 'date' },
					{ name: 'total_price', title: this.tableTitle('purchase.total_payable'), sortField: 'purchases.total_price', callback: 'currency'},
					{ name: 'status', title: this.tableTitle('purchase.status'), sortField: 'purchases.status', callback: 'purchaseStatusLabel'},
					{ name: '__component:purchases-actions', title: this.tableTitle('purchase.actions')}
				];
			}
		},

		computed: {
			url() {
				return window.user.is_admin ? "/api/admin/purchases" :"/api/user/" + window.user.id + "/purchases";
			}
		}	
	}
</script>
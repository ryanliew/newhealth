<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<table-view ref="purchases"
						:fields="fields"
						:title="$options.filters.trans('purchase.purchases')"
						:url="url"
						:searchables="searchables"
						v-show="!isPurchasing"
						:dateFilterable="true"
						dateFilterKey="purchases.created_at"
						addNew="purchase.make_new_purchase"
						:canExportPDF="true"
						:canExportExcel="true"
						:exportUrl="exportUrl">
			</table-view>
		</transition>
		<transition name="slide-fade" mode="out-in">
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
				searchables: "purchases.id,purchases.status,users.name",
				isPurchasing: false,
				selectedPurchase: '',
				cancelable: true
			};
		},

		mounted() {
			window.events.$on('add_new', function(){
				this.addNewPurchase();
			}.bind(this));

			this.turnOnEvents();
			
			if(this.getParameterByName('new') == '1') {
				this.cancelable = false;
				this.isPurchasing = true;
			}

			if(this.getParameterByName('id')) {
				this.$events.fire('filter-set', { month: '', text: this.getParameterByName('id'), start: '', end: '' });
				window.events.$on('table-loaded', function(){
					let data = _.filter(this.$refs.purchases.$refs.vuetable.tableData, function(purchase) { 
						return purchase.id == this.getParameterByName('id'); }.bind(this));
					if(data.length > 0)
						this.view(data[0]);
				}.bind(this));
			}
			
		},

		beforeDestroy() {
			this.turnOffEvents();
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
				this.turnOnEvents();
				this.isPurchasing = false;
				this.selectedPurchase = '';
				this.$refs.purchases.refreshTable();
			},

			tableTitle(value) {
				return this.$options.filters.trans(value);
			},

			view(purchase) {
				this.turnOffEvents();
				this.selectedPurchase = purchase;
				this.isPurchasing = true;
			},

			receipt(purchase){
				// this.turnOffEvents();
				window.location.href = "/exports/receipt/" + purchase.id;
			},

			postDelete(purchase) {
				this.turnOffEvents();
				axios.post("/api/purchase/delete/" + purchase.id)
					.then(response => this.onSuccess(response));
			},

			onSuccess(response) {
				flash(this.$options.filters.trans(response.data.message));
				this.$events.fire('loading-complete');

				this.$refs.purchases.refreshTable();
				this.turnOnEvents();
			},

			turnOnEvents() {
				this.$events.on('view', data => this.view(data));
				this.$events.on('receipt', data => this.receipt(data));
				this.$events.on('deletePurchase', data => this.postDelete(data));
			},

			turnOffEvents() {
				this.$events.off('view');
				this.$events.off('receipt');
				this.$events.off('deletePurchase');
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
			},

			exportUrl() {
				return "/exports/purchases?user=" + window.user.id;
			}
		}	
	}
</script>
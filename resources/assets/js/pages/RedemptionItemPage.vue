<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<table-view ref="purchases"
						:fields="fields"
						:title="$options.filters.trans('redemption.redemption')"
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
			<redemption :cancelable="cancelable" :selectedItem="selectedItem" :isRedeemItem="true" v-if="isPurchasing" @back="back"></redemption>
		</transition>
	</div>
</template>

<script>
	import Redemption from "../components/Redemption.vue";
	
	export default {
		props: [''],

		components: { Redemption },

		data() {
			return {
				fields: [
					{ name: 'package_photo_path', title: this.tableTitle('redemption.item'), callback: 'image'},	
					{ name: 'name', title: this.tableTitle('redemption.name'), sortField: 'name'},
					{ name: 'price', title: this.tableTitle('redemption.price'), sortField: 'price'},
					{ name: 'description', title: this.tableTitle('redemption.description'), sortField: 'description'},
					{ name: '__component:redemption-actions', title: this.tableTitle('table.actions')}
				],
				searchables: "purchases.status,users.name",
				isPurchasing: false,
				selectedItem: '',
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
				window.events.$on('table-loaded', function(){
					let data = _.filter(this.$refs.purchases.$refs.vuetable.tableData, function(purchase) { return purchase.id == this.getParameterByName('id'); }.bind(this));
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
				this.selectedItem = '';
				this.$refs.purchases.refreshTable();
			},

			tableTitle(value) {
				return this.$options.filters.trans(value);
			},

			view(item) {
				this.turnOffEvents();
				this.selectedItem = item;
				this.isPurchasing = true;
			},

			receipt(purchase){
				this.turnOffEvents();
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
				this.$events.on('delete', data => this.postDelete(data));
			},

			turnOffEvents() {
				this.$events.off('view');
				this.$events.off('receipt');
				this.$events.off('delete');
			}
		},

		watch: {
			lang: function(newlang, oldLang) {
				this.fields = [
					{ name: 'name', title: this.tableTitle('name'), sortField: 'name'}
				];
			}
		},

		computed: {
			url() {
				return "/api/items";
			},

			exportUrl() {
				return "/exports/purchases?user=" + window.user.id;
			}
		}	
	}
</script>
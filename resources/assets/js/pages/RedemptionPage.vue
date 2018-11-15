<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<table-view ref="redemptions"
						:fields="fields"
						:title="$options.filters.trans('redemption.redemption')"
						:url="url"
						:searchables="searchables"
						v-show="!isPurchasing"
						:dateFilterable="false"
						dateFilterKey="redemptions.created_at"
						:canExportPDF="false"
						:canExportExcel="false"
						:exportUrl="exportUrl">
			</table-view>
		</transition>
		<transition name="slide-fade" mode="out-in">
			<redemption :cancelable="cancelable" :selectedItem="selectedItem" :isRedeemItem="false" v-if="isPurchasing" @back="back"></redemption>
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
					{ name: 'package.package_photo_path', title: this.tableTitle('redemption.item'), callback: 'packageImage'},
					{ name: 'name', title: this.tableTitle('redemption.made_by'), sortField: 'name'},	
					{ name: 'package.name', title: this.tableTitle('redemption.name')},
					{ name: 'total', title: this.tableTitle('redemption.price'), sortField: 'total'},
					{ name: 'status', title: this.tableTitle('redemption.description'), sortField: 'description', callback: 'redemptionStatusLabel'},
					{ name: 'created_at', title: this.tableTitle('redemption.redemption_date'), sortField: 'created_at', callback: 'date' },
					{ name: '__component:redemption-actions', title: this.tableTitle('table.actions')}
				],
				searchables: "redemptions.status,users.name",
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
				this.$events.fire('filter-set', { month: '', text: this.getParameterByName('id'), start: '', end: '' });
				window.events.$on('table-loaded', function(){
					let data = _.filter(this.$refs.redemptions.$refs.vuetable.tableData, function(purchase) { 
						return purchase.id == this.getParameterByName('id'); }.bind(this));
					if(data.length > 0)
						this.viewRedemption(data[0]);
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
				this.$refs.redemptions.refreshTable();
			},

			tableTitle(value) {
				return this.$options.filters.trans(value);
			},

			viewRedemption(item) {
				this.turnOffEvents();
				this.selectedItem = item;
				this.isPurchasing = true;
			},

			receipt(purchase){
				this.turnOffEvents();
				window.location.href = "/exports/receipt/" + purchase.id;
			},

			postCancel(redemption) {
				this.turnOffEvents();
				axios.post('/api/redemption/cancel/' + redemption.id)
					.then(response => this.onSuccess(response));
			},

			postApprove(redemption) {
				this.turnOffEvents();
				axios.post('/api/redemption/approve/' + redemption.id)
					.then(response => this.onSuccess(response));
			},

			onSuccess(response) {
				flash(this.$options.filters.trans(response.data.message));
				this.$events.fire('loading-complete');

				this.$refs.redemptions.refreshTable();
				this.turnOnEvents();
			},

			turnOnEvents() {
				this.$events.on('viewRedemption', data => this.viewRedemption(data));
				this.$events.on('receipt', data => this.receipt(data));
				this.$events.on('approveRedemption', data => this.postApprove(data));
				this.$events.on('cancel', data => this.postCancel(data));
			},

			turnOffEvents() {
				this.$events.off('viewRedemption');
				this.$events.off('receipt');
				this.$events.off('approveRedemption');
				this.$events.off('cancel');
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
				return window.user.is_admin ? "/api/admin/redemptions" : "/api/user/" + window.user.id + "/redemptions";
			},

			exportUrl() {
				return "/exports/purchases?user=" + window.user.id;
			}
		}	
	}
</script>
<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<div v-show="!isViewingDetails">
				<table-view ref="transactions"
							:fields="fields"
							:title="$options.filters.trans('transaction.payouts')"
							:url="url"
							:searchables="searchables"
							:dateFilterable="false"
							dateFilterKey="transactions.created_at"
							:monthFilterable="true"
							monthFilterKey="transactions.date"
							:canExportPDF="true"
							:canExportExcel="true"
							:exportUrl="exportUrl">
				</table-view>

				<table-view ref="transactions_std"
							:fields="std_fields"
							:title="$options.filters.trans('transaction.std_payouts')"
							:url="std_url"
							:searchables="searchables"
							:dateFilterable="false"
							dateFilterKey="transactions.created_at"
							:monthFilterable="false"
							monthFilterKey="transactions.date">
				</table-view>
			</div>
		</transition>
		<transition name="slide-fade" mode="out-in">
			<transaction-page @back="back" v-if="isViewingDetails" :userId="selectedUserId" :userName="selectedUserName" :filterMonth="filterMonth" :cancelable="true"></transaction-page>
		</transition>
	</div>
</template>

<script>
	import moment from 'moment';
	import TransactionPage from './TransactionPage.vue';
	export default {
		props: ['user'],

		components: { TransactionPage },

		data() {
			return {
				fields: [
					{ name: 'name', title: this.tableTitle("auth.name"), sortField: 'name' },
					{ name: 'bank_name', title: this.tableTitle("auth.bank_name"), sortField: 'bank_name' },
					{ name: 'bank_swift', title: this.tableTitle('auth.bank_swift'), sortField: 'bank_swift' },
					{ name: 'bank-address', title: this.tableTitle('auth.bank_address'), sortField: 'bank-address' },
					{ name: 'account_no', title: this.tableTitle('auth.account_no'), sortField: 'account_no' },
					{ name: 'amount', title: this.tableTitle("transaction.amount_myr"), sortField: 'amount' },

					{ name: '__component:payouts-actions', title: this.tableTitle('table.actions') }
				],
				std_fields: [
					{ name: 'name', title: this.tableTitle("auth.name"), sortField: 'name' },
					{ name: 'bank_name', title: this.tableTitle("auth.bank_name"), sortField: 'bank_name' },
					{ name: 'bank_swift', title: this.tableTitle('auth.bank_swift'), sortField: 'bank_swift' },
					{ name: 'bank-address', title: this.tableTitle('auth.bank_address'), sortField: 'bank-address' },
					{ name: 'account_no', title: this.tableTitle('auth.account_no'), sortField: 'account_no' },
					{ name: 'amount_std', title: this.tableTitle("transaction.amount_std"), sortField: 'amount_std' },

					{ name: '__component:payouts-actions', title: this.tableTitle('table.actions') }
				],
				searchables: "",
				selectedUser: "",
				selectedUserName: "",
				month: moment(),
				filterMonth: '',
				isViewingDetails: false,
				payForm: new Form({
					user_id: "",
					month: moment().format('YYYY-MM-DD'),
					is_std: 0
				}),
				exportUrl: "/exports/payouts?"
			};
		},

		mounted() {
			this.$events.on('view', data => this.view(data));	
			this.$events.on('pay', data => this.pay(data));	
			this.$events.on('export', data => this.export(data));
		},

		beforeDestroy() {
			this.$events.off('view');
			this.$events.off('pay');
			this.$events.off('export');
		},

		methods: {

			tableTitle(value) {
				return this.$options.filters.trans(value);
			},

			back() {
				this.selectedUser = '';
				this.isViewingDetails = false;
				Vue.nextTick( function() { this.$refs.transactions.mountEvents(); this.$refs.transactions_std.mountEvents(); }.bind(this));
			},

			view(data) {
				this.filterMonth = this.$refs.transactions.getMonth();
				this.selectedUserId = data.user_id;
				this.selectedUserName = data.name;
				this.isViewingDetails = true;
			},

			pay(data) {
				this.$events.off('pay');
				this.payForm.is_std = data.is_std;
				this.payForm.user_id = data.user_id;

				this.is_std = data.is_std;
				this.payForm.month = this.$refs.transactions.getMonth();

				this.payForm.post('/api/admin/transaction/paid')
					.then(response => this.onSuccess(response));
			},

			export(data) {
				let date = moment(this.$refs.transactions.getMonth());
				let user = data.user_id;

				window.open('/exports/transactions?user=' + user + '&start=' + date.startOf('month').format("YYYY-MM-DD") + '&end=' + date.endOf('month').format("YYYY-MM-DD") + '&type=pdf&dateFilterKey=date');

			},

			onSuccess(response) {
				flash(this.$options.filters.trans(response.message));
				
				if(this.is_std)
					this.$refs.transactions_std.refreshTable()
				else
					this.$refs.transactions.refreshTable();


				this.$events.on('pay', data => this.pay(data));	
			},

		},

		computed: {
			url() {
				return "/api/admin/transactions";
			},

			std_url() {
				return "/api/admin/transactions/standard";
			}
		}
	}
</script>
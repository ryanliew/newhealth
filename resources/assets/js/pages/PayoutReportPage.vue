<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<transaction-page @back="back" v-if="isViewingDetails" :userId="selectedUserId"></transaction-page>
			<div v-else>
				<table-view ref="transactions"
							:fields="fields"
							:title="$options.filters.trans('transaction.payouts')"
							:url="url"
							:searchables="searchables"
							:dateFilterable="false"
							dateFilterKey="transactions.created_at"
							:monthFilterable="true"
							monthFilterKey="transactions.date">
				</table-view>

				<table-view ref="transactions_std"
							:fields="std_fields"
							:title="$options.filters.trans('transaction.std_payouts')"
							:url="std_url"
							:searchables="searchables"
							:dateFilterable="false"
							dateFilterKey="transactions.created_at"
							:monthFilterable="true"
							monthFilterKey="transactions.date">
				</table-view>
			</div>
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
				month: moment().month(),
				isViewingDetails: false
			};
		},

		mounted() {
			this.$events.on('view', data => this.view(data));	
		},

		methods: {

			tableTitle(value) {
				return this.$options.filters.trans(value);
			},

			back() {
				this.selectedUser = '';
				this.isViewingDetails = false;
			},

			view(data) {
				this.selectedUserId = data.user_id;
				this.isViewingDetails = true;
			}

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
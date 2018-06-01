<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<table-view ref="transactions"
						:fields="fields"
						:title="$options.filters.trans('transaction.transactions')"
						:url="url"
						:searchables="searchables"
						:dateFilterable="true"
						dateFilterKey="transactions.date"
						:hasBack="true"
						@back="back">
			</table-view>
		</transition>
	</div>
</template>

<script>
	export default {
		props: ['userId'],

		data() {
			return {
				fields: [
					{ name: 'date', title: this.tableTitle('transaction.created_at'), sortField: 'transactions.date', callback: 'date' },
					{ name: 'type', title: this.tableTitle('transaction.type'), sortField: 'transactions.type', callback: 'commisionTypeLabel'},
					{ name: '__component:transaction-description', title: this.tableTitle('transaction.description'), sortField: 'transactions.description'},
					{ name: 'payout_status', title: this.tableTitle('transaction.status'), sortField: 'transactions.payout_status', callback: 'transactionPayoutStatusLabel'},
					{ name: '__component:table-price-switcher', title: this.tableTitle('transaction.amount'), sortField: 'transactions.amount' }
				],
				searchables: ""
			};
		},

		methods: {

			tableTitle(value) {
				return this.$options.filters.trans(value);
			},

			back() {
				this.$emit('back');
			}

		},

		computed: {
			url() {
				let id = this.userId ? this.userId : window.user.id;

				return "/api/user/" + id + "/transactions";
			} 
		}
	}
</script>
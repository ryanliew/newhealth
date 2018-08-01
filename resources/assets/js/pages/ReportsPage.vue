<template>
	<div>
		<div class="row">
			<div class="col">
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col">
								{{ 'post.commission_report' | trans }}
							</div>
						</div>
					</div>
					<div class="card-body">
						<selector-input :potentialData="users"
							v-model="selectedUser" 
							:defaultData="selectedUser"
							placeholder="Select a user"
							:required="true"
							label="User"
							name="user"
							:editable="true"
							:focus="false"
							:hideLabel="false">
						</selector-input>
						<div class="table-responsive mt-2" v-if="selectedUser">
							<table class="table">
								<thead>
									<tr>
										<th>Date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="report_date in commission_dates">
										<td>{{ report_date.start.format("MMM YYYY")}}</td>
										<td>
											<a class="btn btn-danger" target="_blank" :href="'/exports/transactions?dateFilterKey=transactions.date&user=' + user_id + '&type=pdf&start=' + report_date.start.format('YYYY-MM-DD') + '&end=' + report_date.end.format('YYYY-MM-DD')">
												<span class="fa fa-2x fa-file-pdf-o pr-2"></span> Download PDF
											</a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col">
								{{ 'post.payout_report' | trans }}
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="report_date in dates">
										<td>{{ report_date.start.format("MMM YYYY")}}</td>
										<td>
											<a class="btn btn-danger" target="_blank" :href="'/exports/payouts?type=pdf&month=' + report_date.start.format('YYYY-MM-DD')">
												<span class="fa fa-2x fa-file-pdf-o pr-2"></span> Download PDF
											</a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col">
								{{ 'post.sales_report' | trans }}
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="report_date in dates">
										<td>{{ report_date.start.format("MMM YYYY")}}</td>
										<td>
											<a class="btn btn-danger" target="_blank" :href="'/exports/purchases?type=pdf&user=' + user + 'dateFilterKey=purchases.created_at&start=' + report_date.start.format('YYYY-MM-DD') + '&end=' + report_date.end.format('YYYY-MM-DD')">
												<span class="fa fa-2x fa-file-pdf-o pr-2"></span> Download PDF
											</a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import moment from 'moment';
	export default {
		props: [''],
		data() {
			return {
				dates: [],
				commission_dates: [],
				users: [],
				selectedUser: '',
				user_id: '',
				user: window.user.id
			};
		},

		mounted() {
			this.getUsers();
			this.setReportDates();
		},

		methods: {
			setReportDates() {
				let currentDate = moment("2018-05-01");

				while( !currentDate.isSame(moment().add(1, 'months'), 'month')){

					this.dates.push({start: moment(currentDate), end: moment(currentDate.endOf('month'))});

					currentDate.add(1, 'day');

				};

				_.reverse(this.dates);
			},

			getUsers() {
				axios.get('/api/admin/users')
					.then(response => this.setUsers(response.data.data));
			},

			setUsers(data) {
				data = _.filter(data, function(user){ return user.transaction_start; });

				this.users = data.map(user => {
					let obj = {};
					obj['value'] = user.id;
					obj['label'] = user.name;
					obj['start_month'] = user.transaction_start;
					obj['end_month'] = user.transaction_end;

					
					return obj;
				});
			},
		},

		watch: {
			selectedUser(newVal, oldVal) {
				this.user_id = newVal.value;

				this.commission_dates = [];

				let currentDate = moment(newVal.start_month);

				while( !currentDate.isSame(moment(newVal.end_month).add(1, 'month'), 'month')){

					this.commission_dates.push({start: moment(currentDate), end: moment(currentDate.endOf('month'))});

					currentDate.add(1, 'day');

				};

				_.reverse(this.commission_dates);
			},
		}	
	}
</script>
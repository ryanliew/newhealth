<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<div v-if="!isViewing && !isPurchasing">
				<div class="row">
		             <div class="col-sm" v-if="user.is_admin">
		                <article class="statistic-box green">
		                    <div>
		                        <div class="number">{{ trees_sold }}</div>
		                        <div class="caption"><div>{{ 'dashboard.trees_sold_global' | trans }}</div></div>
		                    </div>
		                </article>
		            </div><!--.col -->
		            <div class="col-sm">
		                <article class="statistic-box yellow">
		                    <div>
		                        <div class="number">{{ user.group_sale }}</div>
		                        <div class="caption"><div>{{ 'dashboard.group_sale' | trans }}</div></div>
		                    </div>
		                </article>
		            </div><!--.col-->
		            <div class="col-sm">
		                <article class="statistic-box red">
		                    <div>
		                        <div class="number">{{ 'user.level_' + user.user_level | trans }}</div>
		                        <div class="caption"><div>{{ 'dashboard.current_grower_level' | trans }}</div></div>
		                    </div>
		                </article>
		            </div><!--.col-->
		            <div class="col-sm">
		                <article class="statistic-box purple">
		                    <div>
		                        <div class="number" v-if="user.is_std">{{ user.commission_received_std | currency_std }}</div>
		                        <div class="number" v-else>{{ user.commission_received | currency }}</div>
		                        <div class="caption"><div>{{ 'dashboard.commission_earned' | trans }}</div></div>
		     
		                    </div>
		                </article>
		            </div><!--.col-->
		        </div>
		        <div class="row">
		        	<div class="col-md-6 dashboard-column">
		        		<section class="box-typical box-typical-dashboard panel panel-default scrollable">
		        			<header class="box-typical-header panel-heading">
		        				<h3 class="panel-title">{{ 'dashboard.my_downlines' | trans }} <span class="badge badge-primary">{{ user.descendants_count }}</span></h3>
		        			</header>
		        			<div class="box-typical-body panel-body">
			        			<div class="box-normal-content">
			        				<text-input v-model="user.referral_code" 
										:defaultValue="user.referral_code"
										:required="false"
										type="text"
										:label="$options.filters.trans('user.referral_code')"
										name="referrer"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>

									<referral-link :code="user.referral_code"></referral-link>

								</div>

								<table class="tbl-typical">
									<tr>
										<th><div>{{ 'auth.name' | trans }}</div></th>
										<th><div>{{ 'dashboard.grower_level' | trans }}</div></th>
										<th><div>{{ 'user.status' | trans }}</div></th>
										<th><div>{{ 'user.tree_count' | trans }}</div></th>
									</tr>
									<tr v-for="descendant in descendants">
										<td>{{ descendant.name }}</td>
										<td><span class="label label-success">{{ 'user.level_' + descendant.user_level | trans }}</span></td>
										<td v-html="$options.filters.formatUserStatus(descendant.id_status)"></td>
										<td>{{ descendant.tree_count }}</td>
									</tr>
								</table>
		        			</div>
		        		</section>

		        		<section class="box-typical box-typical-dashboard panel panel-default scrollable" v-if="user.is_admin">
		        			<transition name="slide-fade" mode="out-in">
								<table-view ref="purchases"
											:fields="purchaseFields"
											:title="$options.filters.trans('purchase.pending_purchases')"
											:url="purchaseUrl"
											v-show="!isPurchasing"
											empty="No purchases that are pending verification">
								</table-view>
							</transition>
							<transition name="slide-fade" mode="out-in">
								
							</transition>
		        		</section>
		        	</div>
		        	<div class="col-md-6 dashboard-column">
		        		<section class="box-typical box-typical-dashboard panel panel-default scrollable">
		        			<header class="box-typical-header panel-heading">
		        				<h3 class="panel-title">{{ 'dashboard.my_account_status' | trans }}</h3>
		        			</header>
		        			<div class="panel-body box-normal-content">
			        			<div class="steps-icon-progress">
			        				<ul>
			        					<li :class="getStepClass(1)">
			        						<div class="icon" v-html="getStepIcon(1)">
			        						</div>
			        						<div class="caption">{{ 'user.verified' | trans }}</div>
			        					</li>
			        					<li :class="getStepClass(2)">
			        						<div class="icon" v-html="getStepIcon(2)">
			        						</div>
			        						<div class="caption">{{ 'user.instruction_issued_to_lawyer' | trans }}</div>
			        					</li>
			        					<li :class="getStepClass(3)">
			        						<div class="icon" v-html="getStepIcon(3)">
			        						</div>
			        						<div class="caption">{{ 'user.sales_agreement_ready_for_execution' | trans }}</div>
			        					</li>
			        					<li :class="getStepClass(4)">
			        						<div class="icon" v-html="getStepIcon(4)">
			        						</div>
			        						<div class="caption">{{ 'user.sales_agreement_executed' | trans }}</div>
			        					</li>
			        				</ul>
			        			</div>
			        			<div class="next-step text-center">
			        				<b>{{ 'dashboard.next_grower_level' | trans }}</b>
			        				<h2><span class="label label-primary mb-3">{{ 'user.level_' + (user.user_level + 1) | trans  }}</span></h2>

			        				<!-- Number of direct referrer target -->
			        				<span>{{ 'user.level_instruction' | trans({ target: user.direct_referrer_needed, left: directReferrerRemaining }) }}</span>
			        				<div class="progress-with-amount mb-3">
			        					<div class="progress">
			        						<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" :style="'width:'+ directReferrerPercentage  + '%;'" :aria-valuenow="user.direct_descendants_count" aria-valuemin="0" :aria-valuemax="user.direct_referrer_needed">
			        							
			        						</div>
			        					</div>
			        					<div class="progress-with-amount-number">{{ directReferrerPercentage }}%</div>
			        				</div>

			        				<template v-if="user.user_level > 1">
			        					<!-- Group sale target -->
			        					<span>{{ 'user.level_instruction_2' | trans({ target: user.group_sale_needed, left: groupSaleRemaining }) }}</span>
				        				<div class="progress-with-amount">
				        					<div class="progress">
				        						<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" :style="'width:'+ groupSalePercentage + '%;'" :aria-valuenow="user.group_sale" aria-valuemin="0" :aria-valuemax="user.group_sale_needed">
				        							
				        						</div>
				        					</div>
				        					<div class="progress-with-amount-number">{{ groupSalePercentage }}%</div>
				        				</div>
			        				</template>
			        			</div>
		        			</div>
		        		</section>

		        		<section class="box-typical box-typical-dashboard panel panel-default scrollable" v-if="user.is_admin">
							<transition name="slide-fade" mode="out-in">
								<table-view ref="users"
											:fields="userFields"
											:title="$options.filters.trans('user.users_pending')"
											empty="No more pending users"
											:url="userUrl"
											v-show="!isViewing">
								</table-view>
							</transition>
		        		</section>
		        	</div>
		        </div>
		    </div>
	        <purchase :cancelable="true" :selectedPurchase="selectedPurchase" v-if="isPurchasing" @back="back"></purchase>
	        <ProfilePage :cancelable="true" :selectedUser="selectedUser" v-if="isViewing" @back="back"></ProfilePage>
	    </transition>
	</div>
</template>

<script>	
	import ReferralLink from "../components/ReferralLink.vue";
	import Purchase from "../components/Purchase.vue";
	import ProfilePage from "./ProfilePage.vue";

	export default {
		props: [''],

		components: { ReferralLink, Purchase, ProfilePage },

		data() {
			return {
				trees_sold: 0,
				user: window.user,
				descendants: [],
				purchaseFields: [
					{ name: 'user_name', title: this.tableTitle('purchase.made_by'), sortField: 'users.name'},
					{ name: 'created_at', title: this.tableTitle('purchase.purchase_date'), sortField: 'purchases.created_at', callback: 'date' },
					{ name: '__component:table-price-switcher', title: this.tableTitle('purchase.total_payable'), sortField: 'purchases.total_price' },
					{ name: '__component:purchases-actions', title: this.tableTitle('table.actions')}
				],
				userFields: [
					{ name: 'name', title: this.tableTitle('auth.name'), sortField: 'name'},
					{ name: 'id_status', title: this.tableTitle('user.status'), sortField: 'id_status', callback: 'userStatusLabel'},
					{ name: '__component:users-actions', title: this.tableTitle('table.actions')}
				],
				isPurchasing: false,
				isViewing: false,
				selectedPurchase: '',
				selectedUser: '',
				purchaseUrl: "/api/admin/purchases/pending",
				userUrl: "/api/admin/users/pending"
			};
		},

		mounted() {
			this.getDashboardData();
			this.$events.on('view', data => this.viewPurchase(data));

			this.$events.on('viewUser', data => this.viewUser(data));
			this.$events.on('remind', data => this.remind(data));
			this.$events.on('previous', data => this.previous(data));
			this.$events.on('next', data => this.next(data));
		},

		methods: {
			getDashboardData() {
				axios.get("/api/dashboard/" + this.user.id)
					.then(response => this.setDashboardData(response.data));
			},

			setDashboardData(data){
				this.trees_sold = data.trees_sold;
				this.descendants = data.descendants;
			},

			getStepClass(level){
				let stepClass = '';
				if(this.statusLevel >= level);
					stepClass = 'active';

				return stepClass;
			},

			getStepIcon(level){
				let icon = '';
				if(this.statusLevel >= level)
					icon = '<i class="font-icon font-icon-check-bird"></i>';

				return icon;
			},

			tableTitle(value) {
				return this.$options.filters.trans(value);
			},

			viewPurchase(data) {
				this.selectedPurchase = data;
				this.isPurchasing = true;
			},

			viewUser(data) {
				this.selectedUser = data;
				this.isViewing = true;
			},

			back() {
				this.isViewing = false;
				this.isPurchasing = false;
			},

			remind(user) {
				this.$events.fire('loading', user.id);
				axios.post('/api/user/' + user.id + '/kyc/remind')
					.then(response => this.onSuccess(response));
			},

			previous(user){
				this.$events.fire('loading-prev', user.id);
				axios.post('/api/user/' + user.id + '/legal/previous')
					.then(response => this.onStepSuccess(response));
			},

			next(user){
				this.$events.fire('loading-next', user.id);
				axios.post('/api/user/' + user.id + '/legal/next')
					.then(response => this.onStepSuccess(response));
			},

			onStepSuccess(response) {
				this.onSuccess(response);
				this.$refs.users.refreshTable();
			},

			onSuccess(response) {
				flash(this.$options.filters.trans(response.data.message));
				this.$events.fire('loading-complete');
			}
		},

		computed: {
			statusLevel() {
				let level = 0;
				switch(this.user.id_status) {
					case "verified":
						level = 1;
						break;
					case "instruction_issued":
						level = 2;
						break;
					case "execution_ready":
						level = 3;
						break;
					case "complete":
						level = 4;
						break;
				}

				return level;
			},

			directReferrerPercentage() {
				return this.user.direct_descendants_count / this.user.direct_referrer_needed * 100;
			},

			directReferrerRemaining() {
				return this.user.direct_referrer_needed - this.user.direct_descendants_count;
			},

			groupSalePercentage() {
				return this.user.group_sale / this.user.group_sale_needed * 100;
			},

			groupSaleRemaining() {
				return this.user.group_sale_needed - this.user.group_sale;
			}
		}
	}
</script>
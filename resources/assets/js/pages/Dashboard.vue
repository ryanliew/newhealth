<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<div v-if="!isViewing && !isPurchasing && !isViewingPost && !isApplying && !isViewingRedemption">
				<div class="row" >
		            <div class="col-sm">
		                <article class="statistic-box yellow">
		                    <div>
		                        <div class="number">{{ e_wallet.amount }}</div>
		                        <div class="caption"><div>{{ 'dashboard.e_wallet_amount' | trans }}</div></div>
		                    </div>
		                </article>
		            </div><!--.col-->
		            <div class="col-sm">
		                <article class="statistic-box red">
		                    <div>
		                        <div class="number">{{ totalAccount }}</div>
		                        <div class="caption"><div>{{ 'dashboard.accounts' | trans }}</div></div>
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
		            <div class="col-sm">
		            	<article class="statistic-box green">
		                    <div>
		                        <div class="number" v-if="user.is_std">{{ user.unpaid_commission_std | currency_std }}</div>
		                        <div class="number" v-else>{{ user.unpaid_commission | currency }}</div>
		                        <div class="caption"><div>{{ 'dashboard.accrued_commission' | trans }}</div></div>
		                    </div>
		                </article>
		            </div>
		        </div>
		        <div class="row">
		        	<div class="col-md-6 dashboard-column">
		        		<section class="box-typical box-typical-dashboard panel panel-default scrollable">
		        			<header class="box-typical-header panel-heading">
		        				<h3 class="panel-title">{{ 'dashboard.my_accounts' | trans }} </h3>
		        			</header>
		        			<div class="box-typical-body panel-body" >
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
								</div>

								<table class="tbl-typical">
									<tr>
										<th><div>{{ 'dashboard.account' | trans }}</div></th>
										<th><div>{{ 'dashboard.account_level' | trans }}</div></th>
										<th><div>{{ 'dashboard.step_to_qualification' | trans }}</div></th>
										<th></th>
									</tr>

									<tr v-for="(qualification_account, index) in qualification_accounts">
										<td>{{ index }}</td>
										<td><span class="label label-success">{{ 'user.account_level_' + qualification_account.account_level | trans }}</span></td>
										<td>
											<span>
												{{ 'dashboard.' + qualificationLabel(qualification_account) | trans({'level': levelLabel(qualification_account, true), 'count': countQualificationBasicRequirement(qualification_account)})}}
											</span>
										</td>
										<td><referral-link :code="qualification_account.account_referral_code"></referral-link></td>
									</tr>
								</table>
		        			</div>
		        		</section>

		        		

		        		<!-- <section class="box-typical box-typical-dashboard panel panel-default scrollable">
		        			<header class="box-typical-header panel-heading">
		        				<h3 class="panel-title">{{ 'dashboard.latest_news' | trans }}</h3>
		        			</header>
		        			<div class="box-typical-body panel-body">
			        			<table class="tbl-typical">
									<tr>
										<th><div>{{ 'post.title' | trans }}</div></th>
										<th><div>{{ 'dashboard.date' | trans }}</div></th>
									</tr>
									<tr v-for="post in posts">
										<td><a @click="viewPost(post)">{{ postTitle(post) }}</a></td>
										<td>{{ post.created_at | date }}</td>
									</tr>
								</table>
		        			</div>
		        		</section> -->

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
		        		<section class="box-typical box-typical-dashboard panel panel-default scrollable" v-if="user.is_admin">
		        			<transition name="slide-fade" mode="out-in">
								<table-view ref="redemptions"
											:fields="redemptionFields"
											:title="$options.filters.trans('dashboard.pending_redemptions')"
											:url="redemptionUrl"
											v-show="!isViewingRedemption"
											empty="No purchases that are pending verification">
								</table-view>
							</transition>
							<transition name="slide-fade" mode="out-in">
								
							</transition>
		        		</section>
		        	</div>
		        	<div class="col-md-6 dashboard-column">
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
							<confirmation
					            message="confirmation.change_legal_status"
					            :loading="isLegalLoading"
					            @confirmed="submitLegal"
					            @canceled="isChangingStatus = false"
					            v-if="isChangingStatus">

					            <div class="mt-2">
						            <selector-input
						            	:potentialData="legalStatus"
						            	:defaultData="selectedLegalStatus"
						            	v-model="selectedLegalStatus"
						            	label="Status"
						            	:required="true"
						            	:unclearable="true"
						            	:error="statusForm.errors.get('status')"
						            	>
						            </selector-input>
						        </div>
					        </confirmation>
		        		</section>
		        	</div>
		        </div>
		    </div>
	        <purchase :cancelable="true" :selectedPurchase="selectedPurchase" v-if="isPurchasing" @back="back"></purchase>
	        <ProfilePage :cancelable="true" :selectedUser="selectedUser" v-if="isViewing" @back="back"></ProfilePage>
	        <redemption :cancelable="true" :selectedItem="selectedRedemption" :isRedeemItem="false" v-if="isViewingRedemption" @back="back"></redemption>
	    </transition>

	    <transition name="slide-fade" mode="out-in">
	    	 <post :selectedPost="selectedPost" :isEditing="false" v-if="isViewingPost" @back="back"></post>
	    </transition>
	</div>
</template>

<script>	
	import ReferralLink from "../components/ReferralLink.vue";
	import Purchase from "../components/Purchase.vue";
	import ProfilePage from "./ProfilePage.vue";
	import Redemption from "../components/Redemption.vue";
	import Post from "../components/Post.vue";

	export default {
		props: ['viewingUser'],

		components: { ReferralLink, Purchase, ProfilePage, Post, Redemption },

		data() {
			return {
				trees_sold: 0,
				e_wallet: 0,
				user: window.user,
				descendants: [],
				accounts: [],
				qualification_accounts: [],
				purchaseFields: [
					{ name: 'user_name', title: this.tableTitle('purchase.made_by'), sortField: 'users.name'},
					{ name: 'created_at', title: this.tableTitle('purchase.purchase_date'), sortField: 'purchases.created_at', callback: 'date' },
					{ name: '__component:table-price-switcher', title: this.tableTitle('purchase.total_payable'), sortField: 'purchases.total_selling_price' },
					{ name: '__component:purchases-actions', title: this.tableTitle('table.actions')}
				],
				redemptionFields: [
					{ name: 'name', title: this.tableTitle('redemption.made_by'), sortField: 'name'},
					{ name: 'created_at', title: this.tableTitle('redemption.redemption_date'), sortField: 'created_at', callback: 'date' },
					{ name: 'package.name', title: this.tableTitle('redemption.name') },
					{ name: 'package_quantity', title: this.tableTitle('dashboard.quantity') },
					{ name: 'total', title: this.tableTitle('redemption.price'), sortField: 'total'},
					{ name: '__component:redemption-actions', title: this.tableTitle('table.actions')}
				],
				userFields: [
					{ name: 'name', title: this.tableTitle('auth.name'), sortField: 'name'},
					{ name: 'user_status', title: this.tableTitle('user.status'), sortField: 'id_status', callback: 'userStatusLabel'},
					{ name: '__component:users-actions', title: this.tableTitle('table.actions')}
				],
				isPurchasing: false,
				isViewing: false,
				isViewingRedemption: false,
				isViewingPost: false,
				selectedPurchase: '',
				selectedUser: '',
				selectedRedemption: '',
				selectedPost: '',
				redemptionUrl: "/api/admin/redemptions/pending",
				purchaseUrl: "/api/admin/purchases/pending",
				userUrl: "/api/admin/users/pending",
				posts: [],
				statusForm: new Form({
					status: ''
				}),
				isLegalLoading: false,
				selectedLegalStatus: {label: "Instruction issued to lawyer", value: "instruction_issued"},
				isChangingStatus: false,
				legalStatus: [
					{label: "Instruction issued to lawyer", value: "instruction_issued"},
					{label: "Sales agreement ready for execution", value: "execution_ready"},
					{label: "Sales agreement executed", value: "complete"},
				],

			};
		},

		mounted() {
			this.user = this.viewingUser ? this.viewingUser : window.user;
			this.getDashboardData();
			this.$events.on('view', data => this.viewPurchase(data));
			this.$events.on('viewRedemption', data => this.viewRedemption(data));
			this.$events.on('viewUser', data => this.viewUser(data));
			this.$events.on('remind', data => this.remind(data));
			this.$events.on('legal', data => this.legal(data));
			this.$events.on('next', data => this.next(data));
			this.$events.on('approveRedemption', data => this.postApprove(data));
			this.$events.on('approveUser', data => this.approveUser(data));
			this.$events.on('rejectUser', data => this.rejectUser(data));
			this.$events.on('deletePurchase', data => this.postDeletePurchase(data));
		},

		beforeDestroy() {
			this.$events.off('view');
			this.$events.off('viewRedemption');
			this.$events.off('viewUser');
			this.$events.off('remind');
			this.$events.off('legal');
			this.$events.off('next');
			this.$events.off('approveRedemption');
			this.$events.off('approveUser');
			this.$events.off('rejectUser');
			this.$events.off('deletePurchase');
		},

		methods: {
			countQualificationBasicRequirement(account){
				let basicRequirement = 3;
				return basicRequirement - account.number_of_children;
			},

			qualificationLabel(account){
				let label = '';
				switch(account.account_level) {
					case 0:
						label = "buy_machine";
						break;
					case 1:
						label = "buy_silver";
						break;
					case 2:
						label = "buy_platinum";
						break;
					case 3:
						label = "account_max";
						break;
				}

				return label;
			},

			levelLabel(account, nextLevelLabel){
				let account_level = 0;
				let label = '';
				nextLevelLabel ? account_level = account.account_level + 1 : account_level = account.account_level;
				switch(account_level) {
					case 0:
						label = "customer";
						break;
					case 1:
						label = "silver";
						break;
					case 2:
						label = "platinum";
						break;
					case 3:
						label = "diamond";
						break;
				}

				return this.$options.filters.trans('purchase.'+label);
			},

			getDashboardData() {
				axios.get("/api/dashboard/" + this.user.id)
					.then(response => this.setDashboardData(response.data));
			},

			setDashboardData(data){
				this.e_wallet = data.e_wallet;
				this.trees_sold = data.trees_sold;
				this.accounts = data.accounts;
				this.qualification_accounts = data.qualification;
				this.descendants = data.descendants;
				this.posts = data.posts;
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

			viewRedemption(data) {
				this.selectedRedemption = data;
				this.isViewingRedemption = true;
			},

			viewPost(data) {
				this.selectedPost = data;
				this.isViewingPost = true;
			},

			back() {
				this.isViewing = false;
				this.isPurchasing = false;
				this.isViewingRedemption = false;
				this.isViewingPost = false;
				this.isApplying = false;
			},

			remind(user) {
				this.$events.fire('loading', user.id);
				axios.post('/api/user/' + user.id + '/kyc/remind')
					.then(response => this.onSuccess(response));
			},

			legal(user) {
				this.selectedUser = user;
				let selectedLegalStatus = _.filter(this.legalStatus, function(status){ return user.id_status == status.value;});
				if(selectedLegalStatus.length > 0) this.selectedLegalStatus = selectedLegalStatus[0];
				this.isChangingStatus = true;
			},

			submitLegal() {
				this.isLegalLoading = true;
				this.statusForm.status = this.selectedLegalStatus.value;
				this.statusForm.post("/api/user/" + this.selectedUser.id + "/legal")
					.then(response => this.onStepSuccess(response));
			},

			onStepSuccess(response) {
				this.onSuccess(response);
				this.isChangingStatus = false;
				this.isLegalLoading = false;
				this.$refs.users.refreshTable();
			},

			onRedemptionSuccess(response) {
				this.onSuccess(response);
				this.isChangingStatus = false;
				this.isLegalLoading = false;
				this.$refs.redemptions.refreshTable();
			},

			onDeleteSuccess(response) {
				this.onSuccess(response);
				this.isChangingStatus = false;
				this.isLegalLoading = false;
				this.$refs.purchases.refreshTable();
			},

			approveUser(user) {
				axios.post('/api/user/' + user.id + '/approve')
					.then(response => this.onStepSuccess(response));
			},

			rejectUser(user) {
				axios.post('/api/user/' + user.id + '/reject')
					.then(response => this.onStepSuccess(response));
			},

			postApprove(redemption) {
				axios.post('/api/redemption/approve/' + redemption.id)
					.then(response => this.onRedemptionSuccess(response));
			},

			postDeletePurchase(purchase) {
				axios.post("/api/purchase/delete/" + purchase.id)
					.then(response => this.onDeleteSuccess(response));
			},

			onSuccess(response) {
				flash(this.$options.filters.trans(response.data.message));
				this.$events.fire('loading-complete');
			},

			postTitle(value) {
				return lang.locale == "en" ? value.title : value.title_zh;
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
			},

			userNotGrower() {
				return this.user.is_admin || this.user.is_grower;
			},

			totalAccount() {
				return this.accounts.length;
			},
		}
	}
</script>
<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<div v-if="!isEditing && !isApplying">
				<div class="row mb-3">
					<div class="col-sm">
						<button class="btn btn-primary" @click="isEditing = true" v-if="!isViewingDashboard && !isViewingGenoTree && !isViewingTransactions">{{ 'user.edit_profile' | trans }}</button>
						<template v-if="currentUser.is_admin">
							<button class="btn btn-primary" @click="viewDashboard">{{ 'nav.dashboard' | trans }}</button>
							<button class="btn btn-primary" @click="viewGenoTree">{{ 'nav.organization' | trans }}</button>
							<button class="btn btn-primary" @click="viewTransactions">{{ 'nav.transactions' | trans }}</button>
							<button class="btn btn-danger" @click="exitViewAs">{{ "nav.exit" | trans }}</button>
						</template>
						<button class="btn btn-primary" @click="back" v-if="cancelable"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
					</div>
				</div>
				<template v-if="!isViewingDashboard && !isViewingGenoTree && !isViewingTransactions">
					<div class="row">
						<div class="col-sm">
							<div class="card">
								<div class="card-header">
									<div class="row align-items-center">
										<div class="col">
											{{ 'auth.personal_info' | trans }}
										</div>
									</div>
								</div>
								<div class="card-block">
									<text-input
										:defaultValue="$options.filters.formatUserStatus(user.user_status)"
										:required="false"
										type="text"
										:label="$options.filters.trans('user.status')"
										name="package"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>
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
									
									<text-input v-model="user.name" 
											:defaultValue="user.name"
											:required="true"
											type="text"
											:label="$options.filters.trans('auth.name')"
											name="name"
											:editable="false"
											:focus="true"
											:hideLabel="false">
									</text-input>

									<text-input v-model="user.identification" 
										:defaultValue="user.identification"
										:required="true"
										type="text"
										:label="$options.filters.trans('input.' + user.id_type)"
										name="identification"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>

									<text-input v-model="user.email" 
										:defaultValue="user.email"
										:required="true"
										type="email"
										:label="$options.filters.trans('auth.email')"
										name="email"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>

									<text-input v-model="user.address" 
										:defaultValue="user.address"
										:required="true"
										type="text"
										:label="$options.filters.trans('auth.address')"
										name="address"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>
									
									<text-input :defaultValue="contactNumber"
										:required="true"
										type="text"
										:label="$options.filters.trans('auth.phone')"
										name="phone"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>

									<text-input v-model="user.nationality" 
										:defaultValue="user.nationality"
										:required="true"
										type="text"
										:label="$options.filters.trans('auth.nationality')"
										name="nationality"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>

									<text-input v-model="user.gender" 
										:defaultValue="$options.filters.trans('auth.gender_' + user.gender)"
										:required="true"
										type="text"
										:label="$options.filters.trans('auth.gender')"
										name="gender"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>
								</div>
							</div>
						</div>
						<div class="col-sm">
							<div class="card">
								<div class="card-header">
									<div class="row align-items-center">
										<div class="col">
											{{ 'auth.bank_info' | trans }}
										</div>
									</div>
								</div>
								<div class="card-block">

									<text-input v-model="user.bank_name" 
											:defaultValue="user.bank_name"
											:required="true"
											type="text"
											:label="$options.filters.trans('auth.bank_name')"
											name="bank_name"
											:editable="false"
											:focus="true"
											:hideLabel="false">
									</text-input>

									<text-input v-model="user.bank_swift" 
										:defaultValue="user.bank_swift ? user.bank_swift : 'N/A' "
										:required="true"
										type="text"
										:label="$options.filters.trans('auth.bank_swift')"
										name="bank_swift"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>

									<text-input v-model="user.bank_address" 
										:defaultValue="user.bank_address"
										:required="true"
										type="bank_address"
										:label="$options.filters.trans('auth.bank_address')"
										name="bank_address"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>

									<text-input v-model="user.account_type" 
										:defaultValue="user.account_type"
										:required="true"
										type="text"
										:label="$options.filters.trans('auth.account_type')"
										name="account_type"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>

									<text-input v-model="user.account_no" 
										:defaultValue="user.account_no"
										:required="true"
										type="text"
										:label="$options.filters.trans('auth.account_no')"
										name="account_no"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>

									<!-- <text-input v-model="user.beneficiary_name" 
										:defaultValue="user.beneficiary_name"
										:required="true"
										type="text"
										:label="$options.filters.trans('auth.beneficiary_name')"
										name="beneficiary_name"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>

									<text-input v-model="user.beneficiary_identification" 
										:defaultValue="user.beneficiary_identification"
										:required="true"
										type="text"
										:label="$options.filters.trans('auth.beneficiary_identification')"
										name="beneficiary_identification"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>

									<text-input v-model="user.beneficiary_address" 
										:defaultValue="user.beneficiary_address"
										:required="true"
										type="text"
										:label="$options.filters.trans('auth.beneficiary_address')"
										name="beneficiary_address"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input>

									<text-input v-model="user.beneficiary_contact" 
										:defaultValue="user.beneficiary_contact"
										:required="true"
										type="text"
										:label="$options.filters.trans('auth.beneficiary_contact')"
										name="beneficiary_contact"
										:editable="false"
										:focus="false"
										:hideLabel="false">
									</text-input> -->
								</div>
							</div>
						</div>
					</div>
					<!-- <user-documents :selectedUser="user" ref="documents"></user-documents> -->

					<button class="btn btn-primary mb-3 " @click="isApplying = true" v-if="!userNotGrower">{{ "user.apply_as_advisor" | trans }}</button>
					<user-level :selectedUser="user" ref="levels" v-if="currentUser.is_admin" @back="back"></user-level>
					<div class="row" v-if="user.company_name">
						<div class="col-sm">
							<div class="card">
								<div class="card-header">
									{{ 'auth.company_info' | trans }}
								</div>
								<div class="card-block">				
									<div class="row">
										<div class="col-sm">
											<text-input v-model="user.company_name" 
													:defaultValue="user.company_name"
													:required="true"
													type="text"
													:label="$options.filters.trans('auth.company_name')"
													name="company_name"
													:editable="false"
													:focus="true"
													:hideLabel="false">
											</text-input>

											<text-input v-model="user.company_email" 
													:defaultValue="user.company_email"
													:required="true"
													type="text"
													:label="$options.filters.trans('auth.email')"
													name="company_email"
													:editable="false"
													:focus="true"
													:hideLabel="false">
											</text-input>

											<text-input v-model="user.company_business_registration" 
												:defaultValue="user.company_business_registration"
												:required="true"
												type="text"
												:label="$options.filters.trans('auth.company_business_registration')"
												name="company_business_registration"
												:editable="false"
												:focus="false"
												:hideLabel="false">
											</text-input>

											<text-input v-model="user.company_incorporation_place" 
												:defaultValue="user.company_incorporation_place"
												:required="true"
												type="text"
												:label="$options.filters.trans('auth.company_incorporation_place')"
												name="company_incorporation_place"
												:editable="false"
												:focus="false"
												:hideLabel="false">
											</text-input>

											<text-input v-model="user.company_regulatory_name" 
												:defaultValue="user.company_regulatory_name"
												:required="true"
												type="text"
												:label="$options.filters.trans('auth.company_regulatory_name')"
												name="company_regulatory_name"
												:editable="false"
												:focus="false"
												:hideLabel="false">
											</text-input>

											<text-input v-model="user.company_incorporation_date" 
												:defaultValue="user.company_incorporation_date"
												:required="true"
												type="text"
												:label="$options.filters.trans('auth.company_incorporation_date')"
												name="company_incorporation_date"
												:editable="false"
												:focus="false"
												:hideLabel="false">
											</text-input>

											<text-input v-model="user.company_type" 
												:defaultValue="$options.filters.trans('company.' + user.company_type)"
												:required="true"
												type="text"
												:label="$options.filters.trans('auth.company_type')"
												name="company_type"
												:editable="false"
												:focus="false"
												:hideLabel="false">
											</text-input>

											<text-input v-model="user.company_address.display" 
												:defaultValue="user.company_address.display"
												:required="true"
												type="text"
												:label="$options.filters.trans('auth.address')"
												name="company_address"
												:editable="false"
												:focus="false"
												:hideLabel="false">
											</text-input>

											<text-input :defaultValue="companyContactNumber"
												:required="true"
												type="text"
												:label="$options.filters.trans('auth.phone')"
												name="phone"
												:editable="false"
												:focus="false"
												:hideLabel="false">
											</text-input>
										</div>
										<div class="col-sm">
											<h6 class="card-title">
												{{ 'auth.contact_personnels' | trans }}
												
											</h6>
											<div class="table-responsive">
												<table class="table">
													<thead>
														<th>{{ 'auth.name' | trans }}</th>
														<th>{{ 'auth.personnel_designation' | trans }}</th>
														<th>{{ 'auth.email' | trans }}</th>
														<th>{{ 'auth.phone' | trans }}</th>
													</thead>
													<tbody>
														<tr v-for="(personnel,index) in contacts">
															<td>{{ personnel.name }}</td>
															<td>{{ personnel.designation }}</td>
															<td>{{ personnel.email }}</td>
															<td>{{ personnel.phone }}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</template>
				
				<button class="btn btn-primary" @click="back" v-if="cancelable"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
			</div>
			<user :selectedUser="user" @back="editComplete" v-if="isEditing"></user>
		</transition>

		<dashboard :viewingUser="user" v-if="isViewingDashboard"></dashboard>
		<geno-page :viewingUser="user" v-if="isViewingGenoTree"></geno-page>
		<transaction-page :userId="user.id" v-if="isViewingTransactions" :cancelable="false"></transaction-page>


	    <transition name="slide-fade" mode="out-in">
	    	 <advisor-application :user="user" v-if="isApplying" @back="isApplying = false"></advisor-application>
	    </transition>

	</div>
</template>

<script>
	import ReferralLink from "../components/ReferralLink.vue";
	import UserDocuments from "../components/UserDocuments";
	import UserLevel from "../components/UserLevel";
	import User from "../components/User.vue";
	import Dashboard from "./DashboardStripped.vue";
	import TransactionPage from "./TransactionPage.vue";
	import GenoPage from "./GenoPage.vue";

	import AdvisorApplication from "./AdvisorApplication.vue";

	export default {
		props: ['cancelable', 'selectedUser'],

		components: { ReferralLink, User, UserDocuments, UserLevel, Dashboard, GenoPage, TransactionPage, AdvisorApplication },

		data() {
			return {
				user: '',
				currentUser: window.user,
				contacts: '',
				isEditing: false,
				isUploading: false,
				isViewingTransactions: false,
				isViewingDashboard: false,
				isViewingGenoTree: false,
				isApplying: false
			};
		},

		mounted() {

			this.user = this.selectedUser ? this.selectedUser : window.user;
			if(this.selectedUser)
			{
				this.getUser();
				this.getAddress();
			}
			// this.$refs.documents.setKycDocs();
			this.getContacts();

			if(this.getParameterByName('document'))
			{
				this.isUploading = true;
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

			viewDashboard() {
				this.isViewingDashboard = true;
				this.isViewingTransactions = false;
				this.isViewingGenoTree = false;
			},

			viewTransactions() {
				this.isViewingDashboard = false;
				this.isViewingTransactions = true;
				this.isViewingGenoTree = false;
			},

			viewGenoTree() {
				this.isViewingDashboard = false;
				this.isViewingTransactions = false;
				this.isViewingGenoTree = true;
			},

			exitViewAs() {
				this.isViewingTransactions = false;
				this.isViewingGenoTree = false;
				this.isViewingDashboard = false;
				// Vue.nextTick(() => this.$refs.documents.setKycDocs());
				Vue.nextTick(() => this.$refs.levels.getParents());
			},

			getContacts() {
				if(user.company_name)
				{
					axios.get('/api/user/' + this.user.id + '/company/contacts')
						.then(response => this.setContacts(response));
				}
			},

			setContacts(response) {
				this.contacts = response.data;
			},

			editComplete() {
				this.isUploading = false;
				this.isEditing = false;
				this.getUser();
			},

			getUser() {
				axios.get('/api/user/' + this.user.id )
					.then(response => this.setUser(response));

				// axios.get('/api/user/' + this.user.id + "/address")
				// 	.then(response => this.setAddress(response));
			},

			getAddress() {
				// axios.get('/api/user/' + this.user.id )
				// 	.then(response => this.setUser(response));

				axios.get('/api/user/' + this.user.id + "/address")
					.then(response => this.setAddress(response));
			},

			setAddress(response) {
				Vue.set(this.user, 'address', response.data);
			},

			setUser(response) {
				this.user = response.data;
			},

			back() {
				this.$emit('back');
			}
		},

		computed: {
			contactNumber() {
				if(this.user)
				{
					let result = _.filter(this.user.addresses, function(address){
						return address.type == "personal";
					});

					if(result.length > 0)
						return result[0].phone;
				}

				return '';
			},

			companyContactNumber() {
				if(this.user && this.user.addresses.length > 1)
				{
					let result = _.filter(this.user.addresses, function(address){
						return address.type == "company";
					});
					if(result.length > 0)
						return result[0].phone;
				}

				return '';
			},

			userNotGrower() {
				return this.user.is_admin || this.user.is_advisor;
			}
		}	
	}
</script>
<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<div v-if="!isEditing">
				<div class="row mb-3">
					<div class="col-sm">
						<button class="btn btn-primary" @click="isEditing = true">{{ 'user.edit_profile' | trans }}</button>
					</div>
				</div>
				<div class="row">
					<div class="col-sm">
						<div class="card">
							<div class="card-header">
								<div class="row align-items-center">
									<div class="col">
										{{ 'auth.personal_info' | trans }}
									</div>
									<div class="col-auto" v-if="cancelable">
										<button class="btn btn-primary" @click="back"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
									</div>
								</div>
							</div>
							<div class="card-block">
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
									:label="$options.filters.trans('auth.identification')"
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

								<!-- <text-input
									:defaultValue="user.package.tree_count + ' ' + $options.filters.trans_choice('auth.tree', user.package.tree_count)"
									:required="false"
									type="text"
									:label="$options.filters.trans('auth.selected_package')"
									name="package"
									:editable="false"
									:focus="false"
									:hideLabel="false">
								</text-input> -->
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
									<div class="col-auto" v-if="cancelable">
										<button class="btn btn-primary" @click="back"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
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

								<text-input v-model="user.beneficiary_name" 
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
							</div>
						</div>
					</div>
				</div>
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
			</div>
			<user :selectedUser="user" @back="editComplete" v-else></user>

		</transition>
	</div>
</template>

<script>
	import ReferralLink from "../components/ReferralLink.vue";
	import User from "../components/User.vue";

	export default {
		props: ['cancelable', 'selectedUser'],

		components: { ReferralLink, User },

		data() {
			return {
				user: '',
				contacts: '',
				isEditing: false
			};
		},

		mounted() {
			this.user = this.selectedUser ? this.selectedUser : window.user;
			this.getContacts();
		},

		methods: {
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
				this.isEditing = false;
				this.getUser();
			},

			getUser() {
				axios.get('/api/user/' + this.user.id )
					.then(response => this.setUser(response));
			},

			setUser(response) {
				this.user = response.data;
			},

			back() {
				this.$emit('back');
			}
		}	
	}
</script>
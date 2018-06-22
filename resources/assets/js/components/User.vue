<template>

	<div class="row">
		<div class="col-sm-12">
			<div class='card'>
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col">
							{{ 'user.edit_profile' | trans }}
						</div>
						<div class="col-auto">
							<button type="submit" @click="submit" class="btn btn-success" :disabled="form.submitting" v-html="submitButtonContent"></button>
							<button class="btn btn-primary" @click="back"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form @submit.prevent="submit" 
						@keydown="form.errors.clear($event.target.name)" 
						@input="form.errors.clear($event.target.name)">
						<h4>{{ 'auth.personal_info' | trans }}</h4>
						<div class="row">
							<div class="col-sm">
								<text-input v-model="form.name" 
									:defaultValue="form.name"
									:required="true"
									type="text"
									:label="$options.filters.trans('auth.name')"
									name="name"
									:editable="user.is_admin"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('name')">
								</text-input>
							</div>
							<div class="col-sm">
								<text-input v-model="form.identification" 
									:defaultValue="form.identification"
									:required="true"
									type="text"
									:label="$options.filters.trans('auth.identification')"
									name="identification"
									:editable="user.is_admin"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('identification')"
									extraClass="mb-0">
								</text-input>
								<radio-group-input v-model="form.id_type"
													:values="['nric', 'passport']" 
													:defaultValue="form.id_type"
													label=""
													name="id_type"
													:hideLabel="true">
								</radio-group-input>
							</div>
						</div>
						<text-input v-model="form.email" 
							:defaultValue="form.email"
							:required="true"
							type="email"
							:label="$options.filters.trans('auth.email')"
							name="email"
							:editable="user.is_admin"
							:focus="false"
							:hideLabel="false"
							:error="form.errors.get('email')">
						</text-input>
						<div class="row">
							<div class="col-sm" v-if="form.isChangePassword">
	    						<text-input v-model="form.password" 
									:defaultValue="form.password"
									:required="true"
									type="password"
									:label="$options.filters.trans('auth.password')"
									name="password"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('password')">
								</text-input>
							</div>
							<div class="col-sm" v-if="form.isChangePassword">
								<text-input v-model="form.password_confirmation" 
									:defaultValue="form.password_confirmation"
									:required="true"
									type="password"
									:label="$options.filters.trans('auth.repeat')+ ' ' + $options.filters.trans('auth.password')"
									name="password"
									:editable="true"
									:focus="false"
									:hideLabel="false">
								</text-input>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-sm">
								<button type="button" class="btn btn-danger" @click="form.isChangePassword = false" v-if="form.isChangePassword">{{ 'user.cancel_change_password' | trans }}</button>
								<button type="button" class="btn btn-primary" @click="form.isChangePassword = true" v-else>{{ 'user.change_password' | trans }}</button>
							</div>
						</div>
						<text-input v-model="form.address_line_1" 
							:defaultValue="form.address_line_1"
							:required="true"
							type="text"
							:label="$options.filters.trans('auth.address')"
							:placeholder="$options.filters.trans('auth.address1')"
							name="address_line_1"
							:editable="true"
							:focus="false"
							:hideLabel="false"
							:error="form.errors.get('address_line_1')">
						</text-input>
						<text-input v-model="form.address_line_2" 
							:defaultValue="form.address_line_2"
							:required="true"
							type="text"
							:placeholder="$options.filters.trans('auth.address2')"
							name="address_line_2"
							:editable="true"
							:focus="false"
							:hideLabel="true"
							:error="form.errors.get('address_line_2')">
						</text-input>
						<div class="row">
							<div class="col-sm">
								<selector-input v-model="country" :defaultData="country" 
										:label="$options.filters.trans('auth.country')" 
										:required="true"
										name="country"
										:potentialData="potentialCountry"
										:editable="true"
										:placeholder="$options.filters.trans('auth.country')"
										:hideLabel="true"
										@input="updateCountry"
										>
								</selector-input>
							</div>
							<div class="col-sm">
								<text-input v-model="form.postcode" 
									:defaultValue="form.postcode"
									:required="true"
									type="text"
									:placeholder="$options.filters.trans('auth.postcode')"
									name="postcode"
									:editable="true"
									:focus="false"
									:hideLabel="true"
									:error="form.errors.get('postcode')">
								</text-input>
							</div>
						</div>
						<div class="row">
							<div class="col-sm">
								<selector-input v-model="nationality" :defaultData="nationality" 
										:label="$options.filters.trans('auth.nationality')" 
										:required="true"
										name="nationality"
										:potentialData="potentialCountry"
										:editable="true"
										:placeholder="$options.filters.trans('auth.nationality')"
										:hideLabel="false"
										@input="updateNationality"
										>
								</selector-input>
							</div>
							<div class="col-sm">
								<text-input v-model="form.phone" 
									:defaultValue="form.phone"
									:required="true"
									type="text"
									:label="$options.filters.trans('auth.phone')"
									name="phone"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('phone')">
								</text-input>
							</div>
							<div class="col-sm">
								<radio-group-input v-model="form.gender"
													:values="['male', 'female']" 
													:defaultValue="form.gender"
													:label="$options.filters.trans('auth.gender')"
													name="gender">
								</radio-group-input>
							</div>
						</div>
						<hr>
						<h4>{{ 'auth.company_info' | trans }}</h4>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" id="company-registration" v-model="isCompanyRegistration">
							<label class="form-check-label" for="company-registration">{{ 'auth.registering_company' | trans }}</label>
						</div>

						<div v-if="isCompanyRegistration">
							<text-input v-model="form.company_name" 
									:defaultValue="form.company_name"
									:required="true"
									type="text"
									:label="$options.filters.trans('auth.company_name')"
									name="company_name"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('company_name')">
								</text-input>
							<div class="row">
								<div class="col-sm">
									<text-input v-model="form.company_business_registration" 
										:defaultValue="form.company_business_registration"
										:required="true"
										type="text"
										:label="$options.filters.trans('auth.company_business_registration')"
										name="company_business_registration"
										:editable="true"
										:focus="false"
										:hideLabel="false"
										:error="form.errors.get('company_business_registration')">
									</text-input>
								</div>
								<div class="col-sm">
									<text-input v-model="form.company_incorporation_date" 
										:defaultValue="form.company_incorporation_date"
										:required="true"
										type="date"
										:label="$options.filters.trans('auth.company_incorporation_date')"
										name="company_incorporation_date"
										:editable="true"
										:focus="false"
										:hideLabel="false"
										:error="form.errors.get('company_incorporation_date')">
									</text-input>
								</div>
							</div>
							<div class="row">
								<div class="col-sm">
									<text-input v-model="form.company_incorporation_place" 
										:defaultValue="form.company_incorporation_place"
										:required="true"
										type="text"
										:label="$options.filters.trans('auth.company_incorporation_place')"
										name="company_incorporation_place"
										:editable="true"
										:focus="false"
										:hideLabel="false"
										:error="form.errors.get('company_incorporation_place')">
									</text-input>
								</div>
								<div class="col-sm">
									<text-input v-model="form.company_regulatory_name" 
										:defaultValue="form.company_regulatory_name"
										:required="false"
										type="text"
										:label="$options.filters.trans('auth.company_regulatory_name')"
										name="company_regulatory_name"
										:editable="true"
										:focus="false"
										:hideLabel="false"
										:error="form.errors.get('company_regulatory_name')">
									</text-input>
								</div>
								<div class="col-sm">
									<selector-input v-model="company_business_entity" :defaultData="company_business_entity" 
													:label="$options.filters.trans('auth.company_type')" 
													:required="true"
													name="company_type"
													:potentialData="companyTypes"
													:editable="true"
													:placeholder="$options.filters.trans('auth.company_type')"
													@input="updateCompanyType"
													>
									</selector-input>
								</div>
							</div>
							
							<text-input v-model="form.company_address_line_1" 
								:defaultValue="form.company_address_line_1"
								:required="true"
								type="text"
								:label="$options.filters.trans('auth.company_address')"
								name="company_address_line_1"
								:editable="true"
								:focus="false"
								:hideLabel="false"
								:error="form.errors.get('company_address_line_1')">
							</text-input>
							<text-input v-model="form.company_address_line_2" 
								:defaultValue="form.company_address_line_2"
								:required="true"
								type="text"
								:label="$options.filters.trans('auth.company_address')"
								name="company_address_line_2"
								:editable="true"
								:focus="false"
								:hideLabel="true"
								:error="form.errors.get('company_address_line_2')">
							</text-input>
				            

				            <div class="row">
				                <div class="col-sm">
				                    <selector-input v-model="companyCountry" :defaultData="companyCountry" 
													:label="$options.filters.trans('auth.country')" 
													:required="true"
													name="company_country"
													:potentialData="potentialCountry"
													:editable="true"
													:placeholder="$options.filters.trans('auth.country') + ' *'"
													:hideLabel="true"
													@input="updateCompanyCountry"
													>
									</selector-input>
				                </div>
				                <div class="col-sm">
				                	<text-input v-model="form.company_postcode" 
										:defaultValue="form.company_postcode"
										:required="true"
										type="text"
										:placeholder="$options.filters.trans('auth.postcode')"
										name="company_postcode"
										:editable="true"
										:focus="false"
										:hideLabel="true"
										:error="form.errors.get('company_postcode')">
									</text-input>
				                </div>
				            </div>

				             <div class="row">
				                <div class="col-sm">
				                    <text-input v-model="form.company_phone" 
										:defaultValue="form.company_phone"
										:required="true"
										type="text"
										:placeholder="$options.filters.trans('auth.phone')"
										name="company_phone"
										:editable="true"
										:focus="false"
										:hideLabel="true"
										:error="form.errors.get('company_phone')">
									</text-input>
				                </div>
				                <div class="col-sm">
				                    <text-input v-model="form.company_email" 
										:defaultValue="form.company_email"
										:required="true"
										type="email"
										:placeholder="$options.filters.trans('auth.email')"
										name="company_email"
										:editable="true"
										:focus="false"
										:hideLabel="true"
										:error="form.errors.get('company_email')">
									</text-input>
				                </div>
				            </div>
							
							<label>{{ 'auth.contact_personnels' | trans }}</label>
							<div class="table-responsive">
								<table class="table">
									<thead>
										<th>{{ 'auth.name' | trans }}</th>
										<th>{{ 'auth.personnel_designation' | trans }}</th>
										<th>{{ 'auth.email' | trans }}</th>
										<th>{{ 'auth.phone' | trans }}</th>
										<th><a @click="form.personnels.push({name: '', designation: '', email: '', phone: ''})" class="btn btn-sm btn-primary">{{ 'auth.add_personnel' | trans }}</a></th>
									</thead>
									<tbody>
										<tr v-for="(personnel,index) in form.personnels">
											<td>
												<text-input v-model="form.personnels[index].name" 
													:defaultValue="form.personnels[index].name"
													:required="true"
													type="text"
													name="personnel-name"
													:editable="true"
													:focus="false"
													:hideLabel="true"
													v-if="form.personnels[index]">
												</text-input>
											</td>
											<td>
												<text-input v-model="form.personnels[index].designation" 
													:defaultValue="form.personnels[index].designation"
													:required="true"
													type="text"
													name="personnel-designation"
													:editable="true"
													:focus="false"
													:hideLabel="true"
													v-if="form.personnels[index]">
												</text-input>
											</td>
											<td>
												<text-input v-model="form.personnels[index].email" 
													:defaultValue="form.personnels[index].email"
													:required="true"
													type="email"
													name="personnel-email"
													:editable="true"
													:focus="false"
													:hideLabel="true"
													v-if="form.personnels[index]">
												</text-input></td>
											<td>
												<text-input v-model="form.personnels[index].phone" 
													:defaultValue="form.personnels[index].phone"
													:required="true"
													type="text"
													name="personnel-phone"
													:editable="true"
													:focus="false"
													:hideLabel="true"
													v-if="form.personnels[index]">
												</text-input>
											</td>
											<td><a @click="personnels.splice(index, 1)" class="btn btn-sm btn-danger">{{ 'auth.remove_personnel' | trans }}</a></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<hr>
    					<h4>{{ 'auth.bank_info' | trans }}</h4>
    					<div class="row">
							<div class="col-sm">
								<text-input v-model="form.bank_name" 
									:defaultValue="form.bank_name"
									:required="true"
									type="text"
									:label="$options.filters.trans('auth.bank_name')"
									name="bank_name"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('bank_name')">
								</text-input>
							</div>
							<div class="col-sm">
								<text-input v-model="form.bank_swift" 
									:defaultValue="form.bank_swift"
									:required="false"
									type="text"
									:label="$options.filters.trans('auth.bank_swift')"
									name="bank_swift"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('bank_swift')">
								</text-input>
							</div>
    					</div>
    					<text-input v-model="form.bank_address" 
							:defaultValue="form.bank_address"
							:required="true"
							type="text"
							:label="$options.filters.trans('auth.bank_address')"
							name="bank_address"
							:editable="true"
							:focus="false"
							:hideLabel="false"
							:error="form.errors.get('bank_address')">
						</text-input>
						<div class="row">
							<div class="col-sm">
								<text-input v-model="form.account_type" 
									:defaultValue="form.account_type"
									:required="true"
									type="text"
									:label="$options.filters.trans('auth.account_type')"
									name="account_type"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('account_type')">
								</text-input>
							</div>
							<div class="col-sm">
								<text-input v-model="form.account_no" 
									:defaultValue="form.account_no"
									:required="true"
									type="text"
									:label="$options.filters.trans('auth.account_no')"
									name="account_no"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('account_no')">
								</text-input>
							</div>
						</div>
						<hr>
    					<h4>{{ 'auth.beneficiary_info' | trans }}</h4>
    					<div class="row">
    						<div class="col-sm">
    							<text-input v-model="form.beneficiary_name" 
									:defaultValue="form.beneficiary_name"
									:required="true"
									type="text"
									:label="$options.filters.trans('auth.beneficiary_name')"
									name="beneficiary_name"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('beneficiary_name')">
								</text-input>
							</div>
							<div class="col-sm">
								<text-input v-model="form.beneficiary_identification" 
									:defaultValue="form.beneficiary_identification"
									:required="true"
									type="text"
									:label="$options.filters.trans('auth.beneficiary_identification')"
									name="beneficiary_identification"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('beneficiary_identification')">
								</text-input>
							</div>
						</div>
						<div class="row">
    						<div class="col-sm">
    							<text-input v-model="form.beneficiary_address" 
									:defaultValue="form.beneficiary_address"
									:required="true"
									type="text"
									:label="$options.filters.trans('auth.beneficiary_address')"
									name="beneficiary_address"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('beneficiary_address')">
								</text-input>
							</div>
							<div class="col-sm">
								<text-input v-model="form.beneficiary_contact" 
									:defaultValue="form.beneficiary_contact"
									:required="true"
									type="text"
									:label="$options.filters.trans('auth.beneficiary_contact')"
									name="beneficiary_contact"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('beneficiary_contact')">
								</text-input>
							</div>
						</div>
						<button type="submit" class="btn btn-success" :disabled="form.submitting" v-html="submitButtonContent"></button>
						<button class="btn btn-primary" @click="back"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import formButtonMixin from '../mixins/form-button.js';

	export default {
		props: ['selectedUser'],

		mixins: [formButtonMixin],

		data() {
			return {
				form: new Form({
					name: '',
					identification: '',
					id_type: '',
					email: '',
					address_line_1: '',
					address_line_2: '',
					country_id: 162,
					postcode: '',
					nationality: 'Malaysia',
					phone: '',
					gender: '',
					company_name: '',
					company_business_registration: '',
					company_incorporation_date: '',
					company_incorporation_place: '',
					company_regulatory_name: '',
					company_type: 'company',
					company_address_line_1: '',
					company_address_line_2: '',
					company_country_id: 162,
					company_postcode: '',
					company_phone: '',
					company_email: '',
					personnels: [],
					bank_name: '',
					bank_swift: '',
					bank_address: '',
					account_type: '',
					account_no: '',
					beneficiary_name: '',
					beneficiary_identification: '',
					beneficiary_address: '',
					beneficiary_contact: '',
					isChangePassword: ''
				}),
				isCompanyRegistration: false,
				companyTypes: [],
				user: window.user,
				companyCountry: { value: 162, label: "Malaysia" },
				potentialCountry: [],
				company_business_entity: {},
				country: { value: 162, label: "Malaysia" },
				nationality: { value: 162, label: "Malaysia" }
			};
		},

		mounted() {
			this.companyTypes = [
					{label: this.$options.filters.trans("company.company"), value: 'company'},
					{label: this.$options.filters.trans("company.sole"), value: 'sole'},
					{label: this.$options.filters.trans("company.partnership"), value: 'partnership'},
					{label: this.$options.filters.trans("company.trust"), value: 'trust'},
				];
			this.company_business_entity =  {label: this.$options.filters.trans("company.company"), value: 'company'};

			this.getCountries();
			
		},

		methods: {
			getCountries() {
				axios.get('/api/countries')
					.then(response => this.setCountry(response));
			},

			setCountry(response) {
				this.potentialCountry = response.data.map(country => {
					let obj = {};
					obj['label'] = country.name;
					obj['value'] = country.id;

					return obj;
				});	 

				this.setUser();
			},

			setUser() {
				this.form.name = this.selectedUser.name;
				this.form.identification = this.selectedUser.identification;
				this.form.id_type = this.selectedUser.id_type;
				this.form.email = this.selectedUser.email;
				this.form.gender = this.selectedUser.gender;
				this.form.bank_name = this.selectedUser.bank_name;
				this.form.bank_swift = this.selectedUser.bank_swift;
				this.form.bank_address = this.selectedUser.bank_address;
				this.form.account_type = this.selectedUser.account_type;
				this.form.account_no = this.selectedUser.account_no;

				this.form.beneficiary_name = this.selectedUser.beneficiary_name;
				this.form.beneficiary_identification = this.selectedUser.beneficiary_identification;
				this.form.beneficiary_address = this.selectedUser.beneficiary_address;
				this.form.beneficiary_contact = this.selectedUser.beneficiary_contact;
				let address =  _.head(_.filter(this.selectedUser.addresses, function(address) {
								return address.type == "personal"; 
							}));

				this.form.address_line_1 = address.line_1;
				this.form.address_line_2 = address.line_2;
				this.form.postcode = address.postcode;
				this.form.country_id = address.country_id;
				this.form.phone = address.phone;
				this.country = { value: address.country.id, label: address.country.name };

				this.form.nationality = this.selectedUser.nationality;

				let nationality =  _.filter(this.potentialCountry, function(country) {
										return country.label == this.selectedUser.nationality; 
									}.bind(this));

				this.nationality = _.head(nationality);

				let company_address =  _.head(_.filter(this.selectedUser.addresses, function(address) {
								return address.type == "company"; 
							}));
				

				if(company_address.line_1)
				{
					this.form.company_address_line_1 = company_address.line_1;
					this.form.company_address_line_2 = company_address.line_2;
					this.form.company_postcode = company_address.postcode;
					this.form.company_country_id = company_address.country_id;
					this.form.company_phone = company_address.phone;
					this.form.company_email = this.selectedUser.company_email;
					this.companyCountry = { value: address.country.id, label: address.country.name };

					this.form.company_name = this.selectedUser.company_name;
					this.form.company_business_registration = this.selectedUser.company_business_registration;
					this.form.company_incorporation_date = this.selectedUser.company_incorporation_date;
					this.form.company_incorporation_place = this.selectedUser.company_incorporation_place;
					this.form.company_regulatory_name = this.selectedUser.company_regulatory_name;

					this.form.personnels = this.selectedUser.contacts;

					this.form.company_type = this.selectedUser.company_type;

					let selectedType = _.filter(this.companyTypes, function(type) {
								return type.value == this.selectedUser.company_type; 
							}.bind(this));

					this.company_business_entity = _.head(selectedType);

					this.isCompanyRegistration = true;
				}
			},

			updateCompanyCountry(data) {
				this.form.company_country_id = data.value;
			},

			updateCountry(data) {
				this.form.country_id = data.value;
			},

			updateNationality(data) {
				this.form.nationality = data.label;
			},

			updateCompanyType(data) {
				this.form.company_type = data.value;
			},

			back() {
				this.$emit('back');
			},

			submit() {
				this.form.post('/api/user/' + this.selectedUser.id + "/update")
					.then(response => this.onSuccess(response));
			},

			onSuccess(response) {
				flash(this.$options.filters.trans(response.message));
				this.back();
			}
		}	
	}
</script>
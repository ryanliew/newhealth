<template>
	<div>
		<div class="form-check">
			<input class="form-check-input" type="checkbox" id="company-registration" v-model="isCompanyRegistration">
			<label class="form-check-label" for="company-registration">{{ 'auth.registering-company' | trans }}</label>
		</div>

		<div v-if="isCompanyRegistration">
			<div class="form-group mt-2">
			    <label>{{ 'auth.company-name' | trans }}  <span class="text-danger">*</span></label>
			    <input name='company_name' type="text" class="form-control" :placeholder="$options.filters.trans('auth.company-name')" v-model="company_name" required/>
			</div>
			<div class="row">
				<div class="col-sm">
					<div class="form-group">
					    <label>{{ 'auth.company-business-registration' | trans }}  <span class="text-danger">*</span></label>
					    <input name='company_business_registration' type="text" class="form-control" :placeholder="$options.filters.trans('auth.company-business-registration')" v-model="company_business_registration" required/>
					</div>
				</div>
				<div class="col-sm">
					<div class="form-group">
					    <label>{{ 'auth.company-incorporation-date' | trans }}  <span class="text-danger">*</span></label>
					    <input name='company_incorporation_date' type="date" class="form-control" :placeholder="$options.filters.trans('auth.company-incorporation-date')" v-model="company_incorporation_date" required/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
					<div class="form-group">
					    <label>{{ 'auth.company-incorporation-place' | trans }}  <span class="text-danger">*</span></label>
					    <input name='company_incorporation_place' type="text" class="form-control" :placeholder="$options.filters.trans('auth.company-incorporation-place')" v-model="company_incorporation_place" required/>
					</div>
				</div>
				<div class="col-sm">
					<div class="form-group">
					    <label>{{ 'auth.company-regulatory-name' | trans }}</label>
					    <input name='company_regulatory_name' type="text" class="form-control" :placeholder="$options.filters.trans('auth.company-regulatory-name')" v-model="company_regulatory_name"/>
					</div>
				</div>
				<div class="col-sm">
					<selector-input v-model="company_business_entity" :defaultData="company_business_entity" 
									:label="$options.filters.trans('auth.company_type')" 
									:required="true"
									name="company_type"
									:potentialData="companyTypes"
									:editable="true"
									:placeholder="$options.filters.trans('auth.company_type')"
									>
					</selector-input>
					<input type="hidden" name="company_country_id" v-model="company_business_entity.value">
				</div>
			</div>
			<div class="form-group">
                <label>{{ 'auth.company-address' | trans }}  <span class="text-danger">*</span></label>
                <input name='company_address_line_1' type="text" class="form-control" :placeholder="$options.filters.trans('auth.address1')" v-model="company_address_line_1" required/>
            </div>
            <div class="form-group">
                <input name='company_address_line_2' type="text" class="form-control" :placeholder="$options.filters.trans('auth.address2')" v-model="company_address_line_2" />
            </div>

            <div class="row">
                <div class="col-sm">
                    <selector-input v-model="company_country" :defaultData="company_country" 
									:label="$options.filters.trans('auth.country')" 
									:required="true"
									name="company_country"
									:potentialData="potentialCountries"
									:editable="true"
									:placeholder="$options.filters.trans('auth.country') + ' *'"
									:hideLabel="true"
									>
					</selector-input>
					<input type="hidden" name="company_country_id" v-model="company_country.value">
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <input name='company_postcode' type="text" class="form-control" :placeholder="$options.filters.trans('auth.postcode') + ' *'" v-model="company_postcode" required/>
                    </div>
                </div>
            </div>

             <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <input name='company_phone' type="tel" class="form-control" :placeholder="$options.filters.trans('auth.phone') + ' *'" v-model="company_phone" required/>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <input name='company_email' type="email" class="form-control" :placeholder="$options.filters.trans('auth.email') + ' *'" v-model="company_email" required/>
                    </div>
                </div>
            </div>
			
			<label>{{ 'auth.contact-personnels' | trans }}</label>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<th>{{ 'auth.name' | trans }}</th>
						<th>{{ 'auth.personnel-designation' | trans }}</th>
						<th>{{ 'auth.email' | trans }}</th>
						<th>{{ 'auth.phone' | trans }}</th>
						<th><a @click="personnels.push([])" class="btn btn-sm btn-primary">{{ 'auth.add-personnel' | trans }}</a></th>
					</thead>
					<tbody>
						<tr v-for="(personnel,index) in personnels">
							<td><input :name="'personnel['+index+'][0]'" type="text" class="form-control"></td>
							<td><input :name="'personnel['+index+'][1]'" type="text" class="form-control"></td>
							<td><input :name="'personnel['+index+'][2]'" type="email" class="form-control"></td>
							<td><input :name="'personnel['+index+'][3]'" type="text" class="form-control"></td>
							<td><a @click="personnels.splice(index, 1)" class="btn btn-sm btn-danger">{{ 'auth.remove-personnel' | trans }}</a></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>

<script>
	import SelectorInput from './SelectorInput.vue';
	export default {
		props: ['user_country', 'app_locale', 'countries'],

		components: { SelectorInput },

		mounted() {
			lang.setLocale(this.app_locale);

			this.potentialCountries = this.countries.map(country => {
				let obj = {};
				obj['label'] = country.name;
				obj['value'] = country.id;

				return obj;
			});

			this.company_country = this.user_country;
		},

		data() {
			return {
				isCompanyRegistration: false,
				company_name: '',
				company_business_registration: '',
				company_incorporation_date: '',
				company_incorporation_place: '',
				company_regulatory_name: '',
				company_address_line_1: '',
				company_address_line_2: '',
				company_country: '',
				company_email: '',
				companyTypes: [
					{label: this.$options.filters.trans("company.company"), value: 'company'},
					{label: this.$options.filters.trans("company.sole"), value: 'sole'},
					{label: this.$options.filters.trans("company.partnership"), value: 'partnership'},
					{label: this.$options.filters.trans("company.trust"), value: 'trust'},
				],
				company_business_entity: {label: this.$options.filters.trans("company.company"), value: 'company'},
				company_postcode: '',
				company_phone: '',
				potentialCountries: '',
				personnels: []


			};
		}	
	}
</script>
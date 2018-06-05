<template>
	<div class="row">
		<div class="col">
			<div class="card">
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col">
							{{ title | trans }}
						</div>
						<div class="col-auto">
							<button class="btn btn-primary" @click="back"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form @submit.prevent="submit" 
						@keydown="form.errors.clear($event.target.name)" 
						@input="form.errors.clear($event.target.name)">
						<text-input v-model="form.tree_count" 
							:defaultValue="form.tree_count"
							:required="true"
							type="number"
							:label="$options.filters.trans('package.tree_count')"
							name="tree_count"
							:editable="true"
							:focus="true"
							:hideLabel="false"
							:error="form.errors.get('tree_count')">
						</text-input>
						<div class="row">
							<div class="col-sm">
								<text-input v-model="form.price" 
									:defaultValue="form.price"
									:required="true"
									type="number"
									:label="$options.filters.trans('package.price')"
									name="price"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('price')">
								</text-input>
								<text-input v-model="form.price_promotion" 
									:defaultValue="form.price_promotion"
									:required="false"
									type="number"
									:label="$options.filters.trans('package.price_promotion')"
									name="price_promotion"
									:editable="true"
									:focus="false"
									:hideLabel="false">
								</text-input>
							</div>
							<div class="col-sm">
								<text-input v-model="form.price_std" 
									:defaultValue="form.price_std"
									:required="true"
									type="number"
									:label="$options.filters.trans('package.price_std')"
									name="price_std"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									:error="form.errors.get('price_std')">
								</text-input>
								<text-input v-model="form.price_std_promotion" 
									:defaultValue="form.price_std_promotion"
									:required="false"
									type="number"
									:label="$options.filters.trans('package.price_std_promotion')"
									name="price_promotion"
									:editable="true"
									:focus="false"
									:hideLabel="false">
								</text-input>
							</div>
						</div>

						<button type="submit" class="btn btn-primary" :disabled="form.submitting" v-html="submitButtonContent"></button>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import formButtonMixin from '../mixins/form-button.js';
	export default {
		props: ['selectedPackage'],

		mixins: [formButtonMixin],

		data() {
			return {
				form: new Form({
					name: 'new',
					tree_count: '',
					price: '',
					price_promotion: '',
					price_std: '',
					price_std_promotion: ''	
				}),

			};
		},

		mounted() {
			if(this.selectedPackage)
			{
				this.setForm();
			}
		},

		methods: {
			setForm() {
				this.form.name = this.selectedPackage.name;
				this.form.tree_count = this.selectedPackage.tree_count;
				this.form.price = this.selectedPackage.price;
				this.form.price_promotion = this.selectedPackage.price_promotion;
				this.form.price_std = this.selectedPackage.price_std;
				this.form.price_std_promotion = this.selectedPackage.price_std_promotion;
			},

			back() {
				this.$emit('back');
			},

			submit() {
				let url = this.selectedPackage 
							? "/api/admin/packages/" + this.selectedPackage.id 
							: "/api/admin/packages";

				this.form.post(url)
						.then(response => this.onSuccess(response));
			},

			onSuccess(response) {
				flash(this.$options.filters.trans(response.message));
				this.back();
			},


		},

		computed: {
			title() {
				let title = this.selectedPackage ? 'package.package_details' : 'package.make_new_package';

				return title;
			},
		}	
	}
</script>
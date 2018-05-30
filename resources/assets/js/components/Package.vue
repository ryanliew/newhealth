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
							:hideLabel="false">
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
									:hideLabel="false">
								</text-input>
								<text-input v-model="form.price_promotion" 
									:defaultValue="form.price_promotion"
									:required="true"
									type="number"
									:label="$options.filters.trans('package.price_promotion')"
									name="price_promotion"
									:editable="true"
									:focus="false"
									:hideLabel="false">
								</text-input>
							</div>
							<div class="col-sm">
								<text-input v-model="form.price_rmb" 
									:defaultValue="form.price_rmb"
									:required="true"
									type="number"
									:label="$options.filters.trans('package.price_rmb')"
									name="price_rmb"
									:editable="true"
									:focus="false"
									:hideLabel="false">
								</text-input>
								<text-input v-model="form.price_rmb_promotion" 
									:defaultValue="form.price_rmb_promotion"
									:required="true"
									type="number"
									:label="$options.filters.trans('package.price_rmb_promotion')"
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
					name: '',
					tree_count: '',
					price: '',
					price_promotion: '',
					price_rmb: '',
					price_rmb_promotion: ''	
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
				this.form.price_rmb = this.selectedPackage.price_rmb;
				this.form.price_rmb_promotion = this.selectedPackage.price_rmb_promotion;
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
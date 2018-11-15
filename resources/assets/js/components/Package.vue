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
						<text-input v-model="form.package_name" 
							:defaultValue="form.package_name"
							:required="true"
							type="text"
							:label="$options.filters.trans('package.package_name')"
							name="package_name"
							:editable="true"
							:focus="true"
							:hideLabel="false"
							:error="form.errors.get('package_name')">
						</text-input>
						<text-input v-model="form.machine_count" 
							:defaultValue="form.machine_count"
							:required="true"
							type="number"
							:label="$options.filters.trans('package.machine_count')"
							name="machine_count"
							:editable="true"
							:focus="false"
							:hideLabel="false"
							:error="form.errors.get('machine_count')">
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
							</div>
							<div class="col-sm">
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
						</div>
						<textarea-input v-model="form.package_description" 
							:defaultValue="form.package_description"
							:required="true"
							type="text"
							:label="$options.filters.trans('package.package_description')"
							name="package_description"
							:editable="true"
							:focus="false"
							:hideLabel="false"
							rows="3"
							cols="5"
							:error="form.errors.get('package_description')">
						</textarea-input>
						<div class="row">
							<div class="col-sm">
								<checkbox-input v-model="form.can_upgrade"
									:defaultChecked="form.can_upgrade"
									:editable="true"
									:label="$options.filters.trans('package.can_upgrade')"
									name="can_upgrade">
								</checkbox-input>
							</div>
							<div class="col-sm">
								<checkbox-input v-model="form.can_redeem"
									:defaultChecked="form.can_redeem"
									:editable="true"
									:label="$options.filters.trans('package.can_redeem')"
									name="can_redeem">
								</checkbox-input>
							</div>
						</div>
						<image-input v-model="packagePicture" :defaultImage="packagePicture"
							@loaded="changePackageImage"
							label="package.package_picture"
							name="package_picture"
							:required="true"
							accept="image/*"
							:error="$options.filters.trans(form.errors.get('package_picture'))">
						</image-input>

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
					package_name: '',
					package_description: '',
					package_picture: '',
					can_upgrade: false,
					can_redeem: false,
					machine_count: '',
					price: '',
					price_promotion: '',
					// price_std: '',
					// price_std_promotion: ''	
				}),
				packagePicture: {name: 'No file selected'},
			};
		},

		mounted() {
			if(this.selectedPackage)
			{
				this.setForm();
			}
		},

		methods: {
			changePackageImage(e) {
				this.packagePicture = { src: e.src, file: e.file };
				this.form.package_picture = e.file;
				this.form.errors.clear('package_picture');
			},

			setForm() {
				console.log("hehe: " + this.selectedPackage.can_redeem);
				this.form.name = this.selectedPackage.name;
				this.form.package_name = this.selectedPackage.name;
				this.form.package_description = this.selectedPackage.description;
				this.form.machine_count = this.selectedPackage.machine_count;
				this.form.can_upgrade = this.selectedPackage.can_upgrade;
				this.form.can_redeem = this.selectedPackage.can_redeem;
				this.form.price = this.selectedPackage.price;
				this.form.price_promotion = this.selectedPackage.price_promotion;
				this.form.package_picture = this.selectedPackage.package_photo_path;

				this.packagePicture = { src: 'storage/' + this.selectedPackage.package_photo_path, file: '' };
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
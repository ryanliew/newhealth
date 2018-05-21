<template>
	<div class="row">
		<div class="col-sm">
			<div class="card">
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col">
							{{ title | trans }}
						</div>
						<div class="col-auto" v-if="cancelable">
							<button class="btn btn-primary" @click="back"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<h5 class="card-title mb-3">{{ instruction | trans }}</h5>
					<table class="table">
						<tbody v-if="!purchase">
							<tr v-for="(package, index) in packages">
								<td>{{ package.tree_count }} {{ 'auth.tree' | trans_choice({'value' : package.tree_count})  }}</td>
								<td>x</td>
								<td><text-input v-model="form.packages[index].amount" 
										:defaultValue="form.packages[index].amount"
										:required="false"
										type="number"
										label="label"
										name="amount"
										:editable="true"
										:focus="false"
										:hideLabel="true">
									</text-input>
								</td>
								<td>
									x
								</td>
								<td>
									{{ package.price }}	
								</td>
								<td>
									=
								</td>
								<td>
									{{ getPackagePrice(form.packages[index].amount, package.price) }}
								</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><b>{{ 'purchase.total' | trans }}</b></td>
								<td>=</td>
								<td>{{ totalPrice }}</td>
							</tr>
						</tbody>
						<tbody v-else>
							<tr v-for="(package, index) in purchase.packages">
								<td>{{ package.tree_count }} {{ 'auth.tree' | trans_choice({'value' : package.tree_count})  }}</td>
								<td>x</td>
								<td>{{ package.pivot.amount }}
								</td>
								<td>
									x
								</td>
								<td>
									{{ package.price }}	
								</td>
								<td>
									=
								</td>
								<td>
									{{ package.pivot.total_price }}
								</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><b>{{ 'purchase.total' | trans }}</b></td>
								<td>=</td>
								<td>{{ totalPrice }}</td>
							</tr>
						</tbody>
					</table>
					<div class="row mr-5" v-if="!purchase">
						<div class="col-sm"></div>
						<div class="col-sm-auto">
							<button class="btn btn-primary btn-lg" @click="submitForm" :disabled="form.submitting" v-html="submitButtonContent"></button>
						</div>
					</div>	
				</div>
			</div>
		</div>
		<div class="col-sm" v-if="purchase">
			<payment @back="back" :purchase="purchase" :cancelable="cancelable"></payment>
		</div>
	</div>
</template>

<script>
	import Payment from './Payment.vue';
	import formButtonMixin from '../mixins/form-button.js';
	export default {
		props: ['selectedPurchase', 'cancelable'],

		components: { Payment },

		mixins: [formButtonMixin],

		data() {
			return {
				purchase: '',
				packages: [],
				form: new Form({
					packages: [],
					user_id: window.user.id	
				}),
				submitText: 'purchase.checkout'
			};
		},

		mounted() {
			this.purchase = this.selectedPurchase;
			this.getPackages();
		},

		methods: {
			getPackages() {
				axios.get('/api/packages')
					.then(response => this.setPackages(response.data));
			},

			setPackages(data) {
				this.form.packages = data.map(pack => {
					let obj = {};
					obj['amount'] = 0;
					obj['id'] = pack.id;
					obj['price'] = pack.price;

					return obj;
				});

				this.packages = data;
			},

			submitForm() {
				this.form.post('/api/purchases')
					.then(response => this.onSuccess(response));
			},

			onSuccess(response) {
				this.purchase = response.purchase;
				flash(this.$options.filters.trans(response.message));
			},

			getPackagePrice(amount = 0, price = 0) {
				return amount * price;
			},

			back() {
				this.$emit('back');
			}
			
		},

		computed: {
			totalPrice() {
				if(this.purchase)
					return this.purchase.total_price;

				return _.sumBy(this.form.packages, function(pack){
					return this.getPackagePrice(pack.amount, pack.price);
				}.bind(this))
			},

			// submitButtonContent() {
			// 	return this.form.submitting ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : this.$options.filters.trans('purchase.checkout');
			// },

			title() {
				let title = this.purchase ? 'purchase.purchase_details' : 'purchase.make_new_purchase';

				return title;
			},

			instruction() {
				let instruction = this.purchase ? 'purchase.selected_packages' : 'purchase.select_package';

				return instruction;
			}
		}	
	}
</script>
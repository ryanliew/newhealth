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
					<h6>{{ 'purchase.base_price' | trans }}</h6>
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
								<td v-if="!user.is_std">
									{{ package.price_promotion ? package.price_promotion : package.price | currency }}	
								</td>
								<td v-else>
									{{ package.price_std_promotion ? package.price_std_promotion : package.price_std | currency_std }}	
								</td>
								<td>
									=
								</td>
								<td v-if="!user.is_std">
									{{ getPackagePrice(form.packages[index].amount, package) | currency }}
								</td>
								<td v-else>
									{{ getPackagePrice(form.packages[index].amount, package) | currency_std }}
								</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><b>{{ 'purchase.total' | trans }}</b></td>
								<td>=</td>
								<td v-if="!user.is_std">{{ totalPrice | currency}}</td>
								<td v-else>{{ totalPrice | currency_std}}</td>
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
								<td v-if="!purchase.is_std">
									{{ package.price | currency }}	
								</td>
								<td v-else>
									{{ package.price_std | currency_std }}	
								</td>
								<td>
									=
								</td>
								<td v-if="!purchase.is_std">
									{{ package.pivot.total_price | currency }}
								</td>
								<td v-else>
									{{ package.pivot.total_price_std  | currency_std }}
								</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><b>{{ 'purchase.total' | trans }}</b></td>
								<td>=</td>
								<td v-if="!purchase.is_std">{{ totalPrice | currency}}</td>
								<td v-else>{{ totalPrice | currency_std}}</td>
							</tr>
						</tbody>
					</table>
					<selector-input v-model="selectedUser" :defaultData="selectedUser" 
									:label="$options.filters.trans('purchase.user')" 
									:required="false"
									name="user_id"
									:potentialData="potentialUsers"
									@input="updateSelectedUser()"
									:editable="true"
									:placeholder="$options.filters.trans('purchase.default_user')"
									:error="form.errors.get('user_id')"
									v-if="user.is_admin && !purchase">
					</selector-input>

					<text-input :defaultValue="purchase.user_name"
							:required="false"
							type="text"
							:label="$options.filters.trans('purchase.user')"
							name="amount"
							:editable="false"
							:focus="false"
							:hideLabel="false"
							v-if="user.is_admin && purchase">
					</text-input>

					<div class="row mr-5 mt-3" v-if="!purchase">
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
				submitText: 'purchase.checkout',
				user: window.user,
				selectedUser: '',
				potentialUsers: ''
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
					obj['price'] = pack.price_promotion ? pack.price_promotion : pack.price;
					obj['price_std'] = pack.price_std_promotion ? pack.price_std_promotion : pack.price_std;

					return obj;
				});

				this.packages = data;

				this.getUsers();
			},

			getUsers() {
				axios.get('/api/admin/users')
					.then(response => this.setUsers(response.data.data));
			},

			setUsers(data) {
				this.potentialUsers = data.map(user => {
					let obj = {};
					obj['value'] = user.id;
					obj['label'] = user.name;

					return obj;
				});
			},

			updateSelectedUser() {
				this.form.user_id = this.selectedUser.value;
			},

			submitForm() {
				this.form.post('/api/purchases')
					.then(response => this.onSuccess(response));
			},

			onSuccess(response) {
				this.purchase = response.purchase;
				flash(this.$options.filters.trans(response.message));
			},

			getPackagePrice(amount = 0, pack) {

				let price = pack.price_promotion ? pack.price_promotion : pack.price;

				if(this.user.is_std)
				{
					price = pack.price_std_promotion ? pack.price_std_promotion : pack.price_std;
				}

				return amount * price;
			},

			back() {
				this.$emit('back');
			}
			
		},

		computed: {
			totalPrice() {
				if(this.purchase)
					return this.purchase.is_std ? this.purchase.total_price_std : this.purchase.total_price;

				return _.sumBy(this.form.packages, function(pack){
					let amount = pack.amount ? pack.amount : 0;
					return this.getPackagePrice(pack.amount, pack);
				}.bind(this))
			},

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
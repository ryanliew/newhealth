<template>
	<div class="row">
		<div class="col-sm">
			<div class="card">
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col">
							{{ title | trans }}
						</div>
						<div class="col-auto">
							<button class="btn btn-info" @click="update" v-if="!isEditing && canUpdatePurchase" v-html="updateButtonContent"></button>
							<template v-if="isEditing && purchase">
								<button class="btn btn-danger" @click="isEditing = false"><i class="fa fa-times"></i> {{ 'purchase.cancel' | trans }}</button>
								<button class="btn btn-success" @click="submitForm" :disabled="totalPrice == 0"><i class="fa fa-check"></i> {{ 'purchase.update' | trans }}</button>
							</template>
							<button v-if="cancelable" class="btn btn-primary" @click="back"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<h5 class="card-title mb-3">{{ instruction | trans }}</h5>
					<h6>{{ 'purchase.base_price' | trans }}</h6>
					<div class="table-responsive">
						<table class="table table-edit">
							<tbody v-if="isEditing">
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
									<td v-if="!is_std">
										<text-input v-model="form.packages[index].price" 
											:defaultValue="form.packages[index].price"
											:required="false"
											type="number"
											label="label"
											name="amount"
											:editable="user.is_admin"
											:focus="false"
											:hideLabel="true">
										</text-input>	
									</td>
									<td v-else>
										<text-input v-model="form.packages[index].price_std" 
											:defaultValue="form.packages[index].price_std"
											:required="false"
											type="number"
											label="label"
											name="amount"
											:editable="user.is_admin"
											:focus="false"
											:hideLabel="true">
										</text-input>
									</td>
									<td>
										=
									</td>
									<td v-if="!is_std">
										{{ getPackagePrice(form.packages[index].amount, form.packages[index]) | currency }}
									</td>
									<td v-else>
										{{ getPackagePrice(form.packages[index].amount, form.packages[index]) | currency_std }}
									</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td><b>{{ 'purchase.total' | trans }}</b></td>
									<td>=</td>
									<td v-if="!is_std">{{ totalPrice | currency}}</td>
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
										{{ package.pivot.total_price / package.pivot.amount | currency }}	
									</td>
									<td v-else>
										{{ package.pivot.total_price_std / package.pivot.amount | currency_std }}	
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
					</div>
					<selector-input v-model="selectedUser" :defaultData="selectedUser" 
									:label="$options.filters.trans('purchase.user')" 
									:required="false"
									name="user_id"
									:potentialData="potentialUsers"
									@input="updateSelectedUser()"
									:editable="true"
									:placeholder="$options.filters.trans('purchase.default_user')"
									:error="form.errors.get('user_id')"
									v-if="user.is_admin && !purchase && potentialUsers">
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

					<text-input v-model="form.purchase_date" :defaultValue="form.purchase_date"
							:label="$options.filters.trans('purchase.purchase_date')"
							:required="false"
							name="purchase_date"
							:editable="isEditing"
							type="date"
							v-if="user.is_admin"
							class="mt-3">
					</text-input>

					<div class="row mr-5 mt-3" v-if="!purchase">
						<div class="col-sm"></div>
						<div class="col-sm-auto">
							<button class="btn btn-primary btn-lg" @click="submitForm" :disabled="form.submitting || totalPrice == 0" v-html="submitButtonContent"></button>
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
	import moment from 'moment';
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
					user_id: '',
					purchase_date: ''
				}),
				submitText: 'purchase.checkout',
				user: window.user,
				selectedUser: '',
				potentialUsers: '',
				isEditing: this.selectedPurchase ? false : true,
				loading: false,
				updateText: 'purchase.update',
				is_std: false
			};
		},

		mounted() {
			this.purchase = this.selectedPurchase;
			this.form.purchase_date = this.purchase ? moment(this.purchase.created_at).format("YYYY-MM-DD") : moment().format("YYYY-MM-DD");
			this.getPackages();
		},

		methods: {
			getPackages() {
				this.loading = true;
				axios.get('/api/packages')
					.then(response => this.setPackages(response.data));
			},

			setPackages(data) {
				this.form.packages = data.map(pack => {
					let obj = {};

					let amount = 0;
					let price = pack.price_promotion ? pack.price_promotion : pack.price;
					let price_std = pack.price_std_promotion ? pack.price_std_promotion : pack.price_std;

					if(this.purchase) {
						let p = _.findIndex(this.purchase.packages, function(p){
							return p.id == pack.id;
						});

						if(this.purchase.packages[p]) {
							amount = this.purchase.packages[p].pivot.amount;
							price = this.purchase.packages[p].pivot.total_price / amount;
							price_std = this.purchase.packages[p].pivot.total_price_std / amount;
						}
					}
					obj['amount'] = amount;
					obj['id'] = pack.id;
					obj['price'] = price;
					obj['price_std'] = price_std;

					return obj;
				});

				this.packages = data;
				this.loading = false;
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
					obj['is_std'] = !(user.country_id == 162 || user.country_id == 211);

					return obj;
				});
			},

			updateSelectedUser() {
				this.form.user_id = this.selectedUser ? this.selectedUser.value : this.user.id;
				this.is_std = this.selectedUser ? this.selectedUser.is_std : this.user.is_std;
			},

			submitForm() {
				let url = this.purchase ? '/api/purchase/' + this.purchase.id + '/update' : '/api/purchases';
				this.form.post(url)
					.then(response => this.onSuccess(response));
			},

			onSuccess(response) {
				this.purchase = response.purchase;
				this.isEditing = false;
				this.form.purchase_date = moment(response.purchase.created_at).format("YYYY-MM-DD");
				flash(this.$options.filters.trans(response.message));
			},

			getPackagePrice(amount = 0, pack) {

				let price = pack.price;

				if(this.is_std)
				{
					price = pack.price_std;
				}

				return amount * price;
			},

			back() {
				this.$emit('back');
			},

			update() {
				this.getPackages();

				this.isEditing = true;
			},
			
		},

		computed: {
			totalPrice() {
				if(this.purchase && !this.isEditing)
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
				let instruction = this.purchase && !this.isEditing ? 'purchase.selected_packages' : 'purchase.select_package';

				return instruction;
			},

			updateButtonContent() {
				return this.loading ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : this.$options.filters.trans(this.updateText);
			},

			canUpdatePurchase() {
				if( this.user.is_admin )
				{
					return this.purchase.status !== "complete";
				}
				return this.purchase.payment ? false : true;
			}
		}	
	}
</script>
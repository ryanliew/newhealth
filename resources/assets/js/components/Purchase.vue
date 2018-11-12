<template>
	<div class="row">
		<div :class="mainContainerClass">
			<div class="card">
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col">
							{{ title | trans }}
						</div>
						<div class="col-auto">
							<button class="btn btn-info" @click="update" v-if="!isEditing && canUpdatePurchase" v-html="updateButtonContent"></button>
							<template v-if="isEditing && purchase">
								<button class="btn btn-danger" @click="cancelUpdate"><i class="fa fa-times"></i> {{ 'purchase.cancel' | trans }}</button>
							</template>
							<button v-if="cancelable" class="btn btn-primary" @click="back"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<loader v-if="loading"></loader>

				    <div v-if="(purchase && isEditing) || !purchase">
				    	<md-tabs @md-changed="tabChange" :md-active-tab="defaultTab">
							<div class="col-sm-12">
								<md-tab :md-label="$options.filters.trans('auth.account_type')" id="account" >
									<md-field md-inline>
								      	<label>{{ 'auth.referrer' | trans}}</label>
							     	 	<md-input @keyup="checkReferrer" v-model="referrerInput" :disabled="!isEditing && canUpdatePurchase"></md-input>
								      	<span v-if="onHelperText" style="color: red;" class="md-helper-text">Invalid referral code</span>
							    	</md-field>
							        <md-radio @change="accountChange" class="md-primary" v-model="radio" value="3">{{ 'purchase.diamond' | trans }}</md-radio>
							        <md-radio @change="accountChange" class="md-primary" v-model="radio" value="2">{{ 'purchase.platinum' | trans }}</md-radio>
							        <md-radio @change="accountChange" class="md-primary" v-model="radio" value="1">{{ 'purchase.silver' | trans }}</md-radio>
									<geno-page :tree="tree" v-if="showTree"></geno-page>
								</md-tab>
							</div>
						
							<md-tab id="packages" :md-label="$options.filters.trans('purchase.packages')" class="table-responsive">
							      	<h5 class="card-title mb-3">{{ instruction | trans }}</h5>
									<h6>{{ 'purchase.base_price' | trans }}</h6>
										<table class="table table-edit">
											<tbody v-if="isEditing && form.packages.length > 0">
												<tr v-for="(package, index) in packages">
													<td>{{ package.name }}</td>
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
															:hideLabel="true"
															v-if="user.is_admin">
														</text-input>	
														<template v-else>
															{{ form.packages[index].price | currency }}
														</template>
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
															:hideLabel="true"
															v-if="user.is_admin">
														</text-input>
														<template v-else>
															{{ form.packages[index].price_std | currency_std }}
														</template>
													</td>
													<td>
														=
													</td>
													<td v-if="!is_std">
														{{ getPackagePrice(form.packages[index].amount, form.packages[index], index) | currency }}
													</td>
													<td v-else>
														{{ getPackagePrice(form.packages[index].amount, form.packages[index], index) | currency_std }}
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
									<selector-input v-model="selectedUser" :defaultData="selectedUser" 
													:label="$options.filters.trans('purchase.buy_with_account')" 
													:required="true"
													name="account_id"
													:potentialData="potentialUsers"
													@input="updateSelectedUser()"
													:editable="true"
													:placeholder="$options.filters.trans('purchase.default_user')"
													:error="form.errors.get('account_id')"
													v-if="potentialUsers">
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
											:editable="false"
											type="date"
											v-if="user.is_admin"
											class="mt-3">
									</text-input>

									<div class="row mr-5 mt-3" v-if="!purchase">
										<div class="col-sm"></div>
										<div class="col-sm-auto">
											<button class="btn btn-primary btn-lg" @click="submitForm" :disabled="form.submitting || totalPrice == 0 || !selectedUser" v-html="submitButtonContent"></button>
										</div>
									</div>
									<div class="row mr-5 mt-3" v-if="purchase && isEditing">
										<div class="col-sm"></div>
										<div class="col-sm-auto">
											<button class="btn btn-success" @click="submitForm" :disabled="totalPrice == 0 || !selectedUser"><i class="fa fa-check"></i> {{ 'purchase.update' | trans }}</button>
										</div>
									</div>
					        </md-tab>
						</md-tabs>	
				    </div>
					<div v-if="(purchase && purchase.is_account && !isEditing)">
						<div class="col-sm-12">
						        <md-radio @change="accountChange" class="md-primary" v-model="radio" value="3">{{ 'purchase.diamond' | trans }}</md-radio>
						        <md-radio @change="accountChange" class="md-primary" v-model="radio" value="2">{{ 'purchase.platinum' | trans }}</md-radio>
						        <md-radio @change="accountChange" class="md-primary" v-model="radio" value="1">{{ 'purchase.silver' | trans }}</md-radio>
								<geno-page :tree="tree" v-if="showTree"></geno-page>
						</div>
				    </div>    
				    <div v-if="(purchase && !purchase.is_account && !isEditing)">
				      	<h5 class="card-title mb-3">{{ instruction | trans }}</h5>
						<h6>{{ 'purchase.base_price' | trans }}</h6>
							<table class="table table-edit">
								<tbody v-if="isEditing && form.packages.length > 0">
									<tr v-for="(package, index) in packages">
										<td>{{ package.name }}</td>
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
												:hideLabel="true"
												v-if="user.is_admin">
											</text-input>	
											<template v-else>
												{{ form.packages[index].price | currency }}
											</template>
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
												:hideLabel="true"
												v-if="user.is_admin">
											</text-input>
											<template v-else>
												{{ form.packages[index].price_std | currency_std }}
											</template>
										</td>
										<td>
											=
										</td>
										<td v-if="!is_std">
											{{ getPackagePrice(form.packages[index].amount, form.packages[index], index) | currency }}
										</td>
										<td v-else>
											{{ getPackagePrice(form.packages[index].amount, form.packages[index], index) | currency_std }}
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
				    </div>  
				</div>
			</div>
		</div>
		<div class="col-sm" v-if="(!purchase && showTree) || (purchase && isEditing && showTree)">
			<div class="card">
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col">
							{{ 'purchase.purchase_details' | trans }}
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col">
							<div class="payment-details-grid">
								<div>{{ "auth.referrer" | trans }}</div>
								<div class="text-center">{{ referrerInput }}</div>
								<div>{{ "auth.account_type" | trans }}</div>
								<div class="text-center">{{ accountSelected }}</div>

								<div>{{ "purchase.original_price" | trans }}</div>
								<div class="text-center">{{ machineQuantity }} machines X RM{{ unitPrice }} = <b>RM{{ totalAccountPrice }}</b></div>
								<div>{{ "purchase.cash_back" | trans }}</div>
								<div class="text-center"><b>RM{{ discount }}</b> <span style="color:red;">{{ 'purchase.saving' | trans }} {{ accountCommission }}%</span></div>
								<div>{{ "purchase.total" | trans }}</div>
								<div class="text-center"><b>RM{{ originalPrice }}</b></div>
							</div>
							<div v-for="accountList in accountLists">
								<selector-input v-model="accountList.selectedPackage" :defaultData="accountList.selectedPackage" 
											:label="accountList.label" 
											:required="true"
											:name="accountList.id"
											:potentialData="potentialPackages"
											:editable="true"
											:placeholder="$options.filters.trans('purchase.select_package')"
											:error="form.errors.get('account_id')"
											v-if="potentialPackages">
								</selector-input>
							</div>
							<button v-if="!purchase" class="btn btn-primary btn-lg" @click="submitAccountForm" :disabled="accountForm.submitting || onHelperText || isAccountFormButtonDisabled" v-html="submitButtonContent"></button>
							<button v-if="purchase && isEditing" class="btn btn-success" @click="submitAccountForm" :disabled="accountForm.submitting || onHelperText || isAccountFormButtonDisabled"><i class="fa fa-check"></i> {{ 'purchase.update' | trans }}</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm" v-if="purchase && !isEditing">
			<payment @back="back" :purchase="purchase" :cancelable="cancelable"></payment>
		</div>
	</div>
</template>

<script>
	import Payment from './Payment.vue';
	import GenoPage from '../pages/GenoPage.vue';
	import formButtonMixin from '../mixins/form-button.js';
	import moment from 'moment';
	
	export default {
		props: ['selectedPurchase', 'cancelable'],

		components: { Payment, GenoPage },

		mixins: [formButtonMixin],

		data() {
			return {
				purchase: '',
				packages: [],

				form: new Form({
					packages: [],
					user_id: '',
					account_id: '',
					purchase_date: '',
					referral_code: '',
				}),
				accountForm: new Form({
					user_id: '',
					purchase_date: '',
					referral_code: '',
					account_level: 3,
					total_price: 0,
					packages: [],
				}),
				submitText: 'purchase.checkout',
				user: window.user,
				selectedPackages: '',
				potentialPackages: '',
				selectedUser: '',
				potentialUsers: '',
				isEditing: this.selectedPurchase ? false : true,
				updateText: 'purchase.update',
				is_std: false,
				radio: "3",
				loading: true,
				showTree: true,
				accountLists : [ {label: 'Diamond', id:'diamond', selectedPackage: null, level: 3}, {label: 'Platinum 1', id:'platinum-1', selectedPackage: null, level: 2}, {label: 'Platinum 2', id:'platinum-2', selectedPackage: null, level: 2}, {label: 'Platinum 3', id:'platinum-3', selectedPackage: null, level: 2}, {label: 'Silver 1', id:'silver-1', selectedPackage: null, level: 1}, {label: 'Silver 2', id:'silver-2', selectedPackage: null, level: 1}, {label: 'Silver 3', id:'silver-3', selectedPackage: null, level: 1}, {label: 'Silver 4', id:'silver-4', selectedPackage: null, level: 1}, {label: 'Silver 5', id:'silver-5', selectedPackage: null, level: 1}, {label: 'Silver 6', id:'silver-6', selectedPackage: null, level: 1}, {label: 'Silver 7', id:'silver-7', selectedPackage: null, level: 1}, {label: 'Silver 8', id:'silver-8', selectedPackage: null, level: 1}, {label: 'Silver 9', id:'silver-9', selectedPackage: null, level: 1} ],
				tree: [],
				referrerInput: '',
				onHelperText: false,
				helperText: '',
				machineQuantity: '',
				accountCommission: '',
				accountSelected: '',
				unitPrice: 8000,
				defaultTab: 'account',
			};
		},

		mounted() {
			this.form.user_id = this.user.id;
			this.accountForm.user_id = this.user.id;
			this.is_std = this.user.country_id == 48;
			this.purchase = this.selectedPurchase;
			this.form.purchase_date = this.purchase ? moment(this.purchase.created_at).format("YYYY-MM-DD") : moment().format("YYYY-MM-DD");
			this.accountForm.purchase_date = this.purchase ? moment(this.purchase.created_at).format("YYYY-MM-DD") : moment().format("YYYY-MM-DD");
			this.getPackages();
			this.getAccountPackages();
			this.setReferrerInput();
			this.accountChange();
			setTimeout(() => {
			    this.loading = false;
			  }, 100);
		},

		methods: {
			cancelUpdate() {
				this.isEditing = false;
				if(this.purchase.is_account)
					this.showTree = true;
			},

			setReferrerInput() {
				if(this.purchase.referral_code){
					this.referrerInput = this.purchase.referral_code;
					this.accountForm.referral_code = this.purchase.referral_code;
				}else{
					this.referrerInput = null;
					this.accountForm.referral_code = null;
				}
				if(!this.purchase){
					axios.get('api/internal/ancestor')
					.then(response => this.setDefaultReferralCode(response));
				}
			},

			setDefaultReferralCode(response) {
				response.data != 'auth.ancestor_not_found' ? this.referrerInput = response.data : null;
				this.accountForm.referral_code = response.data;
			},

			tabChange(id) {
				id == 'account' ? this.showTree = true : this.showTree = false;
			},

			checkReferrer() {
				if(this.referrerInput){
					axios.get('api/internal/referrer?code=' + this.referrerInput)
					.then(response => this.setReferrer(response));
				} else {
					this.resetHelperTextAndTree();
				}
			},

			setReferrer(response) {
				if(response.data == 'auth.referrer_not_found'){
					this.onHelperText = true;
					this.accountChange();
				} else {
					this.accountForm.referral_code = this.referrerInput;
					this.resetHelperTextAndTree();
				}
			},

			resetHelperTextAndTree(){
				this.onHelperText = false;
				this.form.referral_code = this.referrerInput;
				this.accountChange();
			},

			accountChange(){
				if (this.purchase && !this.isEditing && this.purchase.is_account)
					this.radio = this.purchase.account_level.toString();

				var treeArray;
				switch(this.radio){
					case "1":
						this.machineQuantity = 3;
						this.accountCommission = 20;
						this.accountForm.account_level = 1;
						this.accountSelected = this.$options.filters.trans('purchase.silver');
						this.accountLists= [ {label: 'Silver', id:'silver', selectedPackage: null, level: 1} ];
						treeArray = [{name: 'Silver', children: []}];
						break;
					case "2":
						this.machineQuantity = 9;
						this.accountCommission = 35;
						this.accountForm.account_level = 2;
						this.accountSelected = this.$options.filters.trans('purchase.platinum');
						this.accountLists= [ {label: 'Platinum', id:'platinum', selectedPackage: null, level: 2}, {label: 'Silver 1', id:'silver-1', selectedPackage: null, level: 1}, {label: 'Silver 2', id:'silver-2', selectedPackage: null, level: 1}, {label: 'Silver 3', id:'silver-3', selectedPackage: null, level: 1} ];
						treeArray = [
										{name: 'Platinum', children: [
											{name: 'Silver 1', children: []},
											{name: 'Silver 2', children: []},
											{name: 'Silver 3', children: []},
										]}
									];
				 		break;
				 	case "3":
				 		this.machineQuantity = 27;
						this.accountCommission = 45;
						this.accountForm.account_level = 3;
						this.accountSelected = this.$options.filters.trans('purchase.diamond');
						this.accountLists = [ {label: 'Diamond', id:'diamond', selectedPackage: null, level: 3}, {label: 'Platinum 1', id:'platinum-1', selectedPackage: null, level: 2}, {label: 'Platinum 2', id:'platinum-2', selectedPackage: null, level: 2}, {label: 'Platinum 3', id:'platinum-3', selectedPackage: null, level: 2}, {label: 'Silver 1', id:'silver-1', selectedPackage: null, level: 1}, {label: 'Silver 2', id:'silver-2', selectedPackage: null, level: 1}, {label: 'Silver 3', id:'silver-3', selectedPackage: null, level: 1}, {label: 'Silver 4', id:'silver-4', selectedPackage: null, level: 1}, {label: 'Silver 5', id:'silver-5', selectedPackage: null, level: 1}, {label: 'Silver 6', id:'silver-6', selectedPackage: null, level: 1}, {label: 'Silver 7', id:'silver-7', selectedPackage: null, level: 1}, {label: 'Silver 8', id:'silver-8', selectedPackage: null, level: 1}, {label: 'Silver 9', id:'silver-9', selectedPackage: null, level: 1} ];
				 		treeArray = [{name: 'Diamond', 
									children: [
											{name: 'Platinum 1', children: [
												{name: 'Silver 1', children: []},
												{name: 'Silver 2', children: []},
												{name: 'Silver 3', children: []},
											]}, 
											{name: 'Platinum 2', children: [
												{name: 'Silver 4', children: []},
												{name: 'Silver 5', children: []},
												{name: 'Silver 6', children: []},
											]},
											{name: 'Platinum 3', children: [
												{name: 'Silver 7', children: []},
												{name: 'Silver 8', children: []},
												{name: 'Silver 9', children: []},
											]},
										]
									}];
						break;
					default:
						treeArray;
				}

				// if((this.onHelperText == false && this.referrerInput != null) || (this.onHelperText == false && this.referrerInput != "")){
				// 	console.log("here");
				// 	var parentTree = [{name: 'Silver'}];
				// 	parentTree[0]['children'] = treeArray;
				// 	this.tree = parentTree;
				// } else if((this.onHelperText == true && this.referrerInput != null) || (this.onHelperText == true && this.referrerInput != "") || (this.onHelperText == false && this.referrerInput == null) || (this.onHelperText == false && this.referrerInput == "")) {
				// 	console.log("there");
				// 	this.tree = treeArray;
				// } else {
				// 	console.log("else");
				// 	this.tree = treeArray;
				// }
				if(this.referrerInput == null || this.referrerInput == ""){
					this.tree = treeArray;
				} else {
					var parentTree = [{name: this.referrerInput}];
					parentTree[0]['children'] = treeArray;
					this.tree = parentTree;
				}
			},

			setLoadingTimer(){
				this.loading = false;
			},

			getPackages() {
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
				this.getUsers();
			},

			getAccountPackages() {
				axios.get('/api/packages/account')
					.then(response => this.setAccountPackages(response.data));
			},

			setAccountPackages(data) {
				this.setSelectedAccountPackage();
				this.potentialPackages = data.map(pack => {
					let price = pack.price_promotion ? pack.price_promotion : pack.price;
					let obj = {};
					obj['value'] = pack.id;
					obj['label'] = pack.name;
					obj['price'] = price;

					return obj;
				});
			},

			setSelectedAccountPackage() {
				console.log("setSelectedAccountPackage");
				if(this.purchase) {
					for( var i = 0; i < this.purchase.packages.length; i ++){
						let obj = {};
						obj['value'] = this.purchase.packages[i].id;
						obj['label'] = this.purchase.packages[i].name;
						obj['price'] = this.purchase.packages[i].price;

						this.accountLists[i].selectedPackage = obj;
					}
					// this.purchase.packages.forEach(function(value, i){
					// 	console.log("looping: " + i);
						
					// 	console.log(this.accountLists[i]);
					// });
				}
				
			},

			getUsers() {
				let url = this.purchase.user_id ? '/api/internal/auth/accounts/' + this.purchase.user_id : '/api/internal/auth/accounts/' + this.user.id;
				axios.get(url)
					.then(response => this.setUsers(response.data));
			},

			setUsers(data) {
				this.setSelectedUser(data);
				this.potentialUsers = data.map(user => {
					let obj = {};
					obj['value'] = user.id;
					obj['label'] = user.referral_code;

					return obj;
				});
			},

			setSelectedUser(data) {
				let filteredUser = data.filter(user => { 
					return user.id == this.purchase.account_id
				});

				filteredUser.forEach(user => {
					let obj = {};
					obj['value'] = user.id;
					obj['label'] = user.referral_code;
					this.selectedUser = obj;
				});
			},

			updateSelectedUser() {
				this.form.account_id = this.selectedUser ? this.selectedUser.value : '';
				// this.is_std = this.selectedUser ? this.selectedUser.is_std : this.user.country_id == 48;
			},

			submitAccountForm() {
				console.log("submitAccountForm");

				this.accountForm.total_price = this.totalAccountPrice;
				this.accountForm.packages = this.accountLists.map(account => {
					let obj = {};

					obj['id'] = account.selectedPackage.value;
					obj['price'] = account.selectedPackage.price;
					obj['level'] = account.level;

					return obj;
				})

				let url = this.purchase ? '/api/purchase/account/' + this.purchase.id + '/update' : '/api/purchases/account';
				this.accountForm.post(url)
					.then(response => this.onSuccess(response));
			},

			submitForm() {
				console.log("submitForm");

				let url = this.purchase ? '/api/purchase/' + this.purchase.id + '/update' : '/api/purchases';
				this.form.post(url)
					.then(response => this.onSuccess(response));
			},

			onSuccess(response) {
				console.log("onSuccess");
				this.purchase = response.purchase;
				this.isEditing = false;
				this.form.purchase_date = moment(response.purchase.created_at).format("YYYY-MM-DD");
				this.accountForm.purchase_date = moment(response.purchase.created_at).format("YYYY-MM-DD");
				this.accountForm.referral_code = this.referrerInput;
				flash(this.$options.filters.trans(response.message));
			},

			getPackagePrice(amount = 0, pack, index) {
				let price = pack.price;

				if(amount < 0) {
					amount = 0;
					this.form.packages[index].amount = 0;
				}

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
				// this.form.account_id = this.selectedPurchase.account.referral_code;
				if(this.purchase.is_account){
					this.showTree = true;
					return this.defaultTab = "account";
				} else {
					this.showTree = false;
					return this.defaultTab = "packages";
				}
				// this.showTree = true;
			},
			
		},

		computed: {
			allAccountsHasPackage() {
				let allAccountsHasPackage = this.accountLists.filter(account => {
					return account.selectedPackage == null;
				});

				return allAccountsHasPackage.length == 0;
			},

			isAccountFormButtonDisabled() {
				return !this.allAccountsHasPackage;
			},

			discountedPrice() {
				return this.originalPrice - this.discount;
			},

			discount(){
				return this.totalAccountPrice * this.accountCommission / 100;
			},

			originalPrice() {
				return this.machineQuantity * this.unitPrice;
			},

			mainContainerClass() {
				if((!this.purchase && this.showTree) || (this.purchase && this.showTree)){
					return "col-sm-6";
				} else {
					return "col-sm";
				}
			},

			totalAccountPrice() {
				return _.sumBy(this.accountLists, function(account){
					return account.selectedPackage ? parseFloat(account.selectedPackage.price) : 0;
				})
			},

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
			},
		}	
	}
</script>
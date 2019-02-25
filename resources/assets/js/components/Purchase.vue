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
							    	<span style="font-size: 12px;"><b>Instruction: </b>Click on the account level to select package to purchase for each account.</span>
							    	<br>
							        <md-radio @change="accountChange" class="md-primary" v-model="radio" value="3">{{ 'purchase.diamond' | trans }}</md-radio>
							        <md-radio @change="accountChange" class="md-primary" v-model="radio" value="2">{{ 'purchase.platinum' | trans }}</md-radio>
							        <md-radio @change="accountChange" class="md-primary" v-model="radio" value="1">{{ 'purchase.silver' | trans }}</md-radio>
									<geno-page :tree="tree" :isPurchase="true" v-if="showTree" @clicked="changePackage($event.data)"></geno-page>
									<div class="row" style="justify-content: center; margin-top: 25px;">
										<table class="table table-edit" style="border: 1px solid #dee2e6;">
											<tr>
												<th style="border-right: 1px solid #dee2e6;">
													{{ 'package.packages' | trans }}
												</th>
												<th>
													{{ 'package.package_description' | trans }}
												</th>
											</tr>
											<tr v-for="package in potentialPackages">
												<td style="border-right: 1px solid #dee2e6;">
													{{package.label}}
												</td>
												<td>
													{{package.description}}
												</td>
											</tr>
										</table>
									</div>
								</md-tab>
							</div>
						
							<md-tab id="packages" :md-label="$options.filters.trans('purchase.packages')" class="table-responsive">
							    <div v-if="packages.length > 0">  	
							      	<h5 class="card-title mb-3">{{ instruction | trans }}</h5>
										<table class="table table-edit">
											<tbody v-if="isEditing && form.packages.length > 0">
												<tr v-for="(package, index) in packages">
													<td>{{ package.name }} (PV:{{ package.price }})</td>
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
														<text-input v-model="form.packages[index].selling_price" 
															:defaultValue="form.packages[index].selling_price"
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
															{{ form.packages[index].selling_price }}
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
													<td><b>{{ 'purchase.total' | trans }} (PV:{{totalPackagePointValue}})</b></td>
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
														{{ package.pivot.total_selling_price / package.pivot.amount | currency }}	
													</td>
													<td v-else>
														{{ package.pivot.total_price_std / package.pivot.amount | currency_std }}	
													</td>
													<td>
														=
													</td>
													<td v-if="!purchase.is_std">
														{{ package.pivot.total_selling_price | currency }}
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
													<td><b>{{ 'purchase.total' | trans }} (PV: {{package.pivot.total_price}})</b></td>
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
								</div>
								<div v-else>
									<h4>{{ 'package.packageEmpty' | trans }}</h4>
								</div>
					        </md-tab>
						</md-tabs>	
				    </div>
					<div v-if="(purchase && purchase.is_account && !isEditing)">
						<div class="col-sm-12">
						        <md-radio @change="accountChange" class="md-primary" v-model="radio" value="3">{{ 'purchase.diamond' | trans }}</md-radio>
						        <md-radio @change="accountChange" class="md-primary" v-model="radio" value="2">{{ 'purchase.platinum' | trans }}</md-radio>
						        <md-radio @change="accountChange" class="md-primary" v-model="radio" value="1">{{ 'purchase.silver' | trans }}</md-radio>
								<geno-page :tree="tree" :isPurchase="true" v-if="showTree"></geno-page>
						</div>
				    </div>    
				    <div v-if="(purchase && !purchase.is_account && !isEditing)">
				      	<h5 class="card-title mb-3">{{ instruction | trans }}</h5>
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
											<text-input v-model="form.packages[index].selling_price" 
												:defaultValue="form.packages[index].selling_price"
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
												{{ form.packages[index].selling_price | currency }}
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
										<td>{{ package.name }}</td>
										<td>x</td>
										<td>{{ package.pivot.amount }}
										</td>
										<td>
											x
										</td>
										<td v-if="!purchase.is_std">
											{{ package.pivot.total_selling_price / package.pivot.amount | currency }}	
										</td>
										<td v-else>
											{{ package.pivot.total_price_std / package.pivot.amount | currency_std }}	
										</td>
										<td>
											=
										</td>
										<td v-if="!purchase.is_std">
											{{ package.pivot.total_selling_price | currency }}
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
										<td><b>{{ 'purchase.total' | trans }} (PV: {{totalPackagePointValue}})</b></td>
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
								<div class="text-center">{{ 'purchase.' + accountSelected | trans }}</div>

								<div>{{ "purchase.cash_back" | trans }}</div>
								<div class="text-center"><b>${{ discount }}</b> <span style="color:red;">{{ 'purchase.saving' | trans }} {{ accountCommission }}%</span></div>
								<div>{{ "purchase.point_value" | trans }}</div>
								<div class="text-center"><b>${{ totalPointValue }}</b></div>
								<div>{{ "purchase.total" | trans }}</div>
								<div class="text-center"><b>${{ totalAccountSellingPrice }}</b></div>
							</div>
							<!-- <div v-for="accountList in accountLists">
								<selector-input v-model="accountList.selectedPackage" :defaultData="accountList.selectedPackage" 
											:label="$options.filters.trans('purchase.'+accountList.label)" 
											:required="true"
											:name="accountList.id"
											:potentialData="potentialPackages"
											:editable="true"
											:placeholder="$options.filters.trans('purchase.select_package')"
											:error="form.errors.get('account_id')"
											v-if="potentialPackages">
								</selector-input>
							</div> -->
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
					total_selling_price: 0,
					packages: [],
				}),
				treeArray: [],
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
				accountLists : [ {label: 'Diamond', id:'diamond', selectedPackage: null, level: 3}, {label: 'Platinum_1', id:'platinum-1', selectedPackage: null, level: 2}, {label: 'Platinum_2', id:'platinum-2', selectedPackage: null, level: 2}, {label: 'Platinum_3', id:'platinum-3', selectedPackage: null, level: 2}, {label: 'Silver_1', id:'silver-1', selectedPackage: null, level: 1}, {label: 'Silver_2', id:'silver-2', selectedPackage: null, level: 1}, {label: 'Silver_3', id:'silver-3', selectedPackage: null, level: 1}, {label: 'Silver 4', id:'silver-4', selectedPackage: null, level: 1}, {label: 'Silver_5', id:'silver-5', selectedPackage: null, level: 1}, {label: 'Silver_6', id:'silver-6', selectedPackage: null, level: 1}, {label: 'Silver_7', id:'silver-7', selectedPackage: null, level: 1}, {label: 'Silver_8', id:'silver-8', selectedPackage: null, level: 1}, {label: 'Silver_9', id:'silver-9', selectedPackage: null, level: 1} ],
				tree: [],
				referrerInput: '',
				onHelperText: false,
				helperText: '',
				machineQuantity: '',
				accountCommission: '',
				accountSelected: '',
				unitPrice: 8000,
				defaultTab: 'account',
				index: 0,
				triggeredAccount: '',
				triggerAccountList: [],
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
				if(this.purchase.is_account){
					this.accountChange();
					this.showTree = true;
				}
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
				response.data != 'auth.ancestor_not_found' ? this.accountForm.referral_code = response.data : null;
			},

			tabChange(id) {
				if(id == 'account'){
					this.showTree = true;
					this.triggeredAccount = '';
					this.accountChange();
				} else {
					this.showTree = false;
				}
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
				this.accountForm.referral_code = this.referrerInput;
				this.accountChange();
			},

			accountChange(){
				if (this.purchase && !this.isEditing && this.purchase.is_account)
					this.radio = this.purchase.account_level.toString();

				switch(this.radio){
					case "1":
						this.machineQuantity = 3;
						this.accountCommission = 20;
						this.accountForm.account_level = 1;
						this.accountSelected = 'silver';
						this.accountLists= [ {name: 'Silver', id:'silver', selectedPackage: null, package_index: -1, level: 1} ];
						this.treeArray = [{name: 'Silver', selectedPackage: null, package_index: -1, children: []}];
						break;
					case "2":
						this.machineQuantity = 9;
						this.accountCommission = 35;
						this.accountForm.account_level = 2;
						this.accountSelected = 'platinum';
						this.accountLists= [ {name: 'Platinum', id:'platinum', selectedPackage: null, level: 2}, {name: 'Silver_1', id:'silver-1', selectedPackage: null, level: 1}, {name: 'Silver_2', id:'silver-2', selectedPackage: null, level: 1}, {name: 'Silver_3', id:'silver-3', selectedPackage: null, level: 1} ];
						this.treeArray = [
										{name: 'Platinum', selectedPackage: null, package_index: -1, children: [
											{name: 'Silver_1', selectedPackage: null, package_index: -1, children: []},
											{name: 'Silver_2', selectedPackage: null, package_index: -1, children: []},
											{name: 'Silver_3', selectedPackage: null, package_index: -1, children: []},
										]}
									];
				 		break;
				 	case "3":
				 		this.machineQuantity = 27;
						this.accountCommission = 45;
						this.accountForm.account_level = 3;
						this.accountSelected = 'diamond';
						this.accountLists = [ {name: 'Diamond', id:'diamond', selectedPackage: null, level: 3}, {name: 'Platinum_1', id:'platinum-1', selectedPackage: null, level: 2}, {name: 'Platinum_2', id:'platinum-2', selectedPackage: null, level: 2}, {name: 'Platinum_3', id:'platinum-3', selectedPackage: null, level: 2}, {name: 'Silver_1', id:'silver-1', selectedPackage: null, level: 1}, {name: 'Silver_2', id:'silver-2', selectedPackage: null, level: 1}, {name: 'Silver_3', id:'silver-3', selectedPackage: null, level: 1}, {name: 'Silver_4', id:'silver-4', selectedPackage: null, level: 1}, {name: 'Silver_5', id:'silver-5', selectedPackage: null, level: 1}, {name: 'Silver_6', id:'silver-6', selectedPackage: null, level: 1}, {name: 'Silver_7', id:'silver-7', selectedPackage: null, level: 1}, {name: 'Silver_8', id:'silver-8', selectedPackage: null, level: 1}, {name: 'Silver_9', id:'silver-9', selectedPackage: null, level: 1} ];
				 		this.treeArray = [{name: 'Diamond', selectedPackage: null, package_index: -1, 
									children: [
											{name: 'Platinum_1', selectedPackage: null, package_index: -1, children: [
												{name: 'Silver_1', selectedPackage: null, package_index: -1, children: []},
												{name: 'Silver_2', selectedPackage: null, package_index: -1, children: []},
												{name: 'Silver_3', selectedPackage: null, package_index: -1, children: []},
											]}, 
											{name: 'Platinum_2', selectedPackage: null, package_index: -1, children: [
												{name: 'Silver_4', selectedPackage: null, package_index: -1, children: []},
												{name: 'Silver_5', selectedPackage: null, package_index: -1, children: []},
												{name: 'Silver_6', selectedPackage: null, package_index: -1, children: []},
											]},
											{name: 'Platinum_3', selectedPackage: null, package_index: -1, children: [
												{name: 'Silver_7', selectedPackage: null, package_index: -1, children: []},
												{name: 'Silver_8', selectedPackage: null, package_index: -1, children: []},
												{name: 'Silver_9', selectedPackage: null, package_index: -1, children: []},
											]},
										]
									}];
						

						break;
					default:
						this.treeArray;
				}
					this.potentialPackages ? this.setSelectedAccountPackage() : null;
					this.tree = this.treeArray;
						
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
					let selling_price = 0;

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
					obj['selling_price'] = pack.selling_price;
					obj['total_selling_price'] = pack.total_selling_price;

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
				this.potentialPackages = data.map(pack => {
					let price = pack.price_promotion ? pack.price_promotion : pack.price;
					let obj = {};
					obj['value'] = pack.id;
					obj['label'] = pack.name;
					obj['price'] = price;
					obj['description'] = pack.description;
					obj['selling_price'] = pack.selling_price;

					return obj;
				});
				this.setSelectedAccountPackage();

			},

			setSelectedAccountPackage() {
				if(this.purchase && !this.isEditing) {
					for( var i = 0; i < this.purchase.packages.length; i ++){
						this.changePackage(this.accountLists[i], this.treeArray, i);
					}
				} else {
					for( var i = 0; i < this.accountLists.length; i ++){
						this.changePackage(this.accountLists[i], this.treeArray);
					}
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
				this.accountForm.total_price = this.totalPointValue;
				this.accountForm.total_selling_price = this.totalAccountSellingPrice;
				this.accountForm.packages = this.accountLists.map(account => {
					let obj = {};

					obj['id'] = account.selectedPackage.value;
					obj['price'] = account.selectedPackage.price;
					obj['selling_price'] = account.selectedPackage.selling_price;
					obj['level'] = account.level;

					return obj;
				})

				let url = this.purchase ? '/api/purchase/account/' + this.purchase.id + '/update' : '/api/purchases/account';
				this.accountForm.post(url)
					.then(response => this.onSuccess(response));
			},

			submitForm() {
				let url = this.purchase ? '/api/purchase/' + this.purchase.id + '/update' : '/api/purchases';
				this.form.post(url)
					.then(response => this.onSuccess(response));
			},

			onSuccess(response) {
				this.purchase = response.purchase;
				if(this.isEditing) {
					this.accountForm.account_level = this.radio;
					this.accountForm.user_id = this.user.id;
				}
				this.isEditing = false;
				this.form.purchase_date = moment(response.purchase.created_at).format("YYYY-MM-DD");
				this.accountForm.purchase_date = moment(response.purchase.created_at).format("YYYY-MM-DD");
				this.accountForm.referral_code = this.referrerInput;
				flash(this.$options.filters.trans(response.message));
			},

			getPackagePrice(amount = 0, pack, index) {
				let price = pack.selling_price;

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

			getPackageSellingPrice(amount = 0, pack, index) {
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

			changePackage(e, items = this.treeArray, packageIndex) {
				var i = 0;
				// console.log("Showing data data");
				let account = e.data ? e.data : e;
				// console.log(account);
			  	for (; i < items.length; i++) {
			  		let name = items[i].name ? items[i].name : items[i].label;
			  		// console.log(account.name);
			  		// console.log(name);
				    if (name === account.name) {

				    	items[i].package_index = items[i].package_index == this.potentialPackages.length-1 ? 0 : items[i].package_index + 1;
				    	// if(this.triggeredAccount != e){
				    	// // if(this.index == this.potentialPackages.length-1){
				    	// 	this.triggeredAccount = e;
				    	// 	this.index = firstRun ? 0 : 1;
				    	// } else if(this.index == this.potentialPackages.length-1) {
				    	// 	this.index = 0;
				    	// }
				    	// else{
				    	// 	this.index++;
				    	// }
			      		this.setPackageIntoGeno(items[i], items[i].package_index, account.name, packageIndex);
				    } else if (_.isArray(items[i].children)) {
			      		this.changePackage(account, items[i].children, packageIndex);
				    }
			  	}
			},

			setPackageIntoGeno(item, index, e, packageIndex){
				let obj = {};
				if(this.purchase && !this.isEditing) {
					item.selectedPackage = this.sortedPackages[packageIndex].name;
					
					obj['value'] =  this.sortedPackages[packageIndex].id;
					obj['label'] =  this.sortedPackages[packageIndex].name;
					obj['price'] =  this.sortedPackages[packageIndex].price;
					obj['selling_price'] =  this.sortedPackages[packageIndex].selling_price;
				} else {
					item.selectedPackage = this.potentialPackages[index]['label'];

					obj['value'] = this.potentialPackages[index].value;
					obj['label'] = this.potentialPackages[index].label;
					obj['price'] = this.potentialPackages[index].price;
					obj['selling_price'] = this.potentialPackages[index].selling_price;
				}
				this.accountLists.forEach((accountList) => {
					if(accountList.name == e)
						accountList.selectedPackage = obj;
				})
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
				return this.totalPointValue - (this.totalPointValue * this.accountCommission /100);
				// return this.totalAccountPrice * this.accountCommission / 100;
			},

			originalPrice() {
				return this.machineQuantity * this.unitPrice;
			},

			mainContainerClass() {
				if((!this.purchase && this.showTree) || (this.purchase && this.showTree)){
					return "col-sm-8";
				} else {
					return "col-sm";
				}
			},

			totalAccountSellingPrice() {
				return _.sumBy(this.accountLists, function(account){
					return account.selectedPackage ? parseFloat(account.selectedPackage.selling_price) : 0;
				})
			},

			totalPointValue() {
				return _.sumBy(this.accountLists, function(account){
					return account.selectedPackage ? parseFloat(account.selectedPackage.price) : 0;
				})
			},

			totalPrice() {
				if(this.purchase && !this.isEditing)
					return this.purchase.is_std ? this.purchase.total_price_std : this.purchase.total_selling_price;

				return _.sumBy(this.form.packages, function(pack){
					let amount = pack.amount ? pack.amount : 0;
					return this.getPackagePrice(pack.amount, pack);
				}.bind(this))
			},

			totalPackagePointValue() {
				if(this.purchase && !this.isEditing)
					return this.purchase.is_std ? this.purchase.total_price_std : this.purchase.total_price;

				return _.sumBy(this.form.packages, function(pack){
					let amount = pack.amount ? pack.amount : 0;
					return this.getPackageSellingPrice(pack.amount, pack);
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

			sortedPackages() {
				return _.sortBy(this.purchase.packages, function(purchase){
					return purchase.pivot.account_id;
				});
			}
		}	
	}
</script>
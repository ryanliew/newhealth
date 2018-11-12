<template>
	<div class="row">
		<div class="col-sm-7">
			<div class="card">
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col">
							{{ 'redemption.redemption' | trans }}
						</div>
						<div class="col-auto">
							<button class="btn btn-info" @click="update" v-if="false" v-html="updateButtonContent"></button>
							<template v-if="isEditing && item">
								<button class="btn btn-danger" @click="cancelUpdate"><i class="fa fa-times"></i> {{ 'purchase.cancel' | trans }}</button>
							</template>
							<button v-if="cancelable" class="btn btn-primary" @click="back"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<loader v-if="loading"></loader>
					<img :src="'./storage/' + itemPicture">
				</div>
			</div>
		</div>
		<div class="col-sm">
			<div class="card">
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col">
							{{ 'redemption.item_details' | trans }}
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col">
							<div class="payment-details-grid">
								<div v-if="!user.is_admin">{{ "redemption.wallet" | trans }}</div>
								<div v-if="!user.is_admin" class="text-left">{{ wallet.amount }}</div>
								<div v-if="user.is_admin">{{ "redemption.user" | trans }}</div>
								<div v-if="user.is_admin" class="text-left">{{ this.item.name }}</div>
								<div>{{ "redemption.item_name" | trans }}</div>
								<div class="text-left">{{ name }}</div>
								<div>{{ "redemption.description" | trans }}</div>
								<div class="text-left">{{ description }}</div>

								<div>{{ "redemption.point_to_redeem" | trans }}</div>
								<div class="text-left">{{ price }}</b></div>
								<div>{{ "redemption.quantity" | trans }}</div>
									<text-input v-model="form.quantity" 
										:defaultValue="defaultQuantity"
										:required="false"
										type="number"
										name="quantity"
										:editable="isRedeemItem"
										:focus="true"
										:hideLabel="true"
										:error="form.errors.get('quantity')">
									</text-input>
								<div>{{ "purchase.total" | trans }}</div>
								<div class="text-left"><b>{{ isRedeemItem ? totalPrice : item.total }}</b></div>
							</div>
							<button v-if="item && isRedeemItem" @click="submitForm" class="btn btn-primary btn-lg" :disabled="totalPrice == 0" v-html="redeemButtonContent"></button>
							<button v-if="user.is_admin" @click="submitApprove" class="btn btn-success" :disabled="isNotPending"><i class="fa fa-check"></i> {{ 'redemption.approve' | trans }}</button>
							<button v-if="user.is_admin" @click="isConfirmingReject = true" class="btn btn-danger" :disabled="isNotPending"><i class="fa fa-times"></i> {{ 'redemption.reject' | trans }}</button>
							<button v-if="!isRedeemItem && !isNotPending && !user.is_admin" @click="submitCancel" class="btn btn-danger" :disabled="isNotPending"><i class="fa fa-times"></i> {{ 'redemption.cancel' | trans }}</button>
							<div>
								<span v-if="!isWalletAmountAdequate" style="color:red; font-size:10px;">Wallet does not have enough amount.</span>
							</div>
							<confirmation
								message="confirmation.reject_redemption"
								:loading="rejectForm.submitting"
								@confirmed="submitReject"
								@canceled="isConfirmingReject = false"
								v-if="isConfirmingReject">

								<textarea-input v-model="rejectForm.reject_note" 
									:defaultValue="rejectForm.reject_note"
									:required="true"
									type="text"
									:label="$options.filters.trans('payment.reject_note')"
									name="reject_note"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									rows="3"
									cols="5"
									:error="rejectForm.errors.get('reject_note')">
								</textarea-input>
							</confirmation>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</template>

<script>
	import Payment from './Payment.vue';
	import GenoPage from '../pages/GenoPage.vue';
	import formButtonMixin from '../mixins/form-button.js';
	import moment from 'moment';
	
	export default {
		props: ['selectedItem', 'cancelable', 'isRedeemItem'],

		components: { Payment, GenoPage },

		mixins: [formButtonMixin],

		data() {
			return {
				item: '',
				packages: [],

				form: new Form({
					quantity: '',
					user_id: '',
					package_id: '',
					total: '',
				}),
				rejectForm: new Form({
					reject_note: ''
				}),
				price: 0,
				name: '',
				description: '',
				defaultQuantity: 0,
				submitText: 'purchase.checkout',
				isConfirmingReject: false,
				user: window.user,
				selectedPackages: '',
				potentialPackages: '',
				selectedUser: '',
				potentialUsers: '',
				isEditing: this.selectedItem ? false : true,
				updateText: 'purchase.update',
				is_std: false,
				loading: true,
				tree: [],
				referrerInput: '',
				onHelperText: false,
				helperText: '',
				machineQuantity: '',
				accountCommission: '',
				accountSelected: '',
				unitPrice: 8000,
				defaultTab: 'account',
				redeemText: 'Redeem',
				wallet: '',
			};
		},

		mounted() {
			this.form.user_id = this.user.id;
			this.is_std = this.user.country_id == 48;
			this.item = this.selectedItem;
			this.form.package_id = this.item.id;
			this.defaultQuantity = this.isRedeemItem ? 0 : this.item.package_quantity;
			this.price = this.isRedeemItem ? this.item.price : this.item.package.price;
			this.name = this.isRedeemItem ? this.item.name : this.item.package.name;
			this.description = this.isRedeemItem ? this.item.description : this.item.package.description;
			this.getPackages();
			this.getWallet();
			this.getAccountPackages();
			setTimeout(() => {
			    this.loading = false;
			  }, 100);
		},

		methods: {
			getWallet() {
				let id = this.user.is_admin ? this.item.user_id : this.user.id;
				axios.get('api/internal/ewallet/' + id)
					.then(response => this.setWallet(response));
			},

			setWallet(response) {
				this.wallet = response.data;
			},

			cancelUpdate() {
				this.isEditing = false;
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

			resetHelperTextAndTree(){
				this.onHelperText = false;
				this.form.referral_code = this.referrerInput;
			},

			
			setLoadingTimer(){
				this.loading = false;
			},

			getPackages() {
				axios.get('/api/packages')
					.then(response => this.setPackages(response.data));
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

			updateSelectedUser() {
				this.form.account_id = this.selectedUser ? this.selectedUser.value : '';
				// this.is_std = this.selectedUser ? this.selectedUser.is_std : this.user.country_id == 48;
			},

			submitForm() {
				console.log("submitForm");
				this.form.total = this.totalPrice;
				// console.log(this.canUpdatePurchase);
				let url = '/api/redemption/store';
				this.form.post(url)
					.then(response => this.onSuccess(response));
			},

			onSuccess(response) {
				console.log("onSuccess");
				flash(this.$options.filters.trans(response.message));
				this.back();
			},

			submitCancel() {
				let url = '/api/redemption/cancel/' + this.item.id;
				this.form.post(url)
					.then(response => this.onSuccess(response));
			},

			submitApprove() {
				let url = '/api/redemption/approve/' + this.item.id;
				this.form.post(url)
					.then(response => this.onSuccess(response));
			},

			submitReject() {
				let url = '/api/redemption/reject/' + this.item.id;
				this.rejectForm.post(url)
					.then(response => this.onSuccess(response));
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
			},
			
		},

		computed: {
			isAccountFormButtonDisabled() {
				return !this.allAccountsHasPackage;
			},

			redeemButtonContent: function redeemButtonContent() {
				return this.form.submitting ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : this.$options.filters.trans(this.redeemText);
			},

			totalPrice() {
				return this.item.price * this.form.quantity;
			},

			isWalletAmountAdequate() {
				if(!this.isRedeemItem)
					return true;

				return this.wallet.amount >= this.totalPrice; 
			},

			isNotPending() {
				return this.item.status != 'pending' ? true : false; 
			},

			title() {
				let title = 'resell.sell_account';

				return title;
			},

			itemPicture() {
				return this.user.is_admin ? this.item.package.package_photo_path : this.item.package_photo_path;
			},

			instruction() {
				let instruction = this.item && !this.isEditing ? 'purchase.selected_packages' : 'purchase.select_package';

				return instruction;
			},

			updateButtonContent() {
				return this.loading ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : this.$options.filters.trans(this.updateText);
			},

			canUpdatePurchase() {
				if( this.user.is_admin )
				{
					return this.item.status !== "complete";
				}
				return this.item.payment ? false : true;
			},
		}	
	}
</script>
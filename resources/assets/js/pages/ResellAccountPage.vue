<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<template>
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col">
								{{ 'resell.sell_account' | trans }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm">
							<div class="card-block">
								<selector-input v-model="selectedUser" :defaultData="selectedUser" 
												:label="$options.filters.trans('resell.account_to_sell')" 
												:required="true"
												name="account_id"
												:potentialData="potentialAccounts"
												@input="updateSelectedUser()"
												:editable="true"
												:placeholder="$options.filters.trans('resell.account_to_sell')"
												:error="form.errors.get('account_id')"
												>
								</selector-input>
							</div>
						</div>
						<div class="col-sm">
							<div class="card-block">
								<text-input v-model="form.referral_code" 
									:defaultValue="form.referral_code"
									:required="true"
									type="text"
									:label="$options.filters.trans('resell.sell_to_user')"
									name="referral_code"
									:editable="true"
									:focus="false"
									:hideLabel="false"
									:placeholder="$options.filters.trans('resell.user_referral_code')"
									@input="checkReferrer()"
									:error="textHelper">
								</text-input>
							</div>
						</div>
					</div>
					<div class="col-sm-auto">
						<button class="btn btn-primary btn-lg" @click="confirmResell" :disabled="isUserReferralCodeValid || isSelectAccountEmpty" v-html="submitButtonContent"></button>
					</div>
					<confirmation
						message="confirmation.verify_resell"
						:loading="form.submitting"
						@confirmed="submitForm"
						@canceled="isConfirmingResell = false"
						v-if="isConfirmingResell">
					</confirmation>
				</div>
			</template>
		</transition>
	</div>
</template>
<script>
	import formButtonMixin from '../mixins/form-button.js';

	export default {
		props: [''],

		mixins: [formButtonMixin],

		data() {
			return {
				form: new Form({
					account_id: '',
					referral_code: '',
				}),
				selectedUser: null,
				potentialAccounts: [],
				textHelper: '',
				user: '',
				isConfirmingResell: false,
			}
		},

		mounted() {
			this.user = window.user;
			this.getAccounts();
			console.log(window.user.is_admin);
		},

		methods: {
			getAccounts() {
				let url = this.user.is_admin ? '/api/internal/auth/accounts/' : '/api/internal/auth/accounts/' + this.user.id;
				axios.get(url)
					.then(response => this.setAccounts(response.data));
			},

			setAccounts(data) {
				// this.setSelectedAccount(data);
				this.potentialAccounts = data.map(account => {
					let obj = {};
					obj['value'] = account.id;
					obj['label'] = account.referral_code;

					return obj;
				});
			},

			setSelectedAccount(data) {
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
			},

			checkReferrer() {
				if(this.form.referral_code != ''){
					axios.get('api/internal/user/referrer?code=' + this.form.referral_code)
						.then(response => this.setReferrer(response));
				} else {
					this.textHelper = '';
				}
				
			},

			setReferrer(response) {
				if(response.data == 'auth.referrer_not_found'){
					this.textHelper = 'resell.no_user_found';
				} else {
					this.textHelper = '';
				} 
			},

			confirmResell() {
				this.isConfirmingResell = true;
			},

			submitForm() {
				console.log("submitForm");

				this.form.post('/api/account/sell')
					.then(response => this.onSuccess(response));
			},

			onSuccess(response) {
				console.log("onSuccess");

				this.selectedUser = null;
				this.isConfirmingResell = false;
				flash(this.$options.filters.trans(response.message));
			},
		},

		computed: {
			isSelectAccountEmpty() {
				return this.selectedUser == null ? true : false;
			},

			isUserReferralCodeValid() {
				return !this.isTextHelperEmpty || this.isFormReferralCodeEmpty;
			},

			isTextHelperEmpty() {
				return this.textHelper == '' ? true : false;
			},

			isFormReferralCodeEmpty() {
				return this.form.referral_code == '' ? true : false;
			},
		}
	}
</script>
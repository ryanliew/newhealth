<template>
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
			<form @submit.prevent="submit" 
				@keydown="form.errors.clear($event.target.name)" 
				@input="form.errors.clear($event.target.name)">
				<span class="badge badge-danger" v-if="purchase.status == 'rejected'">{{ 'purchase.rejected' | trans }}</span>
				<text-input v-model="payment.reject_note" 
					:defaultValue="payment.reject_note"
					:required="false"
					type="date"
					:label="$options.filters.trans('payment.reject_note')"
					name="date"
					:editable="false"
					:focus="false"
					:hideLabel="false"
					v-if="payment && purchase.status == 'rejected'">
				</text-input>

				<text-input v-model="payment.updated_at" 
					:defaultValue="payment.updated_at"
					:required="false"
					type="date"
					:label="$options.filters.trans('payment.paid_on')"
					name="date"
					:editable="false"
					:focus="false"
					:hideLabel="false"
					v-if="payment">
				</text-input>

				<text-input v-model="form.amount" 
					:defaultValue="$options.filters.currency(form.amount)"
					:required="false"
					type="number"
					:label="$options.filters.trans('payment.amount')"
					name="amount"
					:editable="false"
					:focus="false"
					:hideLabel="false"
					v-if="!purchase.is_std">
				</text-input>

				<text-input v-model="form.amount" 
					:defaultValue="$options.filters.currency_std(form.amount_std)"
					:required="false"
					type="number"
					:label="$options.filters.trans('payment.amount')"
					name="amount_std"
					:editable="false"
					:focus="false"
					:hideLabel="false"
					v-else>
				</text-input>

				<div class="row">
					<div class="col">
						<span class="label-small">{{ "payment.bank_details" | trans }}</span>
						<div class="payment-details-grid">
							<div>{{ "payment.beneficiary_name" | trans }}</div>
							<div class="text-center">NEWLEAF PLANTTION BERHAD<br><span style="font-size:14;">(1251569-U)</span></div>
							<div>{{ "payment.beneficiary_bank" | trans }}</div>
							<div class="text-center">MALAYAN BANKING BERHAD</div>

							<div>{{ "payment.beneficiary_address" | trans }}</div>
							<div class="text-center">Suite E-07-01, Plaza Mont Kiara, No. 2 Jalan Kiara, 50480 Kuala Lumpur, Malaysia</div>
							<div>{{ "payment.bank_address" | trans }}</div>
							<div class="text-center">21 & 23, Jalan 23/70A, Desa Sri Hartamas, 50680 Kuala Lumpur</div>

							<div>{{ "payment.bank_account_no" | trans }}</div>
							<div class="text-center">5647 2668 0297</div>
							<div>{{ "payment.bank_swift" | trans }}</div>
							<div class="text-center">MBBEMYKL</div>
						</div>
					</div>
				</div>

				<image-input v-model="paymentSlip" :defaultImage="paymentSlip"
							@loaded="changePaymentSlipImage"
							label="payment.payment_slip"
							name="payment_slip_path"
							:required="true"
							accept="image/*, .pdf"
							:error="$options.filters.trans(form.errors.get('payment_slip_path'))"
							v-if="!payment || purchase.status == 'rejected'">
				</image-input>

				<div class="mb-3" v-else>
					<div v-if="payment.payment_slip_path.endsWith('.pdf')">
						<a class="btn btn-danger" target="_blank" :href="'/storage/' + payment.payment_slip_path">
							<span class="fa fa-2x fa-file-pdf-o pr-2"></span> Download PDF
						</a>
					</div>
					<img v-else class="img-fluid" :src="'storage/' + payment.payment_slip_path"/>
				</div>
				
				<button type="submit" v-if="!payment || purchase.status == 'rejected'" class="btn btn-success" :disabled="form.submitting" v-html="submitButtonContent"></button>
			</form>
			<div class="row no-gutters">
				<div class="col-auto">
					<form @submit.prevent="confirmVerify" 
						@keydown="form.errors.clear($event.target.name)" 
						@input="form.errors.clear($event.target.name)"
						v-if="user.is_admin && payment && !payment.is_verified && purchase.status !== 'rejected'">
							<button type="submit" class="btn btn-primary" :disabled="verifyForm.submitting" v-html="submitVerifyButtonContent"></button>
					</form>
				</div>
				<div class="col-auto pl-2">
					<form @submit.prevent="confirmReject" 
						@keydown="form.errors.clear($event.target.name)" 
						@input="form.errors.clear($event.target.name)"
						v-if="user.is_admin && payment && !payment.is_verified && purchase.status !== 'rejected'"">
							<button type="submit" class="btn btn-danger" :disabled="rejectForm.submitting" v-html="submitRejectButtonContent"></button>
					</form>
				</div>
			</div>
			<confirmation
				message="confirmation.reject_payment"
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

			<confirmation
				message="confirmation.verify_payment"
				:loading="verifyForm.submitting"
				@confirmed="submitVerify"
				@canceled="isConfirmingVerify = false"
				v-if="isConfirmingVerify">
			</confirmation>
		</div>
	</div>
	
</template>

<script>
	import formButtonMixin from '../mixins/form-button.js';

	export default {
		props: ['purchase', 'cancelable'],

		mixins: [formButtonMixin],

		data() {
			return {
				payment: '',
				form: new Form({
					amount: 0,
					amount_std: 0,
					payment_slip_path: '',
					user_id: window.user.id
				}),
				verifyForm: new Form({

				}),
				rejectForm: new Form({
					reject_note: ''
				}),
				paymentSlip: {name: 'No file selected'},
				camera: false,
				submitText: 'payment.submit_payment',
				submitVerifyText: 'payment.verify',
				submitRejectText: 'payment.reject',
				user: window.user,
				isConfirmingVerify: false,
				isConfirmingReject: false

			};
		},

		mounted() {
			this.payment = this.purchase.payment ? this.purchase.payment : '';
			this.form.amount = this.purchase.total_price;
			this.form.amount_std = this.purchase.total_price_std;
		},

		methods: {
			changePaymentSlipImage(e) {
				this.paymentSlip = { src: e.src, file: e.file };
				this.form.payment_slip_path = e.file;
				this.form.errors.clear('payment_slip_path');
			},

			back() {
				this.$emit('back');
			},

			submit() {
				this.form.post(this.submitUrl)
					.then(response => this.onSuccess(response));
			},

			onSuccess(response, shouldBack = true) {
				flash(this.$options.filters.trans(response.message));
				this.payment = response.payment;
				this.isConfirmingVerify = false;
				this.isConfirmingReject = false;

				if(shouldBack)
					this.back();
			},

			confirmVerify() {
				this.isConfirmingVerify = true;
			},

			confirmReject() {
				this.isConfirmingReject = true;
			},

			submitVerify() {
				this.verifyForm.post('/api/payment/verify/' + this.payment.id)
					.then(response => this.onSuccess(response, false));
			},

			submitReject() {
				this.rejectForm.post('/api/payment/reject/' + this.payment.id)
					.then(response => this.onSuccess(response, true));
			}
		},

		computed: {
			title() {
				return this.payment ? 'payment.payment_details' : 'payment.make_payment';
			},

			submitVerifyButtonContent() {
				return this.verifyForm.submitting ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : this.$options.filters.trans(this.submitVerifyText);
			},

			submitRejectButtonContent() {
				return this.verifyForm.submitting ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : this.$options.filters.trans(this.submitRejectText);
			},

			submitUrl() {
				return this.payment ? "/api/payment/update/" + this.payment.id : "/api/purchase/pay/" + this.purchase.id;
			}
		},

		watch: {
			purchase(val) {
				this.form.amount = val.total_price;
				this.form.amount_std = val.total_price_std;
			}
		}
	}
</script>
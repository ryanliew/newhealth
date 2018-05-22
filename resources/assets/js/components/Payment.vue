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
				<text-input v-model="payment.created_at" 
					:defaultValue="payment.created_at"
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
					:defaultValue="form.amount"
					:required="false"
					type="number"
					:label="$options.filters.trans('payment.amount')"
					name="amount"
					:editable="false"
					:focus="false"
					:hideLabel="false">
				</text-input>

				<image-input v-model="paymentSlip" :defaultImage="paymentSlip"
							@loaded="changePaymentSlipImage"
							label="payment.payment_slip"
							name="payment_slip_path"
							:required="true"
							accept="image/*, .pdf"
							:error="$options.filters.trans(form.errors.get('payment_slip_path'))"
							v-if="!payment">
				</image-input>

				<img v-else class="img-fluid mb-3" :src="'storage/' + payment.payment_slip_path"/>
				
				<button type="submit" v-if="!payment" class="btn btn-success" :disabled="form.submitting" v-html="submitButtonContent"></button>
			</form>
			<form @submit.prevent="submitVerify" 
				@keydown="form.errors.clear($event.target.name)" 
				@input="form.errors.clear($event.target.name)"
				v-if="user.is_admin && payment && !payment.is_verified">
					<button type="submit" class="btn btn-primary" :disabled="verifyForm.submitting" v-html="submitVerifyButtonContent"></button>
			</form>
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
					payment_slip_path: '',
					user_id: window.user.id
				}),
				verifyForm: new Form({

				}),
				paymentSlip: {name: 'No file selected'},
				camera: false,
				submitText: 'payment.submit_payment',
				submitVerifyText: 'payment.verify',
				user: window.user

			};
		},

		mounted() {
			this.payment = this.purchase.payment ? this.purchase.payment : '';
			this.form.amount = this.purchase.total_price;
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
				this.form.post('/api/purchase/pay/' + this.purchase.id)
					.then(response => this.onSuccess(response));
			},

			onSuccess(response, shouldBack = true) {
				flash(this.$options.filters.trans(response.message));
				this.payment = response.payment;

				if(shouldBack)
					this.back();
			},

			submitVerify() {
				this.form.post('/api/payment/verify/' + this.payment.id)
					.then(response => this.onSuccess(response, false));
			}
		},

		computed: {
			title() {
				return this.payment ? 'payment.payment_details' : 'payment.make_payment';
			},

			submitVerifyButtonContent() {
				return this.verifyForm.submitting ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : this.$options.filters.trans(this.submitVerifyText);
			}
		}
	}
</script>
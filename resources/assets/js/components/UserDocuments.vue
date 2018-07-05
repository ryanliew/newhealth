<template>
	<div class="card">
		<div class="card-header">
			<div class="row align-items-center">
				<div class="col">
					{{ 'user.kyc_documents' | trans }} <span class="badge badge-pill badge-danger" v-if="selectedUser.id_status == 'rejected'">!</span><span class="badge badge-pill badge-warning" v-if="selectedUser.id_status == 'pending'">!</span>
				</div>
				<!-- <div class="col-auto">
					<button class="btn btn-primary" @click="back"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
				</div> -->
			</div>
		</div>
		<div class="card-block">
			<text-input v-model="selectedUser.reject_note" 
				:defaultValue="selectedUser.reject_note"
				:required="false"
				type="date"
				:label="$options.filters.trans('payment.reject_note')"
				name="date"
				:editable="false"
				:focus="false"
				:hideLabel="false"
				v-if="selectedUser.id_status == 'rejected'">
			</text-input>
			<div class="row" v-if="can_edit">
				<div class="col-auto">
					<button class="btn btn-primary" @click="updateDocument = true" v-if="!updateDocument">{{ 'user.update_document' | trans }}</button>
					<button class="btn btn-danger" @click="updateDocument = false" v-else>{{ 'user.cancel_update' | trans }}</button>
				</div>
				<div class="col-auto" v-if="user.is_admin && shouldShow">
					<button class="btn btn-success" @click="isConfirmingVerify = true" v-if="selectedUser.id_status == 'pending_verification'">{{ 'user.verify' | trans }}</button>
					<button class="btn btn-danger" @click="isConfirmingReject = true" v-if="selectedUser.id_status == 'pending_verification'">{{ 'user.reject' | trans }}</button>
				</div>
			</div>
			<confirmation
				message="confirmation.reject_user"
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
				message="confirmation.verify_user"
				:loading="verifyForm.submitting"
				@confirmed="submitVerify"
				@canceled="isConfirmingVerify = false"
				v-if="isConfirmingVerify">
			</confirmation>

			<form @submit.prevent="submit" 
				@keydown="form.errors.clear($event.target.name)" 
				@input="form.errors.clear($event.target.name)"
				v-if="updateDocument">

				<div class="row mt-3">
					<div class="col-sm">
						<image-input v-model="identity" :defaultImage="identity"
							@loaded="identityChanged"
							label="user.identity"
							name="identity"
							:required="true"
							accept="image/*, .pdf"
							:error="$options.filters.trans(form.errors.get('identity'))">
						</image-input>
						<hr>
						<image-input v-model="residenceProof" :defaultImage="residenceProof"
							@loaded="residenceProofChanged"
							label="user.residence_proof"
							name="residence_proof"
							:required="true"
							accept="image/*, .pdf"
							:error="$options.filters.trans(form.errors.get('residence_proof'))">
						</image-input>
					</div>
					<div class="col-sm">
						<image-input v-model="bankStatement" :defaultImage="bankStatement"
							@loaded="bankStatementChanged"
							label="user.bank_statement"
							name="bank_statement"
							:required="true"
							accept="image/*, .pdf"
							:error="$options.filters.trans(form.errors.get('bank_statement'))">
						</image-input>
						<hr>
						<image-input v-model="nomineeIdentity" :defaultImage="nomineeIdentity"
							@loaded="nomineeIdentityChanged"
							label="user.nominee_identity"
							name="nominee_identity"
							:required="true"
							accept="image/*, .pdf"
							:error="$options.filters.trans(form.errors.get('nominee_identity'))">
						</image-input>
					</div>
				</div>

				<button class="btn btn-success" :disabled="form.submitting" v-html="submitButtonContent" type="submit"></button>
			</form>
			<div v-if="!updateDocument && selectedUser.kyc_identity">
				<div class="row">
					<div class="col-sm">
						<div class="document mt-3">
							<p>{{ "user.identity" | trans }}</p>
							<img :src="identity.src" class="img-thumbnail" v-if="!identity.src.endsWith('.pdf')"/>
							<a class="btn btn-danger" target="_blank" :href="identity.src" v-else>
								<span class="fa fa-2x fa-file-pdf-o pr-2"></span> Download PDF
							</a>
						</div>
						<hr>
						<div class="document mt-3">
							<p>{{ "user.residence_proof" | trans }}</p>
							<img :src="residenceProof.src" class="img-thumbnail" v-if="!residenceProof.src.endsWith('.pdf')"/>
							<a class="btn btn-danger" target="_blank" :href="residenceProof.src" v-else>
								<span class="fa fa-2x fa-file-pdf-o pr-2"></span> Download PDF
							</a>
						</div>
					</div>
					<div class="col-sm">
						<div class="document mt-3">
							<p>{{ "user.bank_statement" | trans }}</p>
							<img :src="bankStatement.src" class="img-thumbnail" v-if="!bankStatement.src.endsWith('.pdf')"/>
							<a class="btn btn-danger" target="_blank" :href="bankStatement.src" v-else>
								<span class="fa fa-2x fa-file-pdf-o pr-2"></span> Download PDF
							</a>
						</div>
						<hr>
						<div class="document mt-3">
							<p>{{ "user.nominee_identity" | trans }}</p>
							<img :src="nomineeIdentity.src" class="img-thumbnail" v-if="!nomineeIdentity.src.endsWith('.pdf')"/>
							<a class="btn btn-danger" target="_blank" :href="nomineeIdentity.src" v-else>
								<span class="fa fa-2x fa-file-pdf-o pr-2"></span> Download PDF
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import formButtonMixin from '../mixins/form-button.js';

	export default {
		props: ['selectedUser'],

		mixins: [formButtonMixin],

		data() {
			return {
				form: new Form({
					identity: '',
					bank_statement: '',
					nominee_identity: '',
					residence_proof: ''
				}),
				rejectForm: new Form({
					reject_note: ''
				}),
				verifyForm: new Form({

				}),
				isConfirmingVerify: false,
				isConfirmingReject: false,
				bankStatement:  {name: 'No file selected', src: ''},
				nomineeIdentity:  {name: 'No file selected', src: ''},
				residenceProof:  {name: 'No file selected', src: ''},
				identity: {name: 'No file selected', src: ''},
				updateDocument: false,
				user: window.user,
				shouldShow: true
			};
		},

		mounted() {
			this.shouldShow = this.selectedUser.id_status !== 'verified';
			this.setKycDocs();
		},

		methods: {
			identityChanged(e) {
				this.identity = { src: e.src, file: e.file };
				this.form.identity = e.file;
				this.form.errors.clear('identity');
			},

			bankStatementChanged(e) {
				this.bankStatement = { src: e.src, file: e.file };
				this.form.bank_statement = e.file;
				this.form.errors.clear('bank_statement');
			},

			nomineeIdentityChanged(e) {
				this.nomineeIdentity = { src: e.src, file: e.file };
				this.form.nominee_identity = e.file;
				this.form.errors.clear('nominee_identity');
			},

			residenceProofChanged(e) {
				this.residenceProof = { src: e.src, file: e.file };
				this.form.residence_proof = e.file;
				this.form.errors.clear('residence_proof');
			},

			back() {
				this.$emit('back');
			},

			submit() {
				this.form.post('/api/user/' + this.selectedUser.id + '/kyc')
					.then(response => this.onSuccess(response));
			},

			onSuccess(response) {
				flash(this.$options.filters.trans(response.message));
				this.updateDocument = false;
				this.isConfirmingReject = false;
				this.isConfirmingVerify = false;
				this.back();
			},

			submitReject() {
				this.shouldShow = false;
				this.rejectForm.post('/api/user/' + this.selectedUser.id + '/kyc/reject')
					.then(response => this.onSuccess(response));
			},

			submitVerify() {
				this.shouldShow = false;
				this.verifyForm.post('/api/user/' + this.selectedUser.id + '/kyc/verify')
					.then(response => this.onSuccess(response));
			},

			setKycDocs() {
				if(this.selectedUser.kyc_identity) {
					this.form.identity = this.selectedUser.kyc_identity;
					this.form.nominee_identity = this.selectedUser.kyc_nominee_identity;
					this.form.bank_statement = this.selectedUser.kyc_bank_statement;
					this.form.residence_proof = this.selectedUser.kyc_residence_proof;

					this.identity = {name: this.selectedUser.kyc_identity, src: "storage/" + this.selectedUser.kyc_identity};
					this.nomineeIdentity = {name: this.selectedUser.kyc_nominee_identity, src: "storage/" + this.selectedUser.kyc_nominee_identity};
					this.bankStatement = {name: this.selectedUser.kyc_bank_statement, src: "storage/" + this.selectedUser.kyc_bank_statement};
					this.residenceProof = {name: this.selectedUser.kyc_residence_proof, src: "storage/" + this.selectedUser.kyc_residence_proof};
				}
			}
		},

		computed: {
			can_edit() {
				return this.selectedUser.id_status == "pending" || this.selectedUser.id_status == "rejected" || this.user.is_admin;
			}
		},

		watch: {
			selectedUser() {
				this.setKycDocs();
			}
		}	
	}
</script>
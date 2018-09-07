<template>
	<div>
		<h2 class="text-center">Apply for advisor</h2>
		<div class="border rounded terms bg-white">
			<ol start="1" type="1">
				<li>{{ "advisor.advisor_term_1" | trans }}</li>
				<li>{{ "advisor.advisor_term_2" | trans }}</li>
				<li>{{ "advisor.advisor_term_3" | trans }}</li>
				<li>
					{{ "advisor.advisor_term_4" | trans }}
					<ol type="a">
						<li>{{ "advisor.advisor_term_4a" | trans }}</li>
						<li>{{ "advisor.advisor_term_4b" | trans }}</li>
						<li>{{ "advisor.advisor_term_4c" | trans }}</li>
						<li>{{ "advisor.advisor_term_4d" | trans }}</li>
						<li>{{ "advisor.advisor_term_4e" | trans }}</li>
					</ol>
				</li>
				<li>
					{{ "advisor.advisor_term_5" | trans }}
					<ol type="a">
						<li>{{ "advisor.advisor_term_5a" | trans }}</li>
						<li>{{ "advisor.advisor_term_5b" | trans }}</li>
					</ol>
				</li>
				<li>
					{{ "advisor.advisor_term_6" | trans }}
					<ol type="a">
						<li>{{ "advisor.advisor_term_6a" | trans }}</li>
						<li>{{ "advisor.advisor_term_6b" | trans }}</li>
						<li>{{ "advisor.advisor_term_6c" | trans }}</li>
						<li>{{ "advisor.advisor_term_6d" | trans }}</li>
						<li>{{ "advisor.advisor_term_6e" | trans }}</li>
						<li>{{ "advisor.advisor_term_6f" | trans }}</li>
						<li>{{ "advisor.advisor_term_6g" | trans }}</li>
					</ol>
				</li>
				<li>
					{{ "advisor.advisor_term_7" | trans }}
					<ol type="a">
						<li>{{ "advisor.advisor_term_7a" | trans }}</li>
						<li>{{ "advisor.advisor_term_7b" | trans }}</li>
						<li>{{ "advisor.advisor_term_7c" | trans }}</li>
						<li>{{ "advisor.advisor_term_7d" | trans }}</li>
						<li>{{ "advisor.advisor_term_7e" | trans }}</li>
						<li>{{ "advisor.advisor_term_7f" | trans }}</li>
						<li>{{ "advisor.advisor_term_7g" | trans }}</li>
					</ol>
				</li>
				<li>
					{{ "advisor.advisor_term_8" | trans }}
					<ol type="a">
						<li>{{ "advisor.advisor_term_8a" | trans }}</li>
						<li>{{ "advisor.advisor_term_8b" | trans }}</li>
						<li>{{ "advisor.advisor_term_8c" | trans }}</li>
						<li>{{ "advisor.advisor_term_8d" | trans }}</li>
						<li>{{ "advisor.advisor_term_8e" | trans }}</li>
					</ol>
				</li>
				<li>{{ "advisor.advisor_term_9" | trans }}</li>
				<li>{{ "advisor.advisor_term_10" | trans }}</li>
				<li>{{ "advisor.advisor_term_11" | trans }}</li>
				<li>{{ "advisor.advisor_term_12" | trans }}</li>
				<li>{{ "advisor.advisor_term_13" | trans }}</li>
				<li>{{ "advisor.advisor_term_14" | trans }}</li>
				<li>{{ "advisor.advisor_term_15" | trans }}</li>
				<li>{{ "advisor.advisor_term_16" | trans }}</li>
				<li>{{ "advisor.advisor_term_17" | trans }}</li>
				<li>{{ "advisor.advisor_term_18" | trans }}</li>
			</ol>
		</div>

		<div class="form-check mt-4">
			<input class="form-check-input" type="checkbox" id="terms-acceptance" v-model="form.terms_acceptance">
			<label class="form-check-label" for="terms-acceptance">I agree to the <a href="/materials/ADVISOR TERMS AND CONDITION.docx" target="_blank">Terms and Conditions</a> above</label>
		</div>
		<div class="text-center">
			<button type="button" class="btn btn-primary" :disabled="!form.terms_acceptance" @click="submit" v-html="submitButtonContent">
			</button>
			<button type="button" class="btn btn-danger" @click="back">
				Cancel
			</button>
		</div>
	</div>
</template>

<script>
	import formButtonMixin from '../mixins/form-button.js';
	export default {
		props: ['user'],
		mixins: [formButtonMixin],
		data() {
			return {
				form: new Form({
					terms_acceptance: false
				}),
			};
		},

		methods: {
			back() {
				this.$emit('back');
			},

			submit() {
				this.form.post("/api/apply/advisor")
					.then(response => this.onSuccess(response))
					.catch(error => this.onError(error));
			},

			onSuccess(response) {
				flash(this.$options.filters.trans(response.message));
				this.back();
			}
		}	
	}
</script>
export default {
	data() {
		return {
			submitText: 'submit',
			form: new Form({})
		}
	},

	computed: {
		submitButtonContent() {
			return this.form.submitting ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : this.$options.filters.trans(this.submitText);
		},
	}
}
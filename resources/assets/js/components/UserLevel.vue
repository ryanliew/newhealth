<template>
	<div class="card">
		<div class="card-header">
			<div class="row align-items-center">
				<div class="col">
					{{ 'user.settings' | trans }}
				</div>
			</div>
		</div>
		<div class="card-block">
			
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
					parent_id: '',
					user_level: '',
					is_admin: '',
				}),
				user: window.user
			};
		},
	}
</script>
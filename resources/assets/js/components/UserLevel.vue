<template>
	<div class="card">
		<div class="card-header">
			<div class="row align-items-center">
				<div class="col">
					{{ 'user.settings' | trans }}
				</div>
				<div class="col-auto">
					<button class="btn btn-primary" @click="back"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
				</div>
			</div>
		</div>
		<div class="card-block">
			<div class="row">
				<div class="col">
					<selector-input :potentialData="users"
						v-model="selectedParent" 
						:defaultData="selectedParent"
						placeholder="Select a grower"
						:required="false"
						:label="$options.filters.trans('auth.referrer')"
						name="parent_id"
						:editable="true"
						:focus="false"
						:hideLabel="false"
						:error="form.errors.get('parent_id')">
					</selector-input>
				</div>
				<div class="col">
					<selector-input :potentialData="levels"
						v-model="selectedLevel" 
						:defaultData="selectedLevel"
						placeholder="Select grower level"
						:required="true"
						:label="$options.filters.trans('user.level')"
						:editable="true"
						:focus="false"
						:hideLabel="false"
						:error="form.errors.get('user_level')"
						:unclearable="true">
					</selector-input>	
				</div>
			</div>
			<div class="form-check mt-4">
				<input class="form-check-input" type="checkbox" id="is-admin" v-model="form.is_admin">
				<label class="form-check-label" for="is-admin">{{ 'user.is_admin' | trans }}</label>
			</div>

			<button class="btn btn-primary mt-3" :disabled="form.submitting" v-html="submitButtonContent" @click="submit"></button>
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
				user: window.user,
				users: [],
				levels: [
							{value: "1", label: this.$options.filters.trans('user.level_1')},
							{value: "2", label: this.$options.filters.trans('user.level_2')},
							{value: "3", label: this.$options.filters.trans('user.level_3')},
							{value: "4", label: this.$options.filters.trans('user.level_4')},
						],
				selectedParent: '',
				selectedLevel: ''
			};
		},

		methods: {
			getParents(error = '') {
				// console.log(error);
				axios.get("/api/admin/users/parents?user_id=" + this.selectedUser.id)
					.then(response => this.setParents(response))
					.catch(error => this.getParents(error));
			},

			setParents(response) {
				this.users = response.data.map(function(user){
					let obj = {};

					obj['value'] = user.id;
					obj['label'] = user.name + " | " + user.referral_code;

					return obj;
				});

				this.setForm();
			},

			setForm() {
				this.form.is_admin = this.selectedUser.is_admin;
				this.form.parent_id = this.selectedUser.parent_id;
				this.form.user_level = this.selectedUser.user_level;

				this.selectedLevel = _.filter(this.levels, function(level){ return this.form.user_level == level.value; }.bind(this))[0];

				let currentParent = _.filter(this.users, function(user){ return this.form.parent_id == user.value; }.bind(this));

				if(currentParent.length > 0)
					this.selectedParent = currentParent[0];
			},

			submit() {
				this.form.post("/api/admin/users/" + this.selectedUser.id + "/setting", false)
						.then(response => this.onSuccess(response));
			},

			onSuccess(response) {
				flash(this.$options.filters.trans(response.message));
			},

			back() {
				this.$emit('back');
			}
		},

		watch: {
			selectedUser(newVal, oldVal) {
				this.getParents();
			},

			selectedParent(newVal, oldVal) {
				this.form.parent_id = newVal ? newVal.value : '';
			},

			selectedLevel(newVal, oldVal) {
				this.form.user_level = newVal.value;
			}
		}
	}
</script>
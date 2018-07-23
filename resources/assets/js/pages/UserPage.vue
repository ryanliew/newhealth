<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<table-view ref="users"
						:fields="fields"
						:title="$options.filters.trans('user.users')"
						:url="url"
						:searchables="searchables"
						v-show="!isViewing"
						:dateFilterable="true"
						dateFilterKey="created_at"
						:canExportExcel="true"
						:exportUrl="exportUrl">
			</table-view>
		</transition>
		<transition name="slide-fade" mode="out-in">
			<ProfilePage :cancelable="true" :selectedUser="selectedUser" v-if="isViewing" @back="back"></ProfilePage>
		</transition>

		<confirmation
            message="confirmation.change_legal_status"
            :loading="isLegalLoading"
            @confirmed="submitLegal"
            @canceled="isChangingStatus = false"
            v-if="isChangingStatus">

            <div class="mt-2">
	            <selector-input
	            	:potentialData="legalStatus"
	            	:defaultData="selectedLegalStatus"
	            	v-model="selectedLegalStatus"
	            	label="Status"
	            	:required="true"
	            	:unclearable="true"
	            	:error="statusForm.errors.get('status')"
	            	>
	            </selector-input>
	        </div>
        </confirmation>
	</div>
</template>

<script>
	import ProfilePage from "./ProfilePage.vue";
	export default {
		props: [''],

		components: { ProfilePage },

		data() {
			return {
				fields: [
					{ name: 'name', title: this.tableTitle('auth.name'), sortField: 'name'},
					{ name: 'created_at', title: this.tableTitle('user.joined_at'), sortField: 'created_at', callback: 'date' },
					{ name: 'tree_count', title: this.tableTitle('user.tree_count'), sortField: 'tree_count' },
					{ name: 'email', title: this.tableTitle('auth.email'), sortField: 'email'},
					{ name: 'nationality', title: this.tableTitle('auth.nationality'), sortField: 'nationality'},
					{ name: 'referral_code', title: this.tableTitle('user.referral_code'), sortField: 'referral_code'},
					{ name: 'id_status', title: this.tableTitle('user.status'), sortField: 'id_status', callback: 'userStatusLabel'},
					{ name: '__component:users-actions', title: this.tableTitle('table.actions')}
				],
				searchables: "name,email,nationality,id_status",
				isViewing: false,
				selectedUser: '',
				url: '/api/admin/users',
				exportUrl: '/exports/users?',
				statusForm: new Form({
					status: ''
				}),
				isLegalLoading: false,
				selectedLegalStatus: {label: "Instruction issued to lawyer", value: "instruction_issued"},
				isChangingStatus: false,
				legalStatus: [
					{label: "Instruction issued to lawyer", value: "instruction_issued"},
					{label: "Sales agreement ready for execution", value: "execution_ready"},
					{label: "Sales agreement executed", value: "complete"},
				]
			};
		},

		mounted() {
			this.turnOnEvents();
			// this.$events.on('next', data => this.next(data));

			if(this.getParameterByName('id')) {
				window.events.$on('table-loaded', function(){
					let data = _.filter(this.$refs.users.$refs.vuetable.tableData, function(user) { return user.id == this.getParameterByName('id'); }.bind(this));
					if(data.length > 0)
						this.view(data[0]);
				}.bind(this));
			}
		},

		beforeDestroy() {
			this.turnOffEvents();
		},

		methods: {
			getParameterByName(name, url) {
			    if (!url) url = window.location.href;
			    name = name.replace(/[\[\]]/g, "\\$&");
			    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
			        results = regex.exec(url);
			    if (!results) return null;
			    if (!results[2]) return '';
			    return decodeURIComponent(results[2].replace(/\+/g, " "));
			},

			back() {
				this.turnOnEvents();
				this.isViewing = false;
				this.selectedUser = '';
				this.$refs.users.refreshTable();
			},

			tableTitle(value) {
				return this.$options.filters.trans(value);
			},

			view(user) {
				this.turnOffEvents();
				this.selectedUser = user;
				this.isViewing = true;
			},

			remind(user) {
				this.turnOffEvents();
				this.$events.fire('loading', user.id);
				axios.post('/api/user/' + user.id + '/kyc/remind')
					.then(response => this.onSuccess(response));
			},

			legal(user) {
				this.selectedUser = user;
				let selectedLegalStatus = _.filter(this.legalStatus, function(status){ return user.id_status == status.value;});
				if(selectedLegalStatus.length > 0) this.selectedLegalStatus = selectedLegalStatus[0];
				this.isChangingStatus = true;
			},

			lock(user) {
				this.turnOffEvents();
				this.$events.fire('loading-lock', user.id);
				axios.post('/api/user/' + user.id + '/lock')
					.then(response => this.onStepSuccess(response));
			},

			delete(user) {
				this.turnOffEvents();
				this.$events.fire('loading-delete', user.id);
				axios.post('/api/user/' + user.id + "/delete")
					.then(response => this.onStepSuccess(response));
			},

			submitLegal() {
				this.turnOffEvents();
				this.isLegalLoading = true;
				this.statusForm.status = this.selectedLegalStatus.value;
				this.statusForm.post("/api/user/" + this.selectedUser.id + "/legal")
					.then(response => this.onStepSuccess(response));
			},

			onStepSuccess(response) {
				response.data = { message: response.message ? response.message : response.data.message };
				this.isChangingStatus = false;
				this.isLegalLoading = false;
				this.$refs.users.refreshTable();
				this.onSuccess(response);
			},

			onSuccess(response) {
				this.turnOnEvents();
				flash(this.$options.filters.trans(response.data.message));
				this.$events.fire('loading-complete');
			},

			turnOnEvents() {
				this.$events.on('viewUser', data => this.view(data));
				this.$events.on('remind', data => this.remind(data));
				this.$events.on('legal', data => this.legal(data));

				this.$events.on('lock', data => this.lock(data));
				this.$events.on('delete', data => this.delete(data));
			},

			turnOffEvents() {
				this.$events.off('viewUser');
				this.$events.off('remind');
				this.$events.off('legal');

				this.$events.off('lock');
				this.$events.off('delete');
			}
		},

		watch: {
			lang: function(newlang, oldLang) {
				this.fields = [
					{ name: 'name', title: this.tableTitle('auth.name'), sortField: 'name'},
					{ name: 'created_at', title: this.tableTitle('user.joined_at'), sortField: 'created_at', callback: 'date' },
					{ name: 'email', title: this.tableTitle('auth.email'), sortField: 'email', callback: 'currency'},
					{ name: 'nationality', title: this.tableTitle('auth.nationality'), sortField: 'nationality'},
					{ name: '__component:users-actions', title: this.tableTitle('purchase.actions')}
				];
			}
		},	
	}
</script>
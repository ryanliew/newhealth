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
				exportUrl: '/exports/users?'
			};
		},

		mounted() {
			this.$events.on('view', data => this.view(data));
			this.$events.on('remind', data => this.remind(data));
			this.$events.on('previous', data => this.previous(data));
			this.$events.on('next', data => this.next(data));
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
				this.isViewing = false;
				this.selectedUser = '';
				this.$refs.users.refreshTable();
			},

			tableTitle(value) {
				return this.$options.filters.trans(value);
			},

			view(user) {
				this.selectedUser = user;
				this.isViewing = true;
			},

			remind(user) {
				this.$events.fire('loading', user.id);
				axios.post('/api/user/' + user.id + '/kyc/remind')
					.then(response => this.onSuccess(response));
			},

			previous(user){
				this.$events.fire('loading-prev', user.id);
				axios.post('/api/user/' + user.id + '/legal/previous')
					.then(response => this.onStepSuccess(response));
			},

			next(user){
				this.$events.fire('loading-next', user.id);
				axios.post('/api/user/' + user.id + '/legal/next')
					.then(response => this.onStepSuccess(response));
			},

			onStepSuccess(response) {
				this.onSuccess(response);
				this.$refs.users.refreshTable();
			},

			onSuccess(response) {
				flash(this.$options.filters.trans(response.data.message));
				this.$events.fire('loading-complete');
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
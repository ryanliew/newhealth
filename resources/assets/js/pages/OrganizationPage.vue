<template>
	<div class="card">
		<div class="card-header">
			{{ 'tree.tree' | trans }}
		</div>
		<div class="card-body">
			<loader v-if="loading"></loader>
			<div class="row">
				<geno-page :tree="tree"></geno-page>
			</div>
		</div>
	</div>
</template>

<script>
	import GenoPage from "../pages/GenoPage.vue";

	export default {
		props: ['viewingUser'],

		components: { GenoPage },

		data() {
			return {
				tree: '',
				user: '',
				loading: true
			};
		},

		mounted() {
			this.user = this.viewingUser ? this.viewingUser : window.user;
			this.getTree();
		},

		methods: {
			getTree() {
				axios.get(this.url)
					.then(response => this.setTree(response));
			},

			setTree(response) {
				this.tree = response.data;
				this.loading = false;
			}
		},

		computed: {
			url() {
				if(this.viewingUser)
					return "/api/user/" + this.user.id + "/tree"

				return !this.user.is_admin ? "/api/user/" + this.user.id + "/tree" : "/api/admin/tree" ;
			}
		}		
	}
</script>
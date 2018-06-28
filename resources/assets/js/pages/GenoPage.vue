<template>
	<div class="card">
		<div class="card-header">
			{{ 'tree.tree' | trans }}
		</div>
		<div class="card-body">
			<loader v-if="loading"></loader>
			<div class="row">
				<div class="col-12 m-auto">
					<div class="tree-responsive m-auto p-2">
						<div class="tree">
							<GenoRow :users='tree'>
							</GenoRow>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import GenoRow from "../components/GenoRow.vue";

	export default {
		props: ['viewingUser'],

		components: { GenoRow },

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
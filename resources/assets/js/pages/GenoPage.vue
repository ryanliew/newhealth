<template>
	<div class="card">
		<div class="card-header">
			{{ 'tree.tree' | trans }}
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-xl-auto col-12 m-auto">
					<div class="tree-responsive m-auto">
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
		props: [''],

		components: { GenoRow },

		data() {
			return {
				tree: ''
			};
		},

		mounted() {
			this.getTree();
		},

		methods: {
			getTree() {
				axios.get(this.url)
					.then(response => this.setTree(response));
			},

			setTree(response) {
				this.tree = response.data;
			}
		},

		computed: {
			url() {
				return window.user.is_admin ? "/api/admin/tree" :"/api/user/" + window.user.id + "/tree";
			}
		}		
	}
</script>
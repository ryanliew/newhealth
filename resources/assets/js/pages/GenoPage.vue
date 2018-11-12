<template>
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
</template>

<script>
	import GenoRow from "../components/GenoRow.vue";

	export default {
		name: "geno-page",

		props: ['viewingUser', 'tree', 'loading'],

		components: { GenoRow },

		data() {
			return {
				user: '',
			};
		},

		mounted() {
			console.log("tree: " + this.tree);
			this.user = this.viewingUser ? this.viewingUser : window.user;
		},
	}
</script>
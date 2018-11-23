<template>
	<div class="row">
		<div class="col-12 m-auto">
			<div class="tree-responsive m-auto p-2">
				<div class="tree">
					<GenoRow :users='tree' :isPurchase='isPurchase' @clicked="clicked">
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

		props: ['viewingUser', 'tree', 'loading', 'isPurchase'],

		components: { GenoRow },

		data() {
			return {
				user: '',
			};
		},

		mounted() {
			this.user = this.viewingUser ? this.viewingUser : window.user;
			this.viewingUser ? this.getTree() : null;
		},

		methods: {
			getTree() {
				axios.get(this.url)
					.then(response => this.setTree(response));
			},

			setTree(response) {
				this.tree = response.data;
				this.loading = false;
			},

			clicked(e) {
				// console.log('page clicked');
				this.$emit("clicked", {data: e.data});
			}
		},

		computed: {
			url() {
				if(this.viewingUser)
					return "/api/user/" + this.user.id + "/tree"
			}
		}		
	}
</script>
<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<table-view ref="packages"
						:fields="fields"
						:title="$options.filters.trans('package.packages')"
						:url="url"
						v-if="!isEditing"
						:dateFilterable="false"
						dateFilterKey=""
						addNew="package.make_new_package"
						searchables="">
			</table-view>

			<package :selectedPackage="selectedPackage" v-if="isEditing" @back="back"></package>
		</transition>
	</div>
</template>

<script>
	import Package from "../components/Package.vue";
	export default {
		props: [''],

		components: { Package },

		data() {
			return {
				fields: [
					{ name: 'tree_count', title: this.tableTitle('package.tree_count'), sortField: 'tree_count', callback: 'treecount'},					
					{ name: 'price', title: this.tableTitle('package.price'), sortField: 'price', callback: 'currency'},
					{ name: 'price_std', title: this.tableTitle('package.price_std'), sortField: 'price_std', callback: 'currency_std'},
					{ name: 'price_promotion', title: this.tableTitle('package.price_promotion'), sortField: 'price_promotion', callback: 'currency'},
					{ name: 'price_std_promotion', title: this.tableTitle('package.price_std_promotion'), sortField: 'price_std_promotion', callback: 'currency_std'},
					{ name: '__component:packages-actions', title: this.tableTitle('table.actions')}
				],
				isEditing: false,
				selectedPackage: ''
			};
		},

		mounted() {
			window.events.$on('add_new', function(){
				this.addNewPackage();
			}.bind(this));

			this.$events.on('view', data => this.edit(data));			
		},

		beforeDestroy() {
			this.$events.off('view');
			window.events.$off('add_new');
		},

		methods: {
			addNewPackage() {
				this.isEditing = true;
			},

			back() {
				this.isEditing = false;
				this.selectedPackage = '';
			},

			tableTitle(value) {
				return this.$options.filters.trans(value);
			},

			edit(purchase) {
				this.selectedPackage = purchase;
				this.isEditing = true;
			},
		},

		computed: {
			url() {
				return "/api/admin/packages";
			}
		}	
	}
</script>
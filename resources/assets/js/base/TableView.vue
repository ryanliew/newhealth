<template>
	<div class="card">
		<h5 class="card-header">
			{{ title | trans }}
		</h5>
		
		
		<div class="card-body">
			<loader v-if="loading"></loader>
			<vuetable-filter-bar :searchables="searchables" :dateFilterable="dateFilterable" :addNew="addNew"></vuetable-filter-bar>	
			<vuetable ref="vuetable" 
					:api-url="url"
		    		:fields="fields"
		    		:httpFetch="customFetch"
		    		:multi-sort="true"
		    		:css="css"
		    		:append-params="params"
		    		:detail-row-component="detail"
		    		pagination-path=""
		    		@vuetable:pagination-data="onPaginationData"
		    		@vuetable:loaded="onLoaded"
		    		@vuetable-refresh="refreshTable"
		    		@vuetable:cell-clicked="onCellClicked"
		    		:no-data-template="empty"
		    		v-show="!loading">	
		    </vuetable>
		    <div class="row align-items-center">
		    	<div class="col-sm">
				    <vuetable-pagination-info ref="paginationInfo"
		    		:info-template="$options.filters.trans('pagination.info')">
					</vuetable-pagination-info>
				</div>
				<div class="col-sm-auto">
				    <vuetable-pagination 
				    	ref="pagination"
				    	@vuetable-pagination:change-page="onChangePage">
				    </vuetable-pagination>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Vuetable from 'vuetable-2/src/components/Vuetable';
	import VuetablePagination from './VuetablepaginationBulma';
	import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
	import VuetableFilterBar from './VuetableFilterBar';
	import moment from 'moment';
	import Loader from './Loader';

	export default {
		props: ['user', 'fields', 'url', 'searchables', 'detail', 'empty', 'dateFilterable', 'dateFilterKey', 'title', 'addNew'],

		components: { Vuetable, VuetablePagination, VuetablePaginationInfo, VuetableFilterBar, Loader },

		data() {
			return {
				css: {
					tableClass: 'table responsive',
					ascendingIcon: 'fa fa-caret-up',
					descendingIcon: 'fa fa-caret-down',
				},
				params: {},
				loading: true
			};
		},

		mounted() {
			this.$events.$on('filter-set', eventData => this.onFilterSet(eventData));
			this.$events.$on('filter-reset', e => this.onFilterReset());
		},

		methods: {
			customFetch(apiUrl, httpOptions) {
				return axios.get(apiUrl, httpOptions);
			},

			refreshTable() {
				this.loading = true;
				setTimeout(function(){
					this.$refs.vuetable.refresh();
				}.bind(this), 1000);
			},

			onPaginationData(paginationData) {
				this.$refs.pagination.setPaginationData(paginationData);
				this.$refs.paginationInfo.setPaginationData(paginationData);
			},

			onChangePage(page) {
				this.$refs.vuetable.changePage(page);
			},

			onFilterSet(filters) {
				this.loading = true;
				this.params = {
					'filter' : filters.text,
					'searchables' : this.searchables
				}

				if(filters.start && filters.end)
				{
					this.params.start = filters.start;
					this.params.end = filters.end;
					this.params.dateFilterKey = this.dateFilterKey;
				}

				Vue.nextTick( () => this.$refs.vuetable.refresh())
			},

			onFilterReset() {
				this.params = {}
				Vue.nextTick( () => this.$refs.vuetable.refresh())
			},

			onLoaded() {
				this.loading = false;
			},

			onCellClicked(data, field, event) {
				// if(this.detail == 'LotDetailRow'
				// 	|| this.detail == 'ProductDetailRow')
				// 	this.$refs.vuetable.toggleDetailRow(data.id);
			},

			purchaseStatusLabel(value) {
				return this.$options.filters.formatPurchaseStatus(value);
			},

			image(value) {
				return '<figure class="image is-48x48"><img src="'+ value +'"></figure>';
			},

			date(value){
				return this.$options.filters.date(value);
			},

			currency(value){
				return this.$options.filters.currency(value);
			},

			currency_std(value){
				return this.$options.filters.currency_std(value);
			},

			treecount(value){
				return value + " " + this.$options.filters.trans_choice('auth.tree', value);
			},

			customer(value){
				return value ? value : "N/A";
			},


		}
	}
</script>
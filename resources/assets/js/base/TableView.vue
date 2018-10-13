<template>
	<div class="card">
		<h5 class="card-header">
			<div class="row align-items-center">
				<div class="col">
					{{ title | trans }}
				</div>
				<div class="col-auto" v-if="canExportPDF || canExportExcel">
					<div class="dropdown">
						<button class="btn btn-primary dropdown-toggle" type="button" id="exportMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    	{{ "table.export" | trans }}
						</button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportMenuButton">
							<a class="dropdown-item" target="_blank" :href="exportPDFUrl" v-if="canExportPDF"><i class="fa fa-file-pdf-o"></i> {{ 'table.export_pdf' | trans }}</a>
						    <a class="dropdown-item" target="_blank" :href="exportExcelUrl" v-if="canExportExcel"><i class="fa fa-file-excel-o"></i> {{ 'table.export_excel' | trans }}</a>
						</div>
					</div>
				</div>
				<div class="col-auto" v-if="hasBack">
					<button class="btn btn-primary" @click="back"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
				</div>
			</div>
		</h5>
		
		
		<div class="card-body">
			<loader v-if="loading"></loader>
			<vuetable-filter-bar :searchables="searchables" :dateFilterable="dateFilterable" :monthFilterable="monthFilterable" :addNew="addNew" :defaultFilterMonth="filterMonth" :defaultCurrentMonth="defaultCurrentMonth"></vuetable-filter-bar>
			<div class="table-responsive">
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
			</div>
		    <div class="row align-items-center vue-pagination-row">
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
		props: ['user', 'fields', 'url', 'searchables', 'detail', 'empty', 'dateFilterable', 'dateFilterKey', 'title', 'addNew', 'monthFilterable', 'monthFilterKey', 'hasBack', 'filterMonth', 'canExportPDF', 'canExportExcel', 'exportUrl', 'defaultCurrentMonth'],

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

		destroyed: function () {
			this.$events.off('filter-set');
			this.$events.off('filter-reset');
		},

		mounted() {
			this.mountEvents();
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

			mountEvents() {
				this.$events.$on('filter-set', eventData => this.onFilterSet(eventData));
				this.$events.$on('filter-reset', e => this.onFilterReset());
			},

			back() {
				this.$emit('back');
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
				console.log("searchables: " + filters.text);
				console.dir(this.searchables);

				if(filters.start && filters.end)
				{
					this.params.start = filters.start;
					this.params.end = filters.end;
					this.params.dateFilterKey = this.dateFilterKey;
				}

				if(filters.month)
				{
					this.params.month = filters.month;
					this.params.monthFilterKey =this.monthFilterKey;
				}
				console.log("complete event");
				Vue.nextTick( () => this.$refs.vuetable.refresh())
			},

			getMonth() {
				return this.params.month;
			},

			onFilterReset() {
				this.params = {}
				Vue.nextTick( () => this.$refs.vuetable.refresh())

				console.log("Filter reset");
			},

			onLoaded() {
				this.loading = false;
				window.events.$emit('table-loaded');
			},

			onCellClicked(data, field, event) {
				// if(this.detail == 'LotDetailRow'
				// 	|| this.detail == 'ProductDetailRow')
				// 	this.$refs.vuetable.toggleDetailRow(data.id);
			},

			userStatusLabel(value) {
				console.log("value:: " + value);
				return this.$options.filters.formatUserStatus(value);
			},
			
			purchaseStatusLabel(value) {
				return this.$options.filters.formatPurchaseStatus(value);
			},

			transactionPayoutStatusLabel(value) {
				return this.$options.filters.formatPayoutStatus(value);
			},

			commisionTypeLabel(value) {
				return this.$options.filters.trans('transaction.' + value);
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
		},

		computed: {
			exportPDFUrl() {
				let url = this.exportUrl;
				if(this.params.start)
					url += "&start=" + this.params.start + "&end=" + this.params.end;

				if(this.params.month)
					url += "&month=" + this.params.month;

				if(this.params.filter) 
					url += "&filter=" + this.params.filter + "&searchables=" + this.searchables;

				return url + "&type=pdf";
			},

			exportExcelUrl() {
				let url = this.exportUrl;
				if(this.params.start)
					url += "&start=" + this.params.start + "&end=" + this.params.end;

				if(this.params.month)
					url += "&month=" + this.params.month;

				if(this.params.filter) 
					url += "&filter=" + this.params.filter + "&searchables=" + this.searchables;

				return url + "&type=excel";
			}
		}
	}
</script>
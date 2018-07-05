<template>
    <div class="row mb-3 align-items-center">
        <div class="col"></div>
        <div class="col-sm-auto" v-if="dateFilterable">
            <div class="field">
                <input class="form-control" type="date" v-model="filterDateStart">
            </div>
        </div>
        <div class="col-sm-auto" v-if="dateFilterable">
            -
        </div>
        <div class="col-sm-auto" v-if="dateFilterable">
            <div class="field">
                <input class="form-control" type="date" v-model="filterDateEnd">
            </div>
        </div>
        <div class="col-sm-auto" v-if="dateFilterable">
            <button class="btn btn-primary" @click="doFilter">{{ 'table.filter_by_date' | trans }}</button>
        </div>
        <div class="col-sm-auto" v-if="monthFilterable">
            <div class="input-group">
                <input class="form-control" type="date" :placeholder="$options.filters.trans('table.filter_by_month')" v-model="filterMonth" @keyup.enter="doFilter">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button" @click="doFilter">
                        {{ 'table.filter_by_month' | trans }}
                    </button>
                </div>
            </div>
        </div>
        <div class="col-sm-auto" v-if="searchables">
            <div class="input-group">
                <input class="form-control" type="text" :placeholder="$options.filters.trans('table.search')" v-model="filterText" @keyup.enter="doFilter">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                    <button class="btn btn-info" @click="resetFilter">{{ 'table.reset' | trans }}</button>
                </div>
            </div>
        </div>
        <div class="col-sm-auto" v-if="addNew">
            <button class="btn btn-primary" @click="doAddNew"><i class="fa fa-plus"></i> {{ addNew | trans }}</button>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: ['dateFilterable', 'addNew', 'searchables', 'monthFilterable', 'defaultFilterMonth'],

        data () {
            return {
                filterText: '',
                filterDateStart: '',
                filterDateEnd: '',
                filterMonth: ''
            }
        },

        mounted() {
            this.filterMonth = this.defaultFilterMonth ? this.defaultFilterMonth : moment().format("YYYY-MM-DD");
            this.filterDateStart = this.defaultFilterMonth ? moment(this.defaultFilterMonth).startOf('month').format("YYYY-MM-DD") : '';
            this.filterDateEnd = this.defaultFilterMonth ? moment(this.defaultFilterMonth).endOf('month').format("YYYY-MM-DD") : '';

            Vue.nextTick( () => this.doFilter() );
        },

        methods: {
            doFilter () {
                this.$events.fire('filter-set', { month: this.filterMonth, text: this.filterText, start: this.filterDateStart, end: this.filterDateEnd });
            },
            resetFilter () {
                this.filterText = '';
                this.filterDateStart = '';
                this.filterDateEnd = '';
                this.$events.fire('filter-reset');
            },

            doAddNew() {
                window.events.$emit('add_new');
            }
        }
    }
</script>
<template>
    <div class="row mb-3 align-items-center">
        <div class="col"></div>
        <div class="col-sm-auto" v-if="dateFilterable">
            <div class="field">
                <input class="form-control" type="date" placeholder="Start date" v-model="filterDateStart">
            </div>
        </div>
        <div class="col-sm-auto" v-if="dateFilterable">
            -
        </div>
        <div class="col-sm-auto" v-if="dateFilterable">
            <div class="field">
                <input class="form-control" type="date" placeholder="End date" v-model="filterDateEnd">
            </div>
        </div>
        <div class="col-sm-auto" v-if="dateFilterable">
            <button class="btn btn-primary" @click="doFilter">Filter by date</button>
        </div>
        <div class="col-sm-auto">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search" v-model="filterText" @keyup.enter="doFilter">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                    <button class="btn btn-info" @click="resetFilter">Reset</button>
                </div>
            </div>
        </div>
        <div class="col-sm-auto" v-if="addNew">
            <button class="btn btn-primary" @click="doAddNew"><i class="fa fa-plus"></i> {{ addNew | trans }}</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['dateFilterable', 'addNew'],

        data () {
            return {
                filterText: '',
                filterDateStart: '',
                filterDateEnd: ''
            }
        },

        methods: {
            doFilter () {
                this.$events.fire('filter-set', { text: this.filterText, start: this.filterDateStart, end: this.filterDateEnd });
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
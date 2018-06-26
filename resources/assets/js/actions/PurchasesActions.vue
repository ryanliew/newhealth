<template>
    <div>
        <div class="btn-group" role="group" aria-label="Purchase actions">
            <button type="button" class="btn btn-primary" @click="itemAction('view', rowData, rowIndex)">
                <span class="icon">
                    <i class="fa fa-eye"></i>
                </span>
            </button>
            <button type="button" v-if="rowData.status == 'complete'" @click="itemAction('receipt', rowData, rowIndex)" data-toggle="tooltip" data-placement="bottom" :title="$options.filters.trans('purchase.download_receipt')" class="btn btn-info">
                <span class="icon">
                    <i class="fa fa-download"></i>
                </span>
            </button>
            <button type="button" class="btn btn-info" v-if="!rowData.payment && !user.is_admin" @click="itemAction('view', rowData, rowIndex)">
                <span class="icon">
                    <i class="fa fa-money"></i>
                </span>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        rowData: {
            type: Object,
            required: true
        },
        rowIndex: {
            type: Number,
        }
    },

    data() {
        return {
            user: ''
        };
    },

    mounted() {
        this.user = window.user;
    },

    updated() {
        $('[data-toggle="tooltip"]').tooltip();
    },

    methods: {
        itemAction(action, data, index){
            this.$events.fire(action, data);            
        }
    }
  }
</script>
<template>
    <div>
        <div class="btn-group" role="group" aria-label="Payout actions">
            <button type="button" class="btn btn-primary" @click="itemAction('view', rowData, rowIndex)" data-toggle="tooltip" data-placement="bottom" title="View transaction details">
                <span class="icon">
                    <i class="fa fa-eye"></i>
                </span>
            </button>
        </div>
        <div class="btn-group" role="group" aria-label="Payout actions" v-if="rowData.payout_status !== 'paid'">
            <button type="button" class="btn btn-success" @click="itemAction('pay', rowData, rowIndex)" data-toggle="tooltip" data-placement="bottom" title="Mark as paid">
                <span class="icon">
                    <i class="fa fa-money"></i>
                </span>
            </button>
        </div>
        <div class="btn-group" role="group" aria-label="Payout actions">
            <button type="button" class="btn btn-info" @click="itemAction('export', rowData, rowIndex)" data-toggle="tooltip" data-placement="bottom" title="Download commission report">
                <span class="icon">
                    <i class="fa fa-file-pdf-o"></i>
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
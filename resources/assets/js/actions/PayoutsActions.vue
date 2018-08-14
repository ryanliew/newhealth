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
            <button type="button" class="btn btn-success" @click="isConfirming = true" data-toggle="tooltip" data-placement="bottom" title="Mark as paid">
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



        <confirmation 
            message="confirmation.confirm_change_to_paid" 
            :loading="loading" 
            @confirmed="pay"
            @canceled="isConfirming = false"
            v-if="isConfirming">

            <div class="mt-3">
                <text-input v-model="remark" 
                    :defaultValue="remark"
                    :required="false"
                    type="text"
                    label="Remark"
                    name="remark"
                    :editable="true"
                    :focus="true"
                    :hideLabel="false">
                </text-input>
            </div>

        </confirmation>
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
            user: '',
            isConfirming: false,
            remark: '',
            loading: false
        };
    },

    mounted() {
        this.user = window.user;
        this.$events.on('loading-complete', data => this.setLoadingComplete(data));
    },

    updated() {
        $('[data-toggle="tooltip"]').tooltip();
    },

    methods: {
        itemAction(action, data, index){
            this.$events.fire(action, data);            
        },

        pay() {
            this.loading = true;
            this.itemAction('pay', {payout: this.rowData, remark: this.remark}, this.rowIndex);
        },

        setLoadingComplete(data) {
            this.isConfirming = false;
            this.loading = false;
        }
    }
  }
</script>
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
            <button type="button" class="btn btn-danger" v-if="user.is_admin" @click="isConfirming = true">
                <span class="icon">
                    <i class="fa fa-times"></i>
                </span>
            </button>
        </div>




        <confirmation 
            message="confirmation.delete_purchase" 
            :loading="loading" 
            @confirmed="proceedDelete"
            @canceled="isConfirming = false"
            v-if="isConfirming">
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
            loading: false
        };
    },

    mounted() {
        this.user = window.user;
        this.$events.on('loading', data => this.setLoading(data));
        this.$events.on('loading-complete', data => this.setLoadingComplete(data));
    },

    updated() {
        $('[data-toggle="tooltip"]').tooltip();
    },

    methods: {
        itemAction(action, data, index){
            this.$events.fire(action, data);            
        },

        proceedDelete() {
            this.itemAction('delete', this.rowData, this.rowIndex);
        },

        setLoading(data) {
            this.loading = data == this.rowData.id;
        },

        setLoadingComplete(data) {
            this.loading = false;
            this.isConfirming = false;
        },
    }
  }
</script>
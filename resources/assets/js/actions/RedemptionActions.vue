<template>
    <div>
        <div class="btn-group" role="group" aria-label="Purchase actions">
            <button type="button" class="btn btn-primary" @click="itemAction('viewRedemption', rowData, rowIndex)" data-toggle="tooltip" data-placement="bottom" :title="$options.filters.trans('redemption.redemption_detail')">
                <span class="icon">
                    <i class="fa fa-eye"></i>
                </span>
            </button>
            <button type="button" v-if="rowData.status == 'pending' && user.is_admin" @click="itemAction('approveRedemption', rowData, rowIndex)" data-toggle="tooltip" data-placement="bottom" :title="$options.filters.trans('redemption.approve')" class="btn btn-success">
                <span class="icon">
                    <i class="fa fa-check"></i>
                </span>
            </button>
            <button type="button" class="btn btn-danger" v-if="rowData.status == 'pending' && !user.is_admin" @click="isConfirming = true" data-toggle="tooltip" data-placement="bottom" :title="$options.filters.trans('redemption.canceled')">
                <span class="icon">
                    <i class="fa fa-times"></i>
                </span>
            </button>
        </div>

        <confirmation 
            message="confirmation.delete_purchase" 
            :loading="loading" 
            @confirmed="proceedCancel"
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

    beforeDestroy() {
        this.$events.off('loading');
        this.$events.off('loading-complete');
    },

    updated() {
        $('[data-toggle="tooltip"]').tooltip();
    },

    methods: {
        itemAction(action, data, index){
            this.$events.fire(action, data);            
        },

        proceedCancel() {
            this.itemAction('cancel', this.rowData, this.rowIndex);
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
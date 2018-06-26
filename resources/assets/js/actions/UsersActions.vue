<template>
    <div>
        <div class="btn-group" role="group" aria-label="Purchase actions">
            <button data-toggle="tooltip" data-placement="bottom" title="View as user" type="button" class="btn btn-primary" @click="itemAction('viewUser', rowData, rowIndex)">
                <span class="icon">
                    <i class="fa fa-eye"></i>
                </span>
            </button>
            <button type="button" class="btn btn-warning" :disabled="loadingLeft" v-on:click="relayAction('previous', rowData, rowIndex)" data-toggle="tooltip" data-placement="bottom" title="Set to previous legal step" v-if="!(rowData.id_status == 'pending' || rowData.id_status == 'pending_verification' || rowData.id_status == 'rejected' || rowData.id_status == 'verified') && rowData.has_verified_sale">
                <span class="icon" v-html="legalLeftButtonContent">
                    <i class="fa fa-arrow-left"></i>
                </span>
            </button>
            <button type="button" class="btn btn-warning" :disabled="loadingRight" v-on:click="relayAction('next', rowData, rowIndex)" data-toggle="tooltip" data-placement="bottom" title="Set to next legal step" v-if="!(rowData.id_status == 'pending' || rowData.id_status == 'pending_verification' || rowData.id_status == 'rejected' || rowData.id_status == 'complete') && rowData.has_verified_sale">
                <span class="icon" v-html="legalRightButtonContent">
                    <i class="fa fa-arrow-right"></i>
                </span>
            </button>
            <button v-if="user.is_admin && (rowData.id_status == 'pending' || rowData.id_status == 'rejected')" type="button" class="btn btn-info btn-lg" data-toggle="tooltip" data-placement="bottom" title="Send verification reminder" @click="isConfirming = true">
                <span class="icon" v-html="sendButtonContent">
                    <i class="fa fa-send-o"></i>
                </span>
            </button>
        </div>

        <confirmation 
            message="confirmation.remind_user_identification" 
            :loading="loading" 
            @confirmed="remindUser"
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
            user: window.user,
            isConfirming: false,
            loading: false,
            loadingLeft: false,
            loadingRight: false
        };
    },

    mounted() {
        this.user = window.user;
        this.$events.on('loading', data => this.setLoading(data));
        this.$events.on('loading-prev', data => this.setLoadingLeft(data));
        this.$events.on('loading-next', data => this.setLoadingRight(data));
        this.$events.on('loading-complete', data => this.setLoadingComplete(data));
    },

    updated() {
        $('[data-toggle="tooltip"]').tooltip();
    },

    methods: {
        itemAction(action, data, index){
            this.$events.fire(action, data);            
        },

        relayAction(action, data, index){
            this.loadingLeft = true;
            this.loadingRight = true;
            this.itemAction(action, data, index);
        },

        setLoading(data) {
            this.loading = data == this.rowData.id;
        },

        setLoadingLeft(data) {
            this.loadingLeft = data == this.rowData.id;
        },

        setLoadingRight(data) {
            this.loadingRight = data == this.rowData.id;
        },

        setLoadingComplete(data) {
            this.loading = false;
            this.loadingLeft = false;
            this.loadingRight = false;
            this.isConfirming = false;
        },

        remindUser() {
            this.itemAction('remind', this.rowData, this.rowIndex);
        }
    },

    computed: {
        sendButtonContent() {
            return this.loading ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : '<i class="fa fa-send-o"></i>';
        },

        legalLeftButtonContent() {
            return this.loadingLeft ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : '<i class="fa fa-arrow-left"></i>';
        },

        legalRightButtonContent() {
            return this.loadingRight ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : '<i class="fa fa-arrow-right"></i>';
        },
    }
  }
</script>
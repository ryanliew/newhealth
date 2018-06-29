<template>
    <div>
        <div class="btn-group" role="group" aria-label="Purchase actions">
            <button data-toggle="tooltip" data-placement="bottom" title="View as user" type="button" class="btn btn-primary" @click="itemAction('viewUser', rowData, rowIndex)">
                <span class="icon">
                    <i class="fa fa-eye"></i>
                </span>
            </button>
            <button type="button" class="btn btn-warning" @click="itemAction('legal', rowData, rowIndex)" data-toggle="tooltip" data-placement="bottom" title="Change legal status" v-if="!(rowData.id_status == 'pending' || rowData.id_status == 'pending_verification' || rowData.id_status == 'rejected') && rowData.has_verified_sale">
                <span class="icon">
                    <i class="fa fa-book"></i>
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
            isReminding: false
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

        setLoading(data) {
            this.loading = data == this.rowData.id;
        },

        setLoadingComplete(data) {
            this.loading = false;
            this.isReminding = false;
            this.isConfirming = false;
        },

        remindUser() {
            if(!this.isReminding) {
                this.itemAction('remind', this.rowData, this.rowIndex);
                this.isReminding = true;
            }
        }
    },

    computed: {
        sendButtonContent() {
            return this.loading ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : '<i class="fa fa-send-o"></i>';
        }
    }
  }
</script>
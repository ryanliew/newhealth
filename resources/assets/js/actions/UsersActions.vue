<template>
    <div>
        <div class="btn-group" role="group" aria-label="Purchase actions">
            <button data-toggle="tooltip" data-placement="bottom" title="View as user" type="button" class="btn btn-primary" @click="itemAction('viewUser', rowData, rowIndex)">
                <span class="icon">
                    <i class="fa fa-eye"></i>
                </span>
            </button>
            <button v-if="rowData.user_status != 'pending'" type="button" class="btn btn-warning" @click="itemAction('lock', rowData, rowIndex)"  :disabled="lock_loading" data-toggle="tooltip" data-placement="bottom" title="Lock/Unlock user">
                <span class="icon" v-html="lockButtonContent">
                    <i class="fa fa-lock"></i>
                </span>
            </button>
            <button type="button" class="btn btn-info" @click="itemAction('legal', rowData, rowIndex)" data-toggle="tooltip" data-placement="bottom" title="Change legal status" v-if="!(rowData.user_status == 'pending' || rowData.user_status == 'pending_verification' || rowData.id_status == 'rejected') && rowData.has_verified_sale">
                <span class="icon">
                    <i class="fa fa-book"></i>
                </span>
            </button>
            <!-- <button v-if="user.is_admin && (rowData.id_status == 'pending' || rowData.id_status == 'rejected')" type="button" class="btn btn-info btn-lg" data-toggle="tooltip" data-placement="bottom" title="Send verification reminder" @click="isConfirming = true">
                <span class="icon" v-html="sendButtonContent">
                    <i class="fa fa-send-o"></i>
                </span>
            </button> -->
            <button v-if="user.is_admin && (rowData.user_status == 'pending')" type="button" class="btn btn-success" :disabled="approve_loading" data-toggle="tooltip" data-placement="bottom" title="Approve user" @click="itemAction('approveUser', rowData, rowIndex)">
                <span class="icon" v-html="approveButtonContent">
                    <i class="fa fa-check"></i>
                </span>
            </button>
            <button v-if="user.is_admin && (rowData.user_status == 'pending')" type="button" class="btn btn-danger" :disabled="reject_loading" data-toggle="tooltip" data-placement="bottom" title="Reject user" @click="itemAction('rejectUser', rowData, rowIndex)">
                <span class="icon" v-html="rejectButtonContent">
                    <i class="fa fa-times"></i>
                </span>
            </button>
            <button v-if="rowData.user_status != 'pending'" type="button" class="btn btn-secondary" @click="isDeleteConfirming = true" :disabled="delete_loading" data-toggle="tooltip" data-placement="bottom" title="Delete user">
                <span class="icon" v-html="deleteButtonContent">
                    <i class="fa fa-trash-o"></i>
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

        <confirmation 
            message="confirmation.delete_user" 
            :loading="delete_loading" 
            @confirmed="deleteUser"
            @canceled="isDeleteConfirming = false"
            v-if="isDeleteConfirming">
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
            isReminding: false,
            isDeleting: false,
            isDeleteConfirming: false,
            lock_loading: false,
            approve_loading: false,
            reject_loading: false,
            delete_loading: false,
        };
    },

    mounted() {
        this.user = window.user;
        this.$events.on('loading', data => this.setLoading(data));
        this.$events.on('loading-lock', data => this.setLoadingLock(data));
        this.$events.on('loading-approve', data => this.setApproveLock(data));
        this.$events.on('loading-reject', data => this.setRejecctLock(data));
        this.$events.on('loading-delete', data => this.setLoadingDelete(data));
        this.$events.on('loading-complete', data => this.setLoadingComplete(data));
    },

    beforeDestroy() {
        this.$events.off('loading');
        this.$events.off('loading-lock');
        this.$events.off('loading-approve');
        this.$events.off('loading-reject');
        this.$events.off('loading-delete');
        this.$events.off('loading-complete');
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

        setLoadingLock(data) {
            this.lock_loading = data == this.rowData.id;
        },

        setApproveLock(data) {
            this.approve_loading = data == this.rowData.id;
        },

        setRejecctLock(data) {
            this.reject_loading = data == this.rowData.id;
        },

        setLoadingDelete(data) {
            this.delete_loading = data == this.rowData.id;
        },

        setLoadingComplete(data) {
            this.loading = false;
            this.lock_loading = false;
            this.approve_loading = false;
            this.reject_loading = false;
            this.delete_loading = false;
            this.isReminding = false;
            this.isDeleting = false;
            this.isConfirming = false;
            this.isDeleteConfirming = false;
        },

        remindUser() {
            if(!this.isReminding) {
                this.itemAction('remind', this.rowData, this.rowIndex);
                this.isReminding = true;
            }
        },

        deleteUser() {
            if(!this.isDeleting) {
                this.itemAction('delete', this.rowData, this.rowIndex);
                this.isDeleting = true;
            }
        }
    },

    computed: {
        sendButtonContent() {
            return this.loading ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : '<i class="fa fa-send-o"></i>';
        },

        lockButtonContent() {
            let initial = this.rowData.is_locked ? '<i class="fa fa-unlock"></i>' : '<i class="fa fa-lock"></i>';

            return this.lock_loading ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : initial;
        },

        approveButtonContent() {
            return this.approve_loading ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : '<i class="fa fa-check"></i>';
        }, 

        rejectButtonContent() {
            return this.reject_loading ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : "<i class='fa fa-times'></i>";
        },

        deleteButtonContent() {
            return this.delete_loading ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : "<i class='fa fa-trash-o'></i>";
        }
    }
  }
</script>
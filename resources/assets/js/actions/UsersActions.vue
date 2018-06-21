<template>
    <div>
        <div class="btn-group" role="group" aria-label="Purchase actions">
            <button type="button" class="btn btn-primary" @click="itemAction('view', rowData, rowIndex)">
                <span class="icon">
                    <i class="fa fa-eye"></i>
                </span>
            </button>
            <button v-if="user.is_admin && rowData.id_status == 'pending'" type="button" class="btn btn-info btn-lg" data-toggle="tooltip" data-placement="bottom" title="Send verification reminder" @click="isConfirming = true">
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

        setLoading(data) {
            this.loading = data == this.rowData.id;
        },

        setLoadingComplete(data) {
            this.loading = false;
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
    }
  }
</script>
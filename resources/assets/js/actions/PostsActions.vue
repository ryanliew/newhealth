<template>
    <div>
        <div class="btn-group" role="group" aria-label="Purchase actions">
            <button type="button" class="btn btn-primary"  data-toggle="tooltip" data-placement="bottom" title="View"  @click="itemAction('view', rowData, rowIndex)">
                <span class="icon">
                    <i class="fa fa-eye"></i>
                </span>
            </button>
            <button v-if="user.is_admin" type="button" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Edit news" @click="itemAction('edit', rowData, rowIndex)">
                <span class="icon">
                    <i class="fa fa-edit"></i>
                </span>
            </button>
            <button v-if="user.is_admin" type="button" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Blast this news to admins" @click="itemAction('sendAdmin', rowData, rowIndex)">
                <span class="icon" v-html="sendAdminButtonContent">
                    <i class="fa fa-group"></i>
                </span>
            </button>
            <button v-if="user.is_admin" type="button" class="btn btn-lg btn-danger" data-toggle="tooltip" data-placement="bottom" title="Blast this news to growers" @click="isConfirming = true">
                <span class="icon" v-html="sendButtonContent">
                    <i class="fa fa-send-o"></i>
                </span>
            </button>
        </div>

        <confirmation 
            message="confirmation.blast_newsletter_grower" 
            :loading="loading" 
            @confirmed="blastToUser"
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
            loading: false,
            loadingAdmin: false,
            isConfirming: false
        };
    },

    mounted() {
        this.user = window.user;
        this.$events.on('loading', data => this.setLoading(data));
        this.$events.on('loading-admin', data => this.setLoadingAdmin(data));
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

        setLoadingAdmin(data) {
            this.loadingAdmin = data == this.rowData.id;
        },

        setLoadingComplete(data) {
            this.loading = false;
            this.loadingAdmin = false;
            this.isConfirming = false;
        },

        blastToUser() {
            this.itemAction('send', this.rowData, this.rowIndex);
        }
    },

    computed: {
        sendButtonContent() {
            return this.loading ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : '<i class="fa fa-send-o"></i>';
        },

        sendAdminButtonContent() {
            return this.loadingAdmin ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : '<i class="fa fa-group"></i>';
        }
    }
  }
</script>
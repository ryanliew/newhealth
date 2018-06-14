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
            <button v-if="user.is_admin" type="button" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Blast this news to growers" @click="itemAction('send', rowData, rowIndex)">
                <span class="icon" v-html="sendButtonContent">
                    <i class="fa fa-send-o"></i>
                </span>
            </button>
            <button v-if="user.is_admin" type="button" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Blast this news to admins" @click="itemAction('sendAdmin', rowData, rowIndex)">
                <span class="icon" v-html="sendAdminButtonContent">
                    <i class="fa fa-group"></i>
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
            user: '',
            loading: false
        };
    },

    mounted() {
        this.user = window.user;
        this.$events.on('loading', data => this.loading = true);
        this.$events.on('loading-complete', data => this.loading = false);

    },

    updated() {
        $('[data-toggle="tooltip"]').tooltip();
    },

    methods: {
        itemAction(action, data, index){
            this.$events.fire(action, data);            
        }
    },

    computed: {
        sendButtonContent() {
            return this.loading ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : '<i class="fa fa-send-o"></i>';
        },

        sendAdminButtonContent() {
            return this.loading ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : '<i class="fa fa-group"></i>';
        }
    }
  }
</script>
<template>
    <div>
        <div class="btn-group" role="group" aria-label="Purchase actions">
            <button type="button" class="btn btn-primary" @click="itemAction('view', rowData, rowIndex)">
                <span class="icon">
                    <i class="fa fa-eye"></i>
                </span>
            </button>
            <button type="button" class="btn btn-info" v-if="!rowData.payment && !user.is_admin" @click="itemAction('view', rowData, rowIndex)">
                <span class="icon">
                    <i class="fa fa-money"></i>
                </span>
            </button>
            <button type="button" class="btn btn-success" v-if="user.is_admin" @click="itemAction('verify', rowData, rowIndex)">
                <span class="icon">
                    <i class="fa fa-check"></i>
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

    methods: {
        itemAction(action, data, index){
            this.$events.fire(action, data);            
        }
    }
  }
</script>
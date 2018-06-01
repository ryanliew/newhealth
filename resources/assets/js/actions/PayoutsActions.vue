<template>
    <div>
        <div class="btn-group" role="group" aria-label="Payout actions">
            <button type="button" class="btn btn-primary" @click="itemAction('view', rowData, rowIndex)">
                <span class="icon">
                    <i class="fa fa-eye"></i>
                </span>
            </button>
        </div>
        <div class="btn-group" role="group" aria-label="Payout actions" v-if="rowData.payout_status !== 'paid'">
            <button type="button" class="btn btn-success" @click="itemAction('pay', rowData, rowIndex)">
                <span class="icon">
                    <i class="fa fa-money"></i>
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
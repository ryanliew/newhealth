<template>
    <div>
        <span v-if="rowData.is_rmb">{{ rowData.total_price_rmb | currency_rmb }}</span>
        <span v-else>{{ rowData.total_price | currency }}</span>
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
            
        };
    },
  }
</script>
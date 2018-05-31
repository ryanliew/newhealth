<template>
    <div>
        <span v-if="rowData.is_std">{{ rowData.total_price_std | currency_std }}</span>
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
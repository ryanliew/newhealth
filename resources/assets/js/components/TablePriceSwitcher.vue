<template>
    <div>

        <div v-if="!rowData.type">
            <span v-if="rowData.is_std">{{ rowData.total_price_std | currency_std }}</span>
            <span v-else-if="rowData.total_selling_price">{{ rowData.total_selling_price | currency }}</span>
            <span v-else>{{ rowData.total_price | currency }}</span>
        </div>
        <div v-if="rowData.type">
            <span v-if="rowData.is_std">{{ rowData.amount_std | currency_std }}</span>
            <span v-else-if="rowData.total_selling_price">{{ rowData.total_selling_price | currency }}</span>
            <span v-else>{{ rowData.amount | currency }}</span>
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
            
        };
    },
  }
</script>
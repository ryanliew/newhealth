<template>
    <div>
        <span>{{ 'transaction.' + rowData.description | trans({ name: rowData.target.referral_code}) }}</span>
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
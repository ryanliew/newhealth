<template>
    <div>
        <div v-if="lang.locale == 'en'">
            <span v-text="rowData.title"></span>
        </div>
        <div v-else>
            <span v-text="rowData.title_zh"></span>
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

    mounted() {
        console.log(lang.locale);
    },

    data() {
        return {
            lang: lang
        };
    },
  }
</script>
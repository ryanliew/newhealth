<template>
    <nav aria-label="Table navigation" class="pagination" role="navigation">
        
        <ul class="pagination-list">
            <li class="page-item">
                <a v-if="!isOnFirstPage" class="page-link" @click.prevent="loadPage('prev')">
                    <span>&laquo;</span>
                </a>
            </li>
            <template v-if="notEnoughPages">
                <li class="page-item" v-for="n in totalPage">
                    <a class="page-link" :class="{'is-current': isCurrentPage(n)}" @click.prevent="loadPage(n)" v-html="n"></a>
                </li>
            </template>
            <template v-else>
                <li class="page-item" v-for="n in windowSize">
                    <a class="page-link" :class="{'is-current': isCurrentPage(windowStart+n-1)}" @click.prevent="loadPage(windowStart+n-1)" v-html="windowStart+n-1">
                        
                    </a>
                </li>
            </template>
            <li class="page-item">
                <a class="page-next" v-if="!isOnLastPage" @click.prevent="loadPage('next')">
                    <span>&raquo;</span>
                </a>
            </li>
        </ul>

        

    </nav>
</template>

<script>
import VuetablePaginationMixin from 'vuetable-2/src/components/VuetablePaginationMixin'
export default {
  mixins: [VuetablePaginationMixin]
}
</script>
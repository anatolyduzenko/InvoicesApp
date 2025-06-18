<script setup lang="ts">
import {
    Pagination,
    PaginationContent,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination'

const props = defineProps({
    pagination: Object,
});

const emit = defineEmits(['change']);
</script>

<template>
    <Pagination
        v-slot="{ page }"
        :items-per-page="props.pagination.per_page"
        :total="props.pagination.total"
        :sibling-count="1"
        show-edges
        :default-page="props.pagination.current_page"
        @update:page="emit('change', $event)"
    >
        <PaginationContent v-slot="{ items }">
            <PaginationPrevious class="hover:cursor-pointer" />

            <template v-for="(item, index) in items" :key="index">
                <PaginationItem class="hover:cursor-pointer"
                v-if="item.type === 'page'"
                :value="item.value"
                :is-active="item.value === page"
                >
                {{ item.value }}
                </PaginationItem>
            </template>

            <PaginationNext class="hover:cursor-pointer"/>
        </PaginationContent>
    </Pagination>
</template>
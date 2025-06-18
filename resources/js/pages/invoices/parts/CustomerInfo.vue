<script setup lang="ts">
    const props = defineProps({
        selectedCustomer: Object,
    });
</script>

<template>
    <div v-if="props.selectedCustomer" class="grow pr-4 mt-2">
        <div>
            <p class="text-sm text-muted-foreground">Customer Name</p>
            <p class="text-sm font-medium">{{ props.selectedCustomer?.name }}</p>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Address</p>
            <p class="text-sm font-medium">{{ selectedCustomer?.country }}</p>
            <p class="text-sm font-medium">{{ props.selectedCustomer?.address }}</p>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Email</p>
            <p class="text-sm font-medium">{{ props.selectedCustomer?.email }}</p>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Phone</p>
            <p class="text-sm font-medium">{{ props.selectedCustomer?.phone }}</p>
        </div>
    </div>
</template>

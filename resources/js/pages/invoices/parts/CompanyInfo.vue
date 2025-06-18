<script setup lang="ts">
    const props = defineProps({
        company: Object,
    });
</script>

<template>
    <div class="border-t mt-4 pt-2">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <p class="text-sm text-muted-foreground">Company Name</p>
                <p class="text-sm font-medium">{{ props.company?.company_name }}</p>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Company Address</p>
                <p class="text-sm font-medium">{{ props.company?.company_address }}</p>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Company Email</p>
                <p class="text-sm font-medium">{{ props.company?.company_email }}</p>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Company Phone</p>
                <p class="text-sm font-medium">{{ props.company?.company_phone }}</p>
            </div>
        </div>
    </div>
</template>

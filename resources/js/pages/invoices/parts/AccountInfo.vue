<script setup lang="ts">
    const props = defineProps({
        selectedAccount: Object,
    });
</script>

<template>
    <div v-if="props.selectedAccount" class="grow pr-4 mt-2">
        <div>
            <p class="text-sm text-muted-foreground">Account</p>
            <p class="text-sm font-medium">{{ props.selectedAccount?.account }}</p>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Currency</p>
            <p class="text-sm font-medium">{{ props.selectedAccount?.currency }}</p>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Beneficiary</p>
            <p class="text-sm font-medium">{{ props.selectedAccount?.beneficiary }}</p>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Intermediary</p>
            <p class="text-sm font-medium">{{ props.selectedAccount?.intermediary }}</p>
        </div>
    </div>
</template>

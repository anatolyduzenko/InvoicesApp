<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import { useGoBack } from '@/composables/useGoBack';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft, Edit } from 'lucide-vue-next';

const { goBack } = useGoBack();

const props = defineProps({
    account: Object,
    accounts: Object,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Accounts',
        href: '/accounts',
    },
];

function editAccount(id: any) {
    router.get(
        route('accounts.edit', { account: id }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}
</script>

<template>
    <Head title="Account Info" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-6">
            <Card>
                <CardContent>
                    <div class="flex items-center justify-between border-b p-4">
                        <h2 class="flex-grow text-lg font-semibold">Account Info</h2>
                        <button
                            v-if="props.account?.id"
                            @click="editAccount(props.account.id)"
                            class="mr-2 text-sm text-muted-light-green hover:text-light-green"
                        >
                            <component :is="Edit" />
                        </button>
                        <button @click="goBack" class="text-sm text-muted-foreground hover:text-foreground"><component :is="ArrowLeft" /></button>
                    </div>

                    <div class="space-y-6 p-4">
                        <template v-if="props.account">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <p class="text-sm text-muted-foreground">Account</p>
                                    <p class="text-md font-medium">{{ props.account.account }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-muted-foreground">Currency</p>
                                    <p class="text-md font-medium">{{ props.account.currency }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-muted-foreground">Beneficiary</p>
                                    <p class="text-md font-medium">{{ props.account.beneficiary }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-muted-foreground">Intermediary</p>
                                    <p class="text-md font-medium">{{ props.account.intermediary }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-muted-foreground">Institution</p>
                                    <p class="text-md font-medium">{{ props.account.institution }}</p>
                                </div>
                            </div>
                        </template>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

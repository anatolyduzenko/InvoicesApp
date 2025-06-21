<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import { useGoBack } from '@/composables/useGoBack';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft, Edit, Save, Printer } from 'lucide-vue-next';
import CustomerInfo from './parts/CustomerInfo.vue';
import AccountInfo from './parts/AccountInfo.vue';
import InvoiceItems from './InvoiceItems.vue';
import { computed } from 'vue';

const { goBack } = useGoBack();

const props = defineProps({
    invoice: Object,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Invoices',
        href: '/invoices',
    },
];

function editInvoice(id: any) {
    router.get(
        route('invoices.edit', { invoice: id }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}

function viewInvoice(id: any) {
    const url = route('invoices.preview', { invoice: id });
    window.open(url, '_blank');
}

function downloadInvoice(id: any) {
    const url = route('invoices.download', { invoice: id });
    window.open(url, '_blank');
}

const title = computed(() => (props.invoice?.id ? 'Invoice ' + props.invoice?.number : 'New invoice'));
</script>

<template>
    <Head :title="title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-6">
            <Card>
                <CardContent>
                    <div class="flex items-center justify-between border-b p-4">
                        <h2 class="flex-grow text-lg font-semibold">Invoice Info</h2>
                        <button
                            v-if="props.invoice?.id"
                            @click="editInvoice(props.invoice.id)"
                            class="mr-2 text-sm text-muted-light-green hover:text-light-green hover:cursor-pointer"
                        >
                            <component :is="Edit" />
                        </button>
                        <button @click="viewInvoice(props.invoice?.id)" class="mr-2 text-sm text-muted-foreground hover:text-foreground hover:cursor-pointer"><component :is="Printer" /></button>
                        <button @click="downloadInvoice(props.invoice?.id)" class="mr-2 text-sm text-muted-foreground hover:text-foreground hover:cursor-pointer"><component :is="Save" /></button>
                        <button @click="goBack" class="text-sm text-muted-foreground hover:text-foreground hover:cursor-pointer"><component :is="ArrowLeft" /></button>
                    </div>

                    <div class="space-y-6 p-4">
                        <template v-if="props.invoice">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-5">
                                <div>
                                    <p class="text-sm text-muted-foreground">Invoice Number</p>
                                    <p class="text-md font-medium">{{ props.invoice.number }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-muted-foreground">Customer</p>
                                    <p class="text-md font-medium">{{ props.invoice.customer.name }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-muted-foreground">Issue Date</p>
                                    <p class="text-md font-medium">{{ props.invoice.issue_date }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-muted-foreground">Due Date</p>
                                    <p class="text-md font-medium">{{ props.invoice.due_date }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-muted-foreground">Total Amount</p>
                                    <p class="text-muted-light-green text-md font-medium">{{ props.invoice.total_amount }} {{ props.invoice.account.currency }}</p>
                                </div>
                            </div>
                            <div class="border-t pt-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <p class="text-lg font-medium">Customer Info</p>
                                    <CustomerInfo :selectedCustomer="props.invoice.customer" />
                                </div>
                                <div>
                                    <p class="text-lg text-foreground">Account Info</p>
                                    <AccountInfo :selectedAccount="props.invoice.account" />
                                </div>
                            </div>
                            <div class="border-t pt-4 grid grid-cols-1">
                                <InvoiceItems :disableEditing="true" :items="props.invoice?.items" />
                            </div>
                        </template>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

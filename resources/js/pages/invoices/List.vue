<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Edit, Receipt, ReceiptPoundSterling, ReceiptText, ReceiptEuro } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Invoices',
        href: '/invoices',
    },
];

const props = defineProps({
    invoice: Object,
    invoices: Object,
});

function addInvoice() {
    router.get(
        route('invoices.create'),
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}

function editInvoice(id) {
    router.get(
        route('invoices.edit', { invoice: id }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}

function openInvoice(id) {
    router.get(
        route('invoices.show', { invoice: id }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}

const currencyIcons = {
    USD: Receipt,
    EUR: ReceiptEuro,
    GBP: ReceiptPoundSterling,
};

function getCurrencyIcon(currency: string) {
    return currencyIcons[currency] ?? ReceiptText;
}
</script>

<template>
    <Head title="invoices" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-6">
            <div class="space-y-2 border-b pb-4">
                <Button @click="addInvoice" class="text-sm text-muted hover:text-light-green"><component :is="ReceiptText" />Add New</Button>
            </div>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead><component :is="ReceiptText" class="w-4 h-4 inline-block mr-1"/><span>Invoice</span></TableHead>
                        <TableHead>Customer</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Issue Date</TableHead>
                        <TableHead>Due Date</TableHead>
                        <TableHead>Amount</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="invoice in props.invoices.data"
                        :key="invoice.id"
                        class="cursor-pointer hover:bg-muted/50"
                    >
                        <TableCell @click="openInvoice(invoice.id)">
                            <component
                                :is="getCurrencyIcon(invoice.account.currency)"
                                class="w-4 h-4 inline-block text-muted-foreground mr-1"
                            />
                            <span 
                                >{{ invoice.number }}</span>
                        </TableCell>
                        <TableCell @click="openInvoice(invoice.id)">{{ invoice.customer.name }}</TableCell>
                        <TableCell @click="openInvoice(invoice.id)">{{ invoice.status }}</TableCell>
                        <TableCell @click="openInvoice(invoice.id)">{{ invoice.issue_date }}</TableCell>
                        <TableCell @click="openInvoice(invoice.id)">{{ invoice.due_date }}</TableCell>
                        <TableCell @click="openInvoice(invoice.id)">{{ invoice.total_amount }}</TableCell>
                        <TableCell @click="editInvoice(invoice.id)"><component :is="Edit"/></TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Edit, Receipt, ReceiptPoundSterling, ReceiptText, ReceiptEuro, Copy } from 'lucide-vue-next';
import Pagination from './parts/Pagination.vue';

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

function navigateToInvoice(action: string, id) {
    router.get(
        route(`invoices.${action}`, { invoice: id }),
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

const loadInvoices = async (page = 1) => {
    router.get(
        route('invoices.index'),
        {
            // ...searchParams.value,
            page,
        },
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
};
</script>

<template>
    <Head title="Invoices" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-6">
            <div class="space-y-2 flex justify-between border-b pb-4 pl-2">
                <h2 class="text-xl font-bold">Invoices</h2>
                <Button @click="addInvoice" class="text-sm text-muted hover:text-light-green hover:cursor-pointer"><component :is="ReceiptText" />Add New</Button>
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
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="invoice in props.invoices.data"
                        :key="invoice.id"
                        class="cursor-pointer hover:bg-muted/50"
                    >
                        <TableCell @click="navigateToInvoice('show', invoice.id)">
                            <component
                                :is="getCurrencyIcon(invoice.account.currency)"
                                class="w-4 h-4 inline-block text-muted-foreground mr-1"
                            />
                            <span 
                                >{{ invoice.number }}</span>
                        </TableCell>
                        <TableCell @click="navigateToInvoice('show', invoice.id)">{{ invoice.customer.name }}</TableCell>
                        <TableCell @click="navigateToInvoice('show', invoice.id)">{{ invoice.status }}</TableCell>
                        <TableCell @click="navigateToInvoice('show', invoice.id)">{{ invoice.issue_date }}</TableCell>
                        <TableCell @click="navigateToInvoice('show', invoice.id)">{{ invoice.due_date }}</TableCell>
                        <TableCell @click="navigateToInvoice('show', invoice.id)">{{ invoice.total_amount }}</TableCell>
                        <TableCell class="grid grid-cols-2">
                            <component class="text-muted-light-green" :is="Edit" @click="navigateToInvoice('edit', invoice.id)"/>
                            <component class="text-muted-light-blue" :is="Copy" @click="navigateToInvoice('clone', invoice.id)"/>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <Pagination :pagination="props.invoices" @change="loadInvoices"/>
        </div>
    </AppLayout>
</template>

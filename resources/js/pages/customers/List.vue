<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { PersonStanding } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Customers',
        href: '/customers',
    },
];

const props = defineProps({
    customer: Object,
    customers: Object,
});

function openCustomer(id) {
    router.get(
        route('customers.show', { customer: id }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}

function addCustomer() {
    router.get(
        route('customers.create'),
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}
</script>

<template>
    <Head title="Customers" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-6">
            <div class="space-y-2 border-b pb-4">
                <Button @click="addCustomer" class="text-sm text-muted hover:text-light-green"><component :is="PersonStanding" />Add New</Button>
            </div>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Phone</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="customer in props.customers"
                        :key="customer.id"
                        @click="openCustomer(customer.id)"
                        class="cursor-pointer hover:bg-muted/50"
                    >
                        <TableCell>{{ customer.name }}</TableCell>
                        <TableCell>{{ customer.email }}</TableCell>
                        <TableCell>{{ customer.phone }}</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>

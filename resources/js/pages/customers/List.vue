<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { PersonStanding } from 'lucide-vue-next';
import Pagination from '@/components/Pagination.vue';

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

function openCustomer(id: any) {
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

const loadCustomers = async (page = 1) => {
    router.get(
        route('customers.index'),
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

console.log(props)
</script>

<template>
    <Head title="Customers" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-6">
            <div class="space-y-2 flex justify-between border-b pb-4 pl-2">
                <h2 class="text-xl font-bold">Customers</h2>
                <Button @click="addCustomer" class="text-sm text-muted hover:text-light-green hover:cursor-pointer"><component :is="PersonStanding" />Add New</Button>
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
                        v-for="customer in props.customers?.data"
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
            <Pagination v-if="props?.customers?.last_page > 1" :pagination="props?.customers" @change="loadCustomers" />
        </div>
    </AppLayout>
</template>

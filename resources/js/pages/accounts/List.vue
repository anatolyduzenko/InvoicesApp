<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Wallet } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Accounts',
        href: '/accounts',
    },
];

const props = defineProps({
    account: Object,
    accounts: Object,
});

function openAccount(id: any) {
    router.get(
        route('accounts.show', { account: id }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}

function addAccount() {
    router.get(
        route('accounts.create'),
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}

const loadAccounts = async (page = 1) => {
    router.get(
        route('accounts.index'),
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
    <Head title="Accounts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-6">
            <div class="space-y-2 flex justify-between border-b pb-4 pl-2">
                <h2 class="text-xl font-bold">Accounts</h2>
                <Button @click="addAccount" class="text-sm text-muted hover:text-light-green hover:cursor-pointer"><component :is="Wallet" />Add New</Button>
            </div>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Account</TableHead>
                        <TableHead>Beneficiary</TableHead>
                        <TableHead>Currency</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="account in props.accounts?.data"
                        :key="account.id"
                        @click="openAccount(account.id)"
                        class="cursor-pointer hover:bg-muted/50"
                    >
                        <TableCell>{{ account.account }}</TableCell>
                        <TableCell>{{ account.beneficiary }}</TableCell>
                        <TableCell>{{ account.currency }}</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <Pagination v-if="props?.accounts?.last_page > 1" :pagination="props?.accounts" @change="loadAccounts" />
        </div>
    </AppLayout>
</template>

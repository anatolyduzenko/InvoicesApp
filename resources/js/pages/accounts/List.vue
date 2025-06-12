<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
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

function openAccount(id) {
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
</script>

<template>
    <Head title="Accounts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-6">
            <Card>
                <CardContent>
                    <div class="space-y-2 border-b pb-4">
                        <Button @click="addAccount" class="text-sm text-muted hover:text-light-green"><component :is="Wallet" />Add New</Button>
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
                                v-for="account in props.accounts"
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
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

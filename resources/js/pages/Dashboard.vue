<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Handshake, ReceiptText, Wallet } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage();
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 space-y-6">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4">
                    <div>
                        <p class="text-xl text-muted-foreground">Total Invoices</p>
                        <p class="text-2xl font-medium border-b mb-2">{{ page.props?.total_invoices }}</p>
                        <Link href="/invoices/create">
                            <Button variant="secondary" class="hover:cursor-pointer hover:text-muted-light-green">
                                <component :is="ReceiptText" />
                                Add New Invoice
                            </Button>
                        </Link>
                    </div>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4">
                    <div>
                        <p class="text-xl text-muted-foreground">Total Accounts</p>
                        <p class="text-2xl font-medium border-b mb-2">{{ page.props?.total_accounts }}</p>
                        <Link href="/accounts/create">
                            <Button variant="secondary" class="hover:cursor-pointer hover:text-muted-light-green">
                                <component :is="Wallet" />
                                Add New Account
                            </Button>
                        </Link>
                    </div>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4">
                    <div>
                        <p class="text-xl text-muted-foreground">Total Customers</p>
                        <p class="text-2xl font-medium border-b mb-2">{{ page.props?.total_customers }}</p>
                        <Link href="/customers/create">
                            <Button variant="secondary" class="hover:cursor-pointer hover:text-muted-light-green">
                                <component :is="Handshake" />
                                Add New Customer
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

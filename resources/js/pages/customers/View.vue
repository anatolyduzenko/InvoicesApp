<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import { useGoBack } from '@/composables/useGoBack';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft, Edit } from 'lucide-vue-next';
import { capitalize } from 'vue';

const { goBack } = useGoBack();

const props = defineProps({
    customer: Object,
    // customers: Object,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'customers',
        href: '/customers',
    },
];

function editcustomer(id: any) {
    router.get(
        route('customers.edit', { customer: id }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}
</script>

<template>
    <Head title="Customer Info" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-6">
            <Card>
                <CardContent>
                    <div class="flex items-center justify-between border-b p-4">
                        <h2 class="flex-grow text-lg font-semibold">Customer Info</h2>
                        <button
                            v-if="props.customer?.id"
                            @click="editcustomer(props.customer.id)"
                            class="mr-2 text-sm text-muted-light-green hover:text-light-green"
                        >
                            <component :is="Edit" />
                        </button>
                        <button @click="goBack" class="text-sm text-muted-foreground hover:text-foreground"><component :is="ArrowLeft" /></button>
                    </div>

                    <div class="space-y-6 p-4">
                        <template v-if="props.customer">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <p class="text-sm text-muted-foreground">Name</p>
                                    <p class="text-md font-medium">{{ props.customer.name }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-muted-foreground">Currency</p>
                                    <p class="text-md font-medium">{{ props.customer.currency }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-muted-foreground">Email</p>
                                    <p class="text-md font-medium">{{ props.customer.email }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-muted-foreground">Phone</p>
                                    <p class="text-md font-medium">{{ props.customer.phone }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-muted-foreground">Country</p>
                                    <p class="text-md font-medium">{{ props.customer.country }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-muted-foreground">Address</p>
                                    <p class="text-md font-medium">{{ props.customer.address }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-muted-foreground">Invoice Template</p>
                                    <p class="text-md font-medium">{{ capitalize(props.customer.template_name) }}</p>
                                </div>
                            </div>
                        </template>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { FormControl, FormDescription, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

import { useGoBack } from '@/composables/useGoBack';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { ArrowLeft, Save, Trash } from 'lucide-vue-next';
import { FlattenAndSetPathsType, useForm } from 'vee-validate';
import { computed, watchEffect, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import InvoiceItems from '@/pages/invoices/InvoiceItems.vue';
import DatePicker from '@/components/DatePicker.vue';
import AccountInfo from '@/pages/invoices/parts/AccountInfo.vue';
import CustomerInfo from '@/pages/invoices/parts/CustomerInfo.vue';

import {
    parseDate
} from '@internationalized/date'

import * as z from 'zod';

const { goBack } = useGoBack();

const props = defineProps({
    invoice: Object,
    accounts: Object,
    companies: Object,
    customers: Object,
});

const selectedAccount = ref({});
const selectedCustomer = ref({});

const page = usePage();

const formSchema = z.object({
    number: z.string().min(2).max(25),
    status: z.string().min(0).max(15),
    issue_date: z
        .string()
        .refine(v => v, { message: 'A date of issue is required.' }),
    due_date: z
        .string()
        .refine(v => v, { message: 'A due date is required.' }),
    total_amount: z.number().optional(),
    company_id: z.number(),
    account_id: z.number(),
    customer_id: z.number(),
    items: z.array(
        z.object({
            id: z.number(),
            description: z.string().min(1, 'Description is required'),
            qty: z.number().min(1, 'Quantity must be at least 1'),
            unit: z.string().min(1, 'Unit type must be set'),
            price: z.number().min(0, 'Price must be non-negative'),
            amount: z.number().min(0, 'Amount must be non-negative'),
        })
    ).min(1, 'At least one item is required'),
});

type FormValues = z.infer<typeof formSchema>;

const invoiceForm = useForm({
    validationSchema: toTypedSchema(formSchema),
    initialValues: {
        number: props.invoice?.number ?? '',
        status: props.invoice?.status ?? '',
        issue_date: props.invoice?.issue_date ? toDateInputString(props.invoice.issue_date) : toDateInputString(new Date()),
        due_date: props.invoice?.due_date ? toDateInputString(props.invoice.due_date) : toDateInputString(new Date()),
        total_amount: props.invoice?.total_amount ?? 0,
        account_id: Number(props.invoice?.account_id ?? 0),
        company_id: Number(props.invoice?.company_id) ?? 0,
        customer_id: Number(props.invoice?.customer_id) ?? 0,
        items: props.invoice?.items ?? [],
    },
    initialErrors: page.props.errors as unknown as FlattenAndSetPathsType<FormValues, string>,
});

const onSubmit = invoiceForm.handleSubmit((values) => {
    if (props.invoice?.id) {
        router.put(route('invoices.update', props.invoice.id), values, {});
    } else {
        router.post(route('invoices.store'), values, {});
    }
});

function toDateInputString(date: Date | string): string {
    const d = typeof date === 'string' ? new Date(date) : date;
    return d.toISOString().slice(0, 10);
}

const title = computed(() => (props.invoice?.id ? 'Edit invoice' : 'New invoice'));

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Invoices',
        href: '/invoices',
    },
];

function deleteInvoice(id) {
    router.delete(route('invoices.destroy', { invoice: id }), {});
}

watchEffect(() => {
    if (Object.keys(page.props.errors).length > 0) {
        invoiceForm.setFieldError('number', page.props.errors?.number);
        invoiceForm.setFieldError('issue_date', page.props.errors?.issue_date);
        invoiceForm.setFieldError('due_date', page.props.errors?.due_date);
    }

    const success = page.props.flash?.success;
    if (success) {
        toast.success(success);
    }

});

const idToEntityMap = {
    account_id: {
        source: () => invoiceForm.values.account_id,
        target: selectedAccount,
        list: props.accounts,
    },
    customer_id: {
        source: () => invoiceForm.values.customer_id,
        target: selectedCustomer,
        list: props.customers,
    },
    };

Object.values(idToEntityMap).forEach(({ source, target, list }) => {
    watch(source, (id) => {
        target.value = list.find((item) => item.id === id) ?? null;
    }, { immediate: true });
});

watch(
    () => invoiceForm.values.items,
    (items) => {
        const total = items?.reduce((sum, item) => sum + Number(item.amount || 0), 0);
        invoiceForm.setFieldValue('total_amount', total);
    },
    { immediate: true }
);

watch(
    () => page.props.invoice.items,
    (items) => {
        invoiceForm.setFieldValue('items', items);
    },
    { immediate: true }
);

const issue_date = computed({
    get: () => invoiceForm.values.issue_date ? parseDate(invoiceForm.values.issue_date) : undefined,
    set: val => val,
})

const due_date = computed({
    get: () => invoiceForm.values.due_date ? parseDate(invoiceForm.values.due_date) : undefined,
    set: val => val,
})

const total_amount = computed({
    get: () => invoiceForm.values.total_amount ? Number(invoiceForm.values.total_amount) : undefined,
    set: val => val,
})
</script>

<template>
    <Head :title="title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-6">
            <Card>
                <CardContent>
                    <div class="flex items-center justify-between border-b p-4">
                        <h2 class="flex-grow text-lg font-semibold">
                            {{ props.invoice?.id ? 'Edit invoice: ' + props.invoice.number : 'New invoice' }}
                        </h2>
                        <button
                            v-if="props.invoice?.id"
                            @click="deleteInvoice(props.invoice.id)"
                            class="mr-2 text-sm text-destructive hover:text-destructive-foreground"
                        >
                            <component :is="Trash" />
                        </button>
                        <button @click="goBack()" class="text-sm text-muted-foreground hover:text-foreground"><component :is="ArrowLeft" /></button>
                    </div>

                    <div class="space-y-6 p-4">
                        <form @submit.prevent="onSubmit">
                            <div class="flex items-start justify-between">
                                <div class="grow-2">
                                    <FormField v-slot="{ componentField }" name="number">
                                        <FormItem class="grow-2 pr-4">
                                            <FormLabel>Invoice Number:</FormLabel>
                                            <FormControl>
                                                <Input type="text" placeholder="Invoice Number" v-bind="componentField" />
                                            </FormControl>
                                            <FormDescription />
                                            <FormMessage />
                                        </FormItem>
                                    </FormField>
                                    <div class="grow pr-4 border-t mr-4  pt-2 mt-2">
                                        <FormField v-slot="{ componentField }" name="customer_id">
                                            <FormItem>
                                                <FormLabel>Customer: </FormLabel>
                                                <Select v-bind="componentField">
                                                    <FormControl>
                                                        <SelectTrigger class="grow w-full">
                                                            <SelectValue placeholder="Customer" />
                                                        </SelectTrigger>
                                                    </FormControl>
                                                    <SelectContent>
                                                        <SelectItem v-for="customer in page.props.customers" :key="customer.id" :value="Number(customer.id)"> {{ customer.name }} </SelectItem>
                                                    </SelectContent>
                                                </Select>
                                                <FormDescription />
                                                <FormMessage />
                                            </FormItem>
                                        </FormField>
                                        <CustomerInfo :selectedCustomer="selectedCustomer" />
                                    </div>
                                </div>
                                <div class="grow-2 max-w-[50%]">
                                    <div class="grid md:grid-cols-3  pb-2 mb-4  border-b ">
                                        <FormField name="issue_date">
                                            <FormItem class="pr-4">
                                                <FormLabel for="issue_date">Issue Date: </FormLabel>
                                                <DatePicker  id="issue_date" v-model:modelValue="issue_date" 
                                                    @update:modelValue="(v) => invoiceForm.setFieldValue('issue_date', v)"
                                                />
                                                <FormDescription />
                                                <FormMessage />
                                            </FormItem>
                                        </FormField>
                                        <FormField name="due_date">
                                            <FormItem class="pr-4"> 
                                                <FormLabel htmlFor="due_date" asChild>Due Date: </FormLabel>
                                                <FormControl>
                                                    <DatePicker id="due_date" v-model:modelValue="due_date" 
                                                        @update:modelValue="(v) => invoiceForm.setFieldValue('due_date', v)"
                                                    />
                                                </FormControl>
                                                <FormDescription />
                                                <FormMessage />
                                            </FormItem>
                                        </FormField>
                                        <div class="pl-6">
                                            <p class="text-sm text-foreground">Total</p>
                                            <p class="text-2xl text-chart-2 font-bold">{{ total_amount }} {{ selectedAccount?.currency }}</p>
                                        </div>
                                    </div>
                                    <FormField v-slot="{ componentField }" name="account_id">
                                        <FormItem>
                                            <FormLabel>Account: </FormLabel>
                                            <Select v-bind="componentField" id="account_id">
                                                <FormControl>
                                                    <SelectTrigger class="grow w-full">
                                                        <SelectValue placeholder="Account" />
                                                    </SelectTrigger>
                                                </FormControl>
                                                <SelectContent>
                                                    <SelectItem v-for="account in page.props.accounts" :key="account.id" :value="Number(account.id)"> {{ account.intermediary }} </SelectItem>
                                                </SelectContent>
                                            </Select>
                                            <FormDescription />
                                            <FormMessage />
                                        </FormItem>
                                    </FormField>
                                    <AccountInfo :selectedAccount="selectedAccount" />
                                </div>
                            </div>
                            <FormField v-slot="{ componentField }" name="company_id">
                                <FormItem class=""> 
                                    <FormControl>
                                        <Input type="hidden" placeholder="Company" v-bind="componentField" />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                            <FormField v-slot="{ componentField }" name="total_amount">
                                <FormItem class=""> 
                                    <FormControl>
                                        <Input type="hidden" placeholder="Total Amount" v-bind="componentField" />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                            <!-- <CompanyInfo :company="page.props.company"/> -->
                            <FormField name="items">
                                <FormItem class="border-t-1 pt-4 mt-6"> 
                                    <FormLabel for="items" >Invoice items: </FormLabel>
                                    <!-- <FormControl> -->
                                        <InvoiceItems class="" :items="invoiceForm.values?.items" @update:items="invoiceForm.setFieldValue('items', $event)"/>
                                    <!-- </FormControl> -->
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                            <Button type="submit" class="mt-6 hover:text-light-green"><component :is="Save" />Save Invoice</Button>
                        </form>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

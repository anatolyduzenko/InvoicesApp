<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { FormControl, FormDescription, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { useGoBack } from '@/composables/useGoBack';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { ArrowLeft, Save, Trash } from 'lucide-vue-next';
import { FlattenAndSetPathsType, useForm } from 'vee-validate';
import { computed, watchEffect, capitalize } from 'vue';
import { toast } from 'vue-sonner';
import * as z from 'zod';

const { goBack } = useGoBack();

const props = defineProps({
    customer: Object,
    customers: Object,
});

const page = usePage();

const formSchema = z.object({
    name: z.string().min(2).max(255),
    country: z.string().min(0).max(255),
    address: z.string().min(0).max(255),
    email: z.string().min(5).max(100),
    phone: z.string().min(5).max(100),
    currency: z.coerce.string().min(3).max(5),
    template_name: z.coerce.string().min(3),
});

type FormValues = z.infer<typeof formSchema>;

const customerForm = useForm({
    validationSchema: toTypedSchema(formSchema),
    initialValues: {
        name: props.customer?.name ?? '',
        address: props.customer?.address ?? '',
        email: props.customer?.email ?? '',
        phone: props.customer?.phone ?? '',
        country: props.customer?.country ?? '',
        currency: props.customer?.currency ?? 'USD',
        template_name: props.customer?.template_name ?? 'basic',
    },
    initialErrors: page.props.errors as unknown as FlattenAndSetPathsType<FormValues, string>,
});

const onSubmit = customerForm.handleSubmit((values) => {
    if (props.customer?.id) {
        router.put(route('customers.update', props.customer.id), values, {});
    } else {
        router.post(route('customers.store'), values, {});
    }
});

const title = computed(() => (props.customer?.id ? 'Edit customer' : 'New customer'));

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'customers',
        href: '/customers',
    },
];

function deleteCustomer(id: any) {
    router.delete(route('customers.destroy', { customer: id }), {});
}

watchEffect(() => {
    if (Object.keys(page.props.errors).length > 0) {
        customerForm.setFieldError('name', page.props.errors?.name);
        customerForm.setFieldError('address', page.props.errors?.address);
        customerForm.setFieldError('email', page.props.errors?.email);
        customerForm.setFieldError('phone', page.props.errors?.phone);
        customerForm.setFieldError('currency', page.props.errors?.currency);
        customerForm.setFieldError('template_name', page.props.errors?.template_name);
        customerForm.setFieldError('country', page.props.errors?.country);
    }

    const success = page.props.flash?.success;
    if (success) {
        toast.success(success);
    }
});
</script>

<template>
    <Head :title="title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-6">
            <Card>
                <CardContent>
                    <div class="flex items-center justify-between border-b p-4">
                        <h2 class="flex-grow text-lg font-semibold">
                            {{ props.customer?.id ? 'Edit customer: ' + props.customer?.name : 'New customer' }}
                        </h2>
                        <button
                            v-if="props.customer?.id"
                            @click="deleteCustomer(props.customer.id)"
                            class="mr-2 text-sm text-destructive hover:text-destructive-foreground"
                        >
                            <component :is="Trash" />
                        </button>
                        <button @click="goBack()" class="text-sm text-muted-foreground hover:text-foreground"><component :is="ArrowLeft" /></button>
                    </div>

                    <div class="space-y-6 p-4">
                        <form @submit.prevent="onSubmit">
                            <FormField v-slot="{ componentField }" name="name">
                                <FormItem class="">
                                    <FormLabel>Customer's Name: </FormLabel>
                                    <FormControl>
                                        <Input type="text" placeholder="Customer's Name" v-bind="componentField" />
                                    </FormControl>
                                    <FormDescription />
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                            <div class="grid grid-cols-2 gap-6 pt-2">
                                <FormField v-slot="{ componentField }" name="email">
                                    <FormItem>
                                        <FormLabel>Email: </FormLabel>
                                        <FormControl>
                                            <Input type="email" placeholder="Email Address" v-bind="componentField" />
                                        </FormControl>
                                        <FormDescription />
                                        <FormMessage />
                                    </FormItem>
                                </FormField>
                                <FormField v-slot="{ componentField }" name="phone">
                                    <FormItem>
                                        <FormLabel>Phone: </FormLabel>
                                        <FormControl>
                                            <Input type="tel" placeholder="Phone Number" v-bind="componentField" />
                                        </FormControl>
                                        <FormDescription />
                                        <FormMessage />
                                    </FormItem>
                                </FormField>
                            </div>

                            <div class="grid grid-cols-2 gap-6 pt-2 items-start">
                                <FormField v-slot="{ componentField }" name="country">
                                    <FormItem>
                                        <FormLabel>Country: </FormLabel>
                                        <FormControl>
                                            <Input type="text" placeholder="country" v-bind="componentField" />
                                        </FormControl>
                                        <FormDescription />
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="address">
                                    <FormItem >
                                        <FormLabel>Address: </FormLabel>
                                        <FormControl>
                                            <Textarea placeholder="Address" v-bind="componentField" />
                                        </FormControl>
                                        <FormDescription />
                                        <FormMessage />
                                    </FormItem>
                                </FormField>
                            </div>
                            <div class="grid grid-cols-2 gap-6 pt-2 items-start">
                                <FormField v-slot="{ componentField }" name="currency">
                                    <FormItem>
                                        <FormLabel>Currency: </FormLabel>
                                        <FormControl>
                                            <Select v-bind="componentField">
                                                <SelectTrigger class="w-[180px]">
                                                    <SelectValue placeholder="Currency" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem value="USD"> USD </SelectItem>
                                                    <SelectItem value="EUR"> EUR </SelectItem>
                                                    <SelectItem value="GBP"> GBP </SelectItem>
                                                </SelectContent>
                                            </Select>
                                        </FormControl>
                                        <FormDescription />
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="template_name">
                                    <FormItem>
                                        <FormLabel>Invoice Template: </FormLabel>
                                        <FormControl>
                                            <Select v-bind="componentField">
                                                <SelectTrigger class="w-[180px]">
                                                    <SelectValue placeholder="Invoice template" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem value="basic"> Basic </SelectItem>
                                                    <SelectItem value="modern"> Modern </SelectItem>
                                                </SelectContent>
                                            </Select>
                                        </FormControl>
                                        <FormDescription />
                                        <FormMessage />
                                    </FormItem>
                                </FormField>
                            </div>
                            <Button type="submit" class="mt-2 hover:text-light-green"><component :is="Save" />Save customer</Button>
                        </form>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

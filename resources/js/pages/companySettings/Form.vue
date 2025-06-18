<script setup lang="ts">
import { useGoBack } from '@/composables/useGoBack';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { FlattenAndSetPathsType, useForm } from 'vee-validate';
import { ref, watchEffect } from 'vue';
import { toast } from 'vue-sonner';
import * as z from 'zod';

import { Button } from '@/components/ui/button';
import { FormControl, FormDescription, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { ArrowLeft, Save } from 'lucide-vue-next';

const { goBack } = useGoBack();

const props = defineProps({
    companySettings: Object,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Company Settings',
        href: '/company-settings',
    },
];

const logo = ref<File | null>(null);
const page = usePage();

const formSchema = z.object({
    company_name: z.string().min(2).max(255),
    company_address: z.string().min(2).max(255),
    company_email: z.string().min(2).max(255),
    company_phone: z.string().min(2).max(255),
    company_terms: z.string().min(0).max(100).optional(),
    logo: z
        .any()
        .optional()
        .nullable()
        .refine((file) => file instanceof File || file === null || file === undefined, {
            message: 'Logo must be a file',
        }),
});

type FormValues = z.infer<typeof formSchema>;

const companySettingsForm = useForm({
    validationSchema: toTypedSchema(formSchema),
    initialValues: {
        company_name: props.companySettings?.company_name ?? '',
        company_address: props.companySettings?.company_address ?? '',
        company_email: props.companySettings?.company_email ?? '',
        company_phone: props.companySettings?.company_phone ?? '',
        company_terms: props.companySettings?.company_terms ?? '',
        logo: null,
    },
    initialErrors: page.props.errors as unknown as FlattenAndSetPathsType<FormValues, string>,
});

const onSubmit = companySettingsForm.handleSubmit((values) => {
    router.post(route('company-settings.update'), values, {});
});

watchEffect(() => {
    companySettingsForm.setFieldError('company_name', page.props.errors?.company_name);
    companySettingsForm.setFieldError('company_address', page.props.errors?.company_address);
    companySettingsForm.setFieldError('company_email', page.props.errors?.company_email);
    companySettingsForm.setFieldError('company_phone', page.props.errors?.company_phone);
    companySettingsForm.setFieldError('company_terms', page.props.errors?.company_terms);
    companySettingsForm.setFieldError('logo', page.props.errors?.logo);

    const success = page.props.flash?.success;
    if (success) {
        toast.success(success);
    }
});

function handleFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        logo.value = target.files[0];
        companySettingsForm.setFieldValue('logo', logo.value);
    }
}
</script>

<template>
    <Head title="Company Settings" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between border-b pb-4 pr-4">
                <h2 class="text-xl font-bold">Company Settings</h2>
                <button @click="goBack()" class="text-sm text-muted-foreground hover:text-foreground hover:cursor-pointer"><component :is="ArrowLeft" /></button>
            </div>
            <form @submit.prevent="onSubmit">
                <FormField v-slot="{ componentField }" name="company_name">
                    <FormItem class="grow pr-4">
                        <FormLabel>Company Name: </FormLabel>
                        <FormControl>
                            <Input type="text" placeholder="Company Name" v-bind="componentField" />
                        </FormControl>
                        <FormDescription />
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="company_address">
                    <FormItem class="grow pr-4">
                        <FormLabel>Company Address: </FormLabel>
                        <FormControl>
                            <Input type="text" placeholder="Company Address" v-bind="componentField" />
                        </FormControl>
                        <FormDescription />
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="company_email">
                    <FormItem class="grow pr-4">
                        <FormLabel>Email: </FormLabel>
                        <FormControl>
                            <Input type="email" placeholder="Company Email Address" v-bind="componentField" />
                        </FormControl>
                        <FormDescription />
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="company_phone">
                    <FormItem class="grow pr-4">
                        <FormLabel>Company Phone: </FormLabel>
                        <FormControl>
                            <Input type="tel" placeholder="Company Phone Numaber" v-bind="componentField" />
                        </FormControl>
                        <FormDescription />
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="company_terms">
                    <FormItem class="grow pr-4">
                        <FormLabel>Terms: </FormLabel>
                        <FormControl>
                            <Textarea placeholder="Terms" v-bind="componentField" />
                        </FormControl>
                        <FormDescription />
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField name="logo">
                    <FormItem class="grow pr-4">
                        <FormLabel>Company Logo: </FormLabel>
                        <FormControl>
                            <Input id="logo" type="file" accept="image/*" @change="handleFileChange" />
                        </FormControl>
                        <FormDescription />
                        <FormMessage />
                    </FormItem>
                </FormField>
                <Button type="submit" class="mt-2 hover:text-light-green hover:cursor-pointer"><component :is="Save" />Save</Button>
            </form>
        </div>
    </AppLayout>
</template>

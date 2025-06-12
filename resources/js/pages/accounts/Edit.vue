<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { FormControl, FormDescription, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Progress } from '@/components/ui/progress';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useGoBack } from '@/composables/useGoBack';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { ArrowLeft, Save, Trash } from 'lucide-vue-next';
import { useForm } from 'vee-validate';
import { computed, onMounted, ref } from 'vue';
import * as z from 'zod';

const { goBack } = useGoBack();

const props = defineProps({
    account: Object,
    accounts: Object,
});

const loading = ref(true);

const formSchema = toTypedSchema(
    z.object({
        intermediary: z.string().min(2).max(255),
        institution: z.string().min(2).max(255),
        beneficiary: z.string().min(2).max(255),
        account: z.string().min(2).max(100),
        currency: z.coerce.string().min(3).max(5),
    }),
);

const accountForm = useForm({
    validationSchema: formSchema,
});

const onSubmit = accountForm.handleSubmit((values) => {
    if (props.account?.id) {
        router.put(route('accounts.update', props.account.id), values, {});
    } else {
        router.post(route('accounts.store'), values, {});
    }
});

onMounted(() => {
    if (props.account) {
        accountForm.setValues({
            intermediary: props.account.intermediary,
            institution: props.account.institution,
            beneficiary: props.account.beneficiary,
            account: props.account.account,
            currency: props.account.currency,
        });
        loading.value = false;
    } else {
        accountForm.resetForm({
            values: {
                intermediary: '',
                institution: '',
                beneficiary: '',
                account: '',
                currency: 'USD',
            },
        });
        loading.value = false;
    }
});

const title = computed(() => (props.account?.id ? 'Edit Account' : 'New Account'));

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Accounts',
        href: '/accounts',
    },
];

function deleteAccount(id) {
    router.delete(route('accounts.destroy', { account: id }), {});
}
</script>

<template>
    <Head :title="title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-6">
            <Card>
                <CardContent>
                    <div class="flex items-center justify-between border-b p-4">
                        <h2 class="flex-grow text-lg font-semibold">
                            {{ props.account?.id ? 'Edit Account: ' + props.account.account : 'New Account' }}
                        </h2>
                        <button
                            v-if="props.account?.id"
                            @click="deleteAccount(props.account.id)"
                            class="mr-2 text-sm text-destructive hover:text-destructive-foreground"
                        >
                            <component :is="Trash" />
                        </button>
                        <button @click="goBack()" class="text-sm text-muted-foreground hover:text-foreground"><component :is="ArrowLeft" /></button>
                    </div>

                    <div class="space-y-6 p-4">
                        <template v-if="loading">
                            <Progress :model-value="75" />
                        </template>
                        <template v-else>
                            <form @submit.prevent="onSubmit">
                                <div class="flex items-start justify-between">
                                    <FormField v-slot="{ componentField }" name="account">
                                        <FormItem class="grow pr-4">
                                            <FormLabel>Account: </FormLabel>
                                            <FormControl>
                                                <Input type="text" placeholder="Account" v-bind="componentField" />
                                            </FormControl>
                                            <FormDescription />
                                            <FormMessage />
                                        </FormItem>
                                    </FormField>
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
                                </div>
                                <FormField v-slot="{ componentField }" name="intermediary">
                                    <FormItem>
                                        <FormLabel>Intermediary: </FormLabel>
                                        <FormControl>
                                            <Input type="text" placeholder="Intermediary" v-bind="componentField" />
                                        </FormControl>
                                        <FormDescription />
                                        <FormMessage />
                                    </FormItem>
                                </FormField>
                                <FormField v-slot="{ componentField }" name="institution">
                                    <FormItem>
                                        <FormLabel>Institution: </FormLabel>
                                        <FormControl>
                                            <Input type="text" placeholder="Institution" v-bind="componentField" />
                                        </FormControl>
                                        <FormDescription />
                                        <FormMessage />
                                    </FormItem>
                                </FormField>
                                <FormField v-slot="{ componentField }" name="beneficiary">
                                    <FormItem>
                                        <FormLabel>Beneficiary: </FormLabel>
                                        <FormControl>
                                            <Input type="text" placeholder="Beneficiary" v-bind="componentField" />
                                        </FormControl>
                                        <FormDescription />
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <Button type="submit" class="mt-2 hover:text-light-green"><component :is="Save" />Save Account</Button>
                            </form>
                        </template>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

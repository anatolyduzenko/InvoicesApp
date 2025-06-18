<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { FormControl, FormDescription, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import {
    Dialog,
    DialogContent,
    DialogClose,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog'
import { toTypedSchema } from '@vee-validate/zod';
import { Input } from '@/components/ui/input';
import * as z from 'zod';
import { watch, computed } from 'vue';
import { useForm } from 'vee-validate';
import { Save } from 'lucide-vue-next';

const props = defineProps({
    item: Object,
    open: Boolean,
});

const emit = defineEmits([
    'item-added',
    'update:open'
]);

const formSchema = toTypedSchema(z.object({
    id: z.number(),
    description: z.string().min(5).max(255),
    unit: z.string().min(2).max(50),
    price: z.coerce.number().min(0).max(90000),
    qty: z.coerce.number().min(0).max(500),
}))


const itemForm = useForm({
    validationSchema: formSchema,
    initialValues: {
        id: props.item?.id ?? 10000000000 + Math.ceil(Math.random()*1000000),
        description: props.item?.description ?? '',
        unit: props.item?.unit ?? 'hour',
        price: props.item?.price ?? 0,
        qty: props.item?.qty ?? 1,
    },
})

const onSubmit = itemForm.handleSubmit((values: any) => {
    const id = props.item?.id ?? 10000000000 + Math.ceil(Math.random()*1000000);
    const amount = values.price * values.qty;
    emit('item-added', {...values, amount, id});
    emit('update:open', false);
});

const isOpen = computed({
    get: () => props.open,
    set: (val) => emit('update:open', val),
});

watch(
    () => props.item,
    (val) => {
        if (val) {
            itemForm.setValues({
                id: val.id ?? -1,
                description: val.description ?? '',
                unit: val.unit ?? '',
                price: val.price ?? 0,
                qty: val.qty ?? 1,
            });
        }
    },
    { immediate: true, deep: true }
);

const title = computed(() => (props.item?.id ? 'Edit item' : 'New item'));
</script>
<template>
    <Dialog v-model:open="isOpen" >
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
            <DialogTitle>{{ title }}</DialogTitle>
            <DialogDescription>
                Please add services description.
            </DialogDescription>
            </DialogHeader>
            <!-- <Form v-slot="{ handleSubmit }" as="" :validation-schema="formSchema"> -->
                <form id="itemForm" @submit.prevent="onSubmit">
                    <FormField v-slot="{ componentField }" name="description">
                        <FormItem>
                            <FormLabel>Description</FormLabel>
                            <FormControl>
                                <Input type="text" placeholder="Description" v-bind="componentField" />
                            </FormControl>
                            <FormDescription>
                                This is your service display name.
                            </FormDescription>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <FormField v-slot="{ componentField }" name="unit">
                        <FormItem>
                            <FormLabel>Unit</FormLabel>
                            <Select v-bind="componentField">
                                <FormControl>
                                    <SelectTrigger class="grow w-full">
                                        <SelectValue placeholder="Unit" />
                                    </SelectTrigger>
                                </FormControl>
                                <SelectContent>
                                    <SelectItem value="hour"> Hour </SelectItem>
                                    <SelectItem value="onetime"> One-Time </SelectItem>
                                </SelectContent>
                            </Select>
                            <FormDescription>
                                This is your service units.
                            </FormDescription>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <FormField v-slot="{ componentField }" name="price">
                        <FormItem>
                            <FormLabel>Price</FormLabel>
                            <FormControl>
                                <Input type="text" placeholder="Price" v-bind="componentField" />
                            </FormControl>
                            <FormDescription>
                                This is your service price.
                            </FormDescription>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <FormField v-slot="{ componentField }" name="qty">
                        <FormItem>
                            <FormLabel>Quantity</FormLabel>
                            <FormControl>
                                <Input type="text" placeholder="Quantity" v-bind="componentField" />
                            </FormControl>
                            <FormDescription>
                                This is an amount of provided services.
                            </FormDescription>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                </form>
            <!-- </Form> -->
            <DialogFooter>
                <DialogClose as-child>
                    <Button type="submit" form="itemForm" class="hover:text-light-green hover:cursor-pointer">
                        <component :is="Save" />
                        Save changes
                    </Button>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
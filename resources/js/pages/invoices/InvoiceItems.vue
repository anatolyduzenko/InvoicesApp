<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { ReceiptText, Trash2 } from 'lucide-vue-next';
import ItemDialog from '@/pages/invoices/ItemDialog.vue';
import { FormControl } from '@/components/ui/form';
import { ref } from 'vue';

const props = defineProps({
    items: Array,
    disableEditing: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:items']);

const editingItem = ref({});
const items = ref([...props.items]);
const isDialogOpen = ref(false);

function addNewOrEditItem(processingItem) {
    const index = items.value.findIndex(i => i.id === processingItem.id);
    if (index !== -1) {
        items.value[index] = processingItem;
    } else {
        items.value.push({ ...processingItem});
    }    
    editingItem.value = {};
    items.value = items.value.map(item =>
        item.value ? item.value : item
    );
    emit('update:items', items.value);
    isDialogOpen.value = false;
}

function openEditItemDialog(item) {
    editingItem.value = { ...item }; 
    isDialogOpen.value = true;
}

function removeItem(index) {
    items.value.splice(index, 1);
    emit('update:items', [...items.value]);
}

</script>
<template>
    <div class="mt-2 pt-2">
        <div v-if="!disableEditing" class="">
            <FormControl>
                <Button type="button" variant="outline" @click="openEditItemDialog({})" class="text-sm text-muted-light-green hover:text-light-green">
                    <component :is="ReceiptText" />Add New Item
                </Button>
            </FormControl>
            <ItemDialog @item-added="addNewOrEditItem($event)" :item="editingItem" v-model:open="isDialogOpen" />
        </div>
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>
                        <component :is="ReceiptText" class="w-4 h-4 inline-block mr-1" /><span>Description</span>
                    </TableHead>
                    <TableHead>Price</TableHead>
                    <TableHead>Qty</TableHead>
                    <TableHead>Unit</TableHead>
                    <TableHead>Amount</TableHead>
                    <TableHead v-if="!disableEditing" >Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="(item, index) in items" 
                    :key="item.id ?? item.description" 
                    :class="[ !disableEditing ? 'cursor-pointer' : '', 'hover:bg-muted/50']"
                >
                    <TableCell @dblclick="openEditItemDialog(item)">
                        <span>{{ item.description }}</span>
                    </TableCell>
                    <TableCell @click="openEditItemDialog(item)">{{ item.price }}</TableCell>
                    <TableCell @click="openEditItemDialog(item)">{{ item.qty }}</TableCell>
                    <TableCell @click="openEditItemDialog(item)">{{ item.unit }}</TableCell>
                    <TableCell @click="openEditItemDialog(item)">{{ item.amount }}</TableCell>
                    <TableCell v-if="!disableEditing"><component :is="Trash2" @click="removeItem(index)"/></TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>
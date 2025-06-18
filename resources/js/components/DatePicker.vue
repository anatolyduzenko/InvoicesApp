<script setup lang="ts">
import {
    DateFormatter,
    // type DateValue,
    getLocalTimeZone,
} from '@internationalized/date'
import { CalendarIcon } from 'lucide-vue-next'

import { computed } from 'vue'
import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import { Calendar } from '@/components/ui/calendar'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { FormControl } from '@/components/ui/form';

const df = new DateFormatter('en-UK', {
    dateStyle: 'short',
})

const modelValue = defineModel<string | undefined>()

const internalValue = computed({
    get() {
        return modelValue.value ? modelValue.value : undefined
    },
    set(newVal) {
        modelValue.value = newVal ? newVal.toString() : undefined
    },
})
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <FormControl>
                <Button
                    variant="outline"
                    :class="cn(
                    'justify-start text-left font-normal hover:cursor-pointer',
                    !internalValue && 'text-muted-foreground',
                    )"
                >
                    <CalendarIcon class="mr-2 h-4 w-4" />
                    {{ internalValue ? df.format(internalValue.toDate(getLocalTimeZone())) : "Pick a date" }}
                </Button>
            </FormControl>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0">
            <Calendar v-model="internalValue" initial-focus />
        </PopoverContent>
    </Popover>
</template>
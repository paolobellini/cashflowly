<script setup lang="ts">
import { ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import type { TransactionWithDetails } from '@/types'
import { cn } from '@/lib/utils'
import { Plus, Wallet as WalletIcon, Tag, Calendar as CalendarIcon, Type, Repeat } from 'lucide-vue-next'
import { MOCK_CATEGORIES, MOCK_WALLETS } from '@/constants/mockData'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from '@/components/ui/dialog'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { Calendar } from '@/components/ui/calendar'
import { Switch } from '@/components/ui/switch'
import { getLocalTimeZone, today } from '@internationalized/date'

const { t } = useI18n()

const props = defineProps<{
  isOpen: boolean
  initialData?: TransactionWithDetails | null
}>()

const emit = defineEmits<{
  'update:isOpen': [value: boolean]
  close: []
}>()

const type = ref<'expense' | 'income'>('expense')
// eslint-disable-next-line @typescript-eslint/no-explicit-any
const date = ref<any>(today(getLocalTimeZone()))
const isAddingCategory = ref(false)
const newCategoryName = ref('')
const isRecurrence = ref(false)
const frequency = ref('monthly')
// eslint-disable-next-line @typescript-eslint/no-explicit-any
const endDate = ref<any>()

watch(
  () => props.initialData,
  (data) => {
    type.value = data?.type ?? 'expense'
    isRecurrence.value = data?.is_recurrence ?? false
    frequency.value = data?.recurrence?.frequency ?? 'monthly'
  },
)

function handleClose() {
  emit('update:isOpen', false)
  emit('close')
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
function formatCalDate(d: any) {
  return d.toDate(getLocalTimeZone()).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  })
}
</script>

<template>
  <Dialog :open="isOpen" @update:open="handleClose">
    <DialogContent class="sm:max-w-[480px] p-0 overflow-hidden border-none shadow-2xl rounded-xl">
      <div class="bg-primary p-6 text-primary-foreground">
        <DialogHeader>
          <DialogTitle class="text-xl font-semibold">
            {{ initialData ? t('transactions.form.editTransaction') : t('transactions.form.newTransaction') }}
          </DialogTitle>
        </DialogHeader>

        <div class="flex gap-1 mt-4 p-1 bg-white/10 rounded-lg">
          <button
            :class="
              cn(
                'flex-1 py-1.5 rounded-md text-xs font-semibold transition-all',
                type === 'expense'
                  ? 'bg-white text-primary shadow-sm'
                  : 'text-white/70 hover:text-white',
              )
            "
            @click="type = 'expense'"
          >
            {{ t('transactions.form.expense') }}
          </button>
          <button
            :class="
              cn(
                'flex-1 py-1.5 rounded-md text-xs font-semibold transition-all',
                type === 'income'
                  ? 'bg-white text-primary shadow-sm'
                  : 'text-white/70 hover:text-white',
              )
            "
            @click="type = 'income'"
          >
            {{ t('transactions.form.income') }}
          </button>
        </div>
      </div>

      <div class="p-6 space-y-5 bg-card max-h-[80vh] overflow-y-auto">
        <!-- Amount -->
        <div class="space-y-1.5">
          <Label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground/70">{{ t('transactions.form.amount') }}</Label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xl font-semibold text-muted-foreground">$</span>
            <Input
              type="number"
              :placeholder="t('transactions.form.amountPlaceholder')"
              class="pl-8 h-12 text-2xl font-semibold bg-muted/20 border-border/50 focus-visible:ring-primary/20"
            />
          </div>
        </div>

        <!-- Wallet & Category -->
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-1.5">
            <Label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground/70 flex items-center gap-1.5">
              <WalletIcon class="size-3" /> {{ t('transactions.form.wallet') }}
            </Label>
            <Select :default-value="MOCK_WALLETS[0]?.id">
              <SelectTrigger class="h-10 bg-muted/20 border-border/50 focus:ring-primary/20">
                <SelectValue :placeholder="t('transactions.form.selectWallet')" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem v-for="w in MOCK_WALLETS" :key="w.id" :value="w.id">
                  <div class="flex items-center gap-2">
                    <div class="size-2 rounded-full" :style="{ backgroundColor: w.color }" />
                    {{ w.name }}
                  </div>
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div class="space-y-1.5">
            <Label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground/70 flex items-center gap-1.5">
              <Tag class="size-3" /> {{ t('transactions.form.category') }}
            </Label>
            <div v-if="!isAddingCategory" class="flex gap-2">
              <Select :default-value="MOCK_CATEGORIES[0]?.id">
                <SelectTrigger class="h-10 bg-muted/20 border-border/50 focus:ring-primary/20 flex-1">
                  <SelectValue :placeholder="t('transactions.form.selectCategory')" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem
                    v-for="c in MOCK_CATEGORIES.filter((cat) => cat.type === type)"
                    :key="c.id"
                    :value="c.id"
                  >
                    {{ c.name }}
                  </SelectItem>
                </SelectContent>
              </Select>
              <Button
                variant="outline"
                size="icon"
                class="h-10 w-10 shrink-0 border-dashed border-primary/30 text-primary hover:bg-primary/5"
                @click="isAddingCategory = true"
              >
                <Plus class="size-4" />
              </Button>
            </div>
            <div v-else class="flex gap-2">
              <Input
                v-model="newCategoryName"
                :placeholder="t('transactions.form.categoryName')"
                class="h-10 bg-muted/20 border-primary/30 focus-visible:ring-primary/20 flex-1"
                autofocus
              />
              <Button
                variant="ghost"
                size="icon"
                class="h-10 w-10 shrink-0 text-muted-foreground hover:text-foreground"
                @click="isAddingCategory = false"
              >
                <Plus class="size-4 rotate-45" />
              </Button>
            </div>
          </div>
        </div>

        <!-- Date & Description -->
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-1.5">
            <Label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground/70 flex items-center gap-1.5">
              <CalendarIcon class="size-3" /> {{ t('transactions.form.date') }}
            </Label>
            <Popover>
              <PopoverTrigger as-child>
                <Button
                  variant="outline"
                  :class="
                    cn(
                      'w-full h-10 justify-start text-left font-normal bg-muted/20 border-border/50 hover:bg-muted/30',
                      !date && 'text-muted-foreground',
                    )
                  "
                >
                  <CalendarIcon class="mr-2 size-3.5" />
                  {{ date ? formatCalDate(date) : t('transactions.form.pickDate') }}
                </Button>
              </PopoverTrigger>
              <PopoverContent class="w-auto p-0 border-none shadow-xl" align="start">
                <Calendar v-model="date" />
              </PopoverContent>
            </Popover>
          </div>

          <div class="space-y-1.5">
            <Label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground/70 flex items-center gap-1.5">
              <Type class="size-3" /> {{ t('transactions.form.description') }}
            </Label>
            <Input
              :placeholder="t('transactions.form.notesPlaceholder')"
              class="h-10 bg-muted/20 border-border/50 focus-visible:ring-primary/20"
            />
          </div>
        </div>

        <!-- Recurrence -->
        <div class="pt-2">
          <div class="flex items-center justify-between p-3 rounded-xl bg-muted/20 border border-border/40">
            <div class="flex items-center gap-3">
              <div
                :class="
                  cn(
                    'size-8 rounded-lg flex items-center justify-center transition-colors',
                    isRecurrence ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground',
                  )
                "
              >
                <Repeat class="size-4" />
              </div>
              <div class="flex flex-col">
                <span class="text-xs font-bold">{{ t('transactions.form.repeat') }}</span>
                <span class="text-[10px] text-muted-foreground font-medium">{{ t('transactions.form.repeatHint') }}</span>
              </div>
            </div>
            <Switch v-model:checked="isRecurrence" />
          </div>

          <div v-if="isRecurrence" class="overflow-hidden space-y-4 mt-3">
            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <Label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground/70">{{ t('transactions.form.frequency') }}</Label>
                <Select v-model="frequency">
                  <SelectTrigger class="h-10 bg-muted/20 border-border/50 focus:ring-primary/20">
                    <SelectValue :placeholder="t('transactions.form.selectFrequency')" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="daily">{{ t('transactions.form.daily') }}</SelectItem>
                    <SelectItem value="weekly">{{ t('transactions.form.weekly') }}</SelectItem>
                    <SelectItem value="monthly">{{ t('transactions.form.monthly') }}</SelectItem>
                    <SelectItem value="yearly">{{ t('transactions.form.yearly') }}</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div class="space-y-1.5">
                <Label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground/70">{{ t('transactions.form.endDate') }}</Label>
                <Popover>
                  <PopoverTrigger as-child>
                    <Button
                      variant="outline"
                      :class="
                        cn(
                          'w-full h-10 justify-start text-left font-normal bg-muted/20 border-border/50 hover:bg-muted/30',
                          !endDate && 'text-muted-foreground',
                        )
                      "
                    >
                      <CalendarIcon class="mr-2 size-3.5" />
                      {{ endDate ? formatCalDate(endDate) : t('transactions.form.noEndDate') }}
                    </Button>
                  </PopoverTrigger>
                  <PopoverContent class="w-auto p-0 border-none shadow-xl" align="start">
                    <Calendar v-model="endDate" />
                  </PopoverContent>
                </Popover>
              </div>
            </div>
          </div>
        </div>

        <DialogFooter class="pt-2">
          <Button variant="ghost" class="h-10 px-6" @click="handleClose">{{ t('transactions.form.cancel') }}</Button>
          <Button class="h-10 px-8 font-semibold">
            {{ initialData ? t('transactions.form.update') : t('transactions.form.save') }}
          </Button>
        </DialogFooter>
      </div>
    </DialogContent>
  </Dialog>
</template>

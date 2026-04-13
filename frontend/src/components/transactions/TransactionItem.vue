<script setup lang="ts">
import type { TransactionWithDetails } from '@/types'
import { useI18n } from 'vue-i18n'
import { cn } from '@/lib/utils'
import { Edit2, Trash2 } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { Checkbox } from '@/components/ui/checkbox'
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip'
import CategoryIcon from '@/components/shared/CategoryIcon.vue'

const { t } = useI18n()

const props = defineProps<{
  transaction: TransactionWithDetails
  isSelected: boolean
  viewMode: 'comfortable' | 'ultra-compact' | 'board'
}>()

const emit = defineEmits<{
  select: [id: string]
  edit: [transaction: TransactionWithDetails]
  delete: [transaction: TransactionWithDetails]
}>()

const isIncome = props.transaction.type === 'income'

function formatAmount(amount: number, currency: string) {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency }).format(amount)
}
</script>

<template>
  <!-- Board view -->
  <div
    v-if="viewMode === 'board'"
    :class="
      cn(
        'group relative flex flex-col gap-3 p-4 rounded-xl transition-all duration-300 cursor-pointer',
        'bg-card border border-border/50 hover:border-primary/30 shadow-sm hover:shadow-md',
        isSelected && 'border-primary bg-primary/5 shadow-md',
      )
    "
    @click="emit('select', transaction.id)"
  >
    <div class="flex items-start justify-between">
      <CategoryIcon :icon="transaction.category.icon" :color="transaction.category.color" size="sm" />
      <Checkbox
        :model-value="isSelected"
        class="rounded border-muted-foreground/30 data-[state=checked]:bg-primary data-[state=checked]:border-primary"
        @click.stop
        @update:model-value="emit('select', transaction.id)"
      />
    </div>

    <div class="space-y-1">
      <h4 class="font-bold text-sm text-foreground leading-tight">
        {{ transaction.description || transaction.category.name }}
      </h4>
      <div class="flex items-center gap-2">
        <span class="text-[10px] text-muted-foreground/70 flex items-center gap-1">
          <div class="size-1.5 rounded-full" :style="{ backgroundColor: transaction.wallet.color }" />
          {{ transaction.wallet.name }}
        </span>
      </div>
    </div>

    <div class="flex items-center justify-between mt-2 pt-2 border-t border-border/30">
      <div :class="cn('text-sm font-black', isIncome ? 'text-emerald-600' : 'text-foreground')">
        {{ isIncome ? '+' : '-' }}{{ formatAmount(transaction.amount, transaction.wallet.currency) }}
      </div>

      <div class="flex items-center gap-1" @click.stop>
        <Tooltip>
          <TooltipTrigger as-child>
            <Button
              variant="ghost"
              size="icon"
              class="h-7 w-7 rounded-md text-muted-foreground hover:text-foreground"
              @click="emit('edit', transaction)"
            >
              <Edit2 class="size-3" />
            </Button>
          </TooltipTrigger>
          <TooltipContent side="bottom">{{ t('transactions.item.edit') }}</TooltipContent>
        </Tooltip>
        <Tooltip>
          <TooltipTrigger as-child>
            <Button
              variant="ghost"
              size="icon"
              class="h-7 w-7 rounded-md text-muted-foreground hover:text-destructive"
              @click="emit('delete', transaction)"
            >
              <Trash2 class="size-3" />
            </Button>
          </TooltipTrigger>
          <TooltipContent side="bottom">{{ t('transactions.item.delete') }}</TooltipContent>
        </Tooltip>
      </div>
    </div>
  </div>

  <!-- List view (comfortable / ultra-compact) -->
  <div
    v-else
    :class="
      cn(
        'group relative flex items-center transition-all duration-300 cursor-pointer',
        viewMode === 'ultra-compact' ? 'px-4 py-2.5 gap-4 rounded-lg' : 'p-4 gap-4 rounded-xl',
        'bg-card border border-border/50 hover:border-primary/30 shadow-sm hover:shadow-md',
        isSelected && 'border-primary bg-primary/5 shadow-md',
      )
    "
    @click="emit('select', transaction.id)"
  >
    <div class="flex items-center gap-3 shrink-0">
      <Checkbox
        :model-value="isSelected"
        class="rounded border-muted-foreground/30 data-[state=checked]:bg-primary data-[state=checked]:border-primary"
        @click.stop
        @update:model-value="emit('select', transaction.id)"
      />
      <CategoryIcon
        v-if="viewMode !== 'ultra-compact'"
        :icon="transaction.category.icon"
        :color="transaction.category.color"
      />
    </div>

    <div class="flex-1 min-w-0 flex items-center justify-between gap-4">
      <div class="min-w-0 flex items-center gap-3">
        <div
          v-if="viewMode === 'ultra-compact'"
          class="size-2.5 rounded-full shrink-0"
          :style="{ backgroundColor: transaction.category.color }"
        />
        <h4
          :class="
            cn(
              'font-semibold text-foreground truncate',
              viewMode === 'ultra-compact' ? 'text-sm' : 'text-base',
            )
          "
        >
          {{ transaction.description || transaction.category.name }}
        </h4>
        <div v-if="viewMode !== 'ultra-compact'" class="flex items-center gap-2 mt-0.5">
          <span class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground/70">
            {{ transaction.category.name }}
          </span>
          <span class="text-[10px] text-muted-foreground/70 flex items-center gap-1">
            <div class="size-1.5 rounded-full" :style="{ backgroundColor: transaction.wallet.color }" />
            {{ transaction.wallet.name }}
          </span>
        </div>
      </div>

      <div class="flex items-center gap-4">
        <span
          v-if="viewMode === 'ultra-compact'"
          class="text-muted-foreground hidden md:block text-[10px] font-bold uppercase tracking-widest"
        >
          {{ transaction.category.name }}
        </span>
        <div
          :class="
            cn(
              'flex items-center gap-1 font-bold',
              viewMode === 'ultra-compact' ? 'text-sm' : 'text-base',
              isIncome ? 'text-emerald-600' : 'text-foreground',
            )
          "
        >
          {{ isIncome ? '+' : '-' }}{{ formatAmount(transaction.amount, transaction.wallet.currency) }}
        </div>
      </div>
    </div>

    <div
      :class="
        cn(
          'flex items-center gap-1 ml-2',
          viewMode === 'ultra-compact' ? 'hidden sm:flex' : '',
        )
      "
      @click.stop
    >
      <Tooltip>
        <TooltipTrigger as-child>
          <Button
            variant="ghost"
            size="icon"
            :class="cn('rounded-md text-muted-foreground hover:text-foreground', viewMode === 'ultra-compact' ? 'h-6 w-6' : 'h-8 w-8')"
            @click="emit('edit', transaction)"
          >
            <Edit2 :class="viewMode === 'ultra-compact' ? 'size-3' : 'size-3.5'" />
          </Button>
        </TooltipTrigger>
        <TooltipContent side="bottom">{{ t('transactions.item.edit') }}</TooltipContent>
      </Tooltip>
      <Tooltip>
        <TooltipTrigger as-child>
          <Button
            variant="ghost"
            size="icon"
            :class="cn('rounded-md text-muted-foreground hover:text-destructive', viewMode === 'ultra-compact' ? 'h-6 w-6' : 'h-8 w-8')"
            @click="emit('delete', transaction)"
          >
            <Trash2 :class="viewMode === 'ultra-compact' ? 'size-3' : 'size-3.5'" />
          </Button>
        </TooltipTrigger>
        <TooltipContent side="bottom">{{ t('transactions.item.delete') }}</TooltipContent>
      </Tooltip>
    </div>
  </div>
</template>

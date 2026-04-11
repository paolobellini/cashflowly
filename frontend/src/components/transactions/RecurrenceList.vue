<script setup lang="ts">
import type { TransactionWithDetails } from '@/types'
import { computed } from 'vue'
import { cn } from '@/lib/utils'
import { Repeat, Calendar, Clock, MoreVertical, Edit2, Trash2, Pause } from 'lucide-vue-next'
import { format } from 'date-fns'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
  DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu'
import EmptyState from '@/components/shared/EmptyState.vue'

const props = defineProps<{
  transactions: TransactionWithDetails[]
}>()

const emit = defineEmits<{
  edit: [transaction: TransactionWithDetails]
}>()

const recurringTransactions = computed(() => props.transactions.filter((t) => t.is_recurrence))

function formatAmount(amount: number, currency: string) {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency }).format(amount)
}
</script>

<template>
  <EmptyState
    v-if="recurringTransactions.length === 0"
    :icon="Repeat"
    title="No recurring schedules"
    description="Set up automated transactions to save time and track regular payments."
    dashed
  />

  <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <div
      v-for="transaction in recurringTransactions"
      :key="transaction.id"
      class="group bg-card border border-border/50 rounded-2xl p-5 hover:border-primary/30 transition-all shadow-sm hover:shadow-md"
    >
      <div class="flex items-start justify-between mb-4">
        <div class="flex items-center gap-3">
          <div
            class="size-10 rounded-xl flex items-center justify-center shadow-sm"
            :style="{ backgroundColor: `${transaction.category.color}15`, color: transaction.category.color }"
          >
            <Repeat class="size-5" />
          </div>
          <div>
            <h4 class="font-bold text-sm truncate max-w-[150px]">
              {{ transaction.description || transaction.category.name }}
            </h4>
            <span class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground/70">
              {{ transaction.category.name }}
            </span>
          </div>
        </div>

        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="icon" class="h-8 w-8 rounded-lg">
              <MoreVertical class="size-4" />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end" class="w-40">
            <DropdownMenuItem class="gap-2" @click="emit('edit', transaction)">
              <Edit2 class="size-3.5" /> Edit Schedule
            </DropdownMenuItem>
            <DropdownMenuItem class="gap-2">
              <Pause class="size-3.5" /> Pause
            </DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem class="gap-2 text-destructive focus:text-destructive">
              <Trash2 class="size-3.5" /> Delete
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>

      <div class="space-y-4">
        <div class="flex items-center justify-between p-3 bg-muted/30 rounded-xl">
          <div class="flex flex-col">
            <span class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground/60">Amount</span>
            <span :class="cn('text-lg font-black', transaction.type === 'income' ? 'text-emerald-600' : 'text-foreground')">
              {{ transaction.type === 'income' ? '+' : '-' }}{{ formatAmount(transaction.amount, transaction.wallet.currency) }}
            </span>
          </div>
          <div class="text-right">
            <span class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground/60">Frequency</span>
            <Badge class="block mt-1 bg-primary/10 text-primary border-none font-bold text-[10px] uppercase tracking-wider">
              {{ transaction.recurrence?.frequency ?? 'Monthly' }}
            </Badge>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="flex items-center gap-2 text-xs text-muted-foreground">
            <Calendar class="size-3.5" />
            <span>Next: {{ format(new Date(), 'MMM d') }}</span>
          </div>
          <div class="flex items-center gap-2 text-xs text-muted-foreground justify-end">
            <Clock class="size-3.5" />
            <span>Active</span>
          </div>
        </div>

        <div class="pt-2 border-t border-border/30 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <div class="size-2 rounded-full" :style="{ backgroundColor: transaction.wallet.color }" />
            <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">
              {{ transaction.wallet.name }}
            </span>
          </div>
          <Button
            variant="ghost"
            size="sm"
            class="h-7 text-[10px] font-bold uppercase tracking-widest hover:bg-primary/5 hover:text-primary"
          >
            View History
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>

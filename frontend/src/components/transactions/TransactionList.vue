<script setup lang="ts">
import type { TransactionWithDetails } from '@/types'
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { cn } from '@/lib/utils'
import { isToday, isYesterday, format } from 'date-fns'

const { t } = useI18n()
import { Badge } from '@/components/ui/badge'
import EmptyState from '@/components/shared/EmptyState.vue'
import TransactionItem from './TransactionItem.vue'

const props = defineProps<{
  transactions: TransactionWithDetails[]
  selectedIds: string[]
  viewMode: 'comfortable' | 'ultra-compact' | 'board'
}>()

const emit = defineEmits<{
  select: [id: string]
  edit: [transaction: TransactionWithDetails]
  delete: [transaction: TransactionWithDetails]
}>()

const groupedByDate = computed(() => {
  const groups: Record<string, TransactionWithDetails[]> = {}
  for (const t of props.transactions) {
    ;(groups[t.date] ??= []).push(t)
  }
  return groups
})

const sortedDates = computed(() =>
  Object.keys(groupedByDate.value).sort((a, b) => b.localeCompare(a)),
)

const groupedByCategory = computed(() => {
  const groups: Record<string, TransactionWithDetails[]> = {}
  for (const t of props.transactions) {
    ;(groups[t.category.name] ??= []).push(t)
  }
  return groups
})

function formatDateHeader(dateStr: string) {
  const date = new Date(dateStr)
  if (isToday(date)) return t('transactions.list.today')
  if (isYesterday(date)) return t('transactions.list.yesterday')
  return format(date, 'MMM d, yyyy')
}
</script>

<template>
  <!-- Board view -->
  <div
    v-if="viewMode === 'board'"
    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6 pb-8"
  >
    <div v-for="catName in Object.keys(groupedByCategory)" :key="catName" class="flex flex-col gap-4 min-w-0">
      <div class="flex items-center justify-between px-2">
        <div class="flex items-center gap-2 min-w-0">
          <div
            class="size-2 rounded-full shrink-0"
            :style="{ backgroundColor: groupedByCategory[catName]![0]!.category.color }"
          />
          <h3 class="text-[10px] font-black uppercase tracking-widest text-foreground truncate">
            {{ catName }}
          </h3>
          <Badge
            variant="secondary"
            class="text-[10px] h-5 px-1.5 rounded-md bg-muted/50 text-muted-foreground border-none shrink-0"
          >
            {{ groupedByCategory[catName]!.length }}
          </Badge>
        </div>
      </div>

      <div class="flex-1 bg-muted/10 rounded-2xl p-2 border border-border/30 space-y-2 min-h-[100px]">
        <TransactionItem
          v-for="transaction in groupedByCategory[catName]"
          :key="transaction.id"
          :transaction="transaction"
          :is-selected="selectedIds.includes(transaction.id)"
          view-mode="board"
          @select="emit('select', $event)"
          @edit="emit('edit', $event)"
          @delete="emit('delete', $event)"
        />
      </div>
    </div>
  </div>

  <!-- List view -->
  <div
    v-else
    :class="cn('pb-20 transition-all duration-300', viewMode === 'comfortable' ? 'space-y-10' : 'space-y-4')"
  >
    <div v-for="date in sortedDates" :key="date" class="space-y-3">
      <div class="flex items-center gap-3 px-1">
        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground/50">
          {{ formatDateHeader(date) }}
        </h3>
        <div class="h-px bg-border/30 flex-1" />
      </div>

      <div :class="cn('grid transition-all duration-300', viewMode === 'comfortable' ? 'gap-3' : 'gap-0.5')">
        <TransactionItem
          v-for="transaction in groupedByDate[date]"
          :key="transaction.id"
          :transaction="transaction"
          :is-selected="selectedIds.includes(transaction.id)"
          :view-mode="viewMode"
          @select="emit('select', $event)"
          @edit="emit('edit', $event)"
          @delete="emit('delete', $event)"
        />
      </div>
    </div>

    <EmptyState
      v-if="transactions.length === 0"
      emoji="&#x1F4B8;"
      :title="t('transactions.list.noTransactions')"
      :description="t('transactions.list.noTransactionsHint')"
    />
  </div>
</template>

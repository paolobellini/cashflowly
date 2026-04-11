<script setup lang="ts">
import { ref, computed } from 'vue'
import type { TransactionWithDetails } from '@/types'
import { cn } from '@/lib/utils'
import { MOCK_TRANSACTIONS } from '@/constants/mockData'
import { Plus, Download } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import PageHeader from '@/components/shared/PageHeader.vue'
import TransactionStats from '@/components/transactions/TransactionStats.vue'
import TransactionFilters from '@/components/transactions/TransactionFilters.vue'
import TransactionList from '@/components/transactions/TransactionList.vue'
import TransactionPagination from '@/components/transactions/TransactionPagination.vue'
import TransactionBulkActions from '@/components/transactions/TransactionBulkActions.vue'
import TransactionForm from '@/components/transactions/TransactionForm.vue'
import RecurrenceList from '@/components/transactions/RecurrenceList.vue'

const transactions = ref<TransactionWithDetails[]>(MOCK_TRANSACTIONS)
const selectedIds = ref<string[]>([])
const isFormOpen = ref(false)
const editingTransaction = ref<TransactionWithDetails | null>(null)
const searchQuery = ref('')
const activeFilter = ref<'all' | 'income' | 'expense'>('all')
const viewMode = ref<'comfortable' | 'ultra-compact' | 'board'>('comfortable')
const currentPage = ref(1)
const itemsPerPage = ref(10)
const activeTab = ref<'transactions' | 'recurrences'>('transactions')

const filteredTransactions = computed(() =>
  transactions.value.filter((t) => {
    const matchesSearch =
      t.description?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      t.category.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesFilter = activeFilter.value === 'all' || t.type === activeFilter.value
    return matchesSearch && matchesFilter
  }),
)

const recurringCount = computed(() => transactions.value.filter((t) => t.is_recurrence).length)

function handleSelect(id: string) {
  const idx = selectedIds.value.indexOf(id)
  if (idx >= 0) {
    selectedIds.value.splice(idx, 1)
  } else {
    selectedIds.value.push(id)
  }
}

function handleEdit(transaction: TransactionWithDetails) {
  editingTransaction.value = transaction
  isFormOpen.value = true
}

function handleNewTransaction() {
  editingTransaction.value = null
  isFormOpen.value = true
}

function handleCloseForm() {
  isFormOpen.value = false
  editingTransaction.value = null
}
</script>

<template>
  <div class="p-8 space-y-10 w-full max-w-[1600px] mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
      <div class="space-y-4">
        <PageHeader
          title="Transactions"
          description="Manage and track your financial activities across all wallets."
        />

        <div class="flex items-center gap-1 p-1 bg-muted/30 rounded-xl w-fit">
          <button
            :class="
              cn(
                'px-6 py-2 rounded-lg text-xs font-bold transition-all',
                activeTab === 'transactions'
                  ? 'bg-background text-foreground shadow-sm'
                  : 'text-muted-foreground hover:text-foreground',
              )
            "
            @click="activeTab = 'transactions'"
          >
            All Transactions
          </button>
          <button
            :class="
              cn(
                'px-6 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2',
                activeTab === 'recurrences'
                  ? 'bg-background text-foreground shadow-sm'
                  : 'text-muted-foreground hover:text-foreground',
              )
            "
            @click="activeTab = 'recurrences'"
          >
            Recurring Schedules
            <Badge
              variant="secondary"
              class="h-4 px-1 text-[8px] font-black bg-primary/10 text-primary border-none"
            >
              {{ recurringCount }}
            </Badge>
          </button>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <Button
          variant="outline"
          size="sm"
          class="h-10 gap-2 border-border/50 px-4 rounded-xl hover:bg-muted/50 transition-all"
        >
          <Download class="size-4" /> Export
        </Button>
        <Button
          size="sm"
          class="h-10 gap-2 font-semibold px-6 shadow-lg shadow-primary/20 rounded-xl"
          @click="handleNewTransaction"
        >
          <Plus class="size-4" /> New Transaction
        </Button>
      </div>
    </div>

    <!-- Stats -->
    <TransactionStats :transactions="filteredTransactions" />

    <!-- Transactions tab -->
    <template v-if="activeTab === 'transactions'">
      <TransactionFilters
        v-model:search-query="searchQuery"
        v-model:active-filter="activeFilter"
        v-model:view-mode="viewMode"
      />

      <TransactionList
        :transactions="filteredTransactions"
        :selected-ids="selectedIds"
        :view-mode="viewMode"
        @select="handleSelect"
        @edit="handleEdit"
      />

      <TransactionPagination
        v-model:current-page="currentPage"
        v-model:items-per-page="itemsPerPage"
        :total-items="filteredTransactions.length"
        :total-pages="Math.ceil(filteredTransactions.length / itemsPerPage)"
      />
    </template>

    <!-- Recurrences tab -->
    <RecurrenceList
      v-else
      :transactions="transactions"
      @edit="handleEdit"
    />

    <!-- Bulk actions -->
    <TransactionBulkActions
      :count="selectedIds.length"
      @clear="selectedIds = []"
    />

    <!-- Form dialog -->
    <TransactionForm
      :is-open="isFormOpen"
      :initial-data="editingTransaction"
      @close="handleCloseForm"
    />
  </div>
</template>

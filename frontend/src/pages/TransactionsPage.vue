<script setup lang="ts">
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import type { TransactionWithDetails } from '@/types'
import { MOCK_TRANSACTIONS } from '@/constants/mockData'
import { Plus, Download } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import PageHeader from '@/components/shared/PageHeader.vue'
import TransactionStats from '@/components/transactions/TransactionStats.vue'
import TransactionFilters from '@/components/transactions/TransactionFilters.vue'
import TransactionList from '@/components/transactions/TransactionList.vue'
import TransactionPagination from '@/components/transactions/TransactionPagination.vue'
import TransactionBulkActions from '@/components/transactions/TransactionBulkActions.vue'
import TransactionForm from '@/components/transactions/TransactionForm.vue'
import RecurrenceList from '@/components/transactions/RecurrenceList.vue'

const { t } = useI18n()

const transactions = ref<TransactionWithDetails[]>(MOCK_TRANSACTIONS)
const selectedIds = ref<string[]>([])
const isFormOpen = ref(false)
const editingTransaction = ref<TransactionWithDetails | null>(null)
const searchQuery = ref('')
const activeFilter = ref<'all' | 'income' | 'expense' | 'recurring'>('all')
const viewMode = ref<'comfortable' | 'ultra-compact' | 'board'>('comfortable')
const currentPage = ref(1)
const itemsPerPage = ref(10)

const filteredTransactions = computed(() =>
  transactions.value.filter((t) => {
    const matchesSearch =
      t.description?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      t.category.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesFilter =
      activeFilter.value === 'all' ||
      activeFilter.value === 'recurring' ||
      t.type === activeFilter.value
    const matchesRecurring = activeFilter.value !== 'recurring' || t.is_recurrence
    return matchesSearch && matchesFilter && matchesRecurring
  }),
)

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
      <PageHeader
        :title="t('transactions.title')"
        :description="t('transactions.description')"
      />

      <div class="flex items-center gap-3">
        <Button
          variant="outline"
          size="sm"
          class="h-10 gap-2 border-border/50 px-4 rounded-xl hover:bg-muted/50 transition-all"
        >
          <Download class="size-4" /> {{ t('transactions.export') }}
        </Button>
        <Button
          size="sm"
          class="h-10 gap-2 font-semibold px-6 shadow-lg shadow-primary/20 rounded-xl"
          @click="handleNewTransaction"
        >
          <Plus class="size-4" /> {{ t('transactions.newTransaction') }}
        </Button>
      </div>
    </div>

    <!-- Stats -->
    <TransactionStats :transactions="filteredTransactions" />

    <!-- Filters -->
    <TransactionFilters
      v-model:search-query="searchQuery"
      v-model:active-filter="activeFilter"
      v-model:view-mode="viewMode"
    />

    <!-- Recurrence cards -->
    <RecurrenceList
      v-if="activeFilter === 'recurring'"
      :transactions="filteredTransactions"
      @edit="handleEdit"
    />

    <!-- Transaction list -->
    <template v-else>
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

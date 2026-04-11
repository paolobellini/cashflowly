<script setup lang="ts">
import type { TransactionWithDetails } from '@/types'
import { computed } from 'vue'
import { ArrowUpDown, Calendar } from 'lucide-vue-next'
import StatCard from '@/components/shared/StatCard.vue'

const props = defineProps<{
  transactions: TransactionWithDetails[]
}>()

const totalIncome = computed(() =>
  props.transactions.filter((t) => t.type === 'income').reduce((sum, t) => sum + t.amount, 0),
)

const totalExpense = computed(() =>
  props.transactions.filter((t) => t.type === 'expense').reduce((sum, t) => sum + t.amount, 0),
)

const totalBalance = computed(() => totalIncome.value - totalExpense.value)

const savingsRate = computed(() => {
  if (totalIncome.value === 0) return 0
  return ((totalIncome.value - totalExpense.value) / totalIncome.value) * 100
})

function formatCurrency(amount: number) {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount)
}
</script>

<template>
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Balance card -->
    <div class="lg:col-span-8 bg-primary p-6 rounded-2xl shadow-xl shadow-primary/10 relative overflow-hidden group">
      <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 blur-3xl group-hover:bg-white/20 transition-all duration-700" />
      <div class="absolute bottom-0 left-0 w-32 h-32 bg-black/10 rounded-full -ml-16 -mb-16 blur-2xl" />

      <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
          <p class="text-primary-foreground/70 text-[10px] font-bold uppercase tracking-widest">Total Balance</p>
          <h2 class="text-3xl font-bold text-primary-foreground mt-1">
            {{ formatCurrency(totalBalance) }}
          </h2>
        </div>

        <div class="flex items-center gap-8">
          <div class="flex flex-col">
            <span class="text-primary-foreground/60 text-[10px] font-bold uppercase">Income</span>
            <span class="text-primary-foreground text-lg font-bold">+{{ formatCurrency(totalIncome) }}</span>
          </div>
          <div class="w-px h-8 bg-primary-foreground/20 hidden md:block" />
          <div class="flex flex-col">
            <span class="text-primary-foreground/60 text-[10px] font-bold uppercase">Expense</span>
            <span class="text-primary-foreground text-lg font-bold">-{{ formatCurrency(totalExpense) }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Side stats -->
    <div class="lg:col-span-4 grid grid-cols-2 gap-6">
      <StatCard
        label="Savings"
        :value="`${savingsRate.toFixed(1)}%`"
        :icon="ArrowUpDown"
        icon-class="bg-emerald-500/10 text-emerald-600"
      />
      <StatCard label="Count" :value="String(transactions.length)" :icon="Calendar" />
    </div>
  </div>
</template>

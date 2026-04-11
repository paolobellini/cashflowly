<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { cn } from '@/lib/utils'
import {
  Search,
  Filter,
  Calendar as CalendarIcon,
  LayoutGrid,
  ArrowUpDown,
  Columns,
} from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
  DropdownMenuSeparator,
  DropdownMenuLabel,
  DropdownMenuCheckboxItem,
} from '@/components/ui/dropdown-menu'

const { t } = useI18n()

const searchQuery = defineModel<string>('searchQuery', { default: '' })
const activeFilter = defineModel<'all' | 'income' | 'expense' | 'recurring'>('activeFilter', { default: 'all' })
const viewMode = defineModel<'comfortable' | 'ultra-compact' | 'board'>('viewMode', {
  default: 'comfortable',
})

const filterOptions = [
  { value: 'all' as const, labelKey: 'transactions.filters.all' },
  { value: 'income' as const, labelKey: 'transactions.filters.income' },
  { value: 'expense' as const, labelKey: 'transactions.filters.expense' },
  { value: 'recurring' as const, labelKey: 'transactions.filters.recurring' },
]
</script>

<template>
  <div class="flex flex-wrap gap-3 items-center">
    <!-- Search -->
    <div class="bg-card border border-border/50 rounded-xl p-1 shadow-sm w-full md:w-72 group focus-within:border-primary/30 transition-all">
      <div class="relative flex items-center">
        <Search class="absolute left-3 size-3.5 text-muted-foreground group-focus-within:text-primary transition-colors" />
        <Input
          :model-value="searchQuery"
          :placeholder="t('transactions.filters.search')"
          class="pl-9 h-9 bg-transparent border-none rounded-lg text-sm focus-visible:ring-0 shadow-none"
          @update:model-value="searchQuery = String($event)"
        />
      </div>
    </div>

    <!-- Type filter -->
    <div class="bg-card border border-border/50 rounded-xl p-1 shadow-sm flex items-center gap-1">
      <button
        v-for="f in filterOptions"
        :key="f.value"
        :class="
          cn(
            'px-4 h-9 rounded-lg text-xs font-bold transition-all relative',
            activeFilter === f.value
              ? 'text-primary bg-primary/10'
              : 'text-muted-foreground hover:text-foreground hover:bg-muted/50',
          )
        "
        @click="activeFilter = f.value"
      >
        {{ t(f.labelKey) }}
      </button>
    </div>

    <!-- Date & more filters -->
    <div class="bg-card border border-border/50 rounded-xl p-1 shadow-sm flex items-center gap-1">
      <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <Button variant="ghost" size="sm" class="h-9 gap-2 text-muted-foreground hover:text-foreground rounded-lg px-3">
            <CalendarIcon class="size-3.5" />
            <span class="text-xs font-bold">Apr 1 - Apr 30</span>
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-56 rounded-xl">
          <DropdownMenuLabel>{{ t('transactions.filters.timePeriod') }}</DropdownMenuLabel>
          <DropdownMenuSeparator />
          <DropdownMenuItem>{{ t('transactions.filters.last7Days') }}</DropdownMenuItem>
          <DropdownMenuItem>{{ t('transactions.filters.last30Days') }}</DropdownMenuItem>
          <DropdownMenuItem>{{ t('transactions.filters.thisMonth') }}</DropdownMenuItem>
          <DropdownMenuItem>{{ t('transactions.filters.customRange') }}</DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>

      <div class="h-5 w-px bg-border/50 mx-1" />

      <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <Button variant="ghost" size="icon" class="h-9 w-9 text-muted-foreground hover:text-foreground rounded-lg">
            <Filter class="size-3.5" />
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-64 p-2 rounded-xl">
          <DropdownMenuLabel class="text-xs font-bold uppercase tracking-widest text-muted-foreground/60 px-2 py-1.5">
            {{ t('transactions.filters.wallets') }}
          </DropdownMenuLabel>
          <DropdownMenuSeparator />
          <DropdownMenuCheckboxItem :checked="true">Main Wallet</DropdownMenuCheckboxItem>
          <DropdownMenuCheckboxItem :checked="true">Savings</DropdownMenuCheckboxItem>
          <DropdownMenuSeparator />
          <DropdownMenuLabel class="text-xs font-bold uppercase tracking-widest text-muted-foreground/60 px-2 py-1.5">
            {{ t('transactions.filters.sort') }}
          </DropdownMenuLabel>
          <DropdownMenuItem class="rounded-lg">{{ t('transactions.filters.newestFirst') }}</DropdownMenuItem>
          <DropdownMenuItem class="rounded-lg">{{ t('transactions.filters.highestAmount') }}</DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>

    <!-- View mode toggles -->
    <div class="bg-card border border-border/50 rounded-xl p-1 shadow-sm flex items-center gap-1 ml-auto">
      <Button
        variant="ghost"
        size="icon"
        :class="cn('h-9 w-9 rounded-lg transition-all', viewMode === 'comfortable' && 'bg-primary/10 text-primary')"
        @click="viewMode = 'comfortable'"
      >
        <LayoutGrid class="size-4" />
      </Button>
      <Button
        variant="ghost"
        size="icon"
        :class="cn('h-9 w-9 rounded-lg transition-all', viewMode === 'ultra-compact' && 'bg-primary/10 text-primary')"
        @click="viewMode = 'ultra-compact'"
      >
        <ArrowUpDown class="size-4" />
      </Button>
      <Button
        variant="ghost"
        size="icon"
        :class="cn('h-9 w-9 rounded-lg transition-all', viewMode === 'board' && 'bg-primary/10 text-primary')"
        @click="viewMode = 'board'"
      >
        <Columns class="size-4" />
      </Button>
    </div>
  </div>
</template>

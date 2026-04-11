<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'

const { t } = useI18n()
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'

const currentPage = defineModel<number>('currentPage', { default: 1 })
const itemsPerPage = defineModel<number>('itemsPerPage', { default: 10 })

defineProps<{
  totalItems: number
  totalPages: number
}>()
</script>

<template>
  <div class="flex flex-col sm:flex-row items-center justify-between py-8 gap-4">
    <div class="flex items-center gap-4">
      <div class="flex items-center gap-1">
        <Button
          variant="outline"
          size="icon"
          class="h-10 w-10 rounded-xl border-border/50 hover:bg-primary/5 hover:text-primary transition-all"
          :disabled="currentPage <= 1"
          @click="currentPage = Math.max(1, currentPage - 1)"
        >
          <ChevronLeft class="size-5" />
        </Button>
        <Button
          variant="outline"
          size="icon"
          class="h-10 w-10 rounded-xl border-border/50 hover:bg-primary/5 hover:text-primary transition-all"
          :disabled="currentPage >= totalPages"
          @click="currentPage++"
        >
          <ChevronRight class="size-5" />
        </Button>
      </div>
      <p class="text-sm font-medium text-muted-foreground">
        {{ t('transactions.pagination.page') }} <span class="text-foreground font-bold">{{ currentPage }}</span> {{ t('transactions.pagination.of') }}
        <span class="text-foreground font-bold">{{ totalPages }}</span>
      </p>
    </div>

    <div class="flex items-center gap-6">
      <div class="flex items-center gap-2">
        <span class="text-xs font-bold text-muted-foreground uppercase tracking-widest">{{ t('transactions.pagination.show') }}</span>
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="sm" class="h-8 gap-2 font-bold rounded-lg px-2">
              {{ itemsPerPage }} <ChevronRight class="size-3 rotate-90" />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent>
            <DropdownMenuItem @click="itemsPerPage = 10">10</DropdownMenuItem>
            <DropdownMenuItem @click="itemsPerPage = 25">25</DropdownMenuItem>
            <DropdownMenuItem @click="itemsPerPage = 50">50</DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>
      <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">
        {{ t('transactions.pagination.total') }} <span class="text-foreground">{{ totalItems }}</span> {{ t('transactions.pagination.items') }}
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import type { Wallet } from '@/types'
import { cn } from '@/lib/utils'
import { MOCK_WALLETS } from '@/constants/mockData'
import {
  ChevronDown,
  Plus,
  Wallet as WalletIcon,
  Check,
  Pencil,
  Trash2,
  LayoutGrid,
} from 'lucide-vue-next'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
  DropdownMenuSeparator,
  DropdownMenuLabel,
} from '@/components/ui/dropdown-menu'
import { useSidebar } from '@/components/ui/sidebar/utils'

const { t } = useI18n()
const { state } = useSidebar()

const wallets = ref<Wallet[]>(MOCK_WALLETS)
const activeWallet = ref<Wallet | null>(null)

function selectWallet(wallet: Wallet | null) {
  activeWallet.value = wallet
}
</script>

<template>
  <div class="w-full px-3 py-2 group-data-[collapsible=icon]:px-0">
    <DropdownMenu>
      <DropdownMenuTrigger as-child>
        <button
          :class="
            cn(
              'w-full flex items-center gap-3 p-2 h-auto rounded-xl transition-all duration-200 group',
              state === 'collapsed'
                ? 'justify-center p-0 size-8 mx-auto'
                : 'justify-between bg-muted/30 hover:bg-muted/50 border border-border/40',
            )
          "
        >
          <div class="flex items-center gap-3 min-w-0">
            <div
              class="size-8 rounded-lg flex items-center justify-center shrink-0 shadow-sm"
              :style="{ backgroundColor: activeWallet?.color ?? '#6366f1' }"
            >
              <WalletIcon class="size-4 text-white" />
            </div>
            <div v-if="state !== 'collapsed'" class="flex flex-col items-start min-w-0">
              <span class="text-xs font-bold truncate w-full">
                {{ activeWallet?.name ?? t('sidebar.wallet.allWallets') }}
              </span>
              <span class="text-[10px] text-muted-foreground font-medium">
                {{ activeWallet ? t('sidebar.wallet.activeWallet') : t('sidebar.wallet.combinedView') }}
              </span>
            </div>
          </div>
          <ChevronDown
            v-if="state !== 'collapsed'"
            class="size-3.5 text-muted-foreground group-hover:text-foreground transition-colors"
          />
        </button>
      </DropdownMenuTrigger>

      <DropdownMenuContent
        align="start"
        side="right"
        :side-offset="22"
        class="w-56 p-1.5 rounded-xl shadow-xl border-border/50 z-100"
      >
        <DropdownMenuLabel
          class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground/60 px-2 py-1"
        >
          {{ t('sidebar.wallet.selectWallet') }}
        </DropdownMenuLabel>
        <DropdownMenuSeparator />

        <!-- All Wallets -->
        <DropdownMenuItem class="rounded-lg h-9 gap-2.5 cursor-pointer" @click="selectWallet(null)">
          <div class="size-7 rounded-md bg-muted flex items-center justify-center shrink-0">
            <LayoutGrid class="size-3.5" />
          </div>
          <span class="text-xs font-bold flex-1">{{ t('sidebar.wallet.allWallets') }}</span>
          <Check v-if="!activeWallet" class="size-3.5 text-primary" />
        </DropdownMenuItem>

        <DropdownMenuSeparator />

        <!-- Wallet list -->
        <div class="max-h-[220px] overflow-y-auto space-y-0.5 py-0.5">
          <DropdownMenuItem
            v-for="wallet in wallets"
            :key="wallet.id"
            class="rounded-lg h-9 gap-2.5 cursor-pointer group/item"
            @click="selectWallet(wallet)"
          >
            <div
              class="size-7 rounded-md flex items-center justify-center shrink-0 shadow-sm"
              :style="{ backgroundColor: wallet.color }"
            >
              <WalletIcon class="size-3.5 text-white" />
            </div>
            <div class="flex flex-col flex-1 min-w-0">
              <span class="text-xs font-bold truncate">{{ wallet.name }}</span>
              <span class="text-[10px] text-muted-foreground truncate capitalize leading-none">{{
                wallet.type
              }}</span>
            </div>

            <div
              class="flex items-center gap-1 opacity-0 group-hover/item:opacity-100 transition-opacity"
            >
              <button
                class="p-0.5 hover:bg-muted rounded text-muted-foreground hover:text-foreground"
                @click.stop
              >
                <Pencil class="size-3" />
              </button>
              <button
                class="p-0.5 hover:bg-muted rounded text-muted-foreground hover:text-destructive"
                @click.stop
              >
                <Trash2 class="size-3" />
              </button>
            </div>

            <Check v-if="activeWallet?.id === wallet.id" class="size-3.5 text-primary shrink-0" />
          </DropdownMenuItem>
        </div>

        <DropdownMenuSeparator />

        <!-- Create new -->
        <DropdownMenuItem
          class="rounded-lg h-9 gap-2.5 cursor-pointer text-primary hover:text-primary hover:bg-primary/5 font-bold"
        >
          <div class="size-7 rounded-md bg-primary/10 flex items-center justify-center shrink-0">
            <Plus class="size-3.5" />
          </div>
          <span class="text-xs">{{ t('sidebar.wallet.createNew') }}</span>
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  </div>
</template>

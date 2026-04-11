<script setup lang="ts">
import { ref, watchEffect, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { SidebarTrigger } from '@/components/ui/sidebar'
import { Separator } from '@/components/ui/separator'
import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import {
  Tooltip,
  TooltipContent,
  TooltipTrigger,
} from '@/components/ui/tooltip'
import { Avatar, AvatarFallback } from '@/components/ui/avatar'
import { Search, Bell, Moon, Sun, Plus } from 'lucide-vue-next'
import CommandPalette from '@/components/shared/CommandPalette.vue'

const { t } = useI18n()

const isDarkMode = ref(false)
const commandPalette = ref<InstanceType<typeof CommandPalette>>()

const isMac = computed(() => navigator.userAgent.includes('Mac'))

watchEffect(() => {
  document.documentElement.classList.toggle('dark', isDarkMode.value)
})

function openCommand() {
  if (commandPalette.value) {
    commandPalette.value.open = true
  }
}
</script>

<template>
  <header
    class="flex h-16 shrink-0 items-center gap-2 border-b bg-background/80 backdrop-blur-md px-4"
  >
    <SidebarTrigger class="-ml-1" />
    <Separator orientation="vertical" class="mr-2 !h-4" />

    <button
      class="relative flex items-center gap-2 w-full max-w-sm h-9 px-3 bg-muted/30 border border-border/50 rounded-lg text-sm text-muted-foreground hover:bg-muted/50 hover:border-border transition-all cursor-pointer"
      @click="openCommand"
    >
      <Search class="size-4 shrink-0" />
      <span class="flex-1 text-left">{{ t('command.searchHint') }}</span>
      <kbd
        class="hidden md:inline-flex items-center gap-0.5 rounded border border-border bg-muted px-1.5 py-0.5 text-[10px] font-medium text-muted-foreground"
      >
        {{ isMac ? '⌘' : 'Ctrl' }}K
      </kbd>
    </button>

    <div class="ml-auto flex items-center gap-2">
      <Tooltip>
        <TooltipTrigger as-child>
          <Button
            variant="ghost"
            size="icon"
            class="size-8 rounded-md text-primary hover:text-primary hover:bg-primary/10"
          >
            <Plus class="size-4" />
          </Button>
        </TooltipTrigger>
        <TooltipContent side="bottom">{{ t('header.newWallet') }}</TooltipContent>
      </Tooltip>

      <Separator orientation="vertical" class="!h-4 mx-1" />

      <Tooltip>
        <TooltipTrigger as-child>
          <Button
            variant="ghost"
            size="icon"
            class="size-8 text-muted-foreground"
            @click="isDarkMode = !isDarkMode"
          >
            <Sun v-if="isDarkMode" class="size-4" />
            <Moon v-else class="size-4" />
          </Button>
        </TooltipTrigger>
        <TooltipContent side="bottom">
          {{ isDarkMode ? t('header.lightMode') : t('header.darkMode') }}
        </TooltipContent>
      </Tooltip>

      <Tooltip>
        <TooltipTrigger as-child>
          <Button variant="ghost" size="icon" class="relative size-8 text-muted-foreground">
            <Bell class="size-4" />
            <span
              class="absolute top-1.5 right-1.5 size-1.5 rounded-full bg-primary border border-background"
            />
          </Button>
        </TooltipTrigger>
        <TooltipContent side="bottom">{{ t('header.notifications') }}</TooltipContent>
      </Tooltip>

      <Separator orientation="vertical" class="!h-4 mx-1" />

      <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <Button variant="ghost" class="h-9 px-2 gap-2">
            <Avatar class="size-7 border border-border">
              <AvatarFallback class="text-[10px]">PB</AvatarFallback>
            </Avatar>
            <span class="text-xs font-semibold hidden md:block">Paolo Bellini</span>
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-48">
          <DropdownMenuLabel class="text-xs">{{ t('header.profile.myAccount') }}</DropdownMenuLabel>
          <DropdownMenuSeparator />
          <DropdownMenuItem class="text-xs">{{ t('sidebar.nav.profile') }}</DropdownMenuItem>
          <DropdownMenuItem class="text-xs">{{ t('sidebar.nav.settings') }}</DropdownMenuItem>
          <DropdownMenuSeparator />
          <DropdownMenuItem class="text-xs text-destructive">{{ t('sidebar.logout') }}</DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
  </header>

  <CommandPalette ref="commandPalette" />
</template>

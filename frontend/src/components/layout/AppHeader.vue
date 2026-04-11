<script setup lang="ts">
import { ref, watchEffect } from 'vue'
import { SidebarTrigger } from '@/components/ui/sidebar'
import { Separator } from '@/components/ui/separator'
import { Input } from '@/components/ui/input'
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
import { Search, Bell, Moon, Sun } from 'lucide-vue-next'

const isDarkMode = ref(false)

watchEffect(() => {
  document.documentElement.classList.toggle('dark', isDarkMode.value)
})
</script>

<template>
  <header
    class="flex h-14 shrink-0 items-center gap-2 border-b bg-background/80 backdrop-blur-md px-4"
  >
    <SidebarTrigger class="-ml-1" />
    <Separator orientation="vertical" class="mr-2 !h-4" />

    <div class="relative w-full max-w-sm">
      <Search class="absolute left-2.5 top-1/2 -translate-y-1/2 size-3.5 text-muted-foreground" />
      <Input
        placeholder="Search..."
        class="pl-8 h-8 bg-muted/30 border-none text-xs focus-visible:ring-primary/20"
      />
    </div>

    <div class="ml-auto flex items-center gap-2">
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
          {{ isDarkMode ? 'Light Mode' : 'Dark Mode' }}
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
        <TooltipContent side="bottom">Notifications</TooltipContent>
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
          <DropdownMenuLabel class="text-xs">My Account</DropdownMenuLabel>
          <DropdownMenuSeparator />
          <DropdownMenuItem class="text-xs">Profile</DropdownMenuItem>
          <DropdownMenuItem class="text-xs">Settings</DropdownMenuItem>
          <DropdownMenuSeparator />
          <DropdownMenuItem class="text-xs text-destructive">Log out</DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
  </header>
</template>

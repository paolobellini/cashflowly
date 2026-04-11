<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import {
  CommandDialog,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
  CommandSeparator,
} from '@/components/ui/command'
import { LayoutDashboard, ArrowLeftRight, User, Settings, Moon, Sun, LogOut } from 'lucide-vue-next'

const { t } = useI18n()
const router = useRouter()

const open = ref(false)

function handleKeydown(e: KeyboardEvent) {
  if (e.key === 'k' && (e.metaKey || e.ctrlKey)) {
    e.preventDefault()
    open.value = !open.value
  }
}

onMounted(() => document.addEventListener('keydown', handleKeydown))
onUnmounted(() => document.removeEventListener('keydown', handleKeydown))

function navigate(path: string) {
  router.push(path)
  open.value = false
}

function toggleTheme() {
  document.documentElement.classList.toggle('dark')
  open.value = false
}

defineExpose({ open })
</script>

<template>
  <CommandDialog v-model:open="open">
    <CommandInput :placeholder="t('command.placeholder')" />
    <CommandList>
      <CommandEmpty>{{ t('command.noResults') }}</CommandEmpty>

      <CommandGroup :heading="t('command.navigation')">
        <CommandItem value="dashboard" @select="navigate('/')">
          <LayoutDashboard class="size-4" />
          <span>{{ t('sidebar.nav.dashboard') }}</span>
        </CommandItem>
        <CommandItem value="transactions" @select="navigate('/transactions')">
          <ArrowLeftRight class="size-4" />
          <span>{{ t('sidebar.nav.transactions') }}</span>
        </CommandItem>
        <CommandItem value="profile" @select="navigate('/profile')">
          <User class="size-4" />
          <span>{{ t('sidebar.nav.profile') }}</span>
        </CommandItem>
        <CommandItem value="settings" @select="navigate('/settings')">
          <Settings class="size-4" />
          <span>{{ t('sidebar.nav.settings') }}</span>
        </CommandItem>
      </CommandGroup>

      <CommandSeparator />

      <CommandGroup :heading="t('command.actions')">
        <CommandItem value="toggle-theme" @select="toggleTheme">
          <Sun class="size-4 dark:hidden" />
          <Moon class="size-4 hidden dark:block" />
          <span>{{ t('command.toggleTheme') }}</span>
        </CommandItem>
        <CommandItem value="logout">
          <LogOut class="size-4" />
          <span>{{ t('sidebar.logout') }}</span>
        </CommandItem>
      </CommandGroup>
    </CommandList>
  </CommandDialog>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarGroup,
  SidebarGroupContent,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarRail,
  SidebarSeparator,
} from '@/components/ui/sidebar'
import { LayoutDashboard, ArrowLeftRight, User, Settings, LogOut } from 'lucide-vue-next'
import WalletSelector from '@/components/wallets/WalletSelector.vue'

const { t } = useI18n()
const route = useRoute()

const navItems = [
  { to: '/', labelKey: 'sidebar.nav.dashboard', icon: LayoutDashboard },
  { to: '/transactions', labelKey: 'sidebar.nav.transactions', icon: ArrowLeftRight },
  { to: '/profile', labelKey: 'sidebar.nav.profile', icon: User },
  { to: '/settings', labelKey: 'sidebar.nav.settings', icon: Settings },
]
</script>

<template>
  <Sidebar collapsible="icon">
    <SidebarHeader
      class="group-data-[collapsible=icon]:pt-3 group-data-[collapsible=icon]:pb-3 group-data-[collapsible=icon]:items-center"
    >
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child>
            <router-link to="/">
              <div
                class="flex size-8 items-center justify-center rounded-lg bg-primary text-primary-foreground shrink-0"
              >
                <span class="text-sm font-bold">C</span>
              </div>
              <span class="font-semibold truncate">{{ t('sidebar.appName') }}</span>
            </router-link>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <SidebarSeparator />

    <div class="py-2">
      <WalletSelector />
    </div>

    <SidebarSeparator />

    <SidebarContent>
      <SidebarGroup>
        <SidebarGroupContent>
          <SidebarMenu class="gap-1.5">
            <SidebarMenuItem v-for="item in navItems" :key="item.to">
              <SidebarMenuButton
                as-child
                :is-active="route.path === item.to"
                :tooltip="t(item.labelKey)"
                :class="
                  route.path === item.to
                    ? 'bg-primary/5 text-primary hover:bg-primary/10 hover:text-primary [&>svg]:text-primary'
                    : 'text-muted-foreground hover:text-foreground'
                "
              >
                <router-link :to="item.to" class="transition-all duration-200">
                  <component :is="item.icon" />
                  <span class="text-xs font-medium">{{ t(item.labelKey) }}</span>
                </router-link>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarGroupContent>
      </SidebarGroup>
    </SidebarContent>

    <SidebarFooter>
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton
            :tooltip="t('sidebar.logout')"
            class="text-muted-foreground hover:text-destructive hover:bg-destructive/5 transition-all duration-200"
          >
            <LogOut />
            <span>{{ t('sidebar.logout') }}</span>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarFooter>

    <SidebarRail />
  </Sidebar>
</template>

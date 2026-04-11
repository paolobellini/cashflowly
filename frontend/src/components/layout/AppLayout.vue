<script setup lang="ts">
import { SidebarProvider, SidebarInset } from '@/components/ui/sidebar'
import AppSidebar from './AppSidebar.vue'
import AppHeader from './AppHeader.vue'
</script>

<template>
  <SidebarProvider>
    <AppSidebar />
    <SidebarInset>
      <AppHeader
        class="fixed top-0 right-0 left-[var(--sidebar-width)] z-20 transition-[left] duration-200 ease-linear group-data-[collapsible=icon]/sidebar-wrapper:left-[var(--sidebar-width-icon)]"
      />
      <main class="flex-1 overflow-y-auto">
        <RouterView />
      </main>
    </SidebarInset>
  </SidebarProvider>
</template>

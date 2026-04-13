<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton'

defineProps<{
  count?: number
}>()
</script>

<template>
  <div class="space-y-10">
    <div v-for="group in 2" :key="group" class="space-y-3">
      <!-- Date header -->
      <div class="flex items-center gap-3 px-1">
        <Skeleton class="h-3 w-24" />
        <div class="h-px bg-border/30 flex-1" />
      </div>

      <!-- Transaction rows -->
      <div class="grid gap-3">
        <div
          v-for="i in (group === 1 ? (count ?? 3) : 2)"
          :key="i"
          class="flex items-center gap-4 p-4 rounded-xl bg-card border border-border/50"
        >
          <!-- Checkbox -->
          <Skeleton class="size-4 rounded" />

          <!-- Category icon -->
          <Skeleton class="size-10 rounded-lg" />

          <!-- Text -->
          <div class="flex-1 space-y-2">
            <Skeleton class="h-4 w-40" />
            <div class="flex items-center gap-2">
              <Skeleton class="h-2.5 w-16" />
              <Skeleton class="h-2.5 w-20" />
            </div>
          </div>

          <!-- Amount -->
          <Skeleton class="h-5 w-24" />
        </div>
      </div>
    </div>
  </div>
</template>

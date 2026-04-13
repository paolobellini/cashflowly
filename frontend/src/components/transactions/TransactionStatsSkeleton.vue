<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton'
</script>

<template>
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Balance card -->
    <div class="lg:col-span-8 bg-primary/80 p-6 rounded-2xl">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
          <Skeleton class="h-3 w-24 bg-primary-foreground/20" />
          <Skeleton class="h-8 w-48 bg-primary-foreground/20" />
        </div>
        <div class="flex items-center gap-8">
          <div class="space-y-2">
            <Skeleton class="h-2.5 w-14 bg-primary-foreground/20" />
            <Skeleton class="h-5 w-28 bg-primary-foreground/20" />
          </div>
          <div class="w-px h-8 bg-primary-foreground/20 hidden md:block" />
          <div class="space-y-2">
            <Skeleton class="h-2.5 w-14 bg-primary-foreground/20" />
            <Skeleton class="h-5 w-28 bg-primary-foreground/20" />
          </div>
        </div>
      </div>
    </div>

    <!-- Side stats -->
    <div class="lg:col-span-4 grid grid-cols-2 gap-6">
      <div class="bg-card border border-border/50 p-6 rounded-2xl flex items-center justify-between">
        <div class="space-y-2">
          <Skeleton class="h-2.5 w-14" />
          <Skeleton class="h-7 w-16" />
        </div>
        <Skeleton class="size-10 rounded-lg" />
      </div>
      <div class="bg-card border border-border/50 p-6 rounded-2xl flex items-center justify-between">
        <div class="space-y-2">
          <Skeleton class="h-2.5 w-14" />
          <Skeleton class="h-7 w-10" />
        </div>
        <Skeleton class="size-10 rounded-lg" />
      </div>
    </div>
  </div>
</template>

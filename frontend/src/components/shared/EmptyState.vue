<script setup lang="ts">
import type { Component } from 'vue'

defineProps<{
  icon?: Component
  emoji?: string
  title: string
  description: string
  dashed?: boolean
}>()
</script>

<template>
  <div
    class="flex flex-col items-center justify-center py-20 text-center"
    :class="dashed ? 'bg-muted/5 rounded-3xl border border-dashed border-border/50' : ''"
  >
    <div class="w-20 h-20 rounded-full bg-muted flex items-center justify-center mb-4">
      <span v-if="emoji" class="text-4xl">{{ emoji }}</span>
      <component v-else-if="icon" :is="icon" class="size-8 text-muted-foreground" />
    </div>
    <h3 class="text-xl font-bold">{{ title }}</h3>
    <p class="text-muted-foreground mt-2 max-w-xs">
      {{ description }}
    </p>
  </div>
</template>

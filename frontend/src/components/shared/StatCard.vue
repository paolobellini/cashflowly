<script setup lang="ts">
import type { Component } from 'vue'

defineProps<{
  label: string
  value: string
  icon: Component
  iconClass?: string
}>()
</script>

<template>
  <div
    class="bg-card border border-border/50 p-6 rounded-2xl shadow-sm flex items-center justify-between hover:border-primary/30 transition-all group"
  >
    <div>
      <p class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground/70">
        {{ label }}
      </p>
      <h3 class="text-2xl font-black mt-1">{{ value }}</h3>
    </div>
    <div
      class="size-10 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform"
      :class="iconClass ?? 'bg-primary/10 text-primary'"
    >
      <component :is="icon" class="size-4" />
    </div>
  </div>
</template>

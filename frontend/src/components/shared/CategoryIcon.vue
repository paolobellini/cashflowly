<script setup lang="ts">
import { Utensils, Car, Briefcase, Gamepad2, ShoppingBag } from 'lucide-vue-next'
import type { Component } from 'vue'

const props = defineProps<{
  icon: string
  color: string
  size?: 'sm' | 'md'
}>()

const iconMap: Record<string, Component> = {
  Utensils,
  Car,
  Briefcase,
  Gamepad2,
  ShoppingBag,
}

const resolvedIcon = iconMap[props.icon] ?? ShoppingBag
</script>

<template>
  <div
    class="flex items-center justify-center shrink-0 rounded-lg"
    :class="size === 'sm' ? 'size-8' : 'size-10'"
    :style="{ backgroundColor: `${color}15`, color }"
  >
    <component :is="resolvedIcon" :class="size === 'sm' ? 'size-4' : 'size-5'" />
  </div>
</template>

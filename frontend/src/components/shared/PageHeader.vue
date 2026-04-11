<script setup lang="ts">
defineProps<{
  title: string
  description?: string
}>()
</script>

<template>
  <div>
    <h1 class="text-3xl font-bold tracking-tight text-foreground">{{ title }}</h1>
    <p v-if="description" class="text-sm text-muted-foreground mt-1">
      {{ description }}
    </p>
  </div>
</template>

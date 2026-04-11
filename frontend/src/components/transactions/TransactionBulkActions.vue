<script setup lang="ts">
import { Download, Trash2, X } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'

defineProps<{
  count: number
}>()

const emit = defineEmits<{
  clear: []
  export: []
  delete: []
}>()
</script>

<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="translate-y-full opacity-0"
      enter-to-class="translate-y-0 opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="translate-y-0 opacity-100"
      leave-to-class="translate-y-full opacity-0"
    >
      <div v-if="count > 0" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50">
        <div class="bg-foreground text-background px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-8 min-w-[380px] border border-background/10">
          <div class="flex items-center gap-3">
            <span class="size-8 rounded-lg bg-primary flex items-center justify-center text-xs font-bold text-primary-foreground shadow-lg shadow-primary/30">
              {{ count }}
            </span>
            <span class="text-sm font-bold">Selected</span>
          </div>

          <div class="h-8 w-px bg-background/20" />

          <div class="flex items-center gap-2 flex-1 justify-center">
            <Button
              variant="ghost"
              size="sm"
              class="hover:bg-background/10 text-background gap-2 h-10 text-xs font-bold rounded-xl"
              @click="emit('export')"
            >
              <Download class="size-4" /> Export
            </Button>
            <Button
              variant="ghost"
              size="sm"
              class="hover:bg-rose-500/20 text-rose-400 gap-2 h-10 text-xs font-bold rounded-xl"
              @click="emit('delete')"
            >
              <Trash2 class="size-4" /> Delete
            </Button>
          </div>

          <Button
            variant="ghost"
            size="icon"
            class="hover:bg-background/10 text-background rounded-full h-8 w-8"
            @click="emit('clear')"
          >
            <X class="size-4" />
          </Button>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

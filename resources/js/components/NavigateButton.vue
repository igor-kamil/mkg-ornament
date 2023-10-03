<template>
    <button class="flex items-center justify-center text-white absolute inset-0 z-10 bg-black/30 hover:bg-black/60">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-20 h-20 lg:w-32 lg:h-32"
            :class="iconClass"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
        <slot></slot>
    </button>
</template>

<script setup>
import { defineProps, computed } from 'vue'

const { direction } = defineProps(['direction'])

const iconClass = computed(() => {
    switch (direction) {
        case 'left':
            return 'transform rotate-180'
        case 'up':
            return 'transform -rotate-90'
        case 'down':
            return 'transform rotate-90'
    }
})
</script>

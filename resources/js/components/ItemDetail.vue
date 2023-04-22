<template>
    <div class="fixed inset-0 z-50 flex justify-center items-center" v-if="visible">
        <div class="bg-black opacity-70 absolute inset-0 cursor-zoom-out" @click="emit('close')" />
        <div class="relative mx-4 bg-white w-full max-w-2xl bg-white rounded-xl">
            <img :src="item.image_src" :alt="item.title" class="w-full rounded-t-xl" />
            <div class="px-4 py-6">
                <h3 class="text-gray-dark text-base" v-if="item.collection">
                    collection: 
                    {{ item.collection }}
                </h3>
                <h2 class="text-xl font-bold">{{ item.title }}</h2>
                <h3 class="text-gray-dark text-base">
                    <span v-if="item.author">{{ item.author }} · </span>
                    {{ item.year_from }}
                </h3>
                <div class="py-4" v-html="item.description"></div>
                <ConfirmButton class="bg-black text-white my-4" @click="emit('close')">{{ $t('OK') }}</ConfirmButton>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import ConfirmButton from './ConfirmButton.vue'
const props = defineProps({
    visible: Boolean,
    item: Object
})
const emit = defineEmits(['close'])
</script>

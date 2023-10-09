<template>
    <div class="fixed inset-0 z-50 flex justify-center items-center" v-if="visible">
        <div class="bg-black opacity-70 absolute inset-0 cursor-zoom-out" @click="emit('close')" />
        <div class="max-h-full overflow-y-auto overflow-x-hidden p-4">
            <div class="relative w-full max-w-2xl md:max-w-3xl bg-white rounded-xl">
                <img
                    :src="item.image_src"
                    :alt="item.title"
                    class="w-full rounded-t-xl max-h-[calc(100dvh-20rem)] md:max-h-[calc(100dvh-34rem)] object-contain"
                />
                <div class="px-4 py-4 md:py-6 md:px-6">
                    <h3 class="text-gray-dark text-lg md:text-2xl mb-1" v-if="item.object">
                        {{ item.object }}
                    </h3>
                    <h2 class="text-xl md:text-4xl font-bold mb-1">{{ item.title }}</h2>
                    <h3 class="text-gray-dark text-lg md:text-2xl mb-4">
                        <span v-if="item.author">{{ item.author }}</span>
                        <span v-if="item.author && item.dating"> · </span>
                        <span v-if="item.dating">{{ item.dating }}</span>
                    </h3>
                    <div class="pb-4" v-if="item.description" v-html="item.description"></div>

                    <div class="flex p-2 md:p-4 border-black border-1 mb-4 rounded-xl" v-if="item.web_url">
                        <img :src="qrCode" class="w-20 h-20 md:w-32 md:h-32" alt="open in collection online" />
                        <div class="grow">
                            <div class="flex h-full items-top">
                                <div class="ml-3 md:ml-4 md:text-2xl">
                                    <span class="font-bold">Scannen Sie den Code, um das Objekt zu öffnen</span><br>
                                    <span>in MK&G Sammlung Online</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ConfirmButton class="bg-black text-white my-2 md:my-4 text-lg md:text-2xl" @click="emit('close')"
                        >Schließen</ConfirmButton
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import ConfirmButton from './ConfirmButton.vue'
const props = defineProps({
    visible: Boolean,
    item: Object,
})
const emit = defineEmits(['close'])
const qrCode = computed(() => {
    return `/qrcode/${props.item.id}.svg`
})
</script>

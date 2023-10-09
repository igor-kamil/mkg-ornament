<template>
    <div class="fixed inset-0 z-50 flex justify-center items-center" v-if="visible">
        <div class="bg-black opacity-70 absolute inset-0 cursor-zoom-out" @click="emit('close')" />
        <div
            class="h-[calc(100dvh-5rem)] md:h-[calc(100dvh-10rem)] w-[calc(100dvw-2rem)] md:w-[calc(100dvw-10rem)] overflow-y-auto overflow-x-hidden p-4 flex items-center justify-center"
        >
            <div class="h-full flex flex-col relative w-full bg-white rounded-xl">
                <div class="bg-gray-softest h-full rounded-t-xl overflow-hidden">
                    <img :src="item.image_src" :alt="item.title" ref="zoom" class="w-full" />
                </div>
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
                            <div class="flex">
                                <div class="ml-3 md:ml-4 self-center">
                                    <div class="font-bold">Scannen Sie den Code, um das Objekt zu öffnen</div>
                                    <div class="text-base">in MK&G Sammlung Online</div>
                                    <div class="text-xs"><i>{{ item.web_url }}</i></div>
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
import { ref, computed, onUnmounted, watch } from 'vue'
import PinchZoom from 'pinch-zoom-js'
import ConfirmButton from './ConfirmButton.vue'

let pz
const zoom = ref()

const props = defineProps({
    visible: Boolean,
    item: Object,
})
const emit = defineEmits(['close'])
const qrCode = computed(() => {
    return `/qrcode/${props.item.id}.svg`
})

onUnmounted(() => {
    pz.destroy()
})

watch(zoom, (newZoom) => {
    if (newZoom) {
        pz = new PinchZoom(newZoom, { minZoom: 0.9 })
    }
})
</script>

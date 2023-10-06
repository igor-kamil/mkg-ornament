<template>
    <div class="fixed inset-0 z-50 flex justify-center items-center" v-if="visible">
        <div class="bg-black opacity-70 absolute inset-0 cursor-zoom-out" @click="emit('close')" />
        <div class="max-h-full overflow-y-auto overflow-x-hidden p-4">
            <div class="relative w-full max-w-4xl bg-white rounded-xl">
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
                    <a
                        :href="item.web_url"
                        target="_blank"
                        class="bg-gray-softest border-2 border-black p-2.5 rounded-xl block"
                        v-if="item.web_url"
                    >
                        <div class="flex cursor-pointer">
                            <div class="grow">
                                <div class="flex">
                                    <div class="mx-3 self-center">
                                        <div class="font-bold">Open this object</div>
                                        <div class="text-sm">in MK&G Online Collection</div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <svg
                                    class="fill-none h-[20px] w-[20px] stroke-2 stroke-black [stroke-linecap:round] [stroke-linejoin:round]"
                                >
                                    <path d="M18.25 7.375V1.75H12.625" />
                                    <path d="M11.5 8.5L18.25 1.75" />
                                    <path
                                        d="M15.25 11.5V17.5C15.25 17.6989 15.171 17.8897 15.0303 18.0303C14.8897 18.171 14.6989 18.25 14.5 18.25H2.5C2.30109 18.25 2.11032 18.171 1.96967 18.0303C1.82902 17.8897 1.75 17.6989 1.75 17.5V5.5C1.75 5.30109 1.82902 5.11032 1.96967 4.96967C2.11032 4.82902 2.30109 4.75 2.5 4.75H8.5"
                                    />
                                </svg>
                            </div>
                        </div>
                    </a>
                    <ConfirmButton class="bg-black text-white my-4" @click="emit('close')">Schließen</ConfirmButton>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import ConfirmButton from './ConfirmButton.vue'
const props = defineProps({
    visible: Boolean,
    item: Object,
})
const emit = defineEmits(['close'])
</script>

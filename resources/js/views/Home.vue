<template>
    <div class="flex w-full h-screen max-h-screen flex-col border-2 border-black" v-if="items.length !== 0">
        <div class="flex bg-black h-20 md:h-32 lg:h-48 shrink-0">
            <div class="border-2 border-black w-20 md:w-32 lg:w-48 shrink-0"></div>
            <div class="border-2 border-black grow relative">
                <navigate-button @click="loadItem(items[0].id)" direction="up"></navigate-button>
                <img
                    @click="loadItem(items[0].id)"
                    :src="items[0].image_src"
                    :alt="items[0].title"
                    class="w-full h-full object-cover object-bottom"
                />
            </div>
            <div class="border-2 border-black w-20 md:w-32 lg:w-48 shrink-0"></div>
        </div>
        <div class="flex bg-black grow">
            <div class="border-2 border-black w-20 md:w-32 lg:w-48 shrink-0 relative">
                <navigate-button @click="loadItem(items[1].id)" direction="left"></navigate-button>
                <img :src="items[1].image_src" :alt="items[1].title" class="w-full h-full object-cover object-right" />
            </div>
            <div
                class="border-2 border-black grow max-h-[calc(100vh-10rem)] md:max-h-[calc(100vh-16rem)] lg:max-h-[calc(100vh-22rem)]"
            >
                <img
                    @click="toggleDetail()"
                    :src="items[2].image_src"
                    :alt="items[2].title"
                    class="w-full h-full object-cover cursor-pointer"
                />
            </div>
            <div class="border-2 border-black w-20 md:w-32 lg:w-48 shrink-0 relative">
                <navigate-button @click="loadItem(items[3].id)" direction="right"></navigate-button>
                <img :src="items[3].image_src" alt="" class="w-full h-full object-cover object-left" />
            </div>
        </div>
        <div class="flex bg-black h-20 md:h-32 lg:h-48 shrink-0">
            <div class="border-2 border-black w-20 md:w-32 lg:w-48 shrink-0"></div>
            <div class="border-2 border-black grow relative">
                <navigate-button @click="loadItem(items[4].id)" direction="down"></navigate-button>
                <img :src="items[4].image_src" :alt="items[4].title" class="w-full h-full object-cover object-top" />
            </div>
            <div class="border-2 border-black w-20 md:w-32 lg:w-48 shrink-0 relative">
                <button
                    class="flex items-center justify-center text-white absolute inset-0 z-10 hover:text-white/60"
                    @click="init"
                >
                    <svg
                        class="w-20 h-auto lg:w-32 lg:h-24"
                        viewBox="0 0 132 97"
                        stroke="currentColor"
                        fill="none"
                        stroke-width="8"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M1 22.5C1 22.5 9.5 22.5 26 22.5C42.5 22.5 51 32.5 51 32.5M126 75C126 75 122.5 75 106 75C89.5 75 77.5 61 77.5 61M126 75L107 94M126 75L107 56"
                        />
                        <path
                            d="M1 75.5C1 75.5 3.00192 75.5 14.501 75.5C26 75.5 36 75.5 50.5 61C65 46.5 69 43 79 33C89 23 98.5 22 109.5 22C120.5 22 126 22 126 22M126 22L107 2.99999M126 22L107 41"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div v-if="isLoading" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-white"></div>
    </div>
    <ItemDetail :visible="detailActive" @close="toggleDetail" :item="items[2]" />
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getActiveLanguage, loadLanguageAsync } from 'laravel-vue-i18n'
import ItemDetail from '../components/ItemDetail.vue'
import NavigateButton from '../components/NavigateButton.vue'

const router = useRouter()
const route = useRoute()
const locale = ref('en')

const items = ref([])
const isLoading = ref(true)
const detailActive = ref(false)

let apiUrl = '/api/items/'

onMounted(async () => {
    const useParam = route.query.use
    apiUrl = useParam === 'digicult' ? '/api/items-digicult/' : '/api/items/'
    init()
})

const init = async () => {
    isLoading.value = true
    const response = await axios.get(`${apiUrl}`)
    items.value = response.data.data
    isLoading.value = false
}

const loadItem = async (id) => {
    isLoading.value = true
    const response = await axios.get(`${apiUrl}?id=${id}`)
    items.value = response.data.data
    isLoading.value = false
}

const toggleDetail = () => {
    detailActive.value = !detailActive.value
}
</script>

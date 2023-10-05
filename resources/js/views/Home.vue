<template>
    <div class="flex w-full h-screen max-h-screen flex-col border-2 border-black" v-if="similarItems.length !== 0">
        <div class="flex bg-black h-20 sm:h-32 md:h-48 shrink-0">
            <div class="border-2 border-black w-20 sm:w-32 md:w-48 shrink-0"></div>
            <div class="border-2 border-black grow relative">
                <navigate-button direction="up"></navigate-button>
                <img
                    :src="differentItems[0].image_src"
                    :alt="differentItems[0].title"
                    class="w-full h-full object-cover object-bottom"
                />
            </div>
            <div class="border-2 border-black w-20 sm:w-32 md:w-48 shrink-0"></div>
        </div>
        <div class="flex bg-black grow">
            <div class="border-2 border-black w-20 sm:w-32 md:w-48 shrink-0 relative">
                <navigate-button @click="moveSimilar('left')" direction="left"></navigate-button>
                <img
                    :src="similarItems[activeItem - 1].image_src"
                    :alt="similarItems[activeItem - 1].title"
                    class="w-full h-full object-cover object-right"
                />
            </div>
            <div
                class="border-2 border-black grow h-[calc(100vh-10rem)] sm:h-[calc(100vh-16rem)] md:h-[calc(100vh-24rem)]"
            >
                <img
                    @click="toggleDetail()"
                    :src="similarItems[activeItem].image_src"
                    :alt="similarItems[activeItem].title"
                    class="w-full h-full object-cover cursor-pointer"
                />
            </div>
            <div class="border-2 border-black w-20 sm:w-32 md:w-48 shrink-0 relative">
                <navigate-button @click="moveSimilar('right')" direction="right"></navigate-button>
                <img
                    :src="similarItems[activeItem + 1].image_src"
                    :alt="similarItems[activeItem + 1].title"
                    class="w-full h-full object-cover object-left"
                />
            </div>
        </div>
        <div class="flex bg-black h-20 sm:h-32 md:h-48 shrink-0">
            <div class="border-2 border-black w-20 sm:w-32 md:w-48 shrink-0"></div>
            <div class="border-2 border-black grow relative">
                <navigate-button direction="down"></navigate-button>
                <img
                    :src="differentItems[1].image_src"
                    :alt="differentItems[1].title"
                    class="w-full h-full object-cover object-top"
                />
            </div>
            <div class="border-2 border-black w-20 sm:w-32 md:w-48 shrink-0 relative">
                <button
                    class="flex items-center justify-center text-white absolute inset-0 z-10 hover:text-white/60"
                    @click="init"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="w-20 h-20 lg:w-32 lg:h-32"
                    >
                        <path
                            d="M1.38251 7.66051C1.38251 7.66051 2.80119 7.66051 5.55509 7.66051C8.309 7.66051 9.72768 9.32955 9.72768 9.32955M22.2454 16.4229C22.2454 16.4229 21.6613 16.4229 18.9074 16.4229C16.1535 16.4229 14.1506 14.0863 14.1506 14.0863M22.2454 16.4229L19.0743 19.5941M22.2454 16.4229L19.0743 13.2518"
                        />
                        <path
                            d="M1.38251 16.5064C1.38251 16.5064 1.71664 16.5064 3.63587 16.5064C5.55509 16.5064 7.22413 16.5064 9.64423 14.0863C12.0643 11.6662 12.7319 11.082 14.401 9.413C16.07 7.74396 17.6556 7.57706 19.4915 7.57706C21.3275 7.57706 22.2454 7.57706 22.2454 7.57706M22.2454 7.57706L19.0743 4.40589M22.2454 7.57706L19.0743 10.7482"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div v-if="isLoading" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-white"></div>
    </div>
    <ItemDetail :visible="detailActive" @close="toggleDetail" :item="similarItems[activeItem]" />
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

// const items = ref([])
const similarItems = ref([])
const differentItems = ref([])
const isLoading = ref(true)
const detailActive = ref(false)
const activeItem = ref(1)

const nextSimilar = ref(null)

let apiUrl = '/api/items/'

onMounted(async () => {
    const useParam = route.query.use
    apiUrl = useParam === 'digicult' ? '/api/items-digicult/' : '/api/items/'
    init()
})

const init = async () => {
    isLoading.value = true
    activeItem.value = 1
    similarItems.value = []
    const response = await axios.get(`${apiUrl}`)
    await processResponse(response)
    isLoading.value = false
    loadNextSimilar()
}

const loadNextSimilar = async () => {
    const id = similarItems.value[activeItem.value].id
    const viewedItemIds = similarItems.value.map((item) => item.id).join(',')
    const response = await axios.get(`/api/similar-item/${id}/?exclude=${viewedItemIds}`)
    nextSimilar.value = response.data.data
    loadNextImage(nextSimilar.value.image_src)
}

const loadItem = async (id) => {
    isLoading.value = true
    const response = await axios.get(`${apiUrl}?id=${id}`)
    processResponse(response)
    isLoading.value = false
}

const moveSimilar = async (direction) => {
    // move pointer to "active"
    isLoading.value = true
    switch (direction) {
        case 'left':
            similarItems.value.unshift(nextSimilar.value)
            activeItem.value--
        default:
            similarItems.value.push(nextSimilar.value)
            activeItem.value++
    }

    isLoading.value = false
    loadNextSimilar()
    // const itemIds = similarItems.value.map(item => item.id);
    // const response = await axios.get(`${apiUrl}?id=${id}&exclude=${similarItems.value.join(',')}`)
    // processResponse(response)
    // isLoading.value = false
}

const processResponse = async (response) => {
    const items = response.data.data
    differentItems.value.push(...[items[0], items[4]])
    similarItems.value.push(...[items[1], items[2], items[3]])
    // similarItems.value.push(...items.value)
    // Load all images and then hide loading
    await loadImages(response.data.data.map((item) => item.image_src))
}

const toggleDetail = () => {
    detailActive.value = !detailActive.value
}

const loadImages = (imageSrcArray) => {
    const promises = imageSrcArray.map((src) => {
        return new Promise((resolve, reject) => {
            const img = new Image()
            img.src = src
            img.onload = resolve
            img.onerror = reject
        })
    })

    return Promise.all(promises)
}

const loadNextImage = (imageSrc) => {
    new Image().src = imageSrc;
}
</script>

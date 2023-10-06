<template>
    <div class="flex w-full h-screen max-h-screen flex-col border-1 border-black" v-if="similarItems.length !== 0">
        <div class="flex bg-white h-20 sm:h-32 md:h-48 shrink-0">
            <div class="border-1 border-black w-20 sm:w-32 md:w-48 shrink-0 relative">
                <button
                    class="flex items-center justify-center text-gray-medium absolute inset-0 z-10 hover:text-white/60 w-full"
                    @click="init"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-20 h-20 md:w-24 md:h-24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"
                        />
                    </svg>
                </button>
            </div>
            <div class="border-1 border-black grow relative">
                <navigate-button @click="moveSimilar('up')" direction="up"></navigate-button>
                <img
                    :src="differentItems[0][1].image_src"
                    :alt="differentItems[0][1].title"
                    class="w-full h-full object-cover object-bottom"
                />
            </div>
            <div class="border-1 border-black w-20 sm:w-32 md:w-48 shrink-0"></div>
        </div>
        <div class="flex bg-white grow">
            <div class="border-1 border-black w-20 sm:w-32 md:w-48 shrink-0 relative">
                <navigate-button @click="moveSimilar('left')" direction="left"></navigate-button>
                <img
                    :src="similarItems[activeItem - 1].image_src"
                    :alt="similarItems[activeItem - 1].title"
                    class="w-full h-full object-cover object-right"
                />
            </div>
            <div
                class="border-1 border-black grow h-[calc(100vh-10rem)] sm:h-[calc(100vh-16rem)] md:h-[calc(100vh-24rem)]"
            >
                <img
                    @click="toggleDetail()"
                    :src="similarItems[activeItem].image_src"
                    :alt="similarItems[activeItem].title"
                    class="w-full h-full object-cover cursor-pointer"
                />
            </div>
            <div class="border-1 border-black w-20 sm:w-32 md:w-48 shrink-0 relative">
                <navigate-button @click="moveSimilar('right')" direction="right"></navigate-button>
                <img
                    :src="similarItems[activeItem + 1].image_src"
                    :alt="similarItems[activeItem + 1].title"
                    class="w-full h-full object-cover object-left"
                />
            </div>
        </div>
        <div class="flex bg-white h-20 sm:h-32 md:h-48 shrink-0">
            <div class="border-1 border-black w-20 sm:w-32 md:w-48 shrink-0 relative">
                
            </div>
            <div class="border-1 border-black grow relative">
                <navigate-button @click="moveSimilar('down')" direction="down"></navigate-button>
                <img
                    :src="differentItems[1][1].image_src"
                    :alt="differentItems[1][1].title"
                    class="w-full h-full object-cover object-top"
                />
            </div>
            <div class="border-1 border-black w-20 sm:w-32 md:w-48 shrink-0 relative">
                <button
                    class="flex items-center justify-center text-gray-medium absolute inset-0 z-10 hover:text-white/60 w-full"
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
                        class="w-20 h-20 md:w-24 md:h-24"
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
    <div v-if="isLoading" class="fixed inset-0 z-50 flex items-center justify-center bg-white/50">
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
const nextDifferent = ref([])

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
    loadNextDifferent()
}

const loadNextSimilar = async () => {
    const id = similarItems.value[activeItem.value].id
    const viewedItemIds = similarItems.value.map((item) => item.id).join(',')
    const response = await axios.get(`/api/similar-item/${id}/?exclude=${viewedItemIds}`)
    nextSimilar.value = response.data.data
    loadImages([nextSimilar.value.image_src])
}

const loadNextDifferent = async () => {
    const id = similarItems.value[activeItem.value].id
    const response = await axios.get(`/api/different-items/${id}`)
    nextDifferent.value = response.data
    loadImages(nextDifferent.value.map((item) => item.image_src))
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
        case 'up':
            differentItems.value[0] = similarItems.value
            similarItems.value = differentItems.value[1]
            activeItem.value = 1
            differentItems.value[1] = nextDifferent.value
            break
        case 'down':
            differentItems.value[1] = similarItems.value
            similarItems.value = differentItems.value[0]
            activeItem.value = 1
            differentItems.value[0] = nextDifferent.value
            break
        case 'left':
            similarItems.value.unshift(nextSimilar.value)
            activeItem.value--
            break
        case 'right':
            similarItems.value.push(nextSimilar.value)
            activeItem.value++
            break
    }

    isLoading.value = false
    loadNextSimilar()
    loadNextDifferent()
    // const itemIds = similarItems.value.map(item => item.id);
    // const response = await axios.get(`${apiUrl}?id=${id}&exclude=${similarItems.value.join(',')}`)
    // processResponse(response)
    isLoading.value = false
}

const processResponse = async (response) => {
    const items = response.data
    differentItems.value = [items[0], items[2]]
    similarItems.value = items[1]
    await loadImages([
        ...similarItems.value.map((item) => item.image_src),
        differentItems.value[0][1].image_src,
        differentItems.value[0][1].image_src,
    ])
    loadImages([
        differentItems.value[0][0].image_src,
        differentItems.value[0][2].image_src,
        differentItems.value[1][0].image_src,
        differentItems.value[1][2].image_src,
    ])
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
</script>

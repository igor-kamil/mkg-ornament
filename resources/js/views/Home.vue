<template>
    <div class="flex w-full h-[100dvh] max-h-[100dvh] flex-col border-1 border-black" v-if="similarItems.length !== 0">
        <div class="flex bg-white h-20 sm:h-32 md:h-48 shrink-0">
            <div class="border-1 border-black w-20 sm:w-32 md:w-48 shrink-0 relative">
                <button
                    class="flex items-center justify-center text-gray-medium absolute inset-0 z-10 hover:text-gray-dark w-full text-3xl md:text-8xl"
                    @click="init()"
                >
                    ?
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
                class="border-1 border-black grow h-[calc(100dvh-10rem)] sm:h-[calc(100dvh-16rem)] md:h-[calc(100dvh-24rem)]"
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
            <div class="border-1 border-black w-20 sm:w-32 md:w-48 shrink-0 relative"></div>
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
                    class="flex items-center justify-center text-gray-medium absolute inset-0 z-10 hover:text-gray-dark w-full"
                    @click="init()"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-9 h-9 md:w-24 md:h-24"
                        xml:space="preserve"
                        viewBox="0 0 64 64"
                        stroke="currentColor"
                        fill="currentColor"
                    >
                        <path
                            d="m24.293 26.707 1.414-1.414L15.414 15H0v2h14.586zM55.293 9.707 60.586 15h-14l-32 32H0v2h15.414l32-32h13.172l-5.293 5.293 1.561 1.414 7.146-7v-1.414l-7.146-7zM55.293 41.707 60.586 47H47.414l-9.707-9.707-1.414 1.414L46.586 49h14l-5.293 5.293 1.561 1.414 7.146-7v-1.414l-7.146-7z"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div v-if="isLoading" class="fixed inset-0 z-50 flex items-center justify-center bg-white/50">
        <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-dark"></div>
    </div>
    <ItemDetail :visible="detailActive" @close="toggleDetail" :item="similarItems[activeItem]" />
</template>

<script setup>
import { ref, onMounted } from 'vue'
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

const apiUrl = '/api/items/'

onMounted(async () => {
    // apiUrl = useParam === 'digicult' ? '/api/items-digicult/' : '/api/items/'
    const itemId = route.query.id ?? null
    init(itemId)
})

const init = async (id = null) => {
    isLoading.value = true
    activeItem.value = 1
    similarItems.value = []
    const response = await axios.get(apiUrl + (id !== null ? `?id=${id}` : ''))
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
            differentItems.value[1] = [similarItems.value[activeItem.value-1], similarItems.value[activeItem.value], similarItems.value[activeItem.value+1]]
            similarItems.value = differentItems.value[0]
            differentItems.value[0] = nextDifferent.value
            activeItem.value = 1
            break
        case 'down':
            differentItems.value[0] = [similarItems.value[activeItem.value-1], similarItems.value[activeItem.value], similarItems.value[activeItem.value+1]]
            similarItems.value = differentItems.value[1]
            differentItems.value[1] = nextDifferent.value
            activeItem.value = 1
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

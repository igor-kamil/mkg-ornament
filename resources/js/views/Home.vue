<template>
    <div class="flex w-full h-screen max-h-screen flex-col border-2 border-black" v-if="items.length !== 0">
        <div class="flex bg-black h-20 sm:h-32 md:h-48 shrink-0">
            <div class="border-2 border-black w-20 sm:w-32 md:w-48 shrink-0"></div>
            <div class="border-2 border-black grow relative">
                <navigate-button @click="loadItem(items[0].id)" direction="up"></navigate-button>
                <img
                    @click="loadItem(items[0].id)"
                    :src="items[0].image_src"
                    :alt="items[0].title"
                    class="w-full h-full object-cover object-bottom"
                />
            </div>
            <div class="border-2 border-black w-20 sm:w-32 md:w-48 shrink-0"></div>
        </div>
        <div class="flex bg-black grow">
            <div class="border-2 border-black w-20 sm:w-32 md:w-48 shrink-0 relative">
                <navigate-button @click="loadItem(items[1].id)" direction="left"></navigate-button>
                <img :src="items[1].image_src" :alt="items[1].title" class="w-full h-full object-cover object-right" />
            </div>
            <div
                class="border-2 border-black grow max-h-[calc(100vh-10rem)] sm:max-hmdcalc(100vh-16rem)] lg:max-h-[calc(100vh-22rem)]"
            >
                <img
                    @click="toggleDetail()"
                    :src="items[2].image_src"
                    :alt="items[2].title"
                    class="w-full h-full object-cover cursor-pointer"
                />
            </div>
            <div class="border-2 border-black w-20 sm:w-32 md:w-48 shrink-0 relative">
                <navigate-button @click="loadItem(items[3].id)" direction="right"></navigate-button>
                <img :src="items[3].image_src" alt="" class="w-full h-full object-cover object-left" />
            </div>
        </div>
        <div class="flex bg-black h-20 sm:h-32 md:h-48 shrink-0">
            <div class="border-2 border-black w-20 sm:w-32 md:w-48 shrink-0"></div>
            <div class="border-2 border-black grow relative">
                <navigate-button @click="loadItem(items[4].id)" direction="down"></navigate-button>
                <img :src="items[4].image_src" :alt="items[4].title" class="w-full h-full object-cover object-top" />
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
    await loadImages(items.value.map(item => item.image_src))
    isLoading.value = false
}

const loadItem = async (id) => {
    isLoading.value = true
    const response = await axios.get(`${apiUrl}?id=${id}`)
    items.value = response.data.data
    // Load all images and then hide loading
    await loadImages(items.value.map(item => item.image_src))
    isLoading.value = false
}

const toggleDetail = () => {
    detailActive.value = !detailActive.value
}

const loadImages = (imageSrcArray) => {
  const promises = imageSrcArray.map(src => {
    return new Promise((resolve, reject) => {
      const img = new Image();
      img.src = src;
      img.onload = resolve;
      img.onerror = reject;
    });
  });

  return Promise.all(promises);
};

</script>

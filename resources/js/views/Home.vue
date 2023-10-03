<template>
    <div class="flex w-full h-screen max-h-screen flex-col border-2 border-black" v-if="items.length !== 0">
        <div class="flex bg-black h-20 md:h-32 lg:h-44 shrink-0">
            <div class="border-2 border-black w-20 md:w-32 lg:w-44 shrink-0"></div>
            <div class="border-2 border-black grow">
                <img @click="loadItem(items[0].id)" :src="items[0].image_src" alt="" class="w-full h-full object-cover object-bottom cursor-pointer opacity-70 hover:opacity-100">
            </div>
            <div class="border-2 border-black  w-20 md:w-32 lg:w-44 shrink-0"></div>
        </div>
        <div class="flex bg-black grow">
            <div class="border-2 border-black w-20 md:w-32 lg:w-44 shrink-0">
                <img :src="items[1].image_src" @click="loadItem(items[1].id)" alt="" class="w-full h-full object-cover object-right cursor-pointer opacity-70 hover:opacity-100">
            </div>
            <div class="border-2 border-black grow max-h-[calc(100vh-10rem)] md:max-h-[calc(100vh-16rem)] lg:max-h-[calc(100vh-22rem)]">
                <img :src="items[2].image_src" @click="toggleDetail()" alt="" class="w-full h-full object-cover cursor-pointer">
            </div>
            <div class="border-2 border-black  w-20 md:w-32 lg:w-44 shrink-0">
                <img :src="items[3].image_src" @click="loadItem(items[3].id)" alt="" class="w-full h-full object-cover object-left cursor-pointer opacity-70 hover:opacity-100">
            </div>
        </div>
        <div class="flex bg-black  h-20 md:h-32 lg:h-44 shrink-0">
            <div class="border-2 border-black w-20 md:w-32 lg:w-44 shrink-0"></div>
            <div class="border-2 border-black grow">
                <img :src="items[4].image_src" @click="loadItem(items[4].id)" alt="" class="w-full h-full object-cover object-top cursor-pointer opacity-70 hover:opacity-100">
            </div>
            <div class="border-2 border-black  w-20 md:w-32 lg:w-44 shrink-0"></div>
        </div>
    </div>
    <div v-if="isLoading" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-900"></div>
    </div>
    <ItemDetail :visible="detailActive" @close="toggleDetail" :item="items[2]" />
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getActiveLanguage, loadLanguageAsync } from 'laravel-vue-i18n'
import ItemDetail from '../components/ItemDetail.vue'

const router = useRouter()
const route = useRoute()
const locale = ref('en')

const items = ref([])
const isLoading = ref(false)
const detailActive = ref(false)

let apiUrl = '/api/items/'

onMounted(async () => {
    const useParam = route.query.use
    apiUrl = useParam === 'digicult' ? '/api/items-digicult/' : '/api/items/'
    const response = await axios.get(`${apiUrl}`)
    items.value = response.data.data
})

const loadItem = async(id) => {
    isLoading.value = true
    const response = await axios.get(`${apiUrl}?id=${id}`)
    items.value = response.data.data
    isLoading.value = false
}

const toggleDetail = () => {
    detailActive.value = !detailActive.value
}

</script>

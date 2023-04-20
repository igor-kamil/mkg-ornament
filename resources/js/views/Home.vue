<template>
    <div class="flex w-full grow flex-col border border-black" v-if="items.length !== 0">
        <div class="flex bg-white h-20">
            <div class="border border-black w-20"></div>
            <div class="border border-black grow">
                <img @click="loadItem(items[0].id)" :src="items[0].image_src" alt="" class="w-full h-full object-cover object-bottom cursor-pointer opacity-40 hover:opacity-100">
            </div>
            <div class="border border-black  w-20"></div>
        </div>
        <div class="flex bg-white grow">
            <div class="border border-black w-20 shrink-0">
                <img :src="items[1].image_src" @click="loadItem(items[1].id)" alt="" class="w-full h-full object-cover object-right cursor-pointer opacity-40 hover:opacity-100">
            </div>
            <div class="border border-black grow">
                <img :src="items[2].image_src" alt="" class="w-full h-full object-cover">
            </div>
            <div class="border border-black  w-20 shrink-0">
                <img :src="items[3].image_src" @click="loadItem(items[3].id)" alt="" class="w-full h-full object-cover object-left cursor-pointer opacity-40 hover:opacity-100">
            </div>
        </div>
        <div class="flex bg-white  h-20">
            <div class="border border-black w-20"></div>
            <div class="border border-black grow">
                <img :src="items[4].image_src" @click="loadItem(items[4].id)" alt="" class="w-full h-full object-cover object-top cursor-pointer opacity-40 hover:opacity-100">
            </div>
            <div class="border border-black  w-20"></div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { getActiveLanguage, loadLanguageAsync } from 'laravel-vue-i18n'

const router = useRouter()
const locale = ref('sk')

const items = ref([])

onMounted(async () => {
    locale.value = getActiveLanguage()
    const response = await axios.get(`/api/items/`)
    items.value = response.data.data
})

const loadItem = async(id) => {
    const response = await axios.get(`/api/items/?id=${id}`)
    items.value = response.data.data
}

</script>

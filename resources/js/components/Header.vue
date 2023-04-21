<template>
    <div class="border-black border-t-2 flex items-center h-12 sticky top-0 w-full z-10 bg-white">
        <div @click="$router.push(back)" class="cursor-pointer px-3 h-full border-r-2 flex items-center bg-black">
            <svg
                class="w-[25px] h-auto fill-white"
                version="1.1"
                id="Logo_Wortmarke"
                viewBox="0 0 71.5 81.7"
            >
                <path
                    class="st0"
                    d="M50.6,67.1c-5.6,6-13.3,9.4-22.8,9.4c-12.5,0-21.3-6.8-21.3-17.1c0-8.6,5.8-14.2,16.9-20.1
	c-4.9-5.2-9.1-10.8-9.1-17.5c0-8,6.4-15.4,16.6-15.4c9.8,0,16.3,6.9,16.3,15.3s-6.4,13.7-15.6,18.7l18.6,19c3.2-5.3,5-12.1,5-20.2
	h5.2c-0.1,9.4-2.4,17.6-6.6,23.9l12,12.3h-7.1L50.6,67.1z M47,63.5L26.9,42.8C15.7,48.8,12,53.1,12,59.4c0,7,6.4,12.1,15.8,12.1
	C35.8,71.6,42.4,68.6,47,63.5z M28.2,36.8c9.3-4.8,13.7-9,13.7-15.2c0-5.8-4.5-10.4-10.9-10.4c-7.1,0-11.2,5-11.2,10.5
	C19.7,26.9,22.9,31.3,28.2,36.8z"
                />
            </svg>
        </div>
        <h1 class="text-xl text-center grow">Objekt forscher</h1>
        <div class="flex-1 px-3 border-l-2 border-transparent text-right" v-if="$route.name === 'my_collection'">
            <button class="bg-green rounded-xl text-sm px-3 py-1 font-bold" @click="scroll('share')">
                {{ $t('Share') }}
            </button>
        </div>
        <FavouritesCount v-else class="border-l-2 border-l-transparent px-4 py-2" :show-tooltip="isActive" />
    </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useItemsStore } from '../stores/ItemsStore'
import { useRoute } from 'vue-router'
import FavouritesCount from './FavouritesCount.vue'

const route = useRoute()
const itemsStore = useItemsStore()

const count = computed(() => itemsStore.count)
const isActive = ref(false)

const displayTooltip = () => {
    isActive.value = true
    setTimeout(() => {
        isActive.value = false
    }, 3000)
}

const scroll = (id) => {
    document.getElementById(id).scrollIntoView({
        behavior: 'smooth',
    })
}

itemsStore.$onAction(({ name: actionName }) => {
    if (actionName === 'add') {
        displayTooltip()
    }
})

const back = computed(() => route.meta.back?.(route) ?? { name: 'home' })
</script>

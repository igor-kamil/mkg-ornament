<template>
    <router-view />
</template>

<script setup>
import { onMounted } from 'vue'

// const setViewHeight = () => {
//     let vh = window.innerHeight * 0.01
//     document.documentElement.style.setProperty('--vh', `${vh}px`)
// }

// onMounted(async () => {
//     setViewHeight()
//     window.addEventListener('resize', () => {
//         setViewHeight()
//     })
// })
</script>

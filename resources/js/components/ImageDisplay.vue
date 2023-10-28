<template>
    <div>
        <img :src="tiny_placeholder" :data-src="image_src" alt="Image" @load="imageLoaded" ref="image" />
    </div>
</template>

<script setup>
import { ref } from 'vue';
const props = defineProps(['tiny_placeholder', 'image_src'])
const imageSource = ref(null);

const imageLoaded = () => {
    imageSource.value = props.image_src;
}
</script>

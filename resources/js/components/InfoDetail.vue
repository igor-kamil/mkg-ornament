<template>
    <div class="fixed inset-0 z-50 flex justify-center items-center" v-if="visible">
        <div class="bg-black opacity-70 absolute inset-0 cursor-zoom-out" @click="emit('close')" />
        <div class="max-h-full overflow-y-auto overflow-x-hidden p-4">
            <div class="relative w-full max-w-2xl md:max-w-3xl bg-white rounded-xl">
                <div class="px-4 py-4 md:py-6 md:px-6">
                    <!-- <h2 class="text-xl md:text-4xl font-bold mb-4 md:mb-6">Ornament Explorer Objektschild</h2> -->
                    <div class="description md:text-2xl whitespace-pre-line mb-4 md:mb-6 md:pt-2">
                        <h3 class="italic mb-4">Die Sammlung mit KI entdecken</h3>
                        <p class="mb-4">
                            Im Ornament Explorer sind über 12.000 Bilder von Objekten aus allen Sammlungen des MK&G zu
                            sehen. Ausgangspunkt ist ein Werk in der Mitte. Auf der horizontalen Ebene schließen sich
                            Objekte an, die mit Hilfe von Künstlicher Intelligenz (KI) basierend auf der Ähnlichkeit zum
                            mittleren Objekt ausgewählt werden. In der Vertikalen findet man Objekte, die jüngeren
                            Datums (oben) oder älter sind (unten).<br />
                            Bei einigen Objekten besteht die Möglichkeit, auf weitere Informationen in der Sammlung
                            Online zuzugreifen. Scannen Sie dazu den QR-Code in der Objektansicht mit Ihrem Smartphone.
                        </p>

                        <h3 class="italic mb-4">Bitte beachten:</h3>
                        <p class="mb-4">
                            Die Art, wie Objekte dokumentiert werden, unterliegt den sich wandelnden Bedingungen der
                            Wissensproduktion. Der Ornament Explorer greift auf alle digitalisierten Daten und Objekte
                            in der Sammlungsdatenbank mit Bezug zum Begriff Ornament zu. Also auch solche, die
                            normalerweise nicht öffentlich zugänglich sind. Diese weisen unter Umständen nicht
                            zeitgemäße oder diskriminierungsfreie Sprache, sowie auch Fehler oder Leerstellen auf, da
                            diese noch nicht vollständig geprüft sind. <br />
                            Warum sind nicht mehr Objekt online verfügbar? Dies liegt neben der intensiven Prüfung auch
                            an urheberrechtlichen Vorgaben, die u.U. verhindern, dass Objekte außerhalb des Museums
                            gezeigt werden dürfen. Die digitale Erfassung und Veröffentlichung der über 500.000 Objekte
                            ist ein fortlaufender Prozess.
                        </p>

                        <hr class="border-b border-gray-medium my-4 md:my-6" />

                        <h3 class="italic mb-4">Explore the collection with the help of AI</h3>
                        <p class="mb-4">
                            The Ornament Explorer contains more than 12,000 images of objects from all collections of
                            the MK&G. The starting point is a work in the middle. On the horizontal level, objects are
                            added that are selected with the help of artificial intelligence (AI) based on their
                            similarity to the central object. On the vertical level, objects are found that are more
                            recent (top) or older (bottom).<br />
                            For some objects, it is possible to access further information in the Collection Online. To
                            do so, scan the QR code in the object view with your smartphone.
                        </p>

                        <h3 class="italic mb-4">Please notice:</h3>
                        <p class="mb-4">
                            The way objects are documented is subject to the changing conditions of knowledge
                            production. Since the Ornament Explorer accesses all digitised data and objects in the
                            collection database related to the term ornament, i.e. also those that are not normally
                            publicly accessible, they may contain language that is not up-to-date or non-discriminatory,
                            as well as errors or blanks, since they have not yet been fully checked.<br />
                            Why aren't more objects available online? Apart from the intensive examination, this is also
                            due to copyright regulations, which may prevent objects from being shown outside the museum.
                            The digital recording and publication of the more than 500,000 objects is an ongoing
                            process.
                        </p>
                    </div>
                    <ConfirmButton class="bg-black text-white my-2 md:my-4 text-lg md:text-2xl" @click="emit('close')"
                        >Schließen</ConfirmButton
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import ConfirmButton from './ConfirmButton.vue'
const props = defineProps({
    visible: Boolean,
})
const emit = defineEmits(['close'])
</script>

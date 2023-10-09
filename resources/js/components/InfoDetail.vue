<template>
    <div class="fixed inset-0 z-50 flex justify-center items-center" v-if="visible">
        <div class="bg-black opacity-70 absolute inset-0 cursor-zoom-out" @click="emit('close')" />
        <div class="max-h-full overflow-y-auto overflow-x-hidden p-4">
            <div class="relative w-full max-w-2xl md:max-w-3xl bg-white rounded-xl">
                <div class="px-4 py-4 md:py-6 md:px-6">
                    <h2 class="text-xl md:text-4xl font-bold mb-4 md:mb-6">Ornament Explorer Objektschild</h2>
                    <div class="description md:text-2xl whitespace-pre-line mb-4 md:mb-6">
                        Im Ornament Explorer sind über 12.000 Bilder von Objekten aus allen Sammlungen des MK&G zu sehen. Der Ausgangspunkt Ihrer Entdeckungsreise ist ein zufälliges Objekt im Zentrum. Berühren Sie den Bereich in der MItte, um das Objekt anzuzeigen. Klicken sie auf den Bereich rechts des Bildes, um ein Objekt zu sehen, dass dem mittleren Bild visuell am ähnlichsten ist. Die Ähnlichkeit wurde durch eine KI ermittelt. Klicken Sie nach links und es wird ein Objekt gezeigt, das visuell unähnlich erscheint. <br><br>Klicken Sie nach oben oder unten, um basierend auf den Metadaten des mittleren Objekts, ein Objekt zu erhalten, dessen Entstehungsdatum xx Jahre früher oder später ist. Ausgehend vom Objekt in der Mitte werden immer wieder neue Objektbezüge hergestellt, die neue und überraschende Perspektiven auf die Vielfalt des Ornaments in der Sammlung des MK&G ermöglichen. <br><br>
                        Bei einigen Objekten besteht die Möglichkeit, auf weitere Informationen in der Sammlung Online zuzugreifen. Scannen Sie dazu den QR-Code in der Objektansicht mit Ihrem Smartphone. <br><br>Daten im Ornament Explorer
                        Die Art, wie Objekte dokumentiert werden, unterliegt den sich wandelnden Bedingungen der Wissensproduktion. Da der Ornament Explorer auf alle digitalisierten Daten und Objekte in der Sammlungsdatenbank mit Bezug zum Begriff Ornament zugreift, also auch solche, die normalerweise nicht öffentlich zugänglich sind, weisen diese unter Umständen nicht zeitgemäße oder diskriminierungsfreie Sprache, sowie auch Fehler oder Leerstellen auf, da diese noch nicht vollständig geprüft sind. 
                        Warum sind nicht mehr Objekt online verfügbar? Dies liegt neben der intensiven Prüfung auch an urheberrechtlichen Vorgaben, die u.U. verhindern, dass Objekte außerhalb des Museums gezeigt werden dürfen. Die digitale Erfassung und Veröffentlichung der über 500.000 Objekte ist ein fortlaufender Prozess.<br><br>Mit dem Ornament Explorer wollen wir nicht nur neue Entdeckungen in diese sonst nicht oder nur wenig gezeigten Sammlungsbeständen ermöglichen, sondern einen Beitrag zum kritischen Umgang mit den Sammlungen leisten und diese einem größeren Publikum auch außerhalb der wissenschaftlichen Forschung zugänglich machen. <br><br>Die Idee zum Ornament Explorer wurde im Rahmen des Data Exploration Sprints am MK&G von Michal Čudrnák, Philo van Kemenade und Igor Rjabinin entwickelt und für die Ausstellung angepasst. Während des Workshops betrachteten und analysierten externe Expert*innen die Sammlungsdaten in ihrer Gesamtheit und entwickelten eigene Ideen für neue Zugriffe und Auswertungen. <br><br>Der Ornament Explorer befindet sich noch in der Testphase. Mit Ihrem Feedback können Sie dazu beitragen, Anwendungen, die neue und experimentelle Zugriffe auf Sammlungen ermöglichen, weiterzuentwickeln. 

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
    visible: Boolean
})
const emit = defineEmits(['close'])
</script>

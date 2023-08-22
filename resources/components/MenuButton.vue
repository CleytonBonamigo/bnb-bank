<template>
    <button @click="openMenu" class="button-menu p-5">
        <Icon :icon="{ name: 'menu', size: 6}"/>
    </button>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import Icon from "./Icon.vue";

export default defineComponent({
    components: {Icon},
    methods: {
        openMenu(): void {
            this.$store.commit('toggleMenu');
        }
    }
})
</script>



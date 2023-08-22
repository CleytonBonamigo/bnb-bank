<template>
    <button class="button-filter p-5">
        <Icon :icon="{ name: 'filter'}"/>
    </button>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import Icon from "./Icon.vue";

export default defineComponent({
    components: {Icon}
})
</script>



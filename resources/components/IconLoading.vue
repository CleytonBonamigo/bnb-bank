<template>
    <div class="icon-loading">
        <Icon :icon="{ name: 'loading'}"/>
    </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import Icon from "./Icon.vue";

export default defineComponent({
    components: {Icon}
})
</script>

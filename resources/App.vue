<template>
    <div class="relative">
        <Sidebar/>

        <div class="content">
            <router-view></router-view>
        </div>
    </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import Sidebar from "./components/Sidebar.vue";

export default defineComponent({
    components: {Sidebar},
})
</script>

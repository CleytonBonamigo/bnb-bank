<template>
    <div class="header-month">
        <span>{{ month }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import {getMonth} from "../utils/getDate";

export default defineComponent({
    computed: {
        month(): string {
            return getMonth();
        }
    }
});
</script>

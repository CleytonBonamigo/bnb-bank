<template>
    <div class="income">
        <div>
            <span class="income-title">{{ title }}</span>
            <span class="income-value">{{ formatValue(value) }}</span>
        </div>

        <button class="income-button" @click="handleButton">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>

            <span>{{ button_text }}</span>
        </button>
    </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import {formatValue} from "../utils/formarValue";

export default defineComponent({
    props: {
        title: {
            type: String,
            required: true
        },
        value: {
            type: Number,
            required: true
        },
        button_text: {
            type: String,
            required: true
        },
    },
    methods: {
        handleButton(): void {
            this.$emit('handleButton');
        },
        formatValue
    }
})
</script>

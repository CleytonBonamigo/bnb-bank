<template>
    <div class="input-group-icon" @click.prevent="handleAction" :class="{'has-action': action}">
        <label>
            <div>
                <slot></slot>
                {{ title }}
            </div>

            <div class="value">{{ value }}</div>
            <small v-if="observation !== ''">{{ observation }}</small>
        </label>

        <button v-if="action" class="button-action">
            <Icon :icon="{ name: 'chevron-right'}"/>
        </button>
    </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import Icon from "./Icon.vue";

export default defineComponent({
    components: {Icon},
    props: {
        title: {
            type: String,
            required: true
        },
        value: {
            type: String,
            required: true
        },
        action: {
            type: Boolean,
            required: false,
            default: false
        },
        observation: {
            type: String,
            required: false,
            default: ''
        }
    },
    methods: {
        handleAction() {
            if (this.action) {
                this.$emit('handleAction');
            }
        }
    }
});
</script>

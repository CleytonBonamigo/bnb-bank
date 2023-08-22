<template>
    <div>
        <span class="header-info-title">Current balance</span>
        <span class="header-info-value">{{ balance_formatted }}</span>
    </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import {formatValue} from "../utils/formarValue";

export default defineComponent({
    computed: {
        balance_formatted(): string {
            return formatValue(this.$store.state.user.balance);
        }
    }
});
</script>

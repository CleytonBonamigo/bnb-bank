<template>
    <div>
        <div class="header relative header-light">
            <MenuButton class="absolute top-0 left-0"/>
            <span class="header-title">EXPENSES</span>
            <div class="header-info">
                <HeaderMonth />
            </div>
        </div>

        <Transactions :filters="{type_id: 2}"/>

        <FloatButton @click.prevent="goToPurchase"/>
    </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import Transactions from "../components/Transactions.vue";
import MenuButton from "../components/MenuButton.vue";
import FloatButton from "../components/FloatButton.vue";
import HeaderMonth from "../components/HeaderMonth.vue";

export default defineComponent({
    components: {HeaderMonth, FloatButton, MenuButton, Transactions},
    methods: {
        goToPurchase(): void {
            this.$router.push('/purchase');
        }
    }
})
</script>

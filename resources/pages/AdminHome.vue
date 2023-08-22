<template>
    <div>
        <div class="header relative header-light">
            <MenuButton class="absolute top-0 left-0"/>
            <span class="header-title">Checks Control</span>
            <FilterButton class="absolute top-1 right-0"/>
        </div>

        <Transactions :show-title="false" @handleTransaction="goToCheckDatail" :filters="{type_id: 1, status_id: 1}"/>
    </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import Transactions from "../components/Transactions.vue";
import MenuButton from "../components/MenuButton.vue";
import FilterButton from "../components/FilterButton.vue";
import {TransactionTypeDTO} from "../dto/Transaction.dto";

export default defineComponent({
    components: {FilterButton, MenuButton, Transactions},
    methods: {
        goToCheckDatail(transaction: TransactionTypeDTO): void {
            this.$router.push('/admin/check-details/' + transaction.id);
        }
    }
})
</script>

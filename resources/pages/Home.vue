<template>
    <div>
        <div class="header relative">
            <MenuButton class="absolute top-0 left-0"/> BNB Bank

            <div class="header-info">
                <CurrentBalance/>
                <HeaderMonth/>
            </div>
        </div>

        <div>
            <HeaderTotalTransaction title="Incomes" :value="incomes" button_text="Deposit a check" @handleButton="deposit"/>
            <HeaderTotalTransaction title="Expenses" :value="expenses" button_text="Purchase" class="expenses" @handleButton="purchase"/>
        </div>

        <Transactions :filters="{status_id: 2}" :get-customer-balance="true" @loadedTransactions="loadedTransactions"/>
    </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import HeaderTotalTransaction from "../components/HeaderTotalTransaction.vue";
import Transactions from "../components/Transactions.vue";
import MenuButton from "../components/MenuButton.vue";
import CurrentBalance from "../components/CurrentBalance.vue";
import HeaderMonth from "../components/HeaderMonth.vue";

export default defineComponent({
    components: {HeaderMonth, CurrentBalance, MenuButton, Transactions, HeaderTotalTransaction},
    data() {
        return {
            incomes: 0,
            expenses: 0,
        }
    },
    methods: {
        deposit(): void {
            this.$router.push('/deposit');
        },
        purchase(): void {
            this.$router.push('/purchase');
        },
        loadedTransactions(payload: any): void {
            this.incomes = payload.incomes
            this.expenses = payload.expenses
        }
    }
})
</script>

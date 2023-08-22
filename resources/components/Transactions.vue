<template>
    <div class="transactions">
        <h2 v-if="showTitle">Transactions</h2>

        <ul>
            <li v-if="isLoading" class="loading">
                <IconLoading/>
                Loading...
            </li>

            <li v-if="!isLoading && transactions.length === 0 && isAdmin">
                Congratulations!<br>
                Everything is correct, no checks to be analyzed.
            </li>

            <li v-if="!isLoading && transactions.length === 0 && !isAdmin">
                No transaction found
            </li>

            <li v-for="transaction in transactions" @click="handleTransaction(transaction)">
                <div>
                    <h3>{{ transaction.description }}</h3>
                    <span>{{ transaction.date_formatted }}</span>
                </div>

                <div class="value" :class="{'negative-value': transaction.amount < 0}">
                    ${{ transaction.amount_formatted }}
                </div>
            </li>
        </ul>
    </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import type {PropType} from 'vue'
import axios from "../services/axios";
import IconLoading from "./IconLoading.vue";
import {TransactionTypeDTO} from "../dto/Transaction.dto";
import {FiltersTypeDTO} from "../dto/Filters.dto";

export default defineComponent({
    components: {IconLoading},
    props: {
        showTitle: {
            type: Boolean,
            required: false,
            default: true
        },
        filters: {
            type: Object as PropType<FiltersTypeDTO>,
            required: false,
            default: {} as FiltersTypeDTO
        },
        getCustomerBalance: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    computed: {
        isAdmin(): boolean {
            return this.$store.state.user.is_admin;
        }
    },
    mounted() {
        this.load(this.filters);
    },
    data() {
        return {
            transactions: <TransactionTypeDTO[]>[],
            isLoading: false
        }
    },
    methods: {
        load(filters: FiltersTypeDTO): void {
            const params = new URLSearchParams(filters as Record<string, string>).toString();
            this.isLoading = true;

            axios.get('/api/transactions?' + params).then((response) => {
                this.transactions = <TransactionTypeDTO[]>response.data.data;
                this.isLoading = false;
                this.totalTransactions();
            });

            if (this.getCustomerBalance) {
                axios.get('/api/customer/balance').then((response) => {
                    if (response.data.balance) {
                        this.$store.commit('setBalance', response.data.balance);
                    }
                });
            }
        },
        handleTransaction(transaction: TransactionTypeDTO): void {
            this.$emit('handleTransaction', transaction);
        },
        totalTransactions(): void {
            const incomes = this.transactions
                .filter((t) => t.amount > 0)
                .map(t => t.amount)
                .reduce((prev, current) => Number(prev) + Number(current), 0);

            const expenses = this.transactions
                .filter((t) => t.amount < 0)
                .map(t => (t.amount * -1))
                .reduce((prev, current) => Number(prev) + Number(current), 0);

            this.$emit('loadedTransactions', {incomes, expenses});
        }
    }
})
</script>

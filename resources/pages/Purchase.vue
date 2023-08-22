<template>
    <div>
        <div class="header relative header-light">
            <MenuButton class="absolute top-0 left-0"/>
            <span class="header-title">Purchase</span>
            <div class="header-info">
                <CurrentBalance/>
            </div>
        </div>

        <form @submit.prevent="submit" class="pt-5">
            <div class="px-5">
                <div class="input-group-icon ">
                    <label :class="{'has-errors': getError(this.errors, 'amount') !== ''}">
                        <div>
                            <Icon :icon="{ name: 'money', size: 6}"/> AMOUNT
                        </div>

                        <input type="text" v-model="form.amount" placeholder="0.00">

                        <p v-if="getError(this.errors, 'amount') !== ''">{{ getError(this.errors, 'amount') }}</p>
                    </label>

                    <span class="input-group-append">USD</span>
                </div>

                <div class="input-group-icon">
                    <label>
                        <div>
                            <Icon :icon="{ name: 'calendar', size: 6}"/> DATE
                        </div>

                        <input type="text" v-model="form.date" readonly>
                    </label>

                    <span class="input-group-append"></span>
                </div>

                <div class="input-group-icon">
                    <label :class="{'has-errors': getError(this.errors, 'description') !== ''}">
                        <div>
                            <Icon :icon="{ name: 'star', size: 6}"/> DESCRIPTION
                        </div>

                        <input type="text" v-model="form.description">

                        <p v-if="getError(this.errors, 'description') !== ''">{{ getError(this.errors, 'description') }}</p>
                    </label>
                </div>

                <p class="message-success" v-if="finished">Purchase successfully. <br>Redirecting in {{timeRemaining}} seconds...</p>
            </div>

            <div class="footer">
                <button class="button" :class="{'button-disabled': isLoading}" v-if="!finished">
                    <IconLoading v-if="isLoading" class="mr-2"/> ADD PURCHASE
                </button>

                <button class="button" @click.prevent="home" v-if="finished">
                    HOME
                </button>
            </div>
        </form>
    </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import MenuButton from "../components/MenuButton.vue";
import {formatValue} from "../utils/formarValue";
import {getError} from "../utils/getError";
import {getDate} from "../utils/getDate";
import axios from "../services/axios";
import IconLoading from "../components/IconLoading.vue";
import Icon from "../components/Icon.vue";
import CurrentBalance from "../components/CurrentBalance.vue";

type PurchaseType = {
    amount: number | null;
    date: string;
    description: string;
}

export default defineComponent({
    components: {CurrentBalance, Icon, IconLoading, MenuButton},
    data() {
        return {
            form: <PurchaseType>{
                amount: null,
                date: this.getDate(),
                description: '',
            },
            errors: {},
            isLoading: false,
            finished: false,
            timeRemaining: 5
        }
    },
    methods: {
        submit(): void {
            if (this.isLoading) {
                return;
            }

            this.isLoading = true;
            this.errors = {};

            axios.post('/api/purchases', this.form)
                .then((response) => {
                    this.isLoading = false;
                    this.finished = true;

                    console.log(response);

                    if (response.data.balance) {
                        this.$store.commit('setBalance', response.data.balance);
                    }

                    setTimeout(() => {
                        this.$router.push('/')
                    }, 5000);
                })
                .catch((error) => {
                    this.isLoading = false;

                    if (error.response.status === 422 && error.response.data.errors) {
                        this.errors = error.response.data.errors;
                    }
                });
        },
        triggerTiming(): void {
            let start = 5;
            setInterval(() => {
                this.timeRemaining = start;
                start -= 1;
            }, 1000);
        },
        home(): void {
            this.$router.push('/')
        },
        formatValue,
        getError,
        getDate,

    }
})
</script>

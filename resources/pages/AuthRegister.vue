<template>
    <form @submit.prevent="submit">
        <div class="header-login">BNB Bank</div>

        <div class="flex flex-col p-8 space-y-4">
            <div :class="{'has-errors': getError(this.errors, 'name') !== ''}">
                <input type="text" class="input-rounded" placeholder="name" v-model="form.name"/>
                <p v-if="getError(this.errors, 'name') !== ''">{{ getError(this.errors, 'name') }}</p>
            </div>

            <div :class="{'has-errors': getError(this.errors, 'email') !== ''}">
                <input type="email" class="input-rounded" placeholder="email" v-model="form.email"/>
                <p v-if="getError(this.errors, 'email') !== ''">{{ getError(this.errors, 'email') }}</p>
            </div>

            <div :class="{'has-errors': getError(this.errors, 'username') !== ''}">
                <input type="text" class="input-rounded" placeholder="username" v-model="form.username"/>
                <p v-if="getError(this.errors, 'username') !== ''">{{ getError(this.errors, 'username') }}</p>
            </div>

            <div :class="{'has-errors': getError(this.errors, 'password') !== ''}">
                <input type="password" class="input-rounded" placeholder="password" v-model="form.password"/>
                <p v-if="getError(this.errors, 'password') !== ''">{{ getError(this.errors, 'password') }}</p>
            </div>

            <div :class="{'has-errors': getError(this.errors, 'password_confirmation') !== ''}">
                <input type="password" class="input-rounded" placeholder="password confirmation" v-model="form.password_confirmation"/>
                <p v-if="getError(this.errors, 'password_confirmation') !== ''">{{ getError(this.errors, 'password_confirmation') }}</p>
            </div>
        </div>

        <div class="my-2 mx-8">
            <button class="button" :class="{'button-disabled': isLoading}">
                <IconLoading v-if="isLoading" class="mr-2"/>
                SIGN UP
            </button>
        </div>

        <div class="flex justify-center mt-12">
            <span class="w-1/12 h-1 bg-blue-100 rounded-full"></span>
        </div>

        <div class="link-login">
            <router-link to="/auth/login">Already have an account?</router-link>
        </div>
    </form>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import axios from "../services/axios";
import {getError} from "../utils/getError";
import IconLoading from "../components/IconLoading.vue";

type CustomerRegister = {
    name: string;
    email: string;
    username: string;
    password: string;
    password_confirmation: string;
}

export default defineComponent({
    components: {IconLoading},
    data() {
        return {
            form: <CustomerRegister>{
                name: '',
                email: '',
                username: '',
                password: '',
                password_confirmation: '',
            },
            errors: {},
            isLoading: false
        }
    },
    methods: {
        async submit() {
            if (this.isLoading) {
                return;
            }

            this.isLoading = true;
            this.errors = {};

            try {
                await axios.post('/auth/register', this.form);

                const {data} = await axios.post('/auth/login', {username: this.form.username, password: this.form.password});
                const user = data.user;

                this.$store.commit('setUser', {
                    id: user.id,
                    name: user.name,
                    email: user.email,
                    username: user.username,
                    is_admin: user.is_admin > 0,
                    balance: parseFloat(user.balance),
                    token: data.token
                });

                document.location.reload();
            } catch (error: any) {
                this.isLoading = false;

                 if (error && error.response && error.response.status === 422 && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                }
            }
        },
        getError
    }
});
</script>

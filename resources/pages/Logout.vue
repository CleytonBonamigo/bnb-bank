<template>
    <IconLoading/>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import axios from "../services/axios";
import IconLoading from "../components/IconLoading.vue";

export default defineComponent({
    components: {IconLoading},
    mounted() {
        this.logout();
    },
    methods: {
        logout(): void {
            axios.post('/auth/logout').then(() => {
                this.clear();
            }).catch(() => {
                this.clear();
            })
        },
        clear(): void {
            this.$store.commit('setUser', {});
            localStorage.removeItem('user');
            document.location.reload();
        }
    }
});
</script>

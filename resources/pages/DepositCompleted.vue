<template>
    <div>
        <div class="header relative header-light">
            <MenuButton class="absolute top-0 left-0"/>
            <span class="header-title">Check Deposited</span>
            <div class="header-info">
                <CurrentBalance/>
            </div>
        </div>

        <div class="w-full flex px-5 py-10 text-blue-400 space-4" v-if="isLoading">
            <IconLoading/>
            <span class="ml-4">Loading transaction...</span>
        </div>

        <div class="w-full flex px-5 py-10 text-blue-400 space-4" v-if="!isLoading && !(transaction.id > 0)">
            <span>transaction not found.</span>
        </div>

        <div class="pb-20 info-list px-5" v-if="transaction.id > 0">
            <DetailListItem title="Amount" :value="'$' + transaction.amount_formatted + ' USD'" observation="* The money will be deposited in your account once the check is approved.">
                <Icon :icon="{ name: 'money', size: 6}"/>
            </DetailListItem>

            <DetailListItem title="DESCRIPTION" value="Gits's">
                <Icon :icon="{ name: 'star', size: 6}"/>
            </DetailListItem>

            <img v-if="file.showPreview" :src="file.imagePreview" class="upload-preview">

            <p class="message-success">Check deposited successfully.</p>
        </div>

        <div class="footer">
            <button class="button" @click.prevent="home">
                HOME
            </button>
        </div>
    </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import MenuButton from "../components/MenuButton.vue";
import DetailListItem from "../components/DetailListItem.vue";
import Icon from "../components/Icon.vue";
import CurrentBalance from "../components/CurrentBalance.vue";
import axios from "../services/axios";
import IconLoading from "../components/IconLoading.vue";
import {TransactionTypeDTO} from "../dto/Transaction.dto";
import {DepositFileType} from '../dto/DepositFile.dto';

export default defineComponent({
    components: {IconLoading, CurrentBalance, Icon, DetailListItem, MenuButton},
    mounted() {
        this.load();
    },
    computed: {
        check_url(): string {
            return '/api/transactions/' + this.transaction_id + '/image';
        }
    },
    data() {
        return {
            transaction_id: this.$route.params.id,
            transaction: {},
            isLoading: false,

            file: <DepositFileType>{
                file: null,
                showPreview: false,
                imagePreview: null
            },
        }
    },
    methods: {
        load(): void {
            this.isLoading = true;
            const transaction_id = String(Number(this.transaction_id));

            axios.get('/api/transactions/' + this.transaction_id).then((response) => {
                this.transaction = <TransactionTypeDTO>response.data.transaction;
                this.isLoading = false;
            }).catch(() => {
                this.isLoading = false;
            });

            axios.get('/api/transactions/' + transaction_id + '/image', {responseType: "blob"})
                .then(response => {
                    const file = response.data;

                    if (file != null) {
                        const reader = new FileReader();

                        reader.onload = (event: ProgressEvent<FileReader>) => {
                            if (event.target != null) {
                                this.file.showPreview = true;
                                this.file.imagePreview = event.target.result;
                            }
                        };

                        reader.readAsDataURL(file);
                    }
                });
        },
        home(): void {
            this.$router.push('/')
        }
    }
})
</script>

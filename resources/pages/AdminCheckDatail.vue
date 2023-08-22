<template>
    <div>
        <div class="header relative header-light">
            <MenuButton class="absolute top-0 left-0"/>
            <span class="header-title">Check Details</span>
        </div>

        <div class="w-full flex px-5 py-10 text-blue-400 space-4" v-if="!(transaction.id > 0)">
            <IconLoading/>
            <span class="ml-4">Loading transaction...</span>
        </div>

        <div class="w-full flex px-5 py-10 text-blue-400 space-4" v-if="!isLoading && !(transaction.id > 0)">
            <span>transaction not found.</span>
        </div>

        <div class="pb-20 info-list px-5" v-if="transaction.id > 0">
            <DetailListItem title="Customer" :value="transaction.customer_name">
                <Icon :icon="{ name: 'user'}"/>
            </DetailListItem>

            <DetailListItem title="Customer email" :value="transaction.customer_email" :action="true">
                <Icon :icon="{ name: 'email'}"/>
            </DetailListItem>

            <DetailListItem title="Account" :value="transaction.customer_account" :action="true">
                <Icon :icon="{ name: 'document-text'}"/>
            </DetailListItem>

            <DetailListItem title="Reported Amount" :value="'$' + transaction.amount_formatted + ' USD'">
                <Icon :icon="{ name: 'money', size: 6}"/>
            </DetailListItem>

          <p class="message-success" v-if="approved">Transaction approved successfully. <br>Redirecting in {{timeRemaining}} seconds...</p>
            <p class="message-success" v-if="rejected">Transaction rejected successfully. <br>Redirecting in {{timeRemaining}} seconds...</p>

            <img v-if="!approved && !rejected && file.showPreview" :src="file.imagePreview" class="upload-preview">

            <p class="message-error" v-if="error !== false">{{ error }}</p>
        </div>

        <div class="footer" v-if="transaction.id > 0 && !approved && !rejected">
            <button class="button button-inverter" @click.prevent="reject" :class="{'button-disabled': isLoading}">
                <IconLoading v-if="isLoading && !isApproving" class="mr-2"/>
                <Icon v-else :icon="{ name: 'close-2'}"/>
                REJECT
            </button>

            <button class="button" @click.prevent="approve" :class="{'button-disabled': isLoading}">
                <IconLoading v-if="isLoading && isApproving" class="mr-2"/>
                <Icon v-else :icon="{ name: 'accept-2'}"/>
                APPROVE
            </button>
        </div>

        <div class="footer" v-if="approved || rejected">
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
import axios from "../services/axios";
import {TransactionTypeDTO} from "../dto/Transaction.dto";
import IconLoading from "../components/IconLoading.vue";
import {DepositFileType} from "../dto/DepositFile.dto";

export default defineComponent({
    components: {IconLoading, Icon, DetailListItem, MenuButton},
    mounted() {
        this.load();
    },
    data() {
        return {
            transaction_id: this.$route.params.id,
            transaction: {},
            isApproving: false,
            isLoading: false,
            approved: false,
            rejected: false,
            error: false,
            timeRemaining: 5,
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

            axios.get('/api/transactions/' + this.transaction_id).then((response) => {
                this.transaction = <TransactionTypeDTO>response.data.transaction;
                this.isLoading = false;
            }).catch(() => {
                this.isLoading = false;
            });

            axios.get('/api/transactions/' + this.transaction_id + '/image', {responseType: "blob"})
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
        approve(): void {
            this.error = false;
            this.isLoading = true;
            this.isApproving = true;

            axios.post('/api/deposit/' + this.transaction_id + '/approve')
                .then(() => {
                    this.isLoading = false;
                    this.approved = true;
                    this.triggerTiming();

                    setTimeout(() => {
                        this.$router.push('/admin')
                    }, 5000);
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err.response.data.message;
                });
        },
        reject(): void {
            this.error = false;
            this.isLoading = true;
            this.isApproving = false;

            axios.post('/api/deposit/' + this.transaction_id + '/reject')
                .then(() => {
                    this.isLoading = false;
                    this.rejected = true;
                    this.triggerTiming();

                    setTimeout(() => {
                        this.$router.push('/admin')
                    }, 5000);
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err.response.data.message;
                });
        },
        home(): void {
            this.$router.push('/admin')
        },
        triggerTiming(): void {
            let start = 5;
            setInterval(() => {
                this.timeRemaining = start;
                start -= 1;
            }, 1000);
        }
    }
})
</script>

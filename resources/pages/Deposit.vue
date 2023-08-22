<template>
    <div>
        <div class="header relative header-light">
            <MenuButton class="absolute top-0 left-0"/>
            <span class="header-title">Check Deposit</span>
            <div class="header-info">
                <CurrentBalance/>
            </div>
        </div>

        <form @submit.prevent="submit" class="pb-20">
            <div class="px-5">
                <div class="input-group-icon">
                    <label :class="{'has-errors': getError(this.errors, 'amount') !== ''}">
                        <div>
                            <Icon :icon="{ name: 'money', size: 6}"/> AMOUNT
                        </div>

                        <input type="text" v-model="form.amount" placeholder="0.00">

                        <p v-if="getError(this.errors, 'amount') !== ''">{{ getError(this.errors, 'amount') }}</p>

                        <small>* The money will be deposited in your account once the check is approved.</small>
                    </label>

                    <span class="input-group-append">USD</span>
                </div>

                <div class="input-group-icon">
                    <label :class="{'has-errors': getError(this.errors, 'description') !== ''}">
                        <div>
                            <Icon :icon="{ name: 'star'}"/> DESCRIPTION
                        </div>

                        <input type="text" v-model="form.description">

                        <p v-if="getError(this.errors, 'description') !== ''">{{ getError(this.errors, 'description') }}</p>
                    </label>
                </div>

                <div :class="{'has-errors': getError(this.errors, 'check') !== ''}">
                    <div class="card-upload" v-if="!fileUpload.showPreview">
                        <Icon :icon="{ name: 'image', size: 12}"/>

                        <span>Upload check picture</span>

                        <input type="file" ref="file" class="file" accept="image/*" @change="handleFileUpload()"/>
                    </div>

                    <img v-else :src="fileUpload.imagePreview" class="upload-preview">

                    <p v-if="getError(this.errors, 'check') !== ''">{{ getError(this.errors, 'check') }}</p>
                </div>

                <button type="button" v-if="fileUpload.file" class="button-link" @click.prevent="resetImage">
                    <Icon :icon="{ name: 'close', size: 4}"/>
                    Reset image
                </button>
            </div>

            <div class="footer">
                <button class="button" :class="{'button-disabled': isLoading}" v-if="!finished">
                    <IconLoading v-if="isLoading" class="mr-2"/>
                    <span v-if="isLoading && fileUpload.file">
                        {{ uploadProgress }}% completed...
                    </span>
                    <span v-else>DEPOSIT CHECK</span>
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
import Icon from "../components/Icon.vue";
import CurrentBalance from "../components/CurrentBalance.vue";
import axios from "../services/axios";
import IconLoading from "../components/IconLoading.vue";
import {DepositFileType} from '../dto/DepositFile.dto';

type DepositType = {
    amount: number | null;
    description: string;
}

export default defineComponent({
    components: {IconLoading, CurrentBalance, Icon, MenuButton},
    data() {
        return {
            form: <DepositType>{
                amount: null,
                description: ''
            },
            fileUpload: <DepositFileType>{
                file: null,
                showPreview: false,
                imagePreview: null
            },
            errors: {},
            isLoading: false,
            finished: false,
            uploadProgress: 0
        }
    },

    methods: {
        submit(): void {
            if (this.isLoading) {
                return;
            }

            this.isLoading = true;
            this.errors = {};

            const form = new FormData();

            form.append("amount", String(this.form.amount));
            form.append("description", this.form.description);

            if (this.fileUpload.file) {
                form.append("check", this.fileUpload.file, this.fileUpload.file.name);
            }

            axios.post('/api/deposit', form, {
                headers: {'Content-Type': 'multipart/form-data'},
                onUploadProgress: (progressEvent) => {
                    this.uploadProgress = Number(Math.round((progressEvent.loaded / progressEvent.total) * 100));
                }
            })
                .then((response) => {
                    this.isLoading = false;
                    this.finished = true;
                    this.$router.push('/deposit-completed/' + response.data.transaction.id);
                })
                .catch((error) => {
                    this.isLoading = false;

                    if (error.response.status === 422 && error.response.data.errors) {
                        this.errors = error.response.data.errors;
                    }
                });
        },
        handleFileUpload(): void {
            const ref = (this.$refs['file'] as HTMLInputElement);

            if (ref.files != null) {
                this.fileUpload.file = ref.files[0];

                const reader = new FileReader();

                reader.onload = (event: ProgressEvent<FileReader>) => {
                    if (event.target != null) {
                        this.fileUpload.showPreview = true;
                        this.fileUpload.imagePreview = event.target.result;
                    }
                };

                reader.readAsDataURL(this.fileUpload.file);

                this.errors = {};
            } else {
                this.resetImage();
            }
        },
        resetImage(): void {
            this.fileUpload.file = null;
            this.fileUpload.showPreview = false;
            this.fileUpload.imagePreview = null;
        },
        formatValue,
        getError
    }
})
</script>

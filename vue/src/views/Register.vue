<script setup>
import { ref } from "vue";
import store from "../store"
import Alert from "../components/Alert.vue";
import {useRouter} from "vue-router"

const router = useRouter();
const user = {
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
};

const errors = ref({})

const loading = ref(false)

function register(ev) {
    ev.preventDefault();

    loading.value = true
    store
        .dispatch('register', user)
        .then((res) => {
            loading.value = false
            router.push({
                name:'Dashboard'
            })
        })
        .catch(err => {
            if(err.response.status === 422) {
                loading.value = false
                errors.value = err.response.data.errors
            }
        })
}
</script>

<template>     
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="space-y-6" @submit="register">
            <Alert v-if="Object.keys(errors).length" class="flex-col items-stretch text-sm">
                <div v-for="(field, i) of Object.keys(errors)" :key="i">
                    <div v-for="(error, ind) of errors[field] || []" :key="ind">
                    *   {{ error }}
                    </div>
                </div>
            </Alert>
            <div>
                <label for="fullname" class="block text-sm/6 font-medium text-gray-900">Full Name</label>
                <div class="mt-2">
                    <input type="text" name="fullname" id="fullname" autocomplete="fullname" required="" v-model="user.name" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                </div>
            </div>
            
            <div>
                <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
                <div class="mt-2">
                    <input type="email" name="email" id="email" autocomplete="email" required="" v-model="user.email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                </div>
            </div>
            
            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
                </div>
                <div class="mt-2">
                    <input type="password" name="password" id="password" autocomplete="current-password" required="" v-model="user.password" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                </div>
            </div>
                        <div>
                <div class="flex items-center justify-between">
                    <label for="password_confirmation" class="block text-sm/6 font-medium text-gray-900">Password Confirmation</label>
                </div>
                <div class="mt-2">
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="current-password_confirmation" required="" v-model="user.password_confirmation" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                </div>
            </div>
            
            <div>
                    <button :disabled="loading" type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" :class="{'cursor-not-allowed': loading, 'hover:bg-indigo-500': loading}">
                    <svg v-if="loading" class="mr-3 -ml-1 size-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Sign Up
                </button>
            </div>
        </form>
    </div>
</template>

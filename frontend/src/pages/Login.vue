<template>
    <div class="authincation h-100 mt-5">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">Sign in your account</h4>
                                    <form @submit.prevent="loginPage">
                                        <div class="form-group">
                                            <label>
                                                <strong>Username</strong>
                                            </label>
                                            <input
                                                v-model="username"
                                                type="text"
                                                class="form-control"
                                                value
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label>
                                                <strong>Password</strong>
                                            </label>
                                            <input
                                                v-model="password"
                                                type="password"
                                                class="form-control"
                                                value
                                            />
                                        </div>
                                        <div class="text-center">
                                            <button
                                                type="submit"
                                                class="btn btn-primary btn-block"
                                            >Sign me in</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { useRouter } from 'vue-router'
import { ref } from 'vue'
import { useAccountStore } from '@/stores/account'
import api from '@/services/api'

document.title = 'Login'
const emit = defineEmits(['login'])
const username = ref('')
const password = ref('')
const account = useAccountStore()
const router = useRouter()
const loginPage = async () => {
    let res = await api.login(username.value, password.value)
    if (res.status) {
        window.localStorage.setItem('auth', res.token)
        window.localStorage.setItem('is_login', true)
        emit('login', await account.update())
        router.replace('/')
    } else {
        sweetAlert('Oops...', res.msg, 'error')
    }
}
</script>

<style scoped>
</style>
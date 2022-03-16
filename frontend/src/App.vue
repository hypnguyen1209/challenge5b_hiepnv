<template>
    <div v-show="isLogin">
        <div id="main-wrapper">
            <Header @logout="changeLogin" />
            <Sidebar />
            <Main />
            <Footer />
        </div>
    </div>
    <Login v-if="!isLogin" @login="changeLogin" />
</template>
<script setup>
import { useRouter, useRoute } from 'vue-router'
import Preloader from '@/components/Preloader.vue'
import Header from '@/components/Header.vue'
import Sidebar from '@/components/Sidebar.vue'
import Main from '@/components/Main.vue'
import Footer from '@/components/Footer.vue'
import Login from '@/pages/Login.vue'
import { ref, watch } from 'vue'
import { useAccountStore } from '@/stores/account'

const router = useRouter()
const route = useRoute()
const account = useAccountStore()
const isLogin = ref(true)
const changeLogin = state => {
    isLogin.value = state
}
const checklogin = async () => {
    isLogin.value = await account.update()
    if (!isLogin.value && route.path !== '/login') {
        router.replace('/login')
        sweetAlert('Oops...', account.log, 'error')
        account.$reset()
    }
    if (route.path === '/login' && isLogin.value) {
        router.replace('/')
    }
}
if (route.path !== '/login' && !window.localStorage.getItem('is_login')) {
    router.replace('/login')
    isLogin.value = false
}

if (route.path !== '/login' && window.localStorage.getItem('is_login')) {
    checklogin()
}

if (route.path === '/login' && window.localStorage.getItem('is_login')) {

}
</script>

<style>
</style>

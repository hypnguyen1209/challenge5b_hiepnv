<template>
    <!--**********************************
            Nav header start
    ***********************************-->
    <div class="nav-header">
        <router-link to="/" class="brand-logo">
            <img class="logo-abbr" src="/assets/images/eo.png" />
            <img class="logo-compact" src="/assets/images/logo-text.png" />
            <img class="brand-title" src="/assets/images/logo-text.png" />
        </router-link>
        <div class="nav-control">
            <div class="hamburger">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </div>
        </div>
    </div>
    <!--**********************************
            Nav header end
    ***********************************-->
    <!--**********************************
            Header start
    ***********************************-->
    <div class="header">
        <div class="header-content">
            <nav class="navbar navbar-expand">
                <div class="collapse navbar-collapse justify-content-between">
                    <div class="header-left"></div>
                    <ul class="navbar-nav header-right">
                        <li class="nav-item dropdown header-profile">
                            <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                <i class="mdi mdi-account"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <router-link to="/me" class="dropdown-item">
                                    <i class="icon-user"></i>
                                    <span class="ml-2">Profile</span>
                                </router-link>
                                <router-link
                                    @click.prevent="showMessage"
                                    :to="route.path"
                                    class="dropdown-item"
                                >
                                    <i class="icon-envelope-open"></i>
                                    <span class="ml-2">Message</span>
                                </router-link>
                                <router-link
                                    @click.prevent="logout"
                                    to="/login"
                                    class="dropdown-item"
                                >
                                    <i class="icon-key"></i>
                                    <span class="ml-2">Logout</span>
                                </router-link>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <!--**********************************
            Header end ti-comment-alt
    ***********************************-->
</template>
<script setup>
import { useRoute } from 'vue-router'
import api from '@/services/api'
import { useAccountStore } from '@/stores/account'
const route = useRoute()
const account = useAccountStore()
const emit = defineEmits(['logout'])
const logout = async () => {
    const res = await api.logout()
    if (res.status) {
        account.$reset()
        window.localStorage.removeItem('is_login')
        window.localStorage.removeItem('auth')
        emit('logout', false)
    } else {
        sweetAlert('Oops...', res.message, 'error')
    }
}
const showMessage = () => {
    let { message } = account
    swal('My message', message, 'info')
}
</script>
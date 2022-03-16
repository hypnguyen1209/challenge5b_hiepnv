<template>
    <!--**********************************
    Sidebar start
    ***********************************-->
    <div class="quixnav">
        <div class="quixnav-scroll">
            <ul class="metismenu" id="menu">
                <li class="nav-label first">Main Menu</li>
                <li :class="{ 'mm-active': currentPage == '/' }">
                    <router-link to="/" aria-expanded="false">
                        <i class="icon icon-home"></i>
                        <span class="nav-text">Dashboard</span>
                    </router-link>
                </li>
                <li :class="{ 'mm-active': currentPage == '/exercises' }">
                    <router-link to="/exercises" aria-expanded="false">
                        <i class="icon icon-single-copy-06"></i>
                        <span class="nav-text">Exercises</span>
                    </router-link>
                </li>
                <li :class="{ 'mm-active': currentPage == '/challenges' }">
                    <router-link to="/challenges" aria-expanded="false">
                        <i class="icon icon-puzzle-10"></i>
                        <span class="nav-text">Challenges</span>
                    </router-link>
                </li>
                <template v-if="account.type === 'admin' || account.type === 'teacher'">
                    <li :class="{ 'mm-active': currentPage == '/students' }">
                        <router-link to="/students" aria-expanded="false">
                            <i class="icon icon-users-mm"></i>
                            <span class="nav-text">Students</span>
                        </router-link>
                    </li>
                </template>
                <template v-if="account.type === 'admin'">
                    <li class="nav-label">Admin</li>
                    <li :class="{ 'mm-active': currentPage == '/manager' }">
                        <router-link to="/manager" aria-expanded="false">
                            <i class="icon icon-preferences-circle"></i>
                            <span class="nav-text">Manager</span>
                        </router-link>
                    </li>
                </template>
            </ul>
        </div>
    </div>
    <!--**********************************
    Sidebar end
    ***********************************-->
</template>
<script setup>
import { useAccountStore } from '@/stores/account'
import { watch, ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const account = useAccountStore()
const route = useRoute()
const currentPage = ref('')

watch(route, (v) => {
    currentPage.value = v.path
})

onMounted(() => {
    currentPage.value = route.path
})

</script>
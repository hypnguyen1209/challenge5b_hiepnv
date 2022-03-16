<template>
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Hí {{ fullname }}, welcome back!</h4>
                        <span class="ml-1"></span>
                    </div>
                </div>
            </div>
            <template v-if="account">
                <router-view :propAccount="{ id, username, fullname, type, phone, email }"></router-view>
            </template>
        </div>
    </div>
</template>
<script setup>
import { useAccountStore } from '@/stores/account'
import { storeToRefs } from 'pinia'
const account = useAccountStore()
const { id, username, fullname, type, phone, email } = storeToRefs(account)
</script>
<template>
    <div class="row justify-content-md-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Avatar</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img
                            data-holder-rendered="true"
                            class="rounded-circle"
                            style="object-position: center; object-fit: cover; max-width: 100%; width: 150px; height: 150px;"
                            :src="avt"
                        />
                        <span ref="avt-fullname" class="text-dark">{{ account.fullname }}</span>
                        <span ref="avt-email">{{ account.email }}</span>
                    </div>
                    <div class="d-flex flex-column align-items-center text-center mt-2">
                        <button
                            type="button"
                            class="btn btn-outline-danger btn-block"
                            data-toggle="modal"
                            data-target="#changeAvt"
                        >Edit Avatar</button>
                        <change-avatar @onChangeAvatar="changeAvt" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Infomation</h4>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-9">
                                <input
                                    :disabled="account.type === 'student'"
                                    v-model="newAccount.username"
                                    class="form-control"
                                    placeholder="Username"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Fullname</label>
                            <div class="col-sm-9">
                                <input
                                    :disabled="account.type === 'student'"
                                    v-model="newAccount.fullname"
                                    class="form-control"
                                    placeholder="Fullname"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input
                                    v-model="newAccount.email"
                                    class="form-control"
                                    placeholder="Email"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Phone Number</label>
                            <div class="col-sm-9">
                                <input
                                    v-model="newAccount.phone"
                                    class="form-control"
                                    placeholder="Phone Number"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button
                                    @click="saveInfo"
                                    type="button"
                                    class="btn btn-primary btn-block"
                                >Save</button>
                            </div>
                            <div class="col-md-6">
                                <button
                                    type="button"
                                    class="btn btn-secondary btn-block"
                                    data-toggle="modal"
                                    data-target="#changePassword"
                                >Change password</button>
                                <change-password />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { reactive, ref, watch, toRef } from "vue"
import api from "@/services/api"
import ChangeAvatar from "@/components/ChangeAvatar.vue"
import ChangePassword from "@/components/ChangePassword.vue"
import { useAccountStore } from '@/stores/account'
const props = defineProps({
    propAccount: {
        type: Object,
        required: true
    }
})
document.title = 'Profile'
const defaultHost = ''
const account = toRef(props, 'propAccount')
const accountStore = useAccountStore()
const avt = ref(defaultHost + '/uploads/default.png')
const typeUser = ref('student')
const newAccount = reactive({
    username: '',
    fullname: '',
    email: '',
    phone: '',
})

const getAvatar = async id => {
    if (!id) return
    let req = await api.get('/avatar')
    if (req.status) {
        avt.value = defaultHost + req.url
    } else {
        sweetAlert('Oops...', req.message, 'error')
    }
}

const getInfo = async account => {
    if (!account.id) return
    const { id, username, fullname, email, phone } = account
    typeUser.value = account.type
    newAccount.username = username
    newAccount.fullname = fullname
    newAccount.email = email
    newAccount.phone = phone
    getAvatar(id)
}

const saveInfo = async () => {
    let newInfo = {
        email: newAccount.email,
        phone: newAccount.phone,
        username: newAccount.username,
        fullname: newAccount.fullname
    }
    let req = await api.put('/me', newInfo)
    if (req.status) {
        accountStore.update()
        swal('Infomation has been updated!', '', 'success')
    } else {
        sweetAlert('Oops...', req.message, 'error')
    }
}

const changeAvt = ({ status, url }) => {
    if (status) {
        avt.value = defaultHost + url
    }
}
watch(account, getInfo)
getInfo(account.value)
</script>
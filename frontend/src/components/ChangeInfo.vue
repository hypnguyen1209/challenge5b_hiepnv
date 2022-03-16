<template>
    <div class="modal fade" :id="`changeInfo${item.id}`">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Info</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Username</label>
                        <div class="col-sm-12">
                            <input v-model="changeInfo.username" class="form-control" placeholder />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Fullname</label>
                        <div class="col-sm-12">
                            <input v-model="changeInfo.fullname" class="form-control" placeholder />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Email</label>
                        <div class="col-sm-12">
                            <input v-model="changeInfo.email" class="form-control" placeholder />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Phone</label>
                        <div class="col-sm-12">
                            <input v-model="changeInfo.phone" class="form-control" placeholder />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        ref="closeModal"
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >Close</button>
                    <button
                        ref="btnSave"
                        type="button"
                        class="btn btn-primary"
                        disabled
                        @click="save"
                    >Save and change</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { reactive, toRef, watch, ref } from "vue";
import api from "../services/api";

const props = defineProps({
    item: {
        type: Object,
        required: true
    }
})
const changeInfo = reactive({
    id: 0,
    username: '',
    fullname: '',
    email: '',
    phone: ''
})
const emit = defineEmits(['onChange'])
const btnSave = ref(null)
const closeModal = ref(null)
const item = toRef(props, 'item')

const setInfo = v => {
    changeInfo.id = v.id
    changeInfo.username = v.username
    changeInfo.fullname = v.fullname
    changeInfo.email = v.email
    changeInfo.phone = v.phone
}

setInfo(item.value)

watch(changeInfo, v => {
    if (v.username !== item.value.username || v.fullname !== item.value.fullname || v.email !== item.value.email || v.phone !== item.value.phone) {
        btnSave.value.disabled = false
    } else {
        btnSave.value.disabled = true
    }
})
const save = async () => {
    let req = await api.put(`/change_info/${changeInfo.id}`, {
        username: changeInfo.username,
        fullname: changeInfo.fullname,
        email: changeInfo.email,
        phone: changeInfo.phone
    })
    if (req.status) {
        closeModal.value.click()
        swal('Change infomation done!', '', 'success')
        emit('onChange', '')
        btnSave.value.disabled = true
    } else {
        sweetAlert('Oops...', req.message, 'error')
    }
}
</script>
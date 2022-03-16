<template>
    <div class="modal fade" id="changePassword">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Old password</label>
                        <div class="col-sm-12">
                            <input
                                type="password"
                                v-model="oldPassword"
                                class="form-control"
                                placeholder
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">New password</label>
                        <div class="col-sm-12">
                            <input
                                type="password"
                                v-model="newPassword"
                                class="form-control"
                                placeholder
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Confirm new password</label>
                        <div class="col-sm-12">
                            <input
                                ref="confirm"
                                type="password"
                                v-model="confirmPassword"
                                class="form-control"
                                placeholder
                            />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        ref="closeBtn"
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >Close</button>
                    <button
                        ref="btn-change"
                        type="button"
                        class="btn btn-primary"
                        :disabled="!validPassword"
                        @click="changePassword"
                    >Save changes</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, watch } from 'vue';
import api from '@/services/api';

const oldPassword = ref('')
const newPassword = ref('')
const confirmPassword = ref('')
const validPassword = ref(false)
const confirm = ref(null)
const closeBtn = ref(null)

const changePassword = async () => {
    let req = await api.put('/change_password', {
        old_password: oldPassword.value,
        new_password: newPassword.value
    })
    if (req.status) {
        closeBtn.value.click()
        swal('Password has been changed!', '', 'success')
    } else {
        sweetAlert('Oops...', req.msg, 'error')
    }
}
watch(confirmPassword, (v) => {
    if (v === newPassword.value) {
        //closeBtn.value.click()
        confirm.value.className = 'form-control'
        if (oldPassword.value !== '') {
            validPassword.value = true
        }
    } else {
        validPassword.value = false
        confirm.value.className = 'form-control is-invalid'
    }
})
</script>
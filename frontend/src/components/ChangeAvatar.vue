<template>
    <div class="modal fade" id="changeAvt">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Avatar</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-xl-12">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input
                                        ref="file-upload"
                                        type="file"
                                        class="custom-file-input"
                                        @change="uploadFile"
                                        accept="image/png, image/jpeg"
                                    />
                                    <label ref="filename" class="custom-file-label">Choose file</label>
                                </div>
                            </div>
                            <div class="mt-1">
                                <p class="text-danger">{{ logError }}</p>
                            </div>
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
                        ref="btn-upload"
                        type="button"
                        class="btn btn-primary"
                        :disabled="!file"
                        @click="save"
                    >Save changes</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from "vue";
import api from "../services/api";

const logError = ref('')
const file = ref(null)
const filename = ref(null)
const closeBtn = ref(null)
const emit = defineEmits(['onChangeAvatar'])

const uploadFile = e => {
    file.value = e.target.files[0]
    filename.value.innerText = e.target.files[0].name
}
const save = async () => {
    let req = await api.upload('/change_avatar', file.value)
    if (req.status) {
        closeBtn.value.click()
        swal('Avatar has been updated!', '', 'success')
        emit('onChangeAvatar', {
            status: true,
            url: req.url
        })
        file.value = null
        logError.value = ''
    } else {
        logError.value = req.message
    }
}
</script>
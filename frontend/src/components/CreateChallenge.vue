<template>
    <div class="modal fade" id="createChallenge">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Challenge</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Title</label>
                        <div class="col-sm-12">
                            <input v-model="newChallenge.title" class="form-control" placeholder />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Hint</label>
                        <div class="col-sm-12">
                            <input
                                v-model="newChallenge.description"
                                class="form-control"
                                placeholder
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-12 col-form-label">Upload file txt</label>
                        <div class="col-sm-12">
                            <div class="custom-file">
                                <input
                                    ref="file-upload"
                                    type="file"
                                    class="custom-file-input"
                                    @change="e => chooseFile(e.target.files[0])"
                                    accept="text/plaint"
                                />
                                <label
                                    ref="filename"
                                    class="custom-file-label"
                                >{{ fileName || 'Choose file' }}</label>
                            </div>
                            <div class="mt-1 text-center">
                                <p class="text-danger">{{ logErr }}</p>
                            </div>
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
                        @click="create"
                    >Save and create</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { reactive, ref, watch } from "vue";
import api from "@/services/api";
const newChallenge = reactive({
    title: '',
    description: '',
    file: null
})
const emit = defineEmits(['onCreate'])
const fileName = ref(null)
const closeModal = ref(null)
const btnSave = ref(null)
const logErr = ref('')

const chooseFile = file => {
    fileName.value = file.name
    newChallenge.file = file
}

const create = async () => {
    let req = await api.upload('/create_challenge', newChallenge.file, {
        title: newChallenge.title,
        description: newChallenge.description
    })
    if (req.status) {
        swal('Exercises created!', '', 'success')
        newChallenge.title = ''
        newChallenge.description = ''
        newChallenge.file = null
        closeModal.value.click()
        emit('onCreate', '')
    } else {
        logErr.value = req.mesage
    }
}

watch(newChallenge, v => {
    if (v.title && v.description && v.file) {
        btnSave.value.disabled = false
    } else {
        btnSave.value.disabled = true
    }
})
</script>
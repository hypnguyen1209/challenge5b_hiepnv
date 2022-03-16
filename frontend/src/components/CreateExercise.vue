<template>
    <div class="modal fade" id="createExercise">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Exercise</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Title</label>
                        <div class="col-sm-12">
                            <input class="form-control" v-model="newExercise.title" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Description</label>
                        <div class="col-sm-12">
                            <input class="form-control" v-model="newExercise.description" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label
                            class="col-xl-12 col-form-label"
                        >Upload file (png, jpg, pdf, docx, txt)</label>
                        <div class="col-sm-12">
                            <div class="custom-file">
                                <input
                                    @change="e => chooseFile(e.target.files[0])"
                                    ref="file-upload"
                                    type="file"
                                    class="custom-file-input"
                                    accept="application/pdf, image/png, image/jpeg, text/plain, .docx"
                                />
                                <label
                                    ref="filename"
                                    class="custom-file-label"
                                >{{ fileName || 'Choose file' }}</label>
                            </div>
                            <div class="mt-1 text-center">
                                <p class="text-danger">{{ log }}</p>
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
                        @click="create"
                        ref="btnSave"
                        type="button"
                        class="btn btn-primary"
                        disabled
                    >Save and create</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { reactive, ref, watch } from 'vue'
import api from '@/services/api'

const log = ref('')
const fileName = ref('')
const closeModal = ref(null)
const btnSave = ref(null)
const emit = defineEmits(['onCreate'])

const newExercise = reactive({
    title: '',
    description: '',
    file: null
})
const chooseFile = file => {
    fileName.value = file.name
    newExercise.file = file
}

const create = async () => {
    let req = await api.upload('/create_exercise', newExercise.file, {
        title: newExercise.title,
        description: newExercise.description
    })
    if (req.status) {
        swal('Exercises created!', '', 'success')
        newExercise.title = ''
        newExercise.description = ''
        newExercise.file = null
        closeModal.value.click()
        emit('onCreate', '')
    } else {
        log.value = req.message
    }
}

watch(newExercise, v => {
    if (v.title && v.description && v.file) {
        btnSave.value.disabled = false
    } else {
        btnSave.value.disabled = true
    }
})
</script>
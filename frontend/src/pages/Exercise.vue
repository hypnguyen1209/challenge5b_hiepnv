<template>
    <div class="row" v-if="account.type !== 'student'">
        <div class="col-xl-12 mb-3">
            <div class="float-right">
                <button
                    class="btn btn-outline-primary"
                    data-toggle="modal"
                    data-target="#createExercise"
                >Create exercise</button>
                <create-exercise @onCreate="getExercise" />
            </div>
        </div>
    </div>
    <div class="row">
        <div v-for="(item, i) in list" class="col-xl-6" :key="i">
            <div class="card text-white bg-dark">
                <div class="card-header">
                    <h5 class="card-title text-white">{{ item.title }}</h5>
                </div>
                <div class="card-body mb-0">
                    <p class="card-text">{{ item.description }}</p>
                    <a
                        :href="item.url"
                        class="btn btn-light btn-card text-dark"
                    >{{ item.url.split('/').slice(-1)[0] }}</a>
                </div>
                <div class="card-footer d-sm-flex justify-content-between">
                    Created {{ new Date(item.created_at).toLocaleString() }}
                    <template
                        v-if="account.type !== 'student'"
                    >
                        <div>
                            <button
                                data-toggle="modal"
                                :data-target="`#modal_submited${item.id}`"
                                class="btn btn-primary mr-3"
                            >{{ item.submitted.length }} solved</button>
                            <submit-exercise :item="item" />
                            <button
                                @click="() => deleteExercise(item.id)"
                                class="btn btn-primary"
                            >Delete</button>
                        </div>
                    </template>
                    <template v-else>
                        <button
                            @click="() => btnUpload(i)"
                            class="btn btn-primary"
                        >{{ item.submitted.length > 0 ? item.submitted[0]?.url?.split('/').slice(-1)[0] : `Upload solve` }}</button>
                        <input
                            @change="(e) => uploadSolve(item.id, e)"
                            :ref="el => uploadInput[i] = el"
                            type="file"
                            style="display:none;"
                            accept="application/pdf, image/png, image/jpeg, text/plain, .docx"
                        />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useExerciseStore } from '@/stores/exercise'
import CreateExercise from '@/components/CreateExercise.vue'
import SubmitExercise from '@/components/SubmitExercise.vue'
import { ref, toRef, watch } from 'vue'
import { storeToRefs } from 'pinia'
import { useRoute } from 'vue-router'
import api from '../services/api'
const props = defineProps({
    propAccount: {
        type: Object,
        required: true
    }
})
const account = toRef(props, 'propAccount')
const route = useRoute()
document.title = 'Exercise'
const exercise = useExerciseStore()

const getExercise = async () => {
    if (!await exercise.get()) {
        sweetAlert('Oops...', exercise.log, 'error')
    }
}
const getExerciseWithType = async (type) => {
    if (!['student', 'teacher', 'admin'].includes(type)) return
    await getExercise()
}

const deleteExercise = async id => {
    let req = await api.delete(`/exercise/${id}`)
    if (req.status) {
        swal('Exercise deleted!', '', 'success')
        getExercise()
    } else {
        sweetAlert('Oops...', req.message, 'error')
    }
}
const uploadInput = ref([])

const btnUpload = i => {
    uploadInput.value[i].click()
}

const uploadSolve = async (id, e) => {
    let req = await api.upload(`/submit_exercise/${id}`, e.target.files[0])
    if (req.status) {
        swal('Yeaa!', req.msg, 'success')
    } else {
        sweetAlert('Oops...', req.message, 'error')
    }
    getExercise()
}

watch(account, (v) => getExerciseWithType(v.type))
getExerciseWithType(account.value.type)

const { list } = storeToRefs(exercise)
</script>

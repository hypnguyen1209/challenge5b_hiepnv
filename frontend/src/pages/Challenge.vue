<template>
    <div class="row" v-if="account.type !== 'student'">
        <div class="col-xl-12 mb-3">
            <div class="float-right">
                <button
                    class="btn btn-outline-primary"
                    data-toggle="modal"
                    data-target="#createChallenge"
                >Create challenge</button>
                <create-challenge @onCreate="getChallenge" />
            </div>
        </div>
    </div>
    <div class="row">
        <div v-for="(item, i) in list" class="col-xl-6" :key="i">
            <div class="card text-white bg-dark">
                <div class="card-header">
                    <h5 class="card-title text-white">Challenge {{ item.id }}</h5>
                </div>
                <div class="card-body mb-0">
                    <p class="card-text">{{ item.description }}</p>
                </div>
                <div class="card-footer d-sm-flex justify-content-between">
                    <div v-if="account.type !== 'student'">
                        <button
                            @click="() => deleteChallenge(item.id)"
                            class="btn btn-primary"
                        >Delete</button>
                    </div>
                    <div class="input-group mb-3" v-else>
                        <input
                            :ref="el => submitInput[i] = el"
                            type="text"
                            class="form-control"
                            placeholder="Answer"
                        />
                        <div class="input-group-append">
                            <button
                                @click="() => submitChallenge(item.id, i)"
                                class="btn btn-primary"
                                type="button"
                            >Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import CreateChallenge from '@/components/CreateChallenge.vue';
import { useChallengeStore } from '@/stores/challenge'
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
document.title = 'Challenge'
const challenge = useChallengeStore()
const submitInput = ref([])

const getChallenge = async () => {
    if (!await challenge.get()) {
        sweetAlert('Oops...', challenge.log, 'error')
    }
}
const getChallengeWithType = async (type) => {
    if (!['student', 'teacher', 'admin'].includes(type)) return
    await getChallenge()
}

const deleteChallenge = async id => {
    let req = await api.delete(`/challenge/${id}`)
    if (req.status) {
        swal('Challenge deleted!', '', 'success')
        await getChallenge()
    } else {
        sweetAlert('Oops...', req.message, 'error')
    }
}

watch(account, (v) => getChallengeWithType(v.type))
getChallengeWithType(account.value.type)

const { list } = storeToRefs(challenge)

/* watch(challenge, v => {
    let tmpList = JSON.parse(JSON.stringify(v.list))
    if (tmpList.length == 0) return
    list.value = tmpList.map(item => {
        return typeof item === 'string' ? {
            title: item.match(/^challenge__(\d+)\.[\w ]+\.[\w ]+\.txt$/)[1],
            name: item,
            hint: item.match(/^challenge__\d+\.[\w ]+\.([\w ]+)\.txt$/)[1]
        } : {
            title: item.title,
            hint: item.hint
        }
    })
}) */

const submitChallenge = async (id, i) => {
    let input = submitInput.value[i].value
    if (input === '') return sweetAlert('Oops...', 'Anwser cannot be empty!', 'error')
    let req = await api.post(`/submit_challenge/${id}`, {
        answer: input
    })
    if (req.status) {
        swal('Content', req.content, 'success')
    } else {
        sweetAlert('Oops...', req.message, 'error')
    }
}
</script>
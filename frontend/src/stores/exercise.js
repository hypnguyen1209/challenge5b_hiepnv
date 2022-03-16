import { defineStore } from 'pinia'
import api from '@/services/api'

export const useExerciseStore =  defineStore('exercise', {
    state() {
        return {
            list: [],
            log: ''
        }
    },
    actions: {
        async get() {
            let req = await api.get('/exercises')
            if(req.exception) {
                this.log = req.message
                return false
            }
            this.list = req.data
            return true
        },
    },
    getters: {
    }
})
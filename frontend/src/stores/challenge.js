import { defineStore } from 'pinia'
import api from '@/services/api'

export const useChallengeStore =  defineStore('challenge', {
    state() {
        return {
            list: [],
            log: ''
        }
    },
    actions: {
        async get() {
            let req = await api.get('/challenges')
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
import { defineStore } from 'pinia'
import api from '@/services/api'

export const useUserStore =  defineStore('user', {
    state() {
        return {
            list: [],
            log: ''
        }
    },
    actions: {
        async get() {
            let req = await api.get('/all')
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